<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProdutoEstoque extends Model
{
    use HasFactory;

    protected $table = 'produtos_estoque';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'categoria', 'quantidade_atual'
    ];

    protected $casts = [
        'quantidade_atual' => 'integer',
        'ultima_atualizacao' => 'datetime'
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

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'produto_id');
    }
}
