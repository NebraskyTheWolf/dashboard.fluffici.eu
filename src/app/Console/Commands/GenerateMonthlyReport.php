<?php

namespace App\Console\Commands;

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
        $today = Carbon::now()->format("Y-m-d");
        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $total = ShopOrders::all()->sum('total_price');
        $paidPrice = ShopOrders::all()->sum('total_price');

        // This happens when a discounts has been placed in the order.
        $loss = $total - $paidPrice;

        $orders = ShopOrders::all();

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $this->products[] = ShopProducts::where('name', $product);
            }
        }

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => $this->products,
            'fees' => 0,
            'sales' => number_format($loss),
            'overallProfit' => number_format($total)
        ]);
        $filename = 'report-' . $today . '-' . $reportId;
        $document->save($filename);

        $client = new Client();
        $response = $client->post('https://autumn.rsiniya.uk/attachments', [
            'multipart' => [
                'name' => $filename,
                'filename' => $filename . '.pdf',
                'contents' => fopen(public_path($filename), 'r')
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $report = new ShopReports();
            $report->attachment_id = $response->getBody()->getContents()->id;
            $report->report_id = $reportId;
            $report->save();

            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new ShopReportReady($reportId));
            }
        } else {
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new ShopReportError());
            }
        }
    }
}
