<?php

namespace App\Console\Commands;

use App\Models\Shop\Customer\Order\OrderedProduct;
use App\Models\Shop\Customer\Order\OrderPayment;
use App\Models\Shop\Internal\ShopReports;
use App\Notifications\ShopReportReady;
use App\Orchid\Screens\Shop\ShopOrders;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Orchid\Platform\Models\User;
use Ramsey\Uuid\Uuid;

class GenerateMonthlyReport extends Command
{
    protected $signature = 'app:generate-monthly-report';
    protected $description = 'Generate the monthly report card.';
    public $products = array();

    public function handle(): void {
        $today = Carbon::today()->format("Y-m-d");
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->subMonth()->month;

        $reportId = strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

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

        $loss = $paidPrice - $refunded;

        if ($loss <= 0) {
            $loss = 0;
        }

        $percentage = $this->percent($loss, $paidPrice);

        $document = Pdf::loadView('documents.report', [
            'reportId' => $reportId,
            'reportDate' => $today,
            'reportExportDate' => $today,
            'reportProducts' => OrderedProduct::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get(),
            'fees' => 0,
            'sales' => number_format(abs($loss)),
            'overallProfit' => number_format(abs($paidPrice - $refunded)),
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
            if ($user->hasAccess('platform.systems.eshop')) {
                Mail::to($user->email)
                    ->locale($user->getLanguage())
                    ->send(new \App\Mail\ShopReportReady());

                $user->notify($notification);
            }
        }
    }
}
