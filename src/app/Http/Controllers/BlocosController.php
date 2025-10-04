<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloco;

class BlocosController extends Controller
{
    public function index()
    {
        $blocos = Bloco::all();
        return view('blocos', compact('blocos'));
    }

    public function blocosCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'nome' => 'required|string|max:255',
            ]);

            Bloco::create([
                'nome' => $request->input('nome'),
            ]);

            return redirect()
                ->route('blocos.index')
                ->with('flasher', toastr()
                ->success('Bloco criado com sucesso'));
        }

        return view('blocosCreate');
    }

    public function blocosEdit(Request $request, $id)
    {
        $bloco = Bloco::find($id);
        if (!$bloco) {
            return redirect()
                ->route('blocos.index')
                ->with('flasher', toastr()
                ->error('Bloco não encontrado.'));
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'nome' => 'required|string|max:255',
            ]);

            $bloco->update([
                'nome' => $request->input('nome'),
            ]);

            return redirect()
                ->route('blocos.index')
                ->with('flasher', toastr()
                ->success('Atualizado com sucesso'));
        }

        return view('blocosEdit', compact('bloco'));
    }

    public function blocosDelete($id){
        $bloco = Bloco::find($id);
        if ($bloco) {
            $bloco->delete();
            return redirect()
                ->back()
                ->with('flasher', toastr()
                ->info('Bloco excluído com sucesso'));
        } else {
            return redirect()
                ->route('blocos.index')
                ->with('flasher', toastr()
                ->error('Bloco não encontrado.'));
        }    
        return view('blocos.index');
    }

}
