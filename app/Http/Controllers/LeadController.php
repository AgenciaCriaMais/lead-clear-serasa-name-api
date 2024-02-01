<?php

namespace App\Http\Controllers;

use App\Dto\ErrorResponseDto;
use App\Dto\SuccessResponseDto;
use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\Response as StatusCode;

/**
 * @author Wallace Miller <wallacemillerdias@gmail.com>
 * @description Essa classe faz o controle de leads
 * @date 30/01/2023
 */
class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws UnknownProperties
     */
    public function index(): JsonResponse
    {
        $leads = Lead::all();
        $responseDto = new SuccessResponseDto(data: $leads, message: 'Listando todos os leads.');
        return response()->json($responseDto->toArray(), StatusCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadRequest $request): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $lead = Lead::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'syndicate' => $request->input('syndicate'),
                'status' => $request->input('status'),
                'description' => $request->input('description'),
                'phone' => $request->input('phone')
            ]);
            $responseDto = new SuccessResponseDto(data: $lead, message: "Lead criado com sucesso.");
            return response()->json($responseDto->toArray(), StatusCode::HTTP_CREATED);
        } catch (Exception $e) {
            $errorResponseDto = new ErrorResponseDto(error: $e->getMessage(), message: "Erro ao criar lead.");
            return response()->json($errorResponseDto->toArray(), StatusCode::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $leads = Lead::findOrFail($id);
            $responseDto = new SuccessResponseDto(data: $leads, message: "Lead encontrado com sucesso.");
            return response()->json($responseDto->toArray(), 200);
        } catch (ModelNotFoundException $e) {
            $errorResponseDto = new ErrorResponseDto(error: $e->getMessage(), message: "Lead não encontrado.");
            return response()->json($errorResponseDto->toArray(), StatusCode::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadRequest $request, string $id): JsonResponse
    {
        try {
            $leads = Lead::findOrFail($id);
            $leads->update($request->all());
            $responseDto = new SuccessResponseDto(data: $leads, message: "Lead atualizado com sucesso.");
            return response()->json($responseDto->toArray(), StatusCode::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            $errorResponseDto = new ErrorResponseDto(error: "Lead não encontrado.", message: "Nenhum lead encontrado com o ID ($id) fornecido para atualização.");
            return response()->json($errorResponseDto->toArray(), StatusCode::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $lead = Lead::findOrFail($id);
            $lead->delete();
            $responseDto = new SuccessResponseDto(data: null, message: "Lead deletado com sucesso.");
            return response()->json($responseDto->toArray(), StatusCode::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            $errorResponseDto = new ErrorResponseDto(error: "Lead não encontrado.", message: "Nenhum lead encontrado com o ID ($id) fornecido para deleção.");
            return response()->json($errorResponseDto->toArray(), StatusCode::HTTP_NOT_FOUND);
        }
    }
}
