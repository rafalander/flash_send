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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarOrigem(id, nome, ativo) {
    const form = document.getElementById('formEditarOrigem');
    form.action = `/config/origem/${id}`;
    
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_status').checked = ativo;
}
</script>

@endsection