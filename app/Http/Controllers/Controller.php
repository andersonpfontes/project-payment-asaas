<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function payment(PaymentRequest $request)
    {
//        $payment_methods = $request->get('payment_methods');
//        $fullname = $request->get('fullname');
//
//
//        //validate if bank slip, credit card or pix mode was selected
//        if ($payment_methods === "bankslip"){
//
//        }elseif ($payment_methods === "pix"){
//
//        }else{
//
//        }

        return response()->json(['success'=>'Request submitted successfully'], 200);
    }
}
