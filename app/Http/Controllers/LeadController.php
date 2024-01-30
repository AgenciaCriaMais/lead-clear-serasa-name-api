<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Lead::all();

        return response($leads, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadRequest $request)
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
            return response(['lead' => $lead], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        try {
            $leads = Lead::findOrFail($id);
            return response()->json($leads);
        } catch (ModelNotFoundException $e) {
            return response()->json(['mensagem' => 'Lead não encontrado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $leads = Lead::findOrFail($id);
            $leads->update($request->all());
            return response()->json($leads);
        } catch (ModelNotFoundException $e) {
            return response()->json(['mensagem' => 'Lead não encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Lead::destroy($id);
        return response(['message' => 'Lead foi Excluído com sucesso'], 204);
    }
}
