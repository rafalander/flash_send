@extends('layouts.base')
@section('content')


<style>
    .totalmoradores {
        background-color: #f1f1f1;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
        border-radius: 18px;
    }
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

<div class="container">
  <h2 class="mb-4">Encomendas</h2>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('encomendas.create') }}" class="btn btn-primary">Nova Encomenda</a>
    <x-count 
      :total="$encomendas->count()" 
      label="Total:" 
    />

  </div>

  <x-search
    :action="route('encomendas.search')" 
    placeholder="Buscar encomenda..."
  />

  <ul class="list-group">
    @foreach($encomendas as $encomenda)
      <li class="list-group-item encomenda-item {{ $encomenda->retirada ? 'encomenda-retirada' : '' }}" id="encomenda-item-{{ $encomenda->id }}">
        <div class="row g-2 align-items-center">
          
          <!-- Coluna Principal: Morador e Apartamento (DESTAQUE) -->
          <div class="col-md-3">
            <form id="form-enc-{{ $encomenda->id }}" action="{{ route('encomendas.edit', $encomenda->id) }}" method="POST">
              @csrf
              @method('PUT')
              
              <div class="mb-1">
                <i class="bi bi-person-fill text-primary me-1"></i>
                <span class="info-destaque" id="morador-display-{{ $encomenda->id }}">
                  {{ optional($encomenda->morador)->nome ?? '—' }}
                </span>
                <select name="morador_id" id="morador-input-{{ $encomenda->id }}" class="form-select form-select-sm d-none">
                  <option value="">Selecione um morador</option>
                  @isset($moradores)
                    @foreach($moradores as $mor)
                      <option value="{{ $mor->id }}" {{ (string)$encomenda->morador_id === (string)$mor->id ? 'selected' : '' }}>
                        {{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero }}
                      </option>
                    @endforeach
                  @endisset
                </select>
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
            </form>
          </div>

          <!-- Coluna: Data e Descrição -->
          <div class="col-md-4">
            <div class="mb-1">
              <i class="bi bi-calendar-event text-success me-1"></i>
              <span class="info-destaque" id="data_recebimento-display-{{ $encomenda->id }}">
                {{ \Carbon\Carbon::parse($encomenda->data_recebimento)->format('d/m/Y') }}
              </span>
              <input 
                type="date" 
                form="form-enc-{{ $encomenda->id }}"
                name="data_recebimento" 
                value="{{ $encomenda->data_recebimento }}" 
                class="form-control form-control-sm d-none" 
                id="data_recebimento-input-{{ $encomenda->id }}" 
                max="{{ now()->toDateString() }}"
              >
            </div>
            
            <div class="info-secundaria">
              <i class="bi bi-box-seam me-1"></i>
              <span id="descricao-display-{{ $encomenda->id }}">{{ $encomenda->descricao }}</span>
              <input 
                type="text" 
                form="form-enc-{{ $encomenda->id }}"
                name="descricao" 
                value="{{ $encomenda->descricao }}" 
                class="form-control form-control-sm d-none" 
                id="descricao-input-{{ $encomenda->id }}" 
                maxlength="255"
              >
            </div>
          </div>

          <!-- Coluna: Rastreamento e Origem -->
          <div class="col-md-3">
            <div class="mb-1 info-secundaria">
              <i class="bi bi-upc-scan me-1"></i>
              <span id="codigo_rastreamento-display-{{ $encomenda->id }}">
                {{ $encomenda->codigo_rastreamento ?? '—' }}
              </span>
              <input 
                type="text" 
                form="form-enc-{{ $encomenda->id }}"
                name="codigo_rastreamento" 
                value="{{ $encomenda->codigo_rastreamento }}" 
                class="form-control form-control-sm d-none" 
                id="codigo_rastreamento-input-{{ $encomenda->id }}" 
                maxlength="100"
              >
            </div>
            
            <div class="info-secundaria">
              <i class="bi bi-truck me-1"></i>
              <span id="origem-display-{{ $encomenda->id }}">{{ $encomenda->origem ?? '—' }}</span>
              <input 
                type="text" 
                form="form-enc-{{ $encomenda->id }}"
                name="origem" 
                value="{{ $encomenda->origem }}" 
                class="form-control form-control-sm d-none" 
                id="origem-input-{{ $encomenda->id }}" 
                maxlength="150"
              >
            </div>
          </div>

          <!-- Coluna: Status e Ações -->
          <div class="col-md-2 text-end">
            <!-- Check de Retirada -->
            <div class="mb-2">
              <div class="form-check form-switch d-inline-block" id="retirada-display-{{ $encomenda->id }}">
                <input 
                  type="checkbox" 
                  class="form-check-input" 
                  id="retirada-quick-{{ $encomenda->id }}" 
                  {{ $encomenda->retirada ? 'checked' : '' }}
                  onchange="toggleRetirada({{ $encomenda->id }}, this.checked)"
                  style="cursor: pointer;"
                >
                <label class="form-check-label" for="retirada-quick-{{ $encomenda->id }}" style="cursor: pointer;">
                  <span class="badge {{ $encomenda->retirada ? 'bg-success' : 'bg-secondary' }} badge-retirada" id="badge-retirada-{{ $encomenda->id }}">
                    {{ $encomenda->retirada ? 'Retirada' : 'Pendente' }}
                  </span>
                </label>
              </div>
              
              <div class="form-check form-switch d-none" id="retirada-input-wrapper-{{ $encomenda->id }}">
                <input type="hidden" form="form-enc-{{ $encomenda->id }}" name="retirada" value="0">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  role="switch" 
                  form="form-enc-{{ $encomenda->id }}"
                  name="retirada" 
                  id="retirada-input-{{ $encomenda->id }}" 
                  value="1" 
                  {{ $encomenda->retirada ? 'checked' : '' }}
                >
                <label class="form-check-label small" for="retirada-input-{{ $encomenda->id }}">
                  Retirada
                </label>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex gap-2 justify-content-end">
              <button
                type="button"
                class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm"
                id="edit-btn-enc-{{ $encomenda->id }}"
                onclick="enableEditEnc({{ $encomenda->id }})"
                title="Editar"
              ></button>

              <button
                type="button"
                class="btn btn-secondary btn-sm bi bi-x shadow-sm d-none"
                id="cancel-btn-enc-{{ $encomenda->id }}"
                onclick="cancelEditEnc({{ $encomenda->id }})"
                title="Cancelar edição"
              ></button>

              <form
                action="{{ route('encomendas.delete', $encomenda->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Tem certeza que deseja excluir esta encomenda?')"
              >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm bi bi-trash" title="Excluir"></button>
              </form>
            </div>
          </div>

        </div>
      </li>
    @endforeach
  </ul>

  <div class="mt-3">
      <x-pagination :paginator="$encomendas" :summary="false" align="center" />
  </div>

  <script>
    // Toggle rápido de retirada
    function toggleRetirada(id, checked) {
      const item = document.getElementById(`encomenda-item-${id}`);
      const badge = document.getElementById(`badge-retirada-${id}`);
      const form = document.getElementById(`form-enc-${id}`);
      
      if (item && badge && form) {
        // Adiciona/remove classe verde
        if (checked) {
          item.classList.add('encomenda-retirada');
          badge.textContent = 'Retirada';
          badge.classList.remove('bg-secondary');
          badge.classList.add('bg-success');
        } else {
          item.classList.remove('encomenda-retirada');
          badge.textContent = 'Pendente';
          badge.classList.remove('bg-success');
          badge.classList.add('bg-secondary');
        }
        
        // Cria input hidden temporário e submete o form
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'retirada';
        input.value = checked ? '1' : '0';
        form.appendChild(input);
        form.submit();
      }
    }

    function enableEditEnc(id) {
      const descricaoInput = document.getElementById(`descricao-input-${id}`);
      const descricaoDisplay = document.getElementById(`descricao-display-${id}`);
      const dataInput = document.getElementById(`data_recebimento-input-${id}`);
      const dataDisplay = document.getElementById(`data_recebimento-display-${id}`);
      const codigoInput = document.getElementById(`codigo_rastreamento-input-${id}`);
      const codigoDisplay = document.getElementById(`codigo_rastreamento-display-${id}`);
      const origemInput = document.getElementById(`origem-input-${id}`);
      const origemDisplay = document.getElementById(`origem-display-${id}`);
      const retiradaWrapper = document.getElementById(`retirada-input-wrapper-${id}`);
      const retiradaQuickDisplay = document.getElementById(`retirada-display-${id}`);
      const moradorInput = document.getElementById(`morador-input-${id}`);
      const moradorDisplay = document.getElementById(`morador-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-enc-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-enc-${id}`);
      const form = document.getElementById(`form-enc-${id}`);

      if (!descricaoInput || !descricaoDisplay || !editBtn || !cancelBtn || !form) return;

      descricaoInput.classList.remove('d-none');
      descricaoDisplay.classList.add('d-none');
      if (dataInput && dataDisplay) { dataInput.classList.remove('d-none'); dataDisplay.classList.add('d-none'); }
      if (codigoInput && codigoDisplay) { codigoInput.classList.remove('d-none'); codigoDisplay.classList.add('d-none'); }
      if (origemInput && origemDisplay) { origemInput.classList.remove('d-none'); origemDisplay.classList.add('d-none'); }
      if (retiradaWrapper && retiradaQuickDisplay) { retiradaWrapper.classList.remove('d-none'); retiradaQuickDisplay.classList.add('d-none'); }
      if (moradorInput && moradorDisplay) { moradorInput.classList.remove('d-none'); moradorDisplay.classList.add('d-none'); }

      editBtn.classList.remove('btn-warning', 'bi-pencil-square');
      editBtn.classList.add('btn-success', 'bi-check-lg');
      editBtn.title = 'Salvar';
      editBtn.onclick = function() { form.submit(); };

      cancelBtn.classList.remove('d-none');

      descricaoInput.focus();
      descricaoInput.select();

      const keyHandler = function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          form.submit();
        } else if (e.key === 'Escape') {
          cancelEditEnc(id);
        }
      };
      descricaoInput.onkeydown = keyHandler;
      if (dataInput) dataInput.onkeydown = keyHandler;
      if (codigoInput) codigoInput.onkeydown = keyHandler;
      if (origemInput) origemInput.onkeydown = keyHandler;
      if (moradorInput) moradorInput.onkeydown = keyHandler;
    }

    function cancelEditEnc(id) {
      const descricaoInput = document.getElementById(`descricao-input-${id}`);
      const descricaoDisplay = document.getElementById(`descricao-display-${id}`);
      const dataInput = document.getElementById(`data_recebimento-input-${id}`);
      const dataDisplay = document.getElementById(`data_recebimento-display-${id}`);
      const codigoInput = document.getElementById(`codigo_rastreamento-input-${id}`);
      const codigoDisplay = document.getElementById(`codigo_rastreamento-display-${id}`);
      const origemInput = document.getElementById(`origem-input-${id}`);
      const origemDisplay = document.getElementById(`origem-display-${id}`);
      const retiradaWrapper = document.getElementById(`retirada-input-wrapper-${id}`);
      const retiradaQuickDisplay = document.getElementById(`retirada-display-${id}`);
      const moradorInput = document.getElementById(`morador-input-${id}`);
      const moradorDisplay = document.getElementById(`morador-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-enc-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-enc-${id}`);

      if (!descricaoInput || !descricaoDisplay || !editBtn || !cancelBtn) return;

      descricaoInput.value = descricaoDisplay.textContent.trim();
      if (dataInput && dataDisplay) {
        // Converte de volta para formato ISO
        const displayText = dataDisplay.textContent.trim();
        const parts = displayText.split('/');
        if (parts.length === 3) {
          dataInput.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
      }
      if (codigoInput && codigoDisplay) {
        const text = codigoDisplay.textContent.trim();
        codigoInput.value = text === '—' ? '' : text;
      }
      if (origemInput && origemDisplay) {
        const text = origemDisplay.textContent.trim();
        origemInput.value = text === '—' ? '' : text;
      }

      descricaoInput.classList.add('d-none');
      descricaoDisplay.classList.remove('d-none');
      if (dataInput && dataDisplay) { dataInput.classList.add('d-none'); dataDisplay.classList.remove('d-none'); }
      if (codigoInput && codigoDisplay) { codigoInput.classList.add('d-none'); codigoDisplay.classList.remove('d-none'); }
      if (origemInput && origemDisplay) { origemInput.classList.add('d-none'); origemDisplay.classList.remove('d-none'); }
      if (retiradaWrapper && retiradaQuickDisplay) { retiradaWrapper.classList.add('d-none'); retiradaQuickDisplay.classList.remove('d-none'); }
      if (moradorInput && moradorDisplay) { moradorInput.classList.add('d-none'); moradorDisplay.classList.remove('d-none'); }

      editBtn.classList.remove('btn-success', 'bi-check-lg');
      editBtn.classList.add('btn-warning', 'bi-pencil-square');
      editBtn.title = 'Editar';
      editBtn.onclick = function() { enableEditEnc(id); };

      cancelBtn.classList.add('d-none');

      descricaoInput.onkeydown = null;
      if (dataInput) dataInput.onkeydown = null;
      if (codigoInput) codigoInput.onkeydown = null;
      if (origemInput) origemInput.onkeydown = null;
      if (moradorInput) moradorInput.onkeydown = null;
    }
  </script>
</div>
@endsection