<?php

namespace app\Http\Controllers;

use App\Models\Accounting;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{

    public function kofiCallback(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data['verification_token'] == null) {
            return response()->json([
                'status' => true,
                "message" => "Cannot accept this request body."
            ]);
        }

        $income = new Accounting();
        $income->type = "INCOME";
        $income->source = "Ko-fi (" . $data['from_name'] . ", " . $data['type'] . ')';
        $income->amount = $data['amount'];
        $income->save();

        return response()->json([
            'status' => true,
            "message" => "Operation successfully saved"
        ]);
    }
}
