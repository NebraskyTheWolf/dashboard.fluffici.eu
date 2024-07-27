<?php

namespace App\Http\Middleware;

use app\Models\Security\Auth\UserApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Zpracujte požadavek.
     *
     * Tato metoda zpracovává příchozí požadavek provedením nezbytných kontrol pro ověření pravosti
     * a přístup k oprávnění. Pokud ověření selže nebo uživatel nemá požadované oprávnění,
     * vrátí se příslušná odpověď. V opačném případě je požadavek zpracován a předán dalšímu
     * middleware nebo obsluze trasy.
     *
     * @param Request $request Příchozí objekt požadavku.
     * @param \Closure $next Uzavření reprezentující další middleware nebo obsluhu trasy.
     * @param string $permission Vyžadované oprávnění pro operaci.
     * @return \Illuminate\Http\Response Objekt odpovědi.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $header = $request->bearerToken();
        if ($header == null) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'Nenalezen žádný bearer token.'
            ]);
        }

        $token = UserApiToken::where('token', $header);
        if (!$token->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'Neplatný bearer token.'
            ]);
        }

        $token = $token->first();
        if ($token->getUser()->isTerminated()) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'Váš účet je ukončen.'
            ]);
        }

        // Oprávnění zablokováno
        if (!$token->getUser()->hasAccess($permission)) {
            return response()->json([
                'status' => false,
                'error' => 'PERMISSION_DENIED',
                'message' => 'Nemáte oprávnění provést tuto operaci.'
            ]);
        }

        $request->merge(['user_id' => $token->user_id]);
        $request->merge(['username' => $token->getUser()->name]);

        return $next($request);
    }
}
