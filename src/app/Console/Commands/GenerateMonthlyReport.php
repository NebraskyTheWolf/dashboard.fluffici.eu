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
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;
use Ramsey\Uuid\Uuid;

class GenerateMonthlyReport extends Command
{
    protected $signature = 'app:generate-monthly-report';
    protected $description = 'Generate the monthly report card.';
    public $products = array();

    public function handle() {
        $today = Carbon::today()->format("Y-m-d");
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->subMonth()->month;

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

        $total = OrderedProduct::orderBy('created_at', 'desc')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('price');

        $paidPrice = OrderPayment::orderBy('created_at', 'desc')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'PAID')
            ->sum('price');

        $refunded = OrderPayment::orderBy('created_at', 'desc')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'REFUNDED')
            ->sum('price');

        $carrierFees = OrderCarrier::orderBy('created_at', 'desc')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('price');

        $loss = $total - $paidPrice + $refunded + $carrierFees;

        if ($loss <= 0) {
            $loss = 0;
        }

        $percentage = $this->percent($loss, $total);

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => OrderedProduct::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get(),
            'fees' => number_format(abs($carrierFees)),
            'sales' => number_format(abs($loss)),
            'overallProfit' => number_format(abs($total - $loss - $carrierFees)),
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
        if ($second == 0)
            return 0;
        return ($first/$second) * 100;
    }

    public function sendToAll($notification): void
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasAccess('platform.accounting.monthly_report')) {
                Mail::to($user->email)->send(new \App\Mail\ShopReportReady());
            }
        }
    }
}
