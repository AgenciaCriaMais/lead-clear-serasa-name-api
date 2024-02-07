<?php

namespace App\Http\Requests;

use App\Dto\ErrorResponseDto;
use App\Models\Lead;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
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
            'email' => 'email|min:3|max:255',
            'cpf' => 'cpf|min:11|max:11',
            'syndicate' => 'string',
            'phone' => 'numeric',
            'status' => 'sometimes|string|nullable',
            'description' => 'sometimes|string|nullable'
        ];
        if ($this->isMethod('post')) {
            $requiredFields = ['name', 'email', 'cpf', 'syndicate', 'phone'];
            foreach ($requiredFields as $field) {
                $rules[$field] = 'required|' . $rules[$field];
            }
            $rules['email'] .= '|unique:leads,email';
            $rules['cpf'] .= '|unique:leads,cpf';
        }
        if ($this->isMethod('put')) {
            $leadId = $this->route('lead');
            $lead = Lead::find($leadId);
            if ($lead) {
                $emailValidation = Rule::unique('leads', 'email')->ignore($lead->id);
                $cpfValidation = Rule::unique('leads', 'cpf')->ignore($lead->id);
                $rules['email'] .= '|' . $emailValidation;
                $rules['cpf'] .= '|' . $cpfValidation;
                foreach (array_keys($rules) as $field) {
                    if (!array_key_exists($field, $this->input())) {
                        unset($rules[$field]);
                    }
                }
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
            'cpf.min' => 'O campo CPF não pode ter menos de :min caracteres.',
            'cpf.max' => 'O campo CPF não pode ter mais de :max caracteres.',
            'syndicate.string' => 'O campo sindicato deve ser do tipo texto.',
            'syndicate.required' => 'O campo sindicato é obrigatório.',
            'status.string' => "O campo status só aceita texto.",
            'description.string' => "O campo de descrição só aceita texto.",
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


