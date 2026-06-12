<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VisitaDoacao extends Model
{
    use HasFactory;

    protected $table = 'visitas_doacoes';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'municipe_id', 'data_visita', 'qtd_roupa_cama', 'qtd_masculino', 
        'qtd_feminino', 'qtd_infantil', 'qtd_calcados', 'outros_materiais', 
        'autorizado_por', 'assinatura_recebedor'
    ];

    protected $casts = [
        'data_visita' => 'datetime',
        'qtd_roupa_cama' => 'integer',
        'qtd_masculino' => 'integer',
        'qtd_feminino' => 'integer',
        'qtd_infantil' => 'integer',
        'qtd_calcados' => 'integer',
        'assinatura_recebedor' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function municipe()
    {
        return $this->belongsTo(Municipe::class);
    }
}
