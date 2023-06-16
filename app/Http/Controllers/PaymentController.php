<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Services\AsaasService;
use CodePhix\Asaas\Asaas;


class PaymentController extends Controller
{
    public function __construct(
        AsaasService $asaasService
    ){
        $this->asaasService = $asaasService;
        $this->asaas = new Asaas(env('API_KEY_ASAAS'), env('ENVIROMENT_ASAAS'));
    }


    public function sendPayment(PaymentRequest $request)
    {

        $payment_methods = $request->get('payment_methods');
        $fullname = $request->get('fullname');
        $cpf = $request->get('cpf');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $birthday = $request->get('birthday');

        $name = $request->get('name');
        $cardnumber = $request->get('cardnumber');
        $expirationdate= $request->get('expirationdate');
        $securitycode = $request->get('securitycode');


        //$id_cobranca = 'pay_4352003198148136';// hardcode para teste
        //$Pix = $this->asaas->Pix()->create($id_cobranca);
        //dd($Pix);

//        if($Pix->success){
//            echo '<img src="data:image/jpeg;base64, '.$Pix->encodedImage.'" />';
//        }

        // Retorna os dados do cliente de acordo com o Id
        $clientes = $this->asaas->Cliente()->getById(env('CUSTOMER_ID_ASAAS'));// hardcode no env para teste

        //Se Cliente não existir com o email de cadastro, então ele cria um e segue o fluxo para o pagamento
        if(!$clientes){
            $dataClients = $this->asaasService->payloadCreateClient($request);
            // Insere um novo cliente
            if(!$this->asaas->Cliente()->create($dataClients)){
                throw new \Exception('Não foi possível criar um cliente, favor tente novamente!');
            }

            // Retorna a listagem de cobranças de acordo com o Id do Cliente
            $cobrancas = $this->asaas->Cobranca()->getByCustomer(env('CUSTOMER_ID_ASAAS'));
            //dd($cobrancas);
        }

        //validate if bank slip, credit card or pix mode was selected
        if ($payment_methods === "bankslip") {
            $result = "Boleto Bancario";
        } elseif ($payment_methods === "pix") {
            $Pix = $this->asaas->Pix()->create($cobrancas->data->id);
            if($Pix->success){
                echo '<img src="data:image/jpeg;base64, '.$Pix->encodedImage.'" />';
            }
        } else {
            $result = "Cartão de Crédito";
        }

        return response()->json([
            'success'=>'Request submitted successfully',
            'data' => $result
        ], 200);
    }
}
