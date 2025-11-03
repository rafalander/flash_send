@extends('layouts.base')
@section('content')

<style>
  .torre-item {
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
  .list-group-item {
    padding: 0.75rem 1rem;
  }
</style>

<div class="container">
  <h2 class="mb-4">Torres do Condomínio</h2>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('torres.create') }}" class="btn btn-primary">Nova Torre</a>
    <x-count 
      :total="$torres->count()" 
      label="Total:" 
    />
  </div>

  <ul class="list-group">
    @foreach($torres as $torre)
      <li class="list-group-item torre-item" id="torre-item-{{ $torre->id }}">
        <div class="row g-2 align-items-center">
          <div class="col-md-10">
            <form id="form-{{ $torre->id }}" action="{{ route('torres.edit', $torre->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row g-2">
                <!-- Nome da Torre -->
                <div class="col-md-6">
                  <div class="mb-1">
                    <i class="bi bi-building text-primary me-1"></i>
                    <span class="info-destaque" id="name-display-{{ $torre->id }}">{{ $torre->nome }}</span>
                    <input type="text" name="nome" value="{{ $torre->nome }}" class="form-control form-control-sm d-none" id="name-input-{{ $torre->id }}" maxlength="100">
                  </div>
                  <div class="info-secundaria">
                    <i class="bi bi-layers me-1"></i>
                    <span id="bloco-display-{{ $torre->id }}">{{ $torre->bloco->nome ?? '—' }}</span>
                    <select name="bloco_id" id="bloco-input-{{ $torre->id }}" class="form-select form-select-sm d-none">
                      <option value="">Selecione um bloco</option>
                      @isset($blocos)
                        @foreach($blocos as $bloco)
                          <option value="{{ $bloco->id }}" {{ (string)$torre->bloco_id === (string)$bloco->id ? 'selected' : '' }}>
                            {{ $bloco->nome ?? "Bloco #{$bloco->id}" }}
                          </option>
                        @endforeach
                      @endisset
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="col-md-2 text-end">
            <button
              type="button"
              class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm me-1"
              id="edit-btn-{{ $torre->id }}"
              onclick="enableEdit({{ $torre->id }})"
              title="Editar"
            ></button>

            <button
              type="button"
              class="btn btn-secondary btn-sm bi bi-x shadow-sm d-none"
              id="cancel-btn-{{ $torre->id }}"
              onclick="cancelEdit({{ $torre->id }})"
              title="Cancelar edição"
            ></button>

            <form
              action="{{ route('torres.delete', $torre->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Tem certeza que deseja deletar esta torre?')"
            >
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm bi bi-trash" title="Excluir"></button>
            </form>
          </div>
        </div>
      </li>
    @endforeach
  </ul>

  <script>
    function enableEdit(id) {
      const input = document.getElementById(`name-input-${id}`);
      const display = document.getElementById(`name-display-${id}`);
      const blocoDisplay = document.getElementById(`bloco-display-${id}`);
      const blocoInput = document.getElementById(`bloco-input-${id}`);
      const editBtn = document.getElementById(`edit-btn-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-${id}`);
      const form = document.getElementById(`form-${id}`);

      if (!input || !display || !editBtn || !cancelBtn || !form) return;

      input.classList.remove('d-none');
      display.classList.add('d-none');
      if (blocoDisplay && blocoInput) {
        blocoDisplay.classList.add('d-none');
        blocoInput.classList.remove('d-none');
      }

      editBtn.classList.remove('btn-warning', 'bi-pencil-square');
      editBtn.classList.add('btn-success', 'bi-check-lg');
      editBtn.title = 'Salvar';

      editBtn.onclick = function() { form.submit(); };

      cancelBtn.classList.remove('d-none');

      input.focus();
      input.select();

      input.onkeydown = function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          form.submit();
        } else if (e.key === 'Escape') {
          cancelEdit(id);
        }
      };
    }

    function cancelEdit(id) {
      const input = document.getElementById(`name-input-${id}`);
      const display = document.getElementById(`name-display-${id}`);
      const blocoDisplay = document.getElementById(`bloco-display-${id}`);
      const blocoInput = document.getElementById(`bloco-input-${id}`);
      const editBtn = document.getElementById(`edit-btn-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-${id}`);
      const form = document.getElementById(`form-${id}`);

      if (!input || !display || !editBtn || !cancelBtn || !form) return;

      input.value = display.textContent.trim();

      input.classList.add('d-none');
      display.classList.remove('d-none');
      if (blocoDisplay && blocoInput) {
        blocoInput.classList.add('d-none');
        blocoDisplay.classList.remove('d-none');
      }

      editBtn.classList.remove('btn-success', 'bi-check-lg');
      editBtn.classList.add('btn-warning', 'bi-pencil-square');
      editBtn.title = 'Editar';

      editBtn.onclick = function() { enableEdit(id); };

      cancelBtn.classList.add('d-none');

      input.onkeydown = null;
    }
  </script>
</div>
@endsection