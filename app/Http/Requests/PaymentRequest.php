<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'string|min:5|max:255|required',
            'phone' => 'string|required',
            'birthday' => 'string|min:8|max:10|required',
            'cpf' => 'string|required',
            'email' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'fullname.min' => 'O nome deve conter de 5 a 255 caracteres',
            'fullname.required' => 'Preencha o campo nome',
            'fullname.string' => 'Preencha o campo nome',

            'phone.min' => 'O RG deve conter de 8 a 12 caracteres',
            'phone.required' => 'Preencha o campo RG',
            'phone.string' => 'Preencha o campo Telefone',

            'birthday.min' => 'A data deve conter de 8 a 10 caracteres',
            'birthday.required' => 'Preencha o campo data de nascimento',
            'birthday.string' => 'Preencha o campo data de nascimento',

            'cpf.min' => 'O CPF deve conter 11 caracteres',
            'cpf.max' => 'O CPF deve conter 11 caracteres',
            'cpf.required' => 'Preencha o campo de CPF',
            'cpf.string' => 'Preencha o campo de CPF',

            'email.required' => 'Preencha o campo email',
            'email.string' => 'Preencha o campo email',
        ];
    }
}
