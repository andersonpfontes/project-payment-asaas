<?php

namespace App\Services;

class AsaasService
{

    /**
     * Cadastrar Cliente
     */
    public function createClient($data)
    {
        $data = json_encode([
            "externalReference" => $data['id'],
            "cpfCnpj"           => $data['cpf_cnpj'],
            "name"              => $data['name'],
            "email"             => $data['email'],
            "phone"             => $data['phone'],
            "mobilePhone"       => $data['phone'],
            "postalCode"        => $data['postal_code'],
            "address"           => $data['address'],
            "addressNumber"     => $data['address_number'],
            "complement"        => $data['complement'],
            "province"          => $data['province'],
        ]);

        // create curl request
    }

    /**
     * Pagamento por PIX
     */
    public function paymentPix($data)
    {
        $data = json_encode([
            "externalReference" => $data['id'],
            "customer"          => $data['customer'],
            "billingType"       => $data['billing_type'],
            "dueDate"           => $data['due_date'],
            "value"             => $data['value'],
            "description"       => $data['description'],
        ]);

        // create curl request
    }

    /**
     * Pagamento por BOLETO
     */
    public function paymentBoleto($data)
    {
        $data = json_encode([
            "externalReference" => $data['id'],
            "customer"          => $data['customer'],
            "billingType"       => $data['billing_type'],
            "dueDate"           => $data['due_date'],
            "value"             => $data['value'],
            "description"       => $data['description'],
            "discount" => [
                "value" => 10,
                "dueDateLimitDays" => 0
            ],
            "fine" => [
                "value" => 1
            ],
            "interest" => [
                "value" => 2
            ],
        ]);

        // create curl request
    }

    /**
     * Pagamento por CARTÃO DE CRÉDITO
     */
    public function paymentCreditCard($data)
    {
        $data = json_encode($data);

        // create curl request
    }

}