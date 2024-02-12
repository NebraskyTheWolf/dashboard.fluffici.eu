<?php

namespace App\Http\Controllers;

use App\Models\AccountingDocument;
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
                case "accounting": {
                    $report = AccountingDocument::where('report_id', $reportId);

                    return $this->extracted($report, $storage, $reportId);
                }
            }
        }

        return response()->json([
            'error' => 'The reportId cannot be null.'
        ]);
    }

    // Dummy commit :) where is the fox?
    // Dummy fox again owo

    /**
     * Extracts a report based on the provided data.
     *
     * @param $report The report object.
     * @param \Illuminate\Contracts\Filesystem\Filesystem $storage The filesystem instance used for storage.
     * @param array|string $reportId The ID of the report.
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse The response containing the extracted report or an error message.
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
