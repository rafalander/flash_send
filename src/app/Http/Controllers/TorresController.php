<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torre;
use App\Models\Bloco;

class TorresController extends Controller
{
    public function index()
    {
        $torres = Torre::with('bloco')->get();
        $blocos = Bloco::all();
        return view('torres', compact('torres', 'blocos'));
    }

    public function torresCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'nome' => 'required|string|max:255',
                'bloco_id' => 'required|exists:blocos,id',
            ]);
        
        $existe = Torre::where('nome', $request->input('nome'))->exists();

        if ($existe) {
            return redirect()
                ->back()
                ->with('flasher', toastr()
                ->warning('Já existe uma torre com esse nome'));
        }

            Torre::create([
                'nome' => $request->input('nome'),
                'bloco_id' => $request->input('bloco_id'),
            ]);

            return redirect()
            ->route('torres.index')
            ->with('flasher', toastr()
            ->success('Torre criada com sucesso!'));
        }

        $blocos = Bloco::all();

        return view('torresCreate', compact('blocos'));
    }
    
    public function torresEdit(Request $request, $id)
    {
        $torre = Torre::find($id);
        if (!$torre) {
            return redirect()
                ->route('torres.index')
                ->with('flasher', toastr()
                ->error('Torre não encontrada.'));
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'nome' => 'required|string|max:255',
                'bloco_id' => 'required|exists:blocos,id',
            ]);

            $torre->update([
                'nome' => $request->input('nome'),
                'bloco_id' => $request->input('bloco_id'),
            ]);

            return redirect()
                ->route('torres.index')
                ->with('flasher', toastr()
                ->success('Atualizado com sucesso'));
        }

        return view('torresEdit', compact('torre'));
    }

    public function torresDelete($id){
        $torre = Torre::find($id);
        if ($torre) {
            $torre->delete();
            return redirect()
                ->back()
                ->with('flasher', toastr()
                ->info('Torre excluída com sucesso'));
        } else {
            return redirect()
                ->route('torres.index')
                ->with('flasher', toastr()
                ->error('Torre não encontrada.'));
        }    
    }
}
