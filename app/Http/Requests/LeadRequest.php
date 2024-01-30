<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LeadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:45',
            'email' => 'required|email|unique:leads,email',
            'cpf' => 'required|cpf|unique:leads,cpf|min:11|max:11',
            'syndicate' => 'required|string',
            'status' => 'string',
            'description' => 'string',
            'phone' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser do tipo texto.',
            'name.min' => 'O campo nome não pode ter menos de :min caracteres.',
            'name.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'email.email' => 'Por favor insira um e-mail válido.',
            'cpf.required' => 'O campo cpf é obrigatório.',
            'cpf.unique' => 'Este cpf já está cadastrado.',
            'cpf.min' => 'O campo nome não pode ter menos de :min caracteres.',
            'cpf.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'syndicate.string' => 'O campo sindicato deve ser do tipo texto.',
            'syndicate.required' => 'O campo sindicato é obrigatório.',
            'status.string' => "O campo status só aceita texto.",
            'description.string' => "O campo status só aceita texto.",
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.numeric' => 'O campo telefone deve conter apenas números.'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $messages = array_merge(...array_values($errors));
        throw new HttpResponseException(
            response()->json([
                'message' => "",
                'errors' => $messages
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}


