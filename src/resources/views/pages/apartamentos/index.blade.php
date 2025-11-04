@extends('layouts.base')
@section('title', 'Apartamentos')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Apartamentos</h2>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cadastro de Apartamentos</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoApartamento">
                <i class="bi bi-plus-circle me-1"></i> Novo Apartamento
            </button>
        </div>
        <div class="card-body">
            <p class="text-muted">Gerencie os apartamentos do condomínio.</p>
            
            <!-- Barra de Busca e Contador -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <x-search
                        :action="route('apartamentos.search')" 
                        placeholder="Buscar apartamento..."
                    />
                </div>
                <div class="col-md-4 text-end">
                    <x-count 
                        :total="$apartamentos->total()" 
                        label="Total:" 
                    />
                </div>
            </div>

            <!-- Tabela de Apartamentos -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Torre</th>
                            <th>Bloco</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apartamentos as $apartamento)
                        <tr>
                            <td>{{ $apartamento->id }}</td>
                            <td>{{ $apartamento->numero }}</td>
                            <td>{{ $apartamento->torre->nome ?? '—' }}</td>
                            <td>{{ optional($apartamento->torre)->bloco->nome ?? '—' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarApartamento"
                                        onclick="editarApartamento({{ $apartamento->id }}, '{{ addslashes($apartamento->numero) }}', {{ $apartamento->torre_id ?? 'null' }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('apartamentos.delete', $apartamento->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este apartamento?')">
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
                            <td colspan="5" class="text-center text-muted">Nenhum apartamento cadastrado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-3">
                <x-pagination :paginator="$apartamentos" :summary="false" align="center" />
            </div>
        </div>
    </div>

    <!-- Seção de Importação -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Importar Apartamentos</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('apartamentos.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-9">
                        <label for="file" class="form-label">Arquivo CSV/Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv,.xls,.xlsx" required>
                        <div class="form-text">Campos esperados: numero, torre_id</div>
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

<!-- Modal Novo Apartamento -->
<div class="modal fade" id="modalNovoApartamento" tabindex="-1" aria-labelledby="modalNovoApartamentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovoApartamentoLabel">Novo Apartamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('apartamentos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="numero" name="numero" required maxlength="10" placeholder="Ex.: 101, 12B">
                    </div>
                    <div class="mb-3">
                        <label for="torre_id" class="form-label">Torre <span class="text-danger">*</span></label>
                        <select class="form-select" id="torre_id" name="torre_id" required>
                            <option value="">Selecione uma torre</option>
                            @foreach($torres as $torre)
                                <option value="{{ $torre->id }}">
                                    {{ $torre->nome ?? "Torre #{$torre->id}" }}
                                    @if($torre->bloco)
                                        ({{ $torre->bloco->nome ?? "Bloco #{$torre->bloco->id}" }})
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

<!-- Modal Editar Apartamento -->
<div class="modal fade" id="modalEditarApartamento" tabindex="-1" aria-labelledby="modalEditarApartamentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarApartamentoLabel">Editar Apartamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarApartamento" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_numero" class="form-label">Número <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_numero" name="numero" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="edit_torre_id" class="form-label">Torre <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_torre_id" name="torre_id" required>
                            <option value="">Selecione uma torre</option>
                            @foreach($torres as $torre)
                                <option value="{{ $torre->id }}">
                                    {{ $torre->nome ?? "Torre #{$torre->id}" }}
                                    @if($torre->bloco)
                                        ({{ $torre->bloco->nome ?? "Bloco #{$torre->bloco->id}" }})
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
function editarApartamento(id, numero, torreId) {
    const form = document.getElementById('formEditarApartamento');
    form.action = `/apartamentos/${id}/edit`;
    
    document.getElementById('edit_numero').value = numero;
    document.getElementById('edit_torre_id').value = torreId || '';
}
</script>

@endsection