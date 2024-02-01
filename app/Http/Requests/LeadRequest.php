<?php

namespace App\Http\Requests;

use App\Dto\ErrorResponseDto;
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

    public function rules(): array
    {
        $rules = [
            'name' => 'string|min:3|max:200',
            'email' => 'email|unique:leads,email,' . $this->lead,
            'cpf' => 'cpf|unique:leads,cpf,' . $this->lead . '|min:11|max:11',
            'syndicate' => 'string',
            'phone' => 'numeric',
            'status' => 'sometimes|string',
            'description' => 'sometimes|string'
        ];

        if ($this->isMethod('post')) {
            $requiredFields = ['name', 'email', 'cpf', 'syndicate', 'phone'];
            foreach ($requiredFields as $field) {
                $rules[$field] = 'required|' . $rules[$field];
            }
        }

        return $rules;
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
     * @param Validator $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = (new ValidationException($validator))->errors();
        $messages = array_merge(...array_values($errors));
        $errorResponseDto = new ErrorResponseDto(error: $messages, message: 'Existe erros de validação.');
        throw new HttpResponseException(
            response()->json($errorResponseDto->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}


