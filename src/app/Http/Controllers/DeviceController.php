<?php

namespace App\Http\Controllers;

use App\Events\UpdateAudit;
use App\Models\DeviceAuthorization;
use App\Models\OrderIdentifiers;
use App\Models\ShopCustomer;
use App\Models\ShopOrders;
use App\Models\ShopProducts;
use App\Models\ShopVouchers;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Orchid\Platform\Models\User;

class DeviceController extends Controller
{
    /**
     * Metoda index.
     *
     * Ze žádosti získá ID zařízení a provede kontroly autorizace.
     * V případě, že je zařízení autorizováno, vrátí JSON odpověď s tokenem,
     * což naznačuje, že zařízení je platné. Pokud zařízení není autorizováno,
     * vrátí se JSON odpověď s chybovou zprávou.
     *
     * @param Request $request Objekt HTTP žádosti.
     *
     * @return JsonResponse JSON odpověď se statusem, tokenem (pokud je zařízení autorizováno),
     *                     nebo chybovou zprávou (pokud zařízení není autorizováno).
     */
    public function index(Request $request): JsonResponse
    {
        $deviceId =  $request->query('deviceId');

        if ($deviceId == null) {
            return response()->json([
               'status' => false,
               'error' => 'CHYBÍ_DEVICE_ID',
               'message' => "Chybí deviceId"
            ]);
        }

        $device = DeviceAuthorization::where('deviceId', $deviceId);
        if ($device->exists()) {
            $device = $device->first();
            if ($device->restricted) {
                return response()->json([
                    'status' => false,
                    'error' => "ZAŘÍZENÍ_OMEZENO",
                    'message' => "Toto zařízení bylo omezeno, obraťte se na svého nadřízeného správce."
                ]);
            }

            $user = User::where('id', $device->linked_user)->first();
            if ($user->isTerminated()) {
                return response()->json([
                    'status' => false,
                    'error' => "ÚČET_UKONČEN",
                    'message' => "Nemůžete používat toto zařízení, protože váš účet je ukončen."
                ]);
            }

            $device->update([
                'status' => "Používá se"
            ]);

            return response()->json([
                'status' => true,
                'data' => [
                    'username' => $user->name,
                    'token' => $user->createUserToken()
                ],
                'message' => "Platné zařízení."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' =>  "Zařízení_NENALEZENO",
                'message' => "Toto zařízení je neautorizované."
            ]);
        }
    }

    /**
     * Metoda objednávek.
     *
     * Načte všechny objednávky obchodu a vrátí JSON odpověď s veřejným identifikátorem,
     * celým jménem zákazníka a e-mailem pro každou objednávku.
     *
     * @param Request $request Objekt HTTP žádosti.
     *
     * @return JsonResponse JSON odpověď se statusem, daty (pole objednávek),
     *                     a zprávou o úspěchu.
     */
    public function orders(Request $request): JsonResponse
    {
        $orderList = [];
        foreach (ShopOrders::all() as $singleOrder) {
            $orderList[] = $this->formatOrder($singleOrder);
        }
        return response()->json([
            'status' => true,
            'data' => $orderList,
            'message' => "Objednávky úspěšně načteny."
        ]);
    }

    /**
     * Formátuje data objednávky do specifického formátu.
     *
     * @param mixed $order Data objednávky k formátování.
     * @return array Formátovaná data objednávky.
     */
    private function formatOrder(ShopOrders $order): array
    {
        $publicData = OrderIdentifiers::where('order_id', $order->order_id)->first();
        return [
            'id' => $publicData->public_identifier,
            'customer' => [
                'fullname' => $order->first_name . ' ' . $order->last_name,
                'email' => $order->email
            ]
        ];
    }

    /**
     * Získá všechny zákazníky z databáze.
     *
     * @param Request $request Objekt žádosti.
     *
     * @return JsonResponse JSON odpověď obsahující načtené zákazníky.
     */
    public function customers(Request $request): JsonResponse
    {
        $customer = [];
        $customers = ShopOrders::all();

        foreach ($customers as $customer) {
            $customer[] = [
                'id' => $customer->id,
                'name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $customer,
            'message' => "Zákazníci úspěšně načteni."
        ]);
    }

  // Ostatní funkce...
}
