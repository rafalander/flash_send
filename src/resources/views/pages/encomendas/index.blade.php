@extends('layouts.base')
@section('title', 'Encomendas')
@section('content')

<style>
    .encomenda-retirada {
        background-color: #d1f4dd !important;
        transition: background-color 0.3s ease;
    }
    .encomenda-item {
        transition: background-color 0.3s ease;
        margin-bottom: 0.75rem;
        border-radius: 8px;
        border: 1px solid #e9ecef !important;
    }
    .info-destaque {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
    }
    .info-secundaria {
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.3;
    }
    .badge-retirada {
        font-size: 0.75rem;
        padding: 0.3em 0.5em;
    }
    .list-group-item {
        padding: 0.75rem 1rem;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Encomendas</h2>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gerenciamento de Encomendas</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaEncomenda">
                <i class="bi bi-plus-circle me-1"></i> Nova Encomenda
            </button>
        </div>
        <div class="card-body">
            <p class="text-muted">Gerencie as encomendas do condomínio.</p>
            
            <!-- Barra de Busca e Contador -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <x-search
                        :action="route('encomendas.search')" 
                        placeholder="Buscar encomenda..."
                    />
                </div>
                <div class="col-md-4 text-end">
                    <x-count 
                        :total="$encomendas->total()" 
                        label="Total:" 
                    />
                </div>
            </div>

            <!-- Lista de Encomendas -->
            <ul class="list-group">
                @foreach($encomendas as $encomenda)
                <li class="list-group-item encomenda-item {{ $encomenda->retirada ? 'encomenda-retirada' : '' }}" id="encomenda-item-{{ $encomenda->id }}">
                    <div class="row g-2 align-items-center">
          
          <!-- Coluna Principal: Morador e Apartamento (DESTAQUE) -->
          <div class="col-md-3">
              <div class="mb-1">
                <i class="bi bi-person-fill text-primary me-1"></i>
                <span class="info-destaque">
                  {{ optional($encomenda->morador)->nome ?? '—' }}
                </span>
              </div>
              
              <div class="info-secundaria">
                <i class="bi bi-building me-1"></i>
                @if(optional($encomenda->morador)->apartamento)
                  Apt {{ optional($encomenda->morador->apartamento)->numero }}
                  @if(optional($encomenda->morador->apartamento)->torre)
                    | {{ optional($encomenda->morador->apartamento->torre)->nome ?? 'Torre' }}
                  @endif
                  @if(optional(optional($encomenda->morador->apartamento)->torre)->bloco)
                    | {{ optional($encomenda->morador->apartamento->torre->bloco)->nome ?? 'Bloco' }}
                  @endif
                @else
                  —
                @endif
              </div>
          </div>

          <!-- Coluna: Data e Descrição -->
          <div class="col-md-4">
            <div class="mb-1">
              <i class="bi bi-calendar-event text-success me-1"></i>
              <span class="info-destaque">
                {{ \Carbon\Carbon::parse($encomenda->data_recebimento)->format('d/m/Y') }}
              </span>
            </div>
            
            <div class="info-secundaria">
              <i class="bi bi-box-seam me-1"></i>
              {{ $encomenda->descricao }}
            </div>
          </div>

          <!-- Coluna: Rastreamento e Origem -->
          <div class="col-md-3">
            <div class="mb-1 info-secundaria">
              <i class="bi bi-upc-scan me-1"></i>
              {{ $encomenda->codigo_rastreamento ?? '—' }}
            </div>
            
            <div class="info-secundaria">
              <i class="bi bi-truck me-1"></i>
              {{ $encomenda->origem ?? '—' }}
            </div>
          </div>

          <!-- Coluna: Status e Ações -->
          <div class="col-md-2 text-end">
            <!-- Badge de Status -->
            <div class="mb-2">
                <span class="badge {{ $encomenda->retirada ? 'bg-success' : 'bg-secondary' }} badge-retirada">
                    {{ $encomenda->retirada ? 'Retirada' : 'Pendente' }}
                </span>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex gap-2 justify-content-end">
              <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                data-bs-toggle="modal" 
                data-bs-target="#modalEditarEncomenda"
                onclick="editarEncomenda({{ $encomenda->id }}, '{{ addslashes($encomenda->descricao) }}', '{{ $encomenda->data_recebimento }}', '{{ addslashes($encomenda->codigo_rastreamento ?? '') }}', '{{ addslashes($encomenda->origem ?? '') }}', {{ $encomenda->morador_id ?? 'null' }}, {{ $encomenda->retirada ? 'true' : 'false' }})"
                title="Editar"
              >
                <i class="bi bi-pencil"></i>
              </button>

              <form
                action="{{ route('encomendas.delete', $encomenda->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Tem certeza que deseja excluir esta encomenda?')"
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
                @endforeach
            </ul>

            <!-- Paginação -->
            <div class="mt-3">
                <x-pagination :paginator="$encomendas" :summary="false" align="center" />
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Encomenda -->
<div class="modal fade" id="modalNovaEncomenda" tabindex="-1" aria-labelledby="modalNovaEncomendaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNovaEncomendaLabel">Nova Encomenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('encomendas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="morador_id" class="form-label">Morador <span class="text-danger">*</span></label>
                        <select class="form-select" id="morador_id" name="morador_id" required>
                            <option value="">Selecione um morador</option>
                            @foreach($moradores as $mor)
                                <option value="{{ $mor->id }}">
                                    {{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero }}
                                    @if(optional($mor->apartamento)->torre)
                                        | {{ $mor->apartamento->torre->nome }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required maxlength="255" placeholder="Ex.: Caixa, envelope, pacote...">
                    </div>
                    <div class="mb-3">
                        <label for="data_recebimento" class="form-label">Data de Recebimento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="data_recebimento" name="data_recebimento" required max="{{ now()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label for="codigo_rastreamento" class="form-label">Código de Rastreamento</label>
                        <input type="text" class="form-control" id="codigo_rastreamento" name="codigo_rastreamento" maxlength="100" placeholder="Ex.: OO123456789BR">
                    </div>
                    <div class="mb-3">
                        <label for="origem" class="form-label">Origem</label>
                        <select class="form-select" id="origem" name="origem">
                            <option value="">Selecione uma origem (opcional)</option>
                            @isset($origens)
                                @foreach($origens as $origem)
                                    <option value="{{ $origem->nome_origem }}">{{ $origem->nome_origem }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="retirada" value="0">
                            <input class="form-check-input" type="checkbox" id="retirada" name="retirada" value="1">
                            <label class="form-check-label" for="retirada">Marque se já foi retirada</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Encomenda -->
<div class="modal fade" id="modalEditarEncomenda" tabindex="-1" aria-labelledby="modalEditarEncomendaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarEncomendaLabel">Editar Encomenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarEncomenda" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_morador_id" class="form-label">Morador <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_morador_id" name="morador_id" required>
                            <option value="">Selecione um morador</option>
                            @foreach($moradores as $mor)
                                <option value="{{ $mor->id }}">
                                    {{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero }}
                                    @if(optional($mor->apartamento)->torre)
                                        | {{ $mor->apartamento->torre->nome }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_descricao" name="descricao" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="edit_data_recebimento" class="form-label">Data de Recebimento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_data_recebimento" name="data_recebimento" required max="{{ now()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_codigo_rastreamento" class="form-label">Código de Rastreamento</label>
                        <input type="text" class="form-control" id="edit_codigo_rastreamento" name="codigo_rastreamento" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="edit_origem" class="form-label">Origem</label>
                        <select class="form-select" id="edit_origem" name="origem">
                            <option value="">Selecione uma origem (opcional)</option>
                            @isset($origens)
                                @foreach($origens as $origem)
                                    <option value="{{ $origem->nome_origem }}">{{ $origem->nome_origem }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="retirada" value="0">
                            <input class="form-check-input" type="checkbox" id="edit_retirada" name="retirada" value="1">
                            <label class="form-check-label" for="edit_retirada">Marque se já foi retirada</label>
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

<script>
function editarEncomenda(id, descricao, data, codigo, origem, moradorId, retirada) {
    const form = document.getElementById('formEditarEncomenda');
    form.action = `/encomendas/${id}/edit`;
    
    document.getElementById('edit_descricao').value = descricao;
    document.getElementById('edit_data_recebimento').value = data;
    document.getElementById('edit_codigo_rastreamento').value = codigo;
    document.getElementById('edit_origem').value = origem;
    document.getElementById('edit_morador_id').value = moradorId || '';
    document.getElementById('edit_retirada').checked = retirada;
}
</script>

@endsection