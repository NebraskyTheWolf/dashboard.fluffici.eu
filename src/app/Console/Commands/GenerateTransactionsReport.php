<?php

namespace App\Console\Commands;

use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\TransactionsReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GenerateTransactionsReport extends Command
{
    const string DATE_FORMAT = "Y-m-d";
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
     * Handles the process of generating and saving a transaction report.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        $today = Carbon::today()->format(self::DATE_FORMAT);
        $currentMonth = Carbon::now()->subMonth()->month;
        $currentYear = Carbon::now()->year;
        $reportId = $this->generateReportId();

        $paidPrice = $this->calculateOrderPayments('PAID', $currentMonth, $currentYear);
        $refunded = $this->calculateOrderPayments('REFUNDED', $currentMonth, $currentYear);
        $overdueAmount = $this->calculateOrderPayments('DISPUTED', $currentMonth, $currentYear);
        $carrierFees = $this->calculateCarrierFees($currentMonth, $currentYear);


        $document = $this->generateDocument(
            $reportId,
            $today,
            $paidPrice - $refunded,
            $carrierFees,
            $overdueAmount
        );

        $filename = $this->saveDocument($document, $today, $reportId);
        $this->saveTransactionReport($filename, $reportId);
    }

    /**
     * Generates a report ID.
     *
     * @return string The generated report ID.
     */
    private function generateReportId(): string
    {
        return strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));
    }

    /**
     * Calculate the total payments for orders based on the status, month, and year.
     *
     * @param string $status The status of the orders.
     * @param int $month The month of the orders.
     * @param int $year The year of the orders.
     * @return float The total payments for the orders matching the specified status, month, and year.
     */
    private function calculateOrderPayments(string $status, int $month, int $year): float
    {
        return OrderPayment::orderBy('created_at', 'desc')
            ->where('status', $status)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('price');
    }

    /**
     * Calculates the total fee charged by carriers for orders made in a specific month and year.
     *
     * @param int $month The month for which to calculate the fees (1-12).
     * @param int $year The year for which to calculate the fees.
     * @return float The total fees charged by carriers for the specified month and year.
     */
    private function calculateCarrierFees(int $month, int $year): float
    {
        return 0;
    }

    /**
     * Generates a document for a transaction report.
     *
     * @param string $reportId The ID of the report.
     * @param string $today The date of the report.
     * @param float $profit The total profit of the transactions.
     * @param float $fees The total fees of the transactions.
     * @param float $overdueAmount The total overdue amount of the transactions.
     * @return \Barryvdh\DomPDF\PDF
     * @throws \Exception
     */
    private function generateDocument(string $reportId, string $today, float $profit, float $fees, float $overdueAmount)
    {
        $document = Pdf::loadView('documents.transactions', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'transactions' => OrderPayment::whereMonth('created_at', Carbon::now()->subMonth()->month)->get(),
            'overdueAmount' => $overdueAmount,
            'fees' => number_format($fees),
            'overallProfit' => number_format($profit),
        ]);

        $document->getOptions()->setIsRemoteEnabled(true);
        $document->getOptions()->setIsJavascriptEnabled(true);
        $document->getDomPDF()->getOptions()->setDefaultPaperSize("A4");
        $document->render();

        return $document;
    }

    /**
     * Saves a document to the database.
     *
     * @param $document
     * @param string $today The current date in the format 'Y-m-d'.
     * @param string $reportId The ID of the report.
     * @return string The filename of the saved document.
     */
    private function saveDocument($document, string $today, string $reportId): string
    {
        $filename = 'transaction_report-' . $today . '-' . $reportId . '.pdf';
        $document->save($filename, 'public');

        return $filename;
    }

    /**
     * Saves a transaction report to the database.
     *
     * @param string $filename The attachment ID of the report.
     * @param string $reportId The ID of the report.
     * @return void
     */
    private function saveTransactionReport(string $filename, string $reportId): void
    {
        $report = new TransactionsReport();
        $report->attachment_id = $filename;
        $report->report_id = $reportId;
        $report->save();
    }
}
