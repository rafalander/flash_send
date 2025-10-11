<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Morador;
use App\Models\Apartamento;
use Maatwebsite\Excel\Facades\Excel;

class MoradoresController extends Controller
{
    public function index()
    {
        $moradores = Morador::with(['apartamento.torre.bloco'])->paginate(15);
        $apartamentos = Apartamento::with(['torre.bloco'])->get();
        return view('moradores', compact('moradores', 'apartamentos'));
    }

    public function moradoresCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'nome' => 'required|string|max:150',
                'email' => 'required|email|max:150|unique:moradores,email',
                'cpf' => 'required|string|max:14|unique:moradores,cpf',
                'telefone' => 'nullable|string|max:20',
                'apartamento_id' => 'required|exists:apartamentos,id',
            ]);

            Morador::create([
                'nome' => $request->input('nome'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'telefone' => $request->input('telefone'),
                'apartamento_id' => $request->input('apartamento_id'),
            ]);

            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->success('Morador criado com sucesso'));
        }

        $apartamentos = Apartamento::with(['torre.bloco'])->get();
        return view('moradoresCreate', compact('apartamentos'));
    }

    public function moradoresEdit(Request $request, $id)
    {
        $morador = Morador::find($id);
        if (!$morador) {
            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->error('Morador não encontrado'));
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'nome' => 'required|string|max:150',
                'email' => 'required|email|max:150|unique:moradores,email,' . $id,
                'cpf' => 'required|string|max:14|unique:moradores,cpf,' . $id,
                'telefone' => 'nullable|string|max:20',
                'apartamento_id' => 'required|exists:apartamentos,id',
            ]);

            $morador->update([
                'nome' => $request->input('nome'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'telefone' => $request->input('telefone'),
                'apartamento_id' => $request->input('apartamento_id'),
            ]);

            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->success('Morador atualizado com sucesso'));
        }

        return redirect()->route('moradores.index');
    }

    public function moradoresDelete($id)
    {
        $morador = Morador::find($id);
        if (!$morador) {
            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->error('Morador não encontrado.'));
        }

        $morador->delete();

        return redirect()
            ->route('moradores.index')
            ->with('flasher', toastr()->success('Morador deletado com sucesso'));
    }

    public function moradoresImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt'
        ]);

        $file = $request->file('file');
        $data = Excel::toCollection(null, $file)->first()->slice(1);

        $erros = [];
        $importados = 0;
        $emailsNoArquivo = [];
        $cpfsNoArquivo = [];

        foreach ($data as $index => $row) {
            $nome = isset($row['nome']) ? trim((string)$row['nome']) : (isset($row[0]) ? trim((string)$row[0]) : null);
            $email = isset($row['email']) ? trim((string)$row['email']) : (isset($row[1]) ? trim((string)$row[1]) : null);
            $cpf = isset($row['cpf']) ? trim((string)$row['cpf']) : (isset($row[2]) ? trim((string)$row[2]) : null);
            $telefone = isset($row['telefone']) ? trim((string)$row['telefone']) : (isset($row[3]) ? trim((string)$row[3]) : null);

            // Accept either apartamento_id (legacy) or apartment number (prefer number)
            $apartamento_id_raw = $row['apartamento_id'] ?? null; // legacy support
            $apartamento_numero = $row['apartamento_numero'] ?? $row['apartamento'] ?? $row['numero'] ?? null; // preferred
            if ($apartamento_numero === null && $apartamento_id_raw === null) {
                // Fallback to 5th column if headers are missing; treat as number by default
                $fallback = $row[4] ?? null;
                if ($fallback !== null) {
                    $apartamento_numero = trim((string)$fallback);
                }
            }

            if (!$nome || !$email || !$cpf || (!$apartamento_numero && !$apartamento_id_raw)) {
                $erros[] = "Linha " . ($index + 2) . ": dados incompletos.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros[] = "Linha " . ($index + 2) . ": e-mail inválido ({$email}).";
                continue;
            }

            if (in_array(strtolower($email), $emailsNoArquivo)) {
                $erros[] = "Linha " . ($index + 2) . ": e-mail duplicado no próprio arquivo ({$email}).";
                continue;
            }
            $emailsNoArquivo[] = strtolower($email);

            $cpfDigits = preg_replace('/\D+/', '', (string)$cpf);
            if (strlen($cpfDigits) !== 11) {
                $erros[] = "Linha " . ($index + 2) . ": CPF inválido ({$cpf}).";
                continue;
            }
            if (in_array($cpfDigits, $cpfsNoArquivo)) {
                $erros[] = "Linha " . ($index + 2) . ": CPF duplicado no próprio arquivo ({$cpf}).";
                continue;
            }
            $cpfsNoArquivo[] = $cpfDigits;

            // Resolve apartamento_id: prefer lookup by number, fallback to provided id
            $apartamento_id = null;
            if ($apartamento_numero) {
                $apartamento = Apartamento::where('numero', $apartamento_numero)->first();
                if (!$apartamento) {
                    $erros[] = "Linha " . ($index + 2) . ": apartamento número inexistente ({$apartamento_numero}).";
                    continue;
                }
                $apartamento_id = $apartamento->id;
            } else {
                // Legacy path using apartamento_id from file
                if (!Apartamento::where('id', $apartamento_id_raw)->exists()) {
                    $erros[] = "Linha " . ($index + 2) . ": apartamento_id inexistente ({$apartamento_id_raw}).";
                    continue;
                }
                $apartamento_id = (int) $apartamento_id_raw;
            }

            if (Morador::where('email', $email)->exists()) {
                $erros[] = "Linha " . ($index + 2) . ": e-mail já cadastrado no banco ({$email}).";
                continue;
            }
            if (Morador::where('cpf', $cpfDigits)->exists()) {
                $erros[] = "Linha " . ($index + 2) . ": CPF já cadastrado no banco ({$cpf}).";
                continue;
            }

            Morador::create([
                'nome' => $nome,
                'email' => $email,
                'cpf' => $cpfDigits,
                'telefone' => $telefone,
                'apartamento_id' => $apartamento_id,
            ]);

            $importados++;
        }

        if (count($erros) > 0) {
            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->error(
                    "Importados: {$importados}. Erros: " . count($erros)
                ));
        }

        return redirect()
            ->route('moradores.index')
            ->with('flasher', toastr()->success("Moradores importados com sucesso! ({$importados})"));
    }
}
