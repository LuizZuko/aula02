<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
   
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nome'      => $this->nome,
            'descrição' => $this -> descrição,
            'preço'     => (float) $this->preço,
            'estoque'   => $this->estoque,
            'criado_em' => $this->created_at,
        ];
    }
}
