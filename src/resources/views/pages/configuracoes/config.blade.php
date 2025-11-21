@extends('layouts.base')
@section('title', 'Configurações')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Configurações do Sistema</h2>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="geral-tab" data-bs-toggle="tab" data-bs-target="#geral" type="button" role="tab">
                <i class="bi bi-gear me-1"></i> Geral
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notificacoes-tab" data-bs-toggle="tab" data-bs-target="#notificacoes" type="button" role="tab">
                <i class="bi bi-bell me-1"></i> Notificações
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
                <i class="bi bi-person-circle me-1"></i> Usuarios
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="origem-tab" data-bs-toggle="tab" data-bs-target="#origem" type="button" role="tab">
                <i class="bi bi-pin-map me-1"></i> Origem
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="configTabContent">
        
        <!-- Aba Origem -->
    <div class="tab-pane fade" id="origem" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cadastro de Origem</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaOrigem">
                        <i class="bi bi-plus-circle me-1"></i> Nova Origem
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted">Gerencie as origens de encomendas do sistema.</p>
                    
                    <!-- Tabela de Origens -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($origens as $origem)
                                <tr>
                                    <td>{{ $origem->id }}</td>
                                    <td>{{ $origem->nome_origem }}</td>
                                    <td>
                                        <span class="badge {{ $origem->ativo ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $origem->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                title="Editar"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarOrigem"
                                                onclick="editarOrigem({{ $origem->id }}, '{{ $origem->nome_origem }}', {{ $origem->ativo ? 'true' : 'false' }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('config.origem.delete', $origem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta origem?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Nenhuma origem cadastrada</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Geral -->
    <div class="tab-pane fade show active" id="geral" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Configurações Gerais</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configurações gerais do sistema virão aqui.</p>
                </div>
            </div>
        </div>

        <!-- Aba Notificações -->
        <div class="tab-pane fade" id="notificacoes" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Configurações de Notificações</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configurações de notificações virão aqui.</p>
                </div>
            </div>
        </div>

        <!-- Aba Usuários -->
        <div class="tab-pane fade" id="usuarios" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gerenciamento de Usuários</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Lista de todos os usuários do sistema.</p>
                    
                    <!-- Barra de Busca -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <x-search
                                :action="route('config.index')" 
                                placeholder="Buscar usuário..."
                            />
                        </div>
                    </div>
                    
                    <!-- Tabela de Usuários -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Tipo</th>
                                    <th>Morador</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->id }}</td>
                                    <td>{{ $usuario->nome }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @php
                                            $cpfDigits = preg_replace('/\D/', '', $usuario->cpf);
                                            if(strlen($cpfDigits) == 11) {
                                                echo preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfDigits);
                                            } else {
                                                echo $usuario->cpf;
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @if($usuario->telefone)
                                            @php
                                                $tel = preg_replace('/\D/', '', $usuario->telefone);
                                                if(strlen($tel) == 11) {
                                                    echo preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $tel);
                                                } elseif(strlen($tel) == 10) {
                                                    echo preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);
                                                } else {
                                                    echo $usuario->telefone;
                                                }
                                            @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($usuario->tipo) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($usuario->morador)
                                            <span class="badge bg-success">Sim</span>
                                        @else
                                            <span class="badge bg-secondary">Não</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                title="Editar"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarUsuario"
                                                onclick="editarUsuario({{ $usuario->id }}, '{{ $usuario->nome }}', '{{ $usuario->email }}', '{{ $usuario->cpf }}', '{{ $usuario->telefone ?? '' }}', '{{ $usuario->tipo }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('usuarios.delete', $usuario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Nenhum usuário cadastrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Nova Origem -->
<div class="modal fade" id="modalNovaOrigem" tabindex="-1" aria-labelledby="modalNovaOrigemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovaOrigemLabel">Nova Origem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('config.origem.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                            <label class="form-check-label" for="status">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Origem -->
<div class="modal fade" id="modalEditarOrigem" tabindex="-1" aria-labelledby="modalEditarOrigemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarOrigemLabel">Editar Origem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarOrigem" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nome" name="nome" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_status" name="status">
                            <label class="form-check-label" for="edit_status">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuário -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarUsuario" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_usuario_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_usuario_nome" name="nome" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="edit_usuario_email" class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_usuario_email" name="email" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="edit_usuario_cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_usuario_cpf" name="cpf" required maxlength="14" data-mask="cpf">
                    </div>
                    <div class="mb-3">
                        <label for="edit_usuario_telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="edit_usuario_telefone" name="telefone" maxlength="20" data-mask="telefone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_usuario_tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_usuario_tipo" name="tipo" required>
                            <option value="">Selecione um tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->nome }}">{{ $tipo->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarUsuario(id, nome, email, cpf, telefone, tipo) {
    const form = document.getElementById('formEditarUsuario');
    form.action = `/usuarios/${id}`;
    
    document.getElementById('edit_usuario_nome').value = nome;
    document.getElementById('edit_usuario_email').value = email;
    
    // Formatar CPF antes de preencher
    const cpfInput = document.getElementById('edit_usuario_cpf');
    if (window.Masks && cpf) {
        cpfInput.value = Masks.formatCPF(cpf);
    } else {
        cpfInput.value = cpf;
    }
    
    // Formatar telefone antes de preencher
    const telefoneInput = document.getElementById('edit_usuario_telefone');
    if (window.Masks && telefone) {
        telefoneInput.value = Masks.formatTelefone(telefone);
    } else {
        telefoneInput.value = telefone || '';
    }
    
    // Selecionar o tipo correto no select
    document.getElementById('edit_usuario_tipo').value = tipo;
}

function editarOrigem(id, nome, ativo) {
    const form = document.getElementById('formEditarOrigem');
    form.action = `/config/origem/${id}`;
    
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_status').checked = ativo;
}

document.addEventListener('DOMContentLoaded', function () {
    const storageKey = 'config-active-tab';
    const tabButtons = Array.from(document.querySelectorAll('#configTabs button[data-bs-toggle="tab"]'));

    function activateTab(selector) {
        if (!selector) return;
        
        const btn = document.querySelector(`#configTabs button[data-bs-target="${selector}"]`);
        if (btn) {
            const tab = bootstrap.Tab.getOrCreateInstance(btn);
            tab.show();
        }
    }

    const hash = window.location.hash;
    if (hash) {
        activateTab(hash);
    } else {
        const saved = localStorage.getItem(storageKey);
        if (saved) activateTab(saved);
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            const target = e.target.getAttribute('data-bs-target');
            if (target) {
                localStorage.setItem(storageKey, target);
                history.replaceState(null, '', target);
            }
        });
    });

    // Aplicar máscaras no modal de editar usuário
    const modalEditarUsuario = document.getElementById('modalEditarUsuario');
    if (modalEditarUsuario && window.Masks) {
        modalEditarUsuario.addEventListener('shown.bs.modal', function() {
            Masks.applyCPF('#edit_usuario_cpf');
            Masks.applyTelefone('#edit_usuario_telefone');
        });
    }
});
</script>

@endsection