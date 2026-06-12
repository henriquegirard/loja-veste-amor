<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Municipe extends Model
{
    use HasFactory;

    protected $table = 'municipes';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'nome', 'data_nascimento', 'cpf', 'telefone', 'endereco', 'bairro', 'possui_filhos', 'idades_filhos'
    ];

    protected $casts = [
        'possui_filhos' => 'boolean',
        'idades_filhos' => 'array',
        'data_nascimento' => 'date',
        'criado_em' => 'datetime'
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

    public function movimentacoesEstoque()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }
}
