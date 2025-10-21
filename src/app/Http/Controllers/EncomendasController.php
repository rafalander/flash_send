<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encomenda;
use App\Models\Morador;

class EncomendasController extends Controller
{
    public function index()
    {
        $encomendas = Encomenda::with(['morador.apartamento.torre.bloco'])->orderBy('created_at', 'desc')->paginate(15);
        $moradores = Morador::with(['apartamento.torre.bloco'])->get();
        return view('pages.encomendas.index', compact('encomendas', 'moradores'));
    }

    public function encomendasCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_recebimento' => 'required|date',
                'origem' => 'nullable|string|max:100',
                'codigo_rastreamento' => 'nullable|string|max:100',
                'morador_id' => 'required|exists:moradores,id',
                'retirada' => 'nullable|boolean',
            ]);

            Encomenda::create([
                'descricao' => $request->input('descricao'),
                'data_recebimento' => $request->input('data_recebimento'),
                'origem' => $request->input('origem'),
                'codigo_rastreamento' => $request->input('codigo_rastreamento'),
                'morador_id' => $request->input('morador_id'),
                'retirada' => $request->input('retirada', false),
            ]);

            return redirect()
                ->route('encomendas.index')
                ->with('flasher', toastr()->success('Encomenda cadastrada com sucesso'));
        }

        $moradores = Morador::with(['apartamento.torre.bloco'])->get();
        return view('pages.encomendas.create', compact('moradores'));
    }

    public function encomendasEdit(Request $request, $id)
    {
        $encomenda = Encomenda::find($id);
        if (!$encomenda) {
            return redirect()
                ->route('encomendas.index')
                ->with('flasher', toastr()->error('Encomenda não encontrada'));
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_recebimento' => 'required|date',
                'origem' => 'nullable|string|max:100',
                'codigo_rastreamento' => 'nullable|string|max:100',
                'morador_id' => 'required|exists:moradores,id',
                'retirada' => 'nullable|boolean',
            ]);

            $encomenda->update([
                'descricao' => $request->input('descricao'),
                'data_recebimento' => $request->input('data_recebimento'),
                'origem' => $request->input('origem'),
                'codigo_rastreamento' => $request->input('codigo_rastreamento'),
                'morador_id' => $request->input('morador_id'),
                'retirada' => $request->input('retirada', false),
            ]);

            return redirect()
                ->route('encomendas.index')
                ->with('flasher', toastr()->success('Encomenda atualizada com sucesso'));
        }

        return redirect()->route('encomendas.index');
    }

    public function encomendasDelete($id)
    {
        $encomenda = Encomenda::find($id);
        if (!$encomenda) {
            return redirect()
                ->route('encomendas.index')
                ->with('flasher', toastr()->error('Encomenda não encontrada.'));
        }

        $encomenda->delete();

        return redirect()
            ->route('encomendas.index')
            ->with('flasher', toastr()->success('Encomenda excluída com sucesso'));
    }

    public function encomendaSearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $encomendas = Encomenda::with(['morador.apartamento.torre.bloco'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('descricao', 'like', "%{$searchTerm}%")
                  ->orWhere('origem', 'like', "%{$searchTerm}%")
                  ->orWhere('codigo_rastreamento', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('morador', function ($query) use ($searchTerm) {
                $query->where('nome', 'like', "%{$searchTerm}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $moradores = Morador::with(['apartamento.torre.bloco'])->get();

        return view('pages.encomendas.index', compact('encomendas', 'moradores'));
    }
}
