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
     */
    public function handle() {
        $today = Carbon::today()->format("Y-m-d");

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $total = OrderedProduct::whereBetween('created_at', [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth()
        ])->sum('price');
        $paidPrice = OrderPayment::whereBetween('created_at', [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth()
        ])->sum('price');
        $carrierFees = OrderCarrier::whereBetween('created_at', [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth()
        ])->sum('price');

        // This happens when a discounts has been placed in the order.
        $loss = $total - $paidPrice;

        // Using a function to avoid non-divisible values.
        $percentage = $this->percent($loss, $total); // ($loss/$total) * 100

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => OrderedProduct::paginate(),
            'fees' => $carrierFees,
            'sales' => number_format($loss),
            'overallProfit' => number_format($total),
            'lossPercentage' => number_format($percentage)
        ]);
        $filename = 'report-' . $today . '-' . $reportId . '.pdf';

        $document->save($filename, 'public');


        $client = new Client();
        $response = $client->post('https://autumn.rsiniya.uk/attachments', [
            'multipart' => [
                'name' => $filename,
                'filename' => $filename,
                'contents' => fopen(public_path($filename), 'r')
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $report = new ShopReports();
            $report->attachment_id = $response->getBody()->getContents()->id;
            $report->report_id = $reportId;
            $report->save();

            $users = User::paginate();
            foreach ($users as $user) {
                $user->notify(new ShopReportReady($reportId));
            }
        } else {
            $users = User::paginate();
            foreach ($users as $user) {
                $user->notify(new ShopReportError());
            }
        }
    }

    public function percent($first, $second): float|int
    {
        if ($first <= 0 || $second <= 0)
            return 0;
        return ($first/$second) * 100;
    }
}
