<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AsaasProvider
{
    /**
     * Recupera o QRCode do PIX
     */
    public function getPixQrCode($id)
    {
        return asaasCurlSend(
            'GET',
            '/api/v3/payments/'.$id.'/pixQrCode'
        );
    }

    /**
     * Recupera um pagamento
     */
    public function getPayment($id)
    {
        return asaasCurlSend(
            'GET',
            '/api/v3/payments/'.$id
        );

    }
}
