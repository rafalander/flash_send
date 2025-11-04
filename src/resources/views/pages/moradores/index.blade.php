@extends('layouts.base')
@section('title', 'Moradores')
@section('content')
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

            <!-- Tabela de Moradores -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Apartamento</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($moradores as $morador)
                        <tr>
                            <td>{{ $morador->nome }}</td>
                            <td>{{ $morador->email }}</td>
                            <td>{{ $morador->cpf ?? '—' }}</td>
                            <td>{{ $morador->telefone ?? '—' }}</td>
                            <td>
                                @if($morador->apartamento)
                                    Apt {{ $morador->apartamento->numero }}
                                    @if(optional($morador->apartamento)->torre)
                                        | {{ $morador->apartamento->torre->nome }}
                                    @endif
                                    @if(optional(optional($morador->apartamento)->torre)->bloco)
                                        | {{ $morador->apartamento->torre->bloco->nome }}
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" 
                                        title="Editar"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarMorador"
                                        onclick="editarMorador({{ $morador->id }}, '{{ addslashes($morador->nome) }}', '{{ addslashes($morador->email) }}', '{{ addslashes($morador->cpf ?? '') }}', '{{ addslashes($morador->telefone ?? '') }}', {{ $morador->apartamento_id ?? 'null' }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('moradores.delete', $morador->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este morador?')">
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
                            <td colspan="6" class="text-center text-muted">Nenhum morador cadastrado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
                        <input type="text" class="form-control" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00">
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20" placeholder="(00) 00000-0000">
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
                        <input type="text" class="form-control" id="edit_cpf" name="cpf" required maxlength="14">
                    </div>
                    <div class="mb-3">
                        <label for="edit_telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="edit_telefone" name="telefone" maxlength="20">
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
}
</script>

@endsection
