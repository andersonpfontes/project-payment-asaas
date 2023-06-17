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
        $request['fullname'] = $request->get('fullname');
        $request['cpfCnpj'] = preg_replace('/[^0-9]/', '', $request->get('cpfCnpj'));
        $request['phone'] = $request->get('phone');
        $request['email'] = $request->get('email');
        $request['birthday'] = $request->get('birthday');

        $request['postalCode'] = $request->get('postalCode');
        $request['addressNumber'] = $request->get('addressNumber');
        $request['addressComplement'] = null;
        $request['mobilePhone'] = $request->get('phone');

        $creditCard = $request->get('creditCard');

        $var = explode("/",$creditCard['expirationdate']);

        $request['holderName'] = $creditCard['holderName'];
        $request['number'] = $creditCard['number'];
        $request['expiryMonth'] = $var[0];
        $request['expiryYear'] = $var[1];
        $request['ccv'] = $creditCard['ccv'];

        $id_cobranca = 'pay_0207267476805289';// hardcode para teste

        // Retorna os dados do cliente de acordo com o Email
        $clientes = $this->asaas->Cliente()->getByEmail($request->get('email'));

        //Se Cliente não existir com o email de cadastro, então ele cria um e segue o fluxo para o pagamento
        if(empty($clientes)){
            $dataClients = $this->asaasService->payloadCreateClient($request);
            // Insere um novo cliente
            if(!$this->asaas->Cliente()->create($dataClients)){
                throw new \Exception('Não foi possível criar um cliente, favor tente novamente!');
            }
        }

        $request['customer']  = $clientes->data[0]->id;
        $request['dueDate'] = "2023-06-25";
        $request['description'] = "Pedido 056984";
        $request['externalReference'] = "056984";


        //validate if bank slip, credit card or pix mode was selected
        if ($payment_methods === "bankslip") {
            $request['billingType'] = "BOLETO";
            $payload = $this->asaasService->payloadBoleto($request);

            // Insere uma nova cobrança / cobrança parcelada / cobrança split
            $cobranca = $this->asaas->Cobranca()->create($payload);
            $result = [
                "bankSlipUrl" => $cobranca->bankSlipUrl,
                "title" => "Boleto Gerado com sucesso"
            ];
        } elseif ($payment_methods === "pix") {
            $request['billingType'] = "PIX";
            $Pix = $this->asaas->Pix()->create($id_cobranca);

            $payload = $this->asaasService->payloadPix($request);
            $cobranca = $this->asaas->Cobranca()->create($payload);
            if($Pix->success){
                $result = [
                    "encodedImage" => '<img src="data:image/jpeg;base64, '.$Pix->encodedImage.'" />',
                    "invoiceUrl" => $cobranca->invoiceUrl,
                    "title"=>"QR CODE PIX GERADO COM SUCESSO!"
                ];
            }
        } else {
            $request['billingType'] = "CREDIT_CARD";
            $payload = $this->asaasService->payloadCreditCard($request);
            // Insere uma nova cobrança / cobrança parcelada / cobrança split
            $cobranca = $this->asaas->Cobranca()->create($payload);

            $result = [
                "status" => $cobranca->status == "CONFIRMED" ? "Pagamento confirmado" : "", // Criar um Enum para agrupar os status e validar
                "invoiceUrl" => $cobranca->invoiceUrl,
                "transactionReceiptUrl" => $cobranca->transactionReceiptUrl,
                "title" => "Pagamento por cartão de crédito"
            ];
        }

        return response()->json([
            'success'=>'Request submitted successfully',
            'data' => $result
        ], 200);
    }
}
