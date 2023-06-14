<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Services\AsaasService;
use CodePhix\Asaas\Asaas;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class PaymentController extends Controller
{
    public function __construct(
        protected AsaasService $asaasService
    ){

    }
    public function sendPayment(PaymentRequest $request)
    {

        $payment_methods = $request->get('payment_methods');
        $fullname = $request->get('fullname');
        $cpf = $request->get('cpf');
        $phone = $request->get('phone');
        $email = $request->get('email');

        $name = $request->get('name');
        $cardnumber = $request->get('cardnumber');
        $expirationdate= $request->get('expirationdate');
        $securitycode = $request->get('securitycode');

        //font: https://github.com/codephix/asaas-sdk

        $asaas = new Asaas(env('API_KEY_ASAAS'), env('ENVIROMENT_ASAAS'));

        // Retorna os dados do cliente de acordo com o Id
        $clienteById = $asaas->Cliente()->getById(env('CUSTOMER_ID_ASAAS'));

        // Retorna os dados do cliente de acordo com o Email
        $clientes = $asaas->Cliente()->getByEmail($email);

        // Retorna a listagem de cobranças de acordo com o Id do Cliente
        $cobrancas = $asaas->Cobranca()->getByCustomer('cus_000005333589');



        $id_cobranca = 'pay_4013704849945666';

        $Pix = $asaas->Pix()->create($id_cobranca);
        var_dump($Pix);
//        if($Pix->success){
//            echo '<img src="data:image/jpeg;base64, '.$Pix->encodedImage.'" />';
//        }

        //Consulta se foi efetivado o pagamento via Pix, (Obs: Recomendo um post a cada 30s, ou um botão para confirmação do pagamento, assim não sobrecarregado o seu sistema e nem o do asaas ;) ).
        //$retorno = $asaas->Pix()->get($id_cobranca);

        //Se Cliente não existir com o email de cadastro, então ele cria um e segue o fluxo para o pagamento
//        if(!$clientes){
//            try {
//                // Insere um novo cliente
//                $asaas->Cliente()->create(array $dadosCliente);
//            }catch (Exception){
//
//            }

        //}else {
            //validate if bank slip, credit card or pix mode was selected
            if ($payment_methods === "bankslip") {
                $result = "Boleto Bancario";
            } elseif ($payment_methods === "pix") {
                $result = "Pix";
            } else {
                $result = "Cartão de Crédito";
            }
        //}

        return response()->json([
            'success'=>'Request submitted successfully',
            'data' => $result
        ], 200);
    }
}
