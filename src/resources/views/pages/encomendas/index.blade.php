@extends('layouts.base')
@section('content')

<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-retirado {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    .status-pendente {
        background-color: #fff3cd;
        color: #664d03;
    }
    .encomenda-retirada {
        opacity: 0.7;
        background-color: #f8f9fa;
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
    @forelse($encomendas as $encomenda)
      <li class="list-group-item d-flex justify-content-between align-items-center {{ $encomenda->retirada ? 'encomenda-retirada' : '' }}">
        <div class="d-flex align-items-center flex-grow-1 gap-2 w-100">
          <form id="form-enc-{{ $encomenda->id }}" action="{{ route('encomendas.edit', $encomenda->id) }}" method="POST" class="row g-2 flex-grow-1 align-items-center">
            @csrf
            @method('PUT')

            <div class="col-auto">
              <span class="status-badge {{ $encomenda->retirada ? 'status-retirado' : 'status-pendente' }}" id="status-display-{{ $encomenda->id }}">
                <i class="bi bi-{{ $encomenda->retirada ? 'check-circle-fill' : 'clock-fill' }}"></i>
                {{ $encomenda->retirada ? 'Retirado' : 'Pendente' }}
              </span>
              <div class="form-check d-none" id="status-input-{{ $encomenda->id }}">
                <input type="checkbox" name="retirada" value="1" class="form-check-input" {{ $encomenda->retirada ? 'checked' : '' }}>
                <label class="form-check-label small">Retirado</label>
              </div>
            </div>

            <div class="col-auto">
              <span class="fw-bold" id="descricao-display-{{ $encomenda->id }}">{{ $encomenda->descricao }}</span>
              <input type="text" name="descricao" value="{{ $encomenda->descricao }}" class="form-control form-control-sm d-none" id="descricao-input-{{ $encomenda->id }}" maxlength="255" required>
            </div>

            <div class="col-auto">
              <span class="text-muted small" id="data-display-{{ $encomenda->id }}">{{ $encomenda->data_recebimento->format('d/m/Y') }}</span>
              <input type="date" name="data_recebimento" value="{{ $encomenda->data_recebimento->format('Y-m-d') }}" class="form-control form-control-sm d-none" id="data-input-{{ $encomenda->id }}" required>
            </div>

            <div class="col-auto">
              <span class="text-muted small" id="origem-display-{{ $encomenda->id }}">{{ $encomenda->origem ?? '—' }}</span>
              <input type="text" name="origem" value="{{ $encomenda->origem }}" class="form-control form-control-sm d-none" id="origem-input-{{ $encomenda->id }}" maxlength="100" placeholder="Origem">
            </div>

            <div class="col-auto">
              <span class="text-muted small" id="codigo-display-{{ $encomenda->id }}" title="Código de rastreamento">
                <i class="bi bi-upc-scan"></i> {{ $encomenda->codigo_rastreamento ?? '—' }}
              </span>
              <div class="input-group input-group-sm d-none" id="codigo-input-{{ $encomenda->id }}">
                <input type="text" name="codigo_rastreamento" value="{{ $encomenda->codigo_rastreamento }}" class="form-control form-control-sm" maxlength="100" placeholder="Código">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="scanBarcode({{ $encomenda->id }})" title="Escanear código">
                  <i class="bi bi-upc-scan"></i>
                </button>
              </div>
            </div>

            <div class="col-auto">
              <span class="text-muted small" id="morador-display-{{ $encomenda->id }}">
                {{ optional($encomenda->morador)->nome ?? '—' }}
                @if(optional($encomenda->morador)->apartamento)
                  | Apt {{ $encomenda->morador->apartamento->numero ?? '—' }}
                @endif
              </span>
              <select name="morador_id" id="morador-input-{{ $encomenda->id }}" class="form-select form-select-sm d-none" required>
                <option value="">Selecione um morador</option>
                @isset($moradores)
                  @foreach($moradores as $mor)
                    <option value="{{ $mor->id }}" {{ (string)$encomenda->morador_id === (string)$mor->id ? 'selected' : '' }}>
                      {{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero ?? '?' }}
                    </option>
                  @endforeach
                @endisset
              </select>
            </div>
          </form>
        </div>

        <div class="d-flex align-items-center">
          <button
            type="button"
            class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm me-1"
            id="edit-btn-enc-{{ $encomenda->id }}"
            onclick="enableEditEnc({{ $encomenda->id }})"
            title="Editar"
          ></button>

          <button
            type="button"
            class="btn btn-secondary btn-sm bi bi-x m-1 shadow-sm d-none"
            id="cancel-btn-enc-{{ $encomenda->id }}"
            onclick="cancelEditEnc({{ $encomenda->id }})"
            title="Cancelar edição"
          ></button>

          <form
            action="{{ route('encomendas.delete', $encomenda->id) }}"
            method="POST"
            class="d-inline ms-2"
            onsubmit="return confirm('Tem certeza que deseja excluir esta encomenda?')"
          >
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm bi bi-trash" title="Excluir"></button>
          </form>
        </div>
      </li>
    @empty
      <li class="list-group-item text-center text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Nenhuma encomenda cadastrada
      </li>
    @endforelse
  </ul>

  <div class="mt-3">
      <x-pagination :paginator="$encomendas" :summary="false" align="center" />
  </div>

  <script>
    function enableEditEnc(id) {
      const descricaoInput = document.getElementById(`descricao-input-${id}`);
      const descricaoDisplay = document.getElementById(`descricao-display-${id}`);
      const dataInput = document.getElementById(`data-input-${id}`);
      const dataDisplay = document.getElementById(`data-display-${id}`);
      const origemInput = document.getElementById(`origem-input-${id}`);
      const origemDisplay = document.getElementById(`origem-display-${id}`);
      const codigoInput = document.getElementById(`codigo-input-${id}`);
      const codigoDisplay = document.getElementById(`codigo-display-${id}`);
      const moradorInput = document.getElementById(`morador-input-${id}`);
      const moradorDisplay = document.getElementById(`morador-display-${id}`);
      const statusInput = document.getElementById(`status-input-${id}`);
      const statusDisplay = document.getElementById(`status-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-enc-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-enc-${id}`);
      const form = document.getElementById(`form-enc-${id}`);

      if (!descricaoInput || !descricaoDisplay || !editBtn || !cancelBtn || !form) return;

      descricaoInput.classList.remove('d-none');
      descricaoDisplay.classList.add('d-none');
      if (dataInput && dataDisplay) { dataInput.classList.remove('d-none'); dataDisplay.classList.add('d-none'); }
      if (origemInput && origemDisplay) { origemInput.classList.remove('d-none'); origemDisplay.classList.add('d-none'); }
      if (codigoInput && codigoDisplay) { codigoInput.classList.remove('d-none'); codigoDisplay.classList.add('d-none'); }
      if (moradorInput && moradorDisplay) { moradorInput.classList.remove('d-none'); moradorDisplay.classList.add('d-none'); }
      if (statusInput && statusDisplay) { statusInput.classList.remove('d-none'); statusDisplay.classList.add('d-none'); }

      editBtn.classList.remove('btn-warning', 'bi-pencil-square');
      editBtn.classList.add('btn-success', 'bi-check-lg');
      editBtn.title = 'Salvar';
      editBtn.onclick = function() { form.submit(); };

      cancelBtn.classList.remove('d-none');

      descricaoInput.focus();
      descricaoInput.select();

      const keyHandler = function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'SELECT') {
          e.preventDefault();
          form.submit();
        } else if (e.key === 'Escape') {
          cancelEditEnc(id);
        }
      };
      descricaoInput.onkeydown = keyHandler;
      if (dataInput) dataInput.onkeydown = keyHandler;
      if (origemInput) origemInput.onkeydown = keyHandler;
      if (codigoInput) {
        const codigoInputField = codigoInput.querySelector('input');
        if (codigoInputField) codigoInputField.onkeydown = keyHandler;
      }
    }

    function cancelEditEnc(id) {
      const descricaoInput = document.getElementById(`descricao-input-${id}`);
      const descricaoDisplay = document.getElementById(`descricao-display-${id}`);
      const dataInput = document.getElementById(`data-input-${id}`);
      const dataDisplay = document.getElementById(`data-display-${id}`);
      const origemInput = document.getElementById(`origem-input-${id}`);
      const origemDisplay = document.getElementById(`origem-display-${id}`);
      const codigoInput = document.getElementById(`codigo-input-${id}`);
      const codigoDisplay = document.getElementById(`codigo-display-${id}`);
      const moradorInput = document.getElementById(`morador-input-${id}`);
      const moradorDisplay = document.getElementById(`morador-display-${id}`);
      const statusInput = document.getElementById(`status-input-${id}`);
      const statusDisplay = document.getElementById(`status-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-enc-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-enc-${id}`);

      if (!descricaoInput || !descricaoDisplay || !editBtn || !cancelBtn) return;

      descricaoInput.value = descricaoDisplay.textContent.trim();
      if (dataInput) {
        const dateMatch = dataDisplay.textContent.trim().match(/(\d{2})\/(\d{2})\/(\d{4})/);
        if (dateMatch) dataInput.value = `${dateMatch[3]}-${dateMatch[2]}-${dateMatch[1]}`;
      }
      if (origemInput) origemInput.value = origemDisplay.textContent.trim() === '—' ? '' : origemDisplay.textContent.trim();

      descricaoInput.classList.add('d-none');
      descricaoDisplay.classList.remove('d-none');
      if (dataInput && dataDisplay) { dataInput.classList.add('d-none'); dataDisplay.classList.remove('d-none'); }
      if (origemInput && origemDisplay) { origemInput.classList.add('d-none'); origemDisplay.classList.remove('d-none'); }
      if (codigoInput && codigoDisplay) { codigoInput.classList.add('d-none'); codigoDisplay.classList.remove('d-none'); }
      if (moradorInput && moradorDisplay) { moradorInput.classList.add('d-none'); moradorDisplay.classList.remove('d-none'); }
      if (statusInput && statusDisplay) { statusInput.classList.add('d-none'); statusDisplay.classList.remove('d-none'); }

      editBtn.classList.remove('btn-success', 'bi-check-lg');
      editBtn.classList.add('btn-warning', 'bi-pencil-square');
      editBtn.title = 'Editar';
      editBtn.onclick = function() { enableEditEnc(id); };

      cancelBtn.classList.add('d-none');

      descricaoInput.onkeydown = null;
      if (dataInput) dataInput.onkeydown = null;
      if (origemInput) origemInput.onkeydown = null;
      if (codigoInput) {
        const codigoInputField = codigoInput.querySelector('input');
        if (codigoInputField) codigoInputField.onkeydown = null;
      }
    }

    function scanBarcode(id) {
      alert('Funcionalidade de scanner de código de barras/QR code será implementada com acesso à câmera do dispositivo.');
      // Future implementation: Use HTML5 getUserMedia API or a library like QuaggaJS/ZXing
    }
  </script>
</div>
@endsection