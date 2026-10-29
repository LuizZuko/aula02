<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Produto;

class ApiProdutoTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_cria_produto_com_margem_correta(): void
    {
       $this->postJson('/api/produtos', [
        'nome' => 'Lajota',
        'preco_custo' => 100,
        'preço' => 110,
        ])->assertCreated();

        $this->assertDatabaseHas('produtos', ['nome' => 'Lajota']);
    }

    public function test_filtra_produtos_por_faixa_de_preco(): void
    {
        Produto::create(['nome' => 'Barato', 'preco_custo' => 10, 'preço' => 15]);
        Produto::create(['nome' => 'Dentro da faixa', 'preco_custo' => 20, 'preço' => 25]);
        Produto::create(['nome' => 'Caro', 'preco_custo' => 40, 'preço' => 50]);

        $this->getJson('/api/produtos?preco_min=20&preco_max=30')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['nome' => 'Dentro da faixa']);
    }

    public function test_atualiza_produto_pelo_servico_e_repositorio(): void
    {
        $produto = Produto::create(['nome' => 'Original', 'preco_custo' => 10, 'preço' => 11]);

        $this->putJson("/api/produtos/{$produto->id}", ['nome' => 'Atualizado'])
            ->assertOk()
            ->assertJsonFragment(['nome' => 'Atualizado']);

        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'nome' => 'Atualizado']);
    }

    public function test_exclui_produto_pelo_servico_e_repositorio(): void
    {
        $produto = Produto::create(['nome' => 'Remover', 'preco_custo' => 10, 'preço' => 11]);

        $this->deleteJson("/api/produtos/{$produto->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    }
}
