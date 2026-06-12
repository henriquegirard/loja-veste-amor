<?php

namespace App\Infrastructure\Eloquent\Estoque;

use Illuminate\Database\Eloquent\Model;

class ProdutoEstoque extends Model
{
    protected $table = 'produtos_estoque';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; 
    
    protected $fillable = [
        'id',
        'categoria',
        'quantidade_atual',
    ];
}
