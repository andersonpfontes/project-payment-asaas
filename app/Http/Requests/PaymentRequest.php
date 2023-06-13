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
            'dados.*.fullname' => 'string|min:5|max:255|required',
            'dados.*.phone' => 'string|required',
            'dados.*.birthday' => 'string|min:8|max:10|required',
            'dados.*.cpf' => 'string|required',
        ];
    }

    public function messages()
    {
        return [
            'dados.*.fullname.min' => 'O nome deve conter de 5 a 255 caracteres',
            'dados.*.fullname.required' => 'Preencha o campo nome',
            'dados.*.fullname.string' => 'Preencha o campo nome',

            'dados.*.phone.min' => 'O RG deve conter de 8 a 12 caracteres',
            'dados.*.phone.required' => 'Preencha o campo RG',
            'dados.*.phone.string' => 'Preencha o campo Telefone',

            'dados.*.birthday.min' => 'A data deve conter de 5 a 255 caracteres',
            'dados.*.birthday.required' => 'Preencha o campo data de nascimento',
            'dados.*.birthday.string' => 'Preencha o campo data de nascimento',

            'dados.*.cpf.min' => 'O CPF deve conter 11 caracteres',
            'dados.*.cpf.max' => 'O CPF deve conter 11 caracteres',
            'dados.*.cpf.required' => 'Preencha o campo de CPF',
            'dados.*.cpf.string' => 'Preencha o campo de CPF',
        ];
    }
}
