<?php

namespace App\Services;

use App\Repositories\ProdutoRepositoryInterface;
use App\Models\Produto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ProdutoService{
private const MARGEM_MINIMA = 0.10;

public function __construct(private ProdutoRepositoryInterface $produtos){}

public function listar(array $filtros): LengthAwarePaginator
{
    return $this->produtos->search($filtros);
}

public function create(array $dados): Produto
{
    $this->verificarMargem($dados['preço'], $dados['preco_custo']);

    return $this->produtos->create($dados);
}

public function update(Produto $produto, array $dados): Produto
{
    if (array_key_exists('preço', $dados) || array_key_exists('preco_custo', $dados)) {
        $preco = $dados['preço'] ?? $produto->preço;
        $preco_custo = $dados['preco_custo'] ?? $produto->preco_custo;
        $this->verificarMargem($preco, $preco_custo);
    }

    return $this->produtos->update($produto, $dados);
}

public function delete(Produto $produto): void
{
    $this->produtos->delete($produto);
}

private function verificarMargem(float $preco, float $preco_custo){
    $margem = $preco_custo + ($preco_custo*self::MARGEM_MINIMA);
    $minimo = round($margem,2);

    if($preco < $minimo){
        throw ValidationException::withMessages([
            'preço'=>sprintf('O preço deve ser de no mínimo %.2f', $minimo)
        ]);
    };
}
}


