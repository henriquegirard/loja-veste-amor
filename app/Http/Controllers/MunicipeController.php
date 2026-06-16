<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipe;
use Carbon\Carbon;

class MunicipeController extends Controller
{
    public function findByCpf($cpf)
    {
        $municipe = Municipe::where('cpf', $cpf)->first();

        if (!$municipe) {
            return response()->json(['message' => 'Munícipe não encontrado'], 404);
        }

        $data = $municipe->toArray();
        if ($municipe->data_nascimento) {
            $data['data_nascimento'] = \Carbon\Carbon::parse($municipe->data_nascimento)->format('Y-m-d');
        }

        $ultimaVisita = $municipe->visitas()->orderBy('data_visita', 'desc')->first();

        if ($ultimaVisita) {
            $data['ultima_visita'] = [
                'data_formatada' => \Carbon\Carbon::parse($ultimaVisita->data_visita)->format('d/m/Y H:i'),
                'dias' => \Carbon\Carbon::parse($ultimaVisita->data_visita)->diffInDays(\Carbon\Carbon::now())
            ];
        }

        return response()->json($data);
    }
}
