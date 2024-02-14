<?php

namespace App\Http\Controllers;

use App\Models\AccountingDocument;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected array $reportClasses = [
        'shop' => \App\Models\ShopReports::class,
        'transactions' => \App\Models\TransactionsReport::class,
        'accounting' => AccountingDocument::class,
    ];

    /**
     * Retrieves and extracts a specific report based on the provided report ID.
     *
     * @param \Illuminate\Http\Request $request The request object containing query parameters.
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse The response containing the extracted report or an error message.
     */
    public function index(Request $request)
    {
        $reportId = $request->query('reportId');
        if ($reportId === null) {
            return response()->json(['error' => 'The reportId cannot be null.']);
        }

        $type = $request->query('type');
        $report = $this->getReport($type, $reportId);
        if ($report === null) {
            return response()->json(['error' => 'The report type is not supported.']);
        }

        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        return $this->extracted($report, $storage, $reportId);
    }

    /**
     * Retrieves a report based on the specified type and report ID.
     *
     * @param string $type The type of the report.
     * @param mixed $reportId The ID of the report.
     * @return mixed|null The report matching the specified type and ID, or null if the type is not found.
     */
    private function getReport(string $type, mixed $reportId) {
        if (!array_key_exists($type, $this->reportClasses)) {
            return null;
        }

        return $this->reportClasses[$type]::where('report_id', $reportId);
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
