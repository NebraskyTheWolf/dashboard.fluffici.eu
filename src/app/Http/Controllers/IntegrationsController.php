<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{

    /**
     * Processes the Ko-fi callback request.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function kofiCallback(Request $request)
    {
        if (!$request->has('data')) {
            return response()->json([
                'status' => false,
                "message" => "Invalid request body."
            ]);
        }

        $data = json_decode($request->input('data'), true);

        $verificationToken = $data['verification_token'];
        $fullname = $data['from_name'];
        $type = $data['type'];
        $amount = $data['amount'];

        if ($verificationToken !== env('KOFI_SECRET', "none")) {
            return response()->json([
                'status' => false,
                "message" => "We cannot verify this callback message."
            ]);
        }

        $client = new Client();
        $response = $client->get('https://v6.exchangerate-api.com/v6/' . env('EXCHANGE_RATE_SECRET') . '/pair/' . $data['currency'] . '/CZK/' . $amount);
        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody()->getContents(), true);
            if ($body['result'] === 'success') {
                $income = new Accounting();
                $income->type = "INCOME";
                $income->source = "Ko-fi (" . $fullname . ", " . $type . ' Rates: ' . $body['conversion_rate'] . ')';
                $income->amount = $body['conversion_result'];
                $income->save();

                return response()->json([
                    'status' => true,
                    "message" => "Operation successfully saved"
                ]);
            }
        } else {
            $income = new Accounting();
            $income->type = "INCOME";
            $income->source = "Ko-fi (" . $fullname . ", " . $type . ' Currency: ' . $request->input('currency') . ')';
            $income->amount = $amount;
            $income->save();
        }

        return response()->json([
            'status' => true,
            "message" => "We cannot make the currency exchange. but the request was saved as fallback."
        ]);
    }
}
