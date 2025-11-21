<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $id,
            'cpf' => 'required|string',
            'telefone' => 'nullable|string',
            'tipo' => 'required|string',
        ]);

        // Normalizar CPF e telefone
        $cpfNormalizado = $this->normalizeCpf($request->input('cpf'));
        $telefoneNormalizado = $this->normalizeTelefone($request->input('telefone'));

        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'cpf' => $cpfNormalizado,
            'telefone' => $telefoneNormalizado,
            'tipo' => $request->input('tipo'),
        ]);

        return redirect()->route('config.index')->with('flasher', toastr()->success('Usuário atualizado com sucesso'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('config.index')->with('flasher', toastr()->success('Usuário deletado com sucesso'));
    }

    /**
     * Normaliza CPF removendo formatação
     */
    private function normalizeCpf($cpf)
    {
        return preg_replace('/\D+/', '', (string) $cpf);
    }

    /**
     * Normaliza telefone removendo formatação
     */
    private function normalizeTelefone($telefone)
    {
        if (empty($telefone)) {
            return null;
        }
        return preg_replace('/\D+/', '', (string) $telefone);
    }
}
