<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @author Jean Paul <jeanpaulwebb@gmail.com>
 * Classe DTO para Respostas de Sucesso da API
 *
 * @description Esta classe é usada para estruturar respostas de sucesso em chamadas de API,
 * garantindo uma estrutura de resposta consistente.
 * Ela encapsula dados de sucesso, permitindo incluir tanto os dados de retorno
 * (como objetos ou arrays) quanto uma mensagem opcional de sucesso.
 * Isso facilita a interpretação dos resultados pelo consumidor da API,
 * promovendo uma comunicação clara e eficiente entre o servidor e o cliente.
 * @date 31/01/2023
 */
class SuccessResponseDto extends DataTransferObject
{
    public bool $success = true;
    public $data;
    public ?string $message = null;
}
