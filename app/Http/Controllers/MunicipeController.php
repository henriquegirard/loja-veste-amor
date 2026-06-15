<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipe;
use Carbon\Carbon;

class MunicipeController extends Controller
{
    public function findByCpf($cpf)
    {
        $municipe = Municipe::with(['visitas' => function($query) {
            $query->orderBy('created_at', 'desc')->first();
        }])->where('cpf', $cpf)->first();

        if (!$municipe) {
            return response()->json(['message' => 'Munícipe não encontrado'], 404);
        }

        $data = $municipe->toArray();
        $ultimaVisita = $municipe->visitas->first();

        if ($ultimaVisita) {
            $data['ultima_visita'] = [
                'data_formatada' => $ultimaVisita->created_at->format('d/m/Y H:i'),
                'dias' => $ultimaVisita->created_at->diffInDays(Carbon::now())
            ];
        }

        return response()->json($data);
    }
}
