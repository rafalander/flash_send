<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encomenda;
use App\Models\Morador;
use App\Models\Apartamento;
use App\Models\Origem;

class EncomendasController extends Controller
{
    public function index()
    {
        $encomendas = Encomenda::with(['morador.apartamento.torre.bloco'])->paginate(15);
        $moradores = Morador::with(['apartamento.torre.bloco'])->get();
        $origens = Origem::where('ativo', true)->orderBy('nome_origem')->get();
        return view('pages.encomendas.index', compact('encomendas', 'moradores', 'origens'));
    }

    public function encomendasCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_recebimento' => 'required|date|before_or_equal:today',
                'retirada' => 'nullable|boolean',
                'origem' => 'nullable|string|max:150',
                'codigo_rastreamento' => 'nullable|string|max:100',
                'morador_id' => 'required|exists:moradores,id',
            ]);

            Encomenda::create([
                'descricao' => $request->input('descricao'),
                'data_recebimento' => $request->input('data_recebimento'),
                'retirada' => (bool)$request->input('retirada', 0),
                'origem' => $request->input('origem'),
                'codigo_rastreamento' => $request->input('codigo_rastreamento'),
                'morador_id' => $request->input('morador_id'),
            ]);

            return redirect()
                ->route('encomendas.index')
                ->with('flasher', toastr()->success('Encomenda criada com sucesso'));
        }

        $moradores = Morador::with(['apartamento.torre.bloco'])->get();
        $origens = Origem::where('ativo', true)->orderBy('nome_origem')->get();
        return view('pages.encomendas.create', compact('moradores', 'origens'));
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
                'data_recebimento' => 'required|date|before_or_equal:today',
                'retirada' => 'nullable|boolean',
                'origem' => 'nullable|string|max:150',
                'codigo_rastreamento' => 'nullable|string|max:100',
                'morador_id' => 'required|exists:moradores,id',
            ]);

            $encomenda->update([
                'descricao' => $request->input('descricao'),
                'data_recebimento' => $request->input('data_recebimento'),
                'retirada' => (bool)$request->input('retirada', 0),
                'origem' => $request->input('origem'),
                'codigo_rastreamento' => $request->input('codigo_rastreamento'),
                'morador_id' => $request->input('morador_id'),
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
            ->with('flasher', toastr()->success('Encomenda deletada com sucesso'));
    }

    public function encomendaSearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $type = $request->input('type');

        $encomendas = Encomenda::with(['morador.apartamento.torre.bloco'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('descricao', 'like', "%{$searchTerm}%")
                  ->orWhere('codigo_rastreamento', 'like', "%{$searchTerm}%")
                  ->orWhere('origem', 'like', "%{$searchTerm}%")
                  ->orWhere('data_recebimento', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('morador', function ($q) use ($searchTerm) {
                $q->where('nome', 'like', "%{$searchTerm}%")
                  // também busca pelo número do apartamento (relacionamento)
                  ->orWhereHas('apartamento', function ($q2) use ($searchTerm) {
                      $q2->where('numero', 'like', "%{$searchTerm}%");
                  })
                  // busca por nome da torre
                  ->orWhereHas('apartamento.torre', function ($q3) use ($searchTerm) {
                      $q3->where('nome', 'like', "%{$searchTerm}%");
                  })
                  // busca por nome do bloco
                  ->orWhereHas('apartamento.torre.bloco', function ($q4) use ($searchTerm) {
                      $q4->where('nome', 'like', "%{$searchTerm}%");
                  });
            })
            ->paginate(15)
            ->withQueryString();

        // Se o parâmetro type=apartamentos for passado, ou se o cliente
        // requisitar JSON, retornamos a lista de apartamentos (para uso via AJAX)
        if ($request->wantsJson() || $type === 'apartamentos') {
            $apartamentos = Apartamento::with(['torre.bloco'])->get();
            return response()->json($apartamentos);
        }

        $moradores = Morador::with(['apartamento.torre.bloco'])->get();
        return view('pages.encomendas.index', compact('encomendas', 'moradores'));
    }
}
