<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use CodePhix\Asaas\Asaas;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function payment(PaymentRequest $request)
    {
        $asaas = new Asaas(env('API_KEY_ASAAS'), env('ENVIROMENT_ASAAS'));

        // Retorna os dados do cliente de acordo com o Id
        $clientes = $asaas->Cliente()->getById(env('CUSTOMER_ID_ASAAS'));

        // Retorna a listagem de cobranças de acordo com o Id do Cliente
        $cobrancas = $asaas->Cobranca()->getByCustomer('cus_000005333589');

        var_dump($cobrancas);

        $payment_methods = $request->get('payment_methods');
        $fullname = $request->get('fullname');
        $cpf = $request->get('cpf');
        $phone = $request->get('phone');

        $name = $request->get('name');
        $cardnumber = $request->get('cardnumber');
        $expirationdate= $request->get('expirationdate');
        $securitycode = $request->get('securitycode');


        //validate if bank slip, credit card or pix mode was selected
        if ($payment_methods === "bankslip"){
            $result = "Boleto Bancario";
        }elseif ($payment_methods === "pix"){
            $result = "Pix";
        }else{
            $result = "Cartão de Crédito";
        }

        return response()->json([
            'success'=>'Request submitted successfully',
            'data' => $result
        ], 200);
    }
}
