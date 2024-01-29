<?php

namespace App\Console\Commands;

use App\Models\OrderCarrier;
use App\Models\OrderPayment;
use App\Models\OrderedProduct;
use App\Models\PlatformAttachments;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopReports;
use App\Notifications\ShopReportError;
use App\Notifications\ShopReportReady;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Components\Notification;
use Orchid\Platform\Models\User;
use Ramsey\Uuid\Uuid;

class GenerateMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the monthly report card.';

    public $products = array();

    /**
     * Execute the console command.
     * @throws GuzzleException
     * @throws \Exception
     */
    public function handle() {
        $today = Carbon::today()->format("Y-m-d");

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $total = OrderedProduct::whereMonth('created_at', Carbon::now())->sum('price');
        $paidPrice = OrderPayment::whereMonth('created_at', Carbon::now())->sum('price');
        $carrierFees = OrderCarrier::whereMonth('created_at', Carbon::now())->sum('price');

        // This happens when a discounts has been placed in the order.
        $loss = $total - $paidPrice - $carrierFees;

        // Using a function to avoid non-divisible values.
        $percentage = $this->percent($loss, $total); // ($loss/$total) * 100

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => OrderedProduct::whereMonth('created_at', Carbon::now())->paginate(),
            'fees' => number_format(abs($carrierFees)),
            'sales' => number_format(abs($loss)),
            'overallProfit' => number_format($total - $loss),
            'lossPercentage' => number_format($percentage)
        ]);
        $document->setEncryption($reportId);
        $document->getDomPDF()->getCss()->resolve_url("https://dashboard.rsiniya.uk/css/style.css");
        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");

        $document->render();
        $filename = 'report-' . $today . '-' . $reportId . '.pdf';

        $document->save($filename, 'public');

        $client = new Client();
        $storage =  Storage::disk('public');
        try {
            if ($storage->exists($filename)) {
                $response = $client->post('https://autumn.rsiniya.uk/attachments', [
                    'headers' => [
                        'Content-Type' => 'multipart/form-data',
                    ],
                    'multipart' => [
                        [
                            'name' => $filename,
                            'filename' => $filename,
                            'contents' => $storage->get($filename)
                        ]
                    ]
                ]);

                if ($response->getStatusCode() === 200) {
                    $body = json_decode($response->getBody()->getContents(), true);

                    $report = new ShopReports();
                    $report->attachment_id = $body->id;
                    $report->report_id = $reportId;
                    $report->save();

                    printf($body->id);

                    $this->sendToAll(new ShopReportReady($reportId));
                } else {
                    $this->sendToAll(new ShopReportError);
                }
            } else {
                $this->sendToAll(new ShopReportError);
            }
        } catch (\Exception $exception) {
            $this->sendToAll(new ShopReportError);
        }
    }

    public function percent($first, $second): float|int {
        if ($first <= 0 || $second <= 0)
            return 0;
        return ($first/$second) * 100;
    }

    public function sendToAll($notification): void
    {
        $users = User::paginate();
        foreach ($users as $user) {
            $toNotify = User::find($user->id);
            $toNotify->notify($notification);
        }
    }
}
