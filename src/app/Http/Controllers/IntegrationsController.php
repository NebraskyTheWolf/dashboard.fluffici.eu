<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    const string EXCHANGE_API_URL = 'https://v6.exchangerate-api.com/v6';
    const string INCOME = "INCOME";
    const string KOFI = "Ko-fi";

    /**
     * Handle the Kofi callback.
     *
     * @param \Illuminate\Http\Request $request The incoming request.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function kofiCallback(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->isRequestValid($request)) {
            return $this->generateResponse(false, "Invalid request body.");
        }

        $data = json_decode($request->input('data'), true);
        $amount = $data['amount'];
        $fullname = $data['from_name'];
        $type = $data['type'];

        if ($data['verification_token'] !== env('KOFI_SECRET', "none")) {
            return $this->generateResponse(false, "We cannot verify this callback message.");
        }

        $exchangeRateSecret = env('EXCHANGE_RATE_SECRET');
        $client = new Client();
        $response = $client->get(self::EXCHANGE_API_URL . "/{$exchangeRateSecret}/pair/{$data['currency']}/CZK/{$amount}");

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody()->getContents(), true);
            if ($body['result'] === 'success') {
                $this->saveIncome($fullname, $type, $body['conversion_rate'], $body['conversion_result']);
                return $this->generateResponse(true, "Operation successfully saved");
            }
        }

        //Fallback
        $this->saveIncome($fullname, $type, $request->input('currency'), $amount);
        return $this->generateResponse(true, "We cannot make the currency exchange, but the request was saved as a fallback.");
    }

    /**
     * Check if the request is valid.
     *
     * @param Request $request The request object.
     *
     * @return bool Returns true if the request is valid, false otherwise.
     */
    private function isRequestValid(Request $request): bool
    {
        return $request->has('data');
    }

    /**
     * Saves income data to the database.
     *
     * @param string $fullname The full name of the income source.
     * @param string $type The type of income.
     * @param string $currency The currency used for the income.
     * @param float $amount The amount of income.
     *
     * @return void
     */
    private function saveIncome(string $fullname, string $type, string $currency, float $amount): void
    {
        $income = new Accounting();
        $income->type = self::INCOME;
        $income->source = sprintf("%s (%s, %s Rates: %s)", self::KOFI, $fullname, $type, $currency);
        $income->amount = $amount;
        $income->save();
    }

    /**
     * Generate a JSON response.
     *
     * @param string $status The status of the response.
     * @param string $message The message of the response.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response with the specified status and message.
     */
    private function generateResponse(string $status, string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            "message" => $message
        ]);
    }
}

