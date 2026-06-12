<?php

namespace App\Infrastructure\Eloquent\Estoque;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'produto_id',
        'tipo',
        'quantidade',
        'municipe_id',
        'realizado_por',
        'data_transacao'
    ];
}
