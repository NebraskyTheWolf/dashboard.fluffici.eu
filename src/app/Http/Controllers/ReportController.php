<?php

namespace app\Http\Controllers;

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
                            'error' => 'No records in database for ' .  $reportId
                        ]);
                    }
                }
                case 'transactions': {
                    $report = \App\Models\TransactionsReport::where('report_id', $reportId);

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
                            'error' => 'No records in database for ' .  $reportId
                        ]);
                    }
                }
            }

        }

        return response()->json([
            'error' => 'The reportId cannot be null.'
        ]);
    }
}
