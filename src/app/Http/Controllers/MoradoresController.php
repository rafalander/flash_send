<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Morador;
use App\Models\Apartamento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MoradoresController extends Controller
{
    public function index()
    {
        $moradores = Morador::with(['apartamento.torre.bloco'])->paginate(15);
        $apartamentos = Apartamento::with(['torre.bloco'])->get();
        return view('pages.moradores.index', compact('moradores', 'apartamentos'));
    }

    public function moradoresCreate(Request $request)
    {
        if ($request->isMethod('POST')) {
            // Validação básica primeiro
            $request->validate([
                'nome' => 'required|string|max:150',
                'email' => 'required|email|max:150|unique:moradores,email',
                'cpf' => 'required|string',
                'telefone' => 'nullable|string',
                'apartamento_id' => 'required|exists:apartamentos,id',
            ]);

            // Normalizar CPF e telefone após validação básica
            $cpfNormalizado = $this->normalizeCpf($request->input('cpf'));
            $telefoneNormalizado = $this->normalizeTelefone($request->input('telefone'));

            // Validações customizadas
            if (empty($cpfNormalizado) || strlen($cpfNormalizado) !== 11) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'O CPF deve conter 11 dígitos.']);
            }

            if (!$this->validarCpf($cpfNormalizado)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'O CPF informado é inválido.']);
            }

            if ($telefoneNormalizado && !$this->validarTelefone($telefoneNormalizado)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['telefone' => 'O telefone deve conter 10 ou 11 dígitos.']);
            }

            // Verificar se CPF já existe (normalizando CPFs do banco também)
            $cpfExiste = Morador::all()->filter(function($morador) use ($cpfNormalizado) {
                $cpfBanco = $this->normalizeCpf($morador->cpf);
                return $cpfBanco === $cpfNormalizado;
            })->isNotEmpty();
            
            if ($cpfExiste) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'Este CPF já está cadastrado.']);
            }

            try {
                $morador = Morador::create([
                    'nome' => $request->input('nome'),
                    'email' => $request->input('email'),
                    'cpf' => $cpfNormalizado,
                    'telefone' => $telefoneNormalizado,
                    'apartamento_id' => $request->input('apartamento_id'),
                ]);

                // Criar usuário automaticamente para o morador
                Usuario::create([
                    'nome' => $morador->nome,
                    'email' => $morador->email,
                    'senha' => Hash::make($cpfNormalizado), // Senha inicial: CPF
                    'telefone' => $telefoneNormalizado,
                    'cpf' => $cpfNormalizado,
                    'morador_id' => $morador->id,
                    'tipo' => 'morador',
                ]);

                return redirect()
                    ->route('moradores.index')
                    ->with('flasher', toastr()->success('Morador criado com sucesso'));
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['error' => 'Erro ao cadastrar morador: ' . $e->getMessage()]);
            }
        }

        $apartamentos = Apartamento::with(['torre.bloco'])->get();
        return view('pages.moradores.create', compact('apartamentos'));
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
            // Validação básica primeiro
            $request->validate([
                'nome' => 'required|string|max:150',
                'email' => 'required|email|max:150|unique:moradores,email,' . $id,
                'cpf' => 'required|string',
                'telefone' => 'nullable|string',
                'apartamento_id' => 'required|exists:apartamentos,id',
            ]);

            // Normalizar CPF e telefone após validação básica
            $cpfNormalizado = $this->normalizeCpf($request->input('cpf'));
            $telefoneNormalizado = $this->normalizeTelefone($request->input('telefone'));

            // Validações customizadas
            if (empty($cpfNormalizado) || strlen($cpfNormalizado) !== 11) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'O CPF deve conter 11 dígitos.']);
            }

            if (!$this->validarCpf($cpfNormalizado)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'O CPF informado é inválido.']);
            }

            if ($telefoneNormalizado && !$this->validarTelefone($telefoneNormalizado)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['telefone' => 'O telefone deve conter 10 ou 11 dígitos.']);
            }

            // Verificar se CPF já existe (exceto para o próprio registro, normalizando CPFs do banco também)
            $cpfExiste = Morador::where('id', '!=', $id)->get()->filter(function($morador) use ($cpfNormalizado) {
                $cpfBanco = $this->normalizeCpf($morador->cpf);
                return $cpfBanco === $cpfNormalizado;
            })->isNotEmpty();
            
            if ($cpfExiste) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['cpf' => 'Este CPF já está cadastrado.']);
            }

            $morador->update([
                'nome' => $request->input('nome'),
                'email' => $request->input('email'),
                'cpf' => $cpfNormalizado,
                'telefone' => $telefoneNormalizado,
                'apartamento_id' => $request->input('apartamento_id'),
            ]);

            // Atualizar usuário associado se existir
            $usuario = Usuario::where('morador_id', $morador->id)->first();
            if ($usuario) {
                $usuario->update([
                    'nome' => $morador->nome,
                    'email' => $morador->email,
                    'telefone' => $telefoneNormalizado,
                    'cpf' => $cpfNormalizado,
                ]);
            }

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
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF deve conter 11 dígitos ({$cpf}).";
                continue;
            }
            
            // Validar CPF com dígitos verificadores
            if (!$this->validarCpf($cpfDigits)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF inválido (dígitos verificadores incorretos) ({$cpf}).";
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

            // Validar telefone se fornecido
            $telefoneNormalizado = $this->normalizeTelefone($telefone);
            if ($telefoneNormalizado && !$this->validarTelefone($telefoneNormalizado)) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": telefone inválido (deve conter 10 ou 11 dígitos) ({$telefone}).";
                continue;
            }

            // Verificar se e-mail já existe no banco (normalizando também os do banco)
            $emailExiste = Morador::all()->filter(function($morador) use ($email) {
                return strtolower($morador->email) === strtolower($email);
            })->isNotEmpty();
            
            if ($emailExiste) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": e-mail já cadastrado no banco ({$email}).";
                continue;
            }
            
            // Verificar se CPF já existe no banco (normalizando também os do banco)
            $cpfExiste = Morador::all()->filter(function($morador) use ($cpfDigits) {
                $cpfBanco = $this->normalizeCpf($morador->cpf);
                return $cpfBanco === $cpfDigits;
            })->isNotEmpty();
            
            if ($cpfExiste) {
                $erros[] = "Linha " . ($hasHeader ? ($index + 1) : ($index + 2)) . ": CPF já cadastrado no banco ({$cpf}).";
                continue;
            }

            $morador = Morador::create([
                'nome' => $nome,
                'email' => $email,
                'cpf' => $cpfDigits,
                'telefone' => $telefoneNormalizado,
                'apartamento_id' => $apartamento_id,
            ]);

            // Criar usuário automaticamente para o morador
            Usuario::create([
                'nome' => $morador->nome,
                'email' => $morador->email,
                'senha' => Hash::make($cpfDigits), // Senha inicial: CPF
                'telefone' => $telefoneNormalizado,
                'cpf' => $cpfDigits,
                'morador_id' => $morador->id,
                'tipo' => 'morador',
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

    private function normalizeTelefone($telefone)
    {
        if (empty($telefone)) {
            return null;
        }
        return preg_replace('/\D+/', '', (string) $telefone);
    }

    private function validarCpf($cpf)
    {
        $cpf = preg_replace('/\D+/', '', $cpf);
        
        // Verifica se tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais (CPF inválido)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Validação do primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += intval($cpf[$i]) * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        if (intval($cpf[9]) != $digito1) {
            return false;
        }
        
        // Validação do segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += intval($cpf[$i]) * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        if (intval($cpf[10]) != $digito2) {
            return false;
        }
        
        return true;
    }

    private function validarTelefone($telefone)
    {
        if (empty($telefone)) {
            return true; // Telefone é opcional
        }
        
        $telefone = preg_replace('/\D+/', '', $telefone);
        $tamanho = strlen($telefone);
        
        // Telefone deve ter 10 dígitos (fixo) ou 11 dígitos (celular)
        return $tamanho == 10 || $tamanho == 11;
    }

    private function buildErrorSummary(int $importados, array $erros)
    {
        $max = 5;
        $lista = implode(' | ', array_slice($erros, 0, $max));
        $extra = count($erros) > $max ? ' +' . (count($erros) - $max) . '...' : '';
        return "Importados: {$importados}. Erros: " . count($erros) . ". " . $lista . $extra;
    }

    public function moradorSearch(Request $request)
    {
        $searchTerm = $request->input('search');
        $type = $request->input('type');
        $moradores = Morador::with(['apartamento.torre.bloco'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('nome', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('cpf', 'like', "%{$searchTerm}%")
                  ->orWhere('telefone', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('apartamento', function ($query) use ($searchTerm) {
                $query->where('numero', 'like', "%{$searchTerm}%");
            })
            ->paginate(15)
            ->withQueryString();

        // Se o parâmetro type=apartamentos for passado, ou se o cliente
        // requisitar JSON, retornamos a lista de apartamentos (para uso via AJAX)
        if ($request->wantsJson() || $type === 'apartamentos') {
            $apartamentos = Apartamento::with(['torre.bloco'])->get();
            return response()->json($apartamentos);
        }

        // Caso normal: renderiza a view com moradores e apartamentos para edição
        $apartamentos = Apartamento::with(['torre.bloco'])->get();
        return view('pages.moradores.index', compact('moradores', 'apartamentos'));
    }
}
