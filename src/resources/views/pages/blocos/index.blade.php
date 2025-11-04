@extends('layouts.base')
@section('title', 'Blocos')
@section('content')

<style>
    .bloco-card {
        background-color: #f8f9fa;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 8px;
    }
    .bloco-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Blocos do Condomínio</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoBloco">
            <i class="bi bi-plus-circle me-1"></i> Novo Bloco
        </button>
    </div>

    <div class="row">
        @forelse($blocos as $bloco)
            <div class="col-md-4 col-lg-3">
                <div class="card bloco-card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-building text-primary me-2"></i>
                            {{ $bloco->nome }}
                        </h5>
                        <p class="card-text text-muted mb-3">
                            <i class="bi bi-diagram-3 me-1"></i>
                            Torres: <strong>{{ $bloco->qtdTorres ?? '0' }}</strong>
                        </p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill" 
                                    title="Editar"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarBloco"
                                    onclick="editarBloco({{ $bloco->id }}, '{{ addslashes($bloco->nome) }}')">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </button>
                            <form action="{{ route('blocos.delete', $bloco->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Tem certeza que deseja excluir este bloco?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhum bloco cadastrado. Clique em "Novo Bloco" para começar.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Novo Bloco -->
<div class="modal fade" id="modalNovoBloco" tabindex="-1" aria-labelledby="modalNovoBlocoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoBlocoLabel">Novo Bloco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('blocos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required maxlength="255" placeholder="Digite o nome do bloco">
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

<!-- Modal Editar Bloco -->
<div class="modal fade" id="modalEditarBloco" tabindex="-1" aria-labelledby="modalEditarBlocoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarBlocoLabel">Editar Bloco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarBloco" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nome" name="nome" required maxlength="255">
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
function editarBloco(id, nome) {
    const form = document.getElementById('formEditarBloco');
    form.action = `/blocos/${id}/edit`;
    
    document.getElementById('edit_nome').value = nome;
}
</script>

@endsection