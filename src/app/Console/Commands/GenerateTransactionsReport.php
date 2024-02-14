<?php

namespace App\Console\Commands;

use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\ShopReports;
use App\Models\TransactionsReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GenerateTransactionsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-transactions-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format("Y-m-d");

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $total = OrderedProduct::orderBy('created_at', 'desc')->whereMonth('created_at', Carbon::now())->sum('price');
        $paidPrice = OrderPayment::orderBy('created_at', 'desc')->where('status', 'PAID')->whereMonth('created_at', Carbon::now())->sum('price');
        $refunded = OrderPayment::orderBy('created_at', 'desc')->where('status', 'REFUNDED')->whereMonth('created_at', Carbon::now())->sum('price');
        $carrierFees = OrderCarrier::orderBy('created_at', 'desc')->whereMonth('created_at', Carbon::now())->sum('price');

        $loss = $total - $paidPrice - $refunded;

        $document = Pdf::loadView('documents.transactions', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'transactions' => OrderPayment::whereMonth('created_at', Carbon::now())->get(),
            'overdueAmount' => OrderPayment::where('status', 'UNPAID')->where('status', 'DISPUTED')->whereMonth('created_at', Carbon::now())->sum('price'),
            'fees' => number_format(abs($carrierFees)),
            'overallProfit' => number_format(abs( $loss )),
        ]);

        $document->getOptions()->setIsRemoteEnabled(true);
        $document->getOptions()->setIsJavascriptEnabled(true);

        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");

        $document->render();
        $filename = 'transaction_report-' . $today . '-' . $reportId . '.pdf';

        $document->save($filename, 'public');

        $report = new TransactionsReport();
        $report->attachment_id = $filename;
        $report->report_id = $reportId;
        $report->save();
    }
}
