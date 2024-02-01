<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @author Jean Paul <jeanpaulwebb@gmail.com>
 * Classe DTO para Respostas de Erro da API
 *
 * @description: Esta classe é projetada para padronizar respostas de erro em chamadas de API,
 * fornecendo uma maneira consistente de informar aos consumidores da API sobre erros ou falhas na execução
 * de uma solicitação. Ela contém um indicador de falha, detalhes sobre o erro
 * (que podem ser uma mensagem de erro interna, códigos de erro específicos, ou ambos),
 * e uma mensagem opcional amigável para o usuário.
 * O uso desta classe ajuda a manter as respostas de erro da API organizadas,
 * tornando mais fácil para os desenvolvedores do cliente tratar e responder a esses erros de forma apropriada.
 * @date 31/01/2023
 */
class ErrorResponseDto extends DataTransferObject
{
    public bool $success = false;
    public $error;
    public ?string $message = null;
}
