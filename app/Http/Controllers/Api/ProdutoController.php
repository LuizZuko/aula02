<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    
    public function __construct(private ProdutoService $service){}


    public function index(Request $request)
    {
        $filtros = [
            'search' => $request->query('search'),
            'preco_min' => $request->query('preco_min'),
            'preco_max' => $request->query('preco_max'),
        ];
        return ProdutoResource::collection($this->service->listar($filtros));
    }

    
    public function store(ProdutoRequest $request)
    {
        $dados = $request->validated();



        $produto = $this->service->create($dados);

        return (new ProdutoResource($produto))->response()->setStatusCode(201);
    }


    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    
    public function update(ProdutoRequest $request, Produto $produto)
    {
        $dados = $request->validated();
        $produtoAtualizado = $this->service->update($produto, $dados);
        return new ProdutoResource($produtoAtualizado);

    }

    
    public function destroy(Produto $produto)
    {
        $this->service->delete($produto);
        return response()->noContent(); //204
    }
}
