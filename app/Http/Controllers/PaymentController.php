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
        $cpfCnpj = $request->get('cpfCnpj');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $birthday = $request->get('birthday');

        $name = $request->get('name');
        $cardnumber = $request->get('cardnumber');
        $expirationdate= $request->get('expirationdate');
        $securitycode = $request->get('securitycode');

        /*$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.asaas.com/api/v3/payments?customer=cus_000005333589',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'access_token: $aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwNTcwNDU6OiRhYWNoXzYyMDc0YTZmLTVjZjktNDYwYi05ZmQ3LTI1ZTEzYWJiNDEwMA==',
                'Cookie: AWSALB=C/H/Hx4lSQiqNIYYIBu2ijncbELFhX/F0d4JvE2/1q13YYifkHEpcljfKN9SwsI4oEJCoyIMCnAKQOusPMq/g9oMRoKVM4a16eb3VgNXf3WhuR9cHn1C68NOZbFo; AWSALBCORS=C/H/Hx4lSQiqNIYYIBu2ijncbELFhX/F0d4JvE2/1q13YYifkHEpcljfKN9SwsI4oEJCoyIMCnAKQOusPMq/g9oMRoKVM4a16eb3VgNXf3WhuR9cHn1C68NOZbFo; AWSALBTG=+4sVehTi8kgZzFayEuEKFV7CAbUW3f9lhZcwwD/4t+NwTFTDNTsNekUYA/XF1OszuHPHWGHBDOGJnNNPvlQHqsR+GSOZYDVDIz8ZyYpHwK9VvP8ECBR/ZcRC/fBvAVH8Yehe0FP+K9ErRoqJuYXHUVyWo38YLEJ7qDwd9NotJJgx; AWSALBTGCORS=+4sVehTi8kgZzFayEuEKFV7CAbUW3f9lhZcwwD/4t+NwTFTDNTsNekUYA/XF1OszuHPHWGHBDOGJnNNPvlQHqsR+GSOZYDVDIz8ZyYpHwK9VvP8ECBR/ZcRC/fBvAVH8Yehe0FP+K9ErRoqJuYXHUVyWo38YLEJ7qDwd9NotJJgx; JSESSIONID=4EB33D6A7761CF4FBD776D8DE22EEA44756DE70C9956A42BC13E7137818EA406C1960E0A9FCF3B038334E522B4DE9CA2F6AE43C7CF07A10983D0AAB7D35D78C8.n1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd$response);*/


        $id_cobranca = 'pay_6791084053953106';// hardcode para teste
        //$Pix = $this->asaas->Pix()->create($id_cobranca);
        //dd($Pix);

//        if($Pix->success){
//            echo '<img src="data:image/jpeg;base64, '.$Pix->encodedImage.'" />';
//        }

        // Retorna os dados do cliente de acordo com o Id
        $clientes = $this->asaas->Cliente()->getById(env('CUSTOMER_ID_ASAAS'));// hardcode no env para teste
        dd($clientes);

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
