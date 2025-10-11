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
        $sheet = Excel::toCollection(null, $file)->first();
        $firstRow = $sheet->first();
        $headerKeys = ['nome','email','cpf','telefone','apartamento_id','apartamento_numero','apartamento','numero','numeroapt'];
        $hasHeader = false;
        if (is_array($firstRow)) {
            foreach ($headerKeys as $k) {
                if (array_key_exists($k, $firstRow)) { $hasHeader = true; break; }
            }
        } elseif ($firstRow instanceof \Illuminate\Support\Collection) {
            foreach ($headerKeys as $k) {
                if ($firstRow->has($k)) { $hasHeader = true; break; }
            }
        }
        $data = $hasHeader ? $sheet : $sheet->slice(1);

        $erros = [];
        $importados = 0;
        $emailsNoArquivo = [];
        $cpfsNoArquivo = [];

        foreach ($data as $index => $row) {
            $nome = $this->extractString($row, ['nome', 0]);
            $email = $this->extractString($row, ['email', 1]);
            $cpf = $this->extractString($row, ['cpf', 2]);
            $telefone = $this->extractString($row, ['telefone', 3]);

            $apartamento_id_raw = $this->extractString($row, ['apartamento_id']);
            $apartamento_numero = $this->extractString($row, ['apartamento_numero','apartamento','numero','numeroapt', 4]);

            $allEmpty = empty($nome) && empty($email) && empty($cpf) && empty($telefone)
                && empty($apartamento_numero) && empty($apartamento_id_raw);
            if ($allEmpty) {
                continue;
            }

            if (!$nome || !$email || !$cpf || (!$apartamento_numero && !$apartamento_id_raw)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": dados incompletos.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": e-mail inválido ({$email}).";
                continue;
            }

            if (in_array(strtolower($email), $emailsNoArquivo)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": e-mail duplicado no próprio arquivo ({$email}).";
                continue;
            }
            $emailsNoArquivo[] = strtolower($email);

            $cpfDigits = $this->normalizeCpf($cpf);
            if (strlen($cpfDigits) !== 11) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF inválido ({$cpf}).";
                continue;
            }
            if (in_array($cpfDigits, $cpfsNoArquivo)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF duplicado no próprio arquivo ({$cpf}).";
                continue;
            }
            $cpfsNoArquivo[] = $cpfDigits;

            $apartamento_id = null;
            if ($apartamento_numero) {
                $apartamento = Apartamento::where('numero', $apartamento_numero)->first();
                if (!$apartamento) {
                    $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": apartamento número inexistente ({$apartamento_numero}).";
                    continue;
                }
                $apartamento_id = $apartamento->id;
            } else {
                if (!Apartamento::where('id', $apartamento_id_raw)->exists()) {
                    $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": apartamento_id inexistente ({$apartamento_id_raw}).";
                    continue;
                }
                $apartamento_id = (int) $apartamento_id_raw;
            }

            if (Morador::where('email', $email)->exists()) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": e-mail já cadastrado no banco ({$email}).";
                continue;
            }
            if (Morador::where('cpf', $cpfDigits)->exists()) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF já cadastrado no banco ({$cpf}).";
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
            $msg = $this->buildErrorSummary($importados, $erros);
            return redirect()
                ->route('moradores.index')
                ->with('flasher', toastr()->error($msg));
        }
        $mensagem = $importados > 1
            ? "{$importados} Moradores importados com sucesso"
            : "{$importados} Morador importado com sucesso";

        return redirect()
            ->route('moradores.index')
            ->with('flasher', toastr()->success($mensagem));
    }

    private function extractString($row, array $candidates)
    {
        foreach ($candidates as $key) {
            if (is_int($key)) {
                if ((is_array($row) && array_key_exists($key, $row)) || ($row instanceof \ArrayAccess && isset($row[$key]))) {
                    $v = $row[$key];
                    return $v === null ? null : trim((string)$v);
                }
            } else {
                if ((is_array($row) && array_key_exists($key, $row)) || (is_object($row) && method_exists($row, 'has') && $row->has($key))) {
                    $v = is_array($row) ? $row[$key] : $row[$key];
                    return $v === null ? null : trim((string)$v);
                }
            }
        }
        return null;
    }

    private function normalizeCpf($cpf)
    {
        return preg_replace('/\D+/', '', (string) $cpf);
    }

    private function buildErrorSummary(int $importados, array $erros)
    {
        $max = 5;
        $lista = implode(' | ', array_slice($erros, 0, $max));
        $extra = count($erros) > $max ? ' +' . (count($erros) - $max) . '...' : '';
        return "Importados: {$importados}. Erros: " . count($erros) . ". " . $lista . $extra;
    }
}
