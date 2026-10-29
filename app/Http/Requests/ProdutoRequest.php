<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
       /* $metodo = $this-> isMethod('post');

        if($metodo == true){
            $regra ='required';
        }else{
            $regra = 'sometimes';
        }*/

        $regra = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'nome' => [$regra, 'string', 'max:255'],
            'descrição'=> 'nullable|string',
            'preco_custo' => [$regra, 'numeric','min:0'],
            'preço' => [$regra, 'numeric','min:0'],
            'estoque' => 'sometimes|integer|min:0',
        ];
    }
}
