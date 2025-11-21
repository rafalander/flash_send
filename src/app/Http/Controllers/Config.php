<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Origem;
use App\Models\Usuario;
use App\Models\Tipo;

class Config extends Controller
{
    public function index(Request $request)
    {
        $origens = Origem::orderBy('created_at', 'desc')->get();
        $tipos = Tipo::orderBy('nome', 'asc')->get();
        
        $searchTerm = $request->input('search');
        
        if ($searchTerm) {
            $usuarios = Usuario::with('morador')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nome', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%")
                      ->orWhere('cpf', 'like', "%{$searchTerm}%")
                      ->orWhere('telefone', 'like', "%{$searchTerm}%")
                      ->orWhere('tipo', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('morador', function ($query) use ($searchTerm) {
                    $query->where('nome', 'like', "%{$searchTerm}%");
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $usuarios = Usuario::with('morador')->orderBy('created_at', 'desc')->get();
        }
        
        return view('pages.configuracoes.config', compact('origens', 'usuarios', 'tipos'));
    }

    public function origemStore(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Origem::create([
            'nome_origem' => $request->input('nome'),
            'ativo' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('config.index')->with('flasher', toastr()->success('Origem criada com sucesso'));
    }

    public function origemUpdate(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $origem = Origem::findOrFail($id);
        $origem->update([
            'nome_origem' => $request->input('nome'),
            'ativo' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('config.index')->with('flasher', toastr()->success('Origem atualizada com sucesso'));
    }

    public function origemDelete($id)
    {
        $origem = Origem::findOrFail($id);
        $origem->delete();

        return redirect()->route('config.index')->with('flasher', toastr()->success('Origem deletada com sucesso'));
    }

}
