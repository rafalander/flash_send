<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Origem;
use App\Models\Usuario;

class Config extends Controller
{
    public function index()
    {
        $origens = Origem::orderBy('created_at', 'desc')->get();
        $usuarios = Usuario::with('morador')->orderBy('created_at', 'desc')->get();
        return view('pages.configuracoes.config', compact('origens', 'usuarios'));
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
