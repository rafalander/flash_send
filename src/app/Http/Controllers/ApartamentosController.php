<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Torre;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ApartamentosController extends Controller
{
    public function index()
    {
        $apartamentos = Apartamento::with(['torre.bloco'])->paginate(15);
        $torres = Torre::with('bloco')->get();
        return view('pages.apartamentos.index', compact('apartamentos', 'torres'));
    }

    public function apartamentosCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'numero' => 'required|string|max:10|unique:apartamentos,numero',
                'torre_id' => 'required|exists:torres,id',
            ]);

            $existe = Apartamento::where('numero', $request->input('numero'))->exists();

            if ($existe) {
                return redirect()
                    ->back()
                    ->with('flasher', toastr()
                    ->warning('Já existe uma apartamento com esse numero'));
            }

            Apartamento::create([
                'numero' => $request->input('numero'),
                'torre_id' => $request->input('torre_id'),
            ]);

            return redirect()
                ->route('apartamentos.index')
                ->with('flasher', toastr()
                ->success('Apartamento criado com sucesso'));
        }

        $torres = Torre::with('bloco')->get();
        return view('pages.apartamentos.create', compact('torres'));
    }

    public function apartamentosEdit(Request $request, $id)
    {
        $apartamento = Apartamento::find($id);
        if (!$apartamento) {
            return redirect()
                ->route('apartamentos.index')
                ->with('flasher', toastr()
                ->error('Apartamento não encontrado'));
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'numero' => 'required|string|max:10|unique:apartamentos,numero,' . $id,
                'torre_id' => 'required|exists:torres,id',
            ]);

            $apartamento->update([
                'numero' => $request->input('numero'),
                'torre_id' => $request->input('torre_id'),
            ]);

            return redirect()
                ->route('apartamentos.index')
                ->with('flasher', toastr()
                ->success('Apartamento atualizado com sucesso'));
        }

        return view('apartamentosEdit', compact('apartamento'));
    }

    public function apartamentosDelete($id)
    {
        $apartamento = Apartamento::find($id);
        if (!$apartamento) {
            return redirect()
                ->route('apartamentos.index')
                ->with('flasher', toastr()
                ->error('Apartamento não encontrado.'));
        }

        $apartamento->delete();

        return redirect()
            ->route('apartamentos.index')
            ->with('flasher', toastr()
            ->success('Apartamento deletado com sucesso'));
    }

    public function apartamentosImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt'
        ]);

        $file = $request->file('file');
        $data = Excel::toCollection(null, $file)->first()->slice(1);

        $erros = [];
        $importados = 0;
        $numerosNoArquivo = [];

        foreach ($data as $index => $row) {
            $numero = $row['numero'] ?? $row[0] ?? null;
            $torre_id = $row['torre_id'] ?? $row[1] ?? null;

            if (!$numero || !$torre_id) {
                $erros[] = "Linha " . ($index + 2) . ": dados incompletos.";
                continue;
            }

            $chave = $numero . '-' . $torre_id;
            if (in_array($chave, $numerosNoArquivo)) {
                $erros[] = "Linha " . ($index + 2) . ": duplicado no próprio arquivo (nº {$numero}, torre {$torre_id}).";
                continue;
            }
            $numerosNoArquivo[] = $chave;

            $existe = Apartamento::where('numero', $numero)
                                ->where('torre_id', $torre_id)
                                ->exists();

            if ($existe) {
                $erros[] = "Linha " . ($index + 2) . ": o apartamento nº {$numero} da torre {$torre_id} já existe no banco.";
                continue;
            }

            Apartamento::create([
                'numero' => $numero,
                'torre_id' => $torre_id,
            ]);

            $importados++;
        }

        if (count($erros) > 0) {
            return redirect()
                ->route('apartamentos.index')
                ->with('flasher', toastr()->error(
                    "Importados: {$importados}. Erros: " . count($erros)
                ));
        }

        return redirect()
            ->route('apartamentos.index')
            ->with('flasher', toastr()->success("Apartamentos importados com sucesso! ({$importados})"));
    }

}
