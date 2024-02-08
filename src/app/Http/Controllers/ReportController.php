<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $reportId = $request->query('reportId');

        if ($reportId != null) {
            $storage = \Illuminate\Support\Facades\Storage::disk('public');

            switch ($type) {
                case 'shop': {
                    $report = \App\Models\ShopReports::where('report_id', $reportId);

                    return $this->extracted($report, $storage, $reportId);
                }
                case 'transactions': {
                    $report = \App\Models\TransactionsReport::where('report_id', $reportId);

                    return $this->extracted($report, $storage, $reportId);
                }
            }

        }

        return response()->json([
            'error' => 'The reportId cannot be null.'
        ]);
    }

    // Dummy commit :) where is the fox?

    /**
     * @param $report
     * @param \Illuminate\Contracts\Filesystem\Filesystem $storage
     * @param array|string $reportId
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function extracted($report, \Illuminate\Contracts\Filesystem\Filesystem $storage, array|string $reportId): \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if ($report->exists()) {
            $data = $report->firstOrFail();
            if ($storage->exists($data->attachment_id)) {
                return response()->download(storage_path('app/public/' . $data->attachment_id));
            } else {
                return response()->json([
                    'error' => 'Not found in the storage.'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'No records in database for ' . $reportId
            ]);
        }
    }
}
