<?php

namespace App\Infrastructure\Eloquent\VisitaDoacao;

use Illuminate\Database\Eloquent\Model;

class VisitaDoacao extends Model
{
    protected $table = 'visitas_doacoes';
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    public $timestamps = false; // Usamos useCurrent nas migrations
    
    protected $fillable = [
        'municipe_id',
        'data_visita',
        'qtd_roupa_cama',
        'qtd_masculino',
        'qtd_feminino',
        'qtd_infantil',
        'qtd_calcados',
        'outros_materiais',
        'autorizado_por',
        'assinatura_recebedor'
    ];
}
