<?php

namespace App\Console\Commands;

use App\Models\Accounting;
use App\Models\AccountingDocument;
use App\Models\OrderCarrier;
use App\Models\OrderedProduct;
use App\Models\OrderPayment;
use App\Models\TransactionsReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GenerateAccountingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-accounting-report';

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
        $income = Accounting::orderBy('created_at', 'desc')->where('type', 'INCOME')->whereMonth('created_at', Carbon::now())->sum('amount');
        $expense = Accounting::orderBy('created_at', 'desc')->where('type', 'EXPENSE')->whereMonth('created_at', Carbon::now())->sum('amount');

        $document = Pdf::loadView('documents.accounting', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'transactions' => Accounting::whereMonth('created_at', Carbon::now())->get(),

            'incomes' => number_format($income),
            'expenses' => number_format($expense),

            'grandTotal' => number_format(abs($income - $expense)),
        ]);

        $document->getOptions()->setIsRemoteEnabled(true);
        $document->getOptions()->setIsJavascriptEnabled(true);

        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");

        $document->render();
        $filename = 'accounting_report-' . $today . '-' . $reportId . '.pdf';

        $document->save($filename, 'public');

        $report = new AccountingDocument();
        $report->attachment_id = $filename;
        $report->report_id = $reportId;
        $report->save();
    }
}
