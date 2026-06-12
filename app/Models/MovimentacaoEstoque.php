<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes_estoque';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'produto_id', 'tipo', 'quantidade', 'municipe_id', 'realizado_por'
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'data_transacao' => 'datetime'
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

    public function produto()
    {
        return $this->belongsTo(ProdutoEstoque::class, 'produto_id');
    }

    public function municipe()
    {
        return $this->belongsTo(Municipe::class, 'municipe_id');
    }
}
