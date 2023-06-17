<?php

namespace App\Services;

class AsaasService
{

    /**
     * Cadastrar Cliente
     */
    public function payloadCreateClient($data)
    {
        $data = [
            "externalReference" => $data['externalReference'],
            "cpfCnpj"           => $data['cpfCnpj'],
            "name"              => $data['name'],
            "email"             => $data['email'],
            "phone"             => $data['phone'],
            "mobilePhone"       => $data['phone'],
            "postalCode"        => $data['postal_code'],
            "address"           => $data['address'],
            "addressNumber"     => $data['address_number'],
            "complement"        => $data['complement'],
            "province"          => $data['province'],
        ];

        return $data;
    }

    /**
     * Pagamento por PIX
     */
    public function payloadPix($data)
    {
        $data = [
            "externalReference" => $data['externalReference'],
            "customer"          => $data['customer'],
            "billingType"       => $data['billingType'],
            "dueDate"           => $data['dueDate'],
            "value"             => $data['value'],
            "description"       => $data['description'],
        ];

        return $data;
    }

    /**
     * Pagamento por BOLETO
     */
    public function payloadBoleto($data)
    {
        $data = [
            "externalReference" => $data['externalReference'],
            "customer"          => $data['customer'],
            "billingType"       => $data['billingType'],
            "dueDate"           => $data['dueDate'],
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
            "postalService" => false
        ];

        return $data;
    }

    /**
     * Pagamento por CARTÃO DE CRÉDITO
     */
    public function payloadCreditCard($data)
    {
        $data = [
              "customer" => $data['customer'],
              "billingType" => $data['billingType'],
              "dueDate" => $data['dueDate'],
              "value" => $data['value'],
              "description" => $data['description'],
              "externalReference" => $data['externalReference'],
              "creditCard" => [
                    "holderName" => $data['holderName'],
                    "number" => $data['number'],
                    "expiryMonth" => $data['expiryMonth'],
                    "expiryYear" => $data['expiryYear'],
                    "ccv" => $data['ccv']
              ],
              "creditCardHolderInfo" => [
                    "name" => $data['fullname'],
                    "email" => $data['email'],
                    "cpfCnpj" => $data['cpfCnpj'],
                    "postalCode" => $data['postalCode'],
                    "addressNumber" => $data['addressNumber'],
                    "addressComplement" => null,
                    "phone" => $data['phone'],
                    "mobilePhone" => $data['phone']
              ],
              "creditCardToken" => "76496073-536f-4835-80db-c45d00f33695"
        ];

        return $data;
    }

}
