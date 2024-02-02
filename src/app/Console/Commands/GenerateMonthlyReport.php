<?php

namespace App\Console\Commands;

use App\Models\OrderCarrier;
use App\Models\OrderPayment;
use App\Models\OrderedProduct;
use App\Models\ShopReports;
use App\Notifications\ShopReportReady;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
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

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $total = OrderedProduct::where('created_at', '<', date('Y-m-d', strtotime('-1 month')))->sum('price');
        $paidPrice = OrderPayment::where('created_at', '<', date('Y-m-d', strtotime('-1 month')))->sum('price');
        $carrierFees = OrderCarrier::where('created_at', '<', date('Y-m-d', strtotime('-1 month')))->sum('price');

        // This happens when a discounts has been placed in the order.
        $loss = $total - $paidPrice ;
        // False positive fix
        if ($loss <= 0) {
            $loss = 0;
        }

        // Using a function to avoid non-divisible values.
        $percentage = $this->percent($loss, $total); // ($loss/$total) * 100

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => OrderedProduct::where('created_at', '<', date('Y-m-d', strtotime('-1 month')))->paginate(),
            'fees' => number_format(abs($carrierFees)),
            'sales' => number_format(abs($loss)),
            'overallProfit' => number_format(abs($total - $loss)),
            'lossPercentage' => number_format($percentage),
            'pagination' => 0
        ]);

        $document->getOptions()->setIsRemoteEnabled(true);
        $document->getOptions()->setIsJavascriptEnabled(true);

        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");

        $document->render();
        $filename = 'report-' . $today . '-' . $reportId . '.pdf';

        $document->save($filename, 'public');

        $report = new ShopReports();
        $report->attachment_id = $filename;
        $report->report_id = $reportId;
        $report->save();

        $this->sendToAll(new ShopReportReady($reportId));


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
