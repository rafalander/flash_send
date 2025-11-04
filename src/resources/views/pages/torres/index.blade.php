@extends('layouts.base')
@section('title', 'Torres')
@section('content')

<style>
    .torre-item {
        transition: background-color 0.3s ease;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        border: 1px solid #e9ecef !important;
    }
    .torre-item:hover {
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
        <h2>Torres</h2>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cadastro de Torres</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaTorre">
                <i class="bi bi-plus-circle me-1"></i> Nova Torre
            </button>
        </div>
        <div class="card-body">
            <p class="text-muted">Gerencie as torres do condomínio.</p>
            
            <!-- Contador -->
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <x-count 
                        :total="$torres->count()" 
                        label="Total:" 
                    />
                </div>
            </div>

            <!-- Lista de Torres -->
            <ul class="list-group">
                @forelse($torres as $torre)
                <li class="list-group-item torre-item">
                    <div class="row g-2 align-items-center">
                        
                        <!-- Coluna: ID -->
                        <div class="col-md-1">
                            <div class="info-secundaria">
                                <i class="bi bi-hash text-muted me-1"></i>
                                <span class="info-destaque">{{ $torre->id }}</span>
                            </div>
                        </div>

                        <!-- Coluna: Nome da Torre (DESTAQUE) -->
                        <div class="col-md-4">
                            <div class="mb-1">
                                <i class="bi bi-building text-primary me-1"></i>
                                <span class="info-destaque">{{ $torre->nome }}</span>
                            </div>
                        </div>

                        <!-- Coluna: Bloco -->
                        <div class="col-md-5">
                            <div class="info-secundaria">
                                <i class="bi bi-grid-3x3 text-success me-1"></i>
                                {{ $torre->bloco->nome ?? '—' }}
                            </div>
                        </div>

                        <!-- Coluna: Ações -->
                        <div class="col-md-2 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarTorre"
                                    onclick="editarTorre({{ $torre->id }}, '{{ addslashes($torre->nome) }}', {{ $torre->bloco_id ?? 'null' }})"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form
                                    action="{{ route('torres.delete', $torre->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta torre?')"
                                >
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
                <li class="list-group-item text-center text-muted">
                    Nenhuma torre cadastrada
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Modal Nova Torre -->
<div class="modal fade" id="modalNovaTorre" tabindex="-1" aria-labelledby="modalNovaTorreLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovaTorreLabel">Nova Torre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('torres.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required maxlength="255" placeholder="Digite o nome da torre">
                    </div>
                    <div class="mb-3">
                        <label for="bloco_id" class="form-label">Bloco <span class="text-danger">*</span></label>
                        <select class="form-select" id="bloco_id" name="bloco_id" required>
                            <option value="">Selecione um bloco</option>
                            @foreach($blocos as $bloco)
                                <option value="{{ $bloco->id }}">{{ $bloco->nome ?? "Bloco #{$bloco->id}" }}</option>
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

<!-- Modal Editar Torre -->
<div class="modal fade" id="modalEditarTorre" tabindex="-1" aria-labelledby="modalEditarTorreLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarTorreLabel">Editar Torre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarTorre" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nome" name="nome" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="edit_bloco_id" class="form-label">Bloco <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_bloco_id" name="bloco_id" required>
                            <option value="">Selecione um bloco</option>
                            @foreach($blocos as $bloco)
                                <option value="{{ $bloco->id }}">{{ $bloco->nome ?? "Bloco #{$bloco->id}" }}</option>
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
function editarTorre(id, nome, blocoId) {
    const form = document.getElementById('formEditarTorre');
    form.action = `/torres/${id}/edit`;
    
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_bloco_id').value = blocoId || '';
}
</script>

@endsection