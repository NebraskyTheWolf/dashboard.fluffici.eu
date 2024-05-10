<?php

namespace App\Console\Commands;

use App\Models\Accounting;
use App\Models\AccountingDocument;
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
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->subMonth()->month;

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
        $income = Accounting::orderBy('created_at', 'desc')
            ->where('type', 'INCOME')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('amount');

        $expense = Accounting::orderBy('created_at', 'desc')
            ->where('type', 'EXPENSE')
            ->where('is_recurring', 0)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('amount');

        $grandTotal = $income - $expense;

        $document = Pdf::loadView('documents.accounting', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'transactions' => Accounting::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->get(),

            'incomes' => number_format($income),
            'expenses' => number_format($expense),
            'grandTotal' => number_format($grandTotal),
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
