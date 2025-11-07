@extends('layouts.base')
@section('title', 'Moradores')
@section('content')

<style>
    .morador-item {
        transition: background-color 0.3s ease;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        border: 1px solid #e9ecef !important;
    }
    .morador-item:hover {
        background-color: #f8f9fa;
    }
    .info-destaque {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
    }
    .info-secundaria {
        font-size: 0.85rem;
        color: #6c757d;
        line-height: 1.3;
    }
    .list-group-item {
        padding: 0.75rem 1rem;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Moradores</h2>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cadastro de Moradores</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoMorador">
                <i class="bi bi-plus-circle me-1"></i> Novo Morador
            </button>
        </div>
        <div class="card-body">
            <p class="text-muted">Gerencie os moradores do condomínio.</p>
            
            <!-- Barra de Busca e Contador -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <x-search
                        :action="route('moradores.search')" 
                        placeholder="Buscar morador..."
                    />
                </div>
                <div class="col-md-4 text-end">
                    <x-count 
                        :total="$moradores->total()" 
                        label="Total:" 
                    />
                </div>
            </div>

            <!-- Lista de Moradores -->
            <ul class="list-group">
                @forelse($moradores as $morador)
                <li class="list-group-item morador-item">
                    <div class="row g-2 align-items-center">
                        
                        <!-- Coluna: Nome (DESTAQUE) -->
                        <div class="col-md-4">
                            <div class="mb-1">
                                <i class="bi bi-person-fill text-primary me-1"></i>
                                <span class="info-destaque">{{ $morador->nome }}</span>
                            </div>
                            <div class="info-secundaria">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $morador->email }}
                            </div>
                        </div>

                        <!-- Coluna: Documentos e Contato -->
                        <div class="col-md-3">
                            <div class="info-secundaria mb-1">
                                <i class="bi bi-credit-card-2-front me-1"></i>
                                {{ $morador->cpf ?? '—' }}
                            </div>
                            <div class="info-secundaria">
                                <i class="bi bi-telephone me-1"></i>
                                {{ $morador->telefone ?? '—' }}
                            </div>
                        </div>

                        <!-- Coluna: Apartamento / Torre / Bloco -->
                        <div class="col-md-3">
                            <div class="info-secundaria">
                                @if($morador->apartamento)
                                    <i class="bi bi-door-closed me-1"></i> Apt {{ $morador->apartamento->numero }}
                                    @if(optional($morador->apartamento)->torre)
                                        | <i class="bi bi-building me-1"></i> {{ $morador->apartamento->torre->nome }}
                                    @endif
                                    @if(optional(optional($morador->apartamento)->torre)->bloco)
                                        | <i class="bi bi-grid-3x3 me-1"></i> {{ $morador->apartamento->torre->bloco->nome }}
                                    @endif
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <!-- Coluna: Ações -->
                        <div class="col-md-2 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-outline-primary btn-sm" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarMorador"
                                        onclick="editarMorador({{ $morador->id }}, '{{ addslashes($morador->nome) }}', '{{ addslashes($morador->email) }}', '{{ addslashes($morador->cpf ?? '') }}', '{{ addslashes($morador->telefone ?? '') }}', {{ $morador->apartamento_id ?? 'null' }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('moradores.delete', $morador->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este morador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </li>
                @empty
                <li class="list-group-item text-center text-muted">Nenhum morador cadastrado</li>
                @endforelse
            </ul>

            <!-- Paginação -->
            <div class="mt-3">
                <x-pagination :paginator="$moradores" :summary="false" align="center" />
            </div>
        </div>
    </div>

    <!-- Seção de Importação -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Importar Moradores</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('moradores.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-9">
                        <label for="file" class="form-label">Arquivo CSV/Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv,.xls,.xlsx" required>
                        <div class="form-text">Campos esperados: nome, email, cpf, telefone, numeroApt</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100 d-block">
                            <i class="bi bi-upload me-1"></i> Importar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Novo Morador -->
<div class="modal fade" id="modalNovoMorador" tabindex="-1" aria-labelledby="modalNovoMoradorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoMoradorLabel">Novo Morador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('moradores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required maxlength="150" placeholder="Nome completo">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required maxlength="150" placeholder="exemplo@dominio.com">
                    </div>
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00" data-mask="cpf">
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20" placeholder="(00) 00000-0000" data-mask="telefone">
                    </div>
                    <div class="mb-3">
                        <label for="apartamento_id" class="form-label">Apartamento <span class="text-danger">*</span></label>
                        <select class="form-select" id="apartamento_id" name="apartamento_id" required>
                            <option value="">Selecione um apartamento</option>
                            @foreach($apartamentos as $apt)
                                <option value="{{ $apt->id }}">
                                    {{ $apt->numero }} — {{ optional($apt->torre)->nome ?? 'Torre' }}
                                    @if(optional($apt->torre)->bloco)
                                        | {{ $apt->torre->bloco->nome }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
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

<!-- Modal Editar Morador -->
<div class="modal fade" id="modalEditarMorador" tabindex="-1" aria-labelledby="modalEditarMoradorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarMoradorLabel">Editar Morador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarMorador" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nome" name="nome" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="edit_cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_cpf" name="cpf" required maxlength="14" data-mask="cpf">
                    </div>
                    <div class="mb-3">
                        <label for="edit_telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="edit_telefone" name="telefone" maxlength="20" data-mask="telefone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_apartamento_id" class="form-label">Apartamento <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_apartamento_id" name="apartamento_id" required>
                            <option value="">Selecione um apartamento</option>
                            @foreach($apartamentos as $apt)
                                <option value="{{ $apt->id }}">
                                    {{ $apt->numero }} — {{ optional($apt->torre)->nome ?? 'Torre' }}
                                    @if(optional($apt->torre)->bloco)
                                        | {{ $apt->torre->bloco->nome }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
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
function editarMorador(id, nome, email, cpf, telefone, apartamentoId) {
    const form = document.getElementById('formEditarMorador');
    form.action = `/moradores/${id}/edit`;
    
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_cpf').value = cpf;
    document.getElementById('edit_telefone').value = telefone;
    document.getElementById('edit_apartamento_id').value = apartamentoId || '';
    
    // Aplicar máscaras nos valores preenchidos
    if (window.Masks) {
        const cpfInput = document.getElementById('edit_cpf');
        const telefoneInput = document.getElementById('edit_telefone');
        if (cpfInput && cpfInput.value) {
            cpfInput.value = Masks.formatCPF(cpfInput.value);
        }
        if (telefoneInput && telefoneInput.value) {
            telefoneInput.value = Masks.formatTelefone(telefoneInput.value);
        }
    }
}

// Aplicar máscaras quando os modais forem abertos
document.addEventListener('DOMContentLoaded', function() {
    // Modal de novo morador
    const modalNovo = document.getElementById('modalNovoMorador');
    if (modalNovo) {
        modalNovo.addEventListener('shown.bs.modal', function() {
            if (window.Masks) {
                Masks.applyCPF('#cpf');
                Masks.applyTelefone('#telefone');
            }
        });
    }
    
    // Modal de editar morador
    const modalEditar = document.getElementById('modalEditarMorador');
    if (modalEditar) {
        modalEditar.addEventListener('shown.bs.modal', function() {
            if (window.Masks) {
                Masks.applyCPF('#edit_cpf');
                Masks.applyTelefone('#edit_telefone');
            }
        });
    }
});
</script>

@endsection
