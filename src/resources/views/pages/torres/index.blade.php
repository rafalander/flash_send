@extends('layouts.base')
@section('content')
<div class="container">
  <h2 class="mb-4">Torres do Condomínio</h2>

  <a href="{{ route('torres.create') }}" class="btn btn-primary mb-3">Nova Torre</a>

  <ul class="list-group">
    @foreach($torres as $torre)
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center flex-grow-1 gap-2">
          <form id="form-{{ $torre->id }}" action="{{ route('torres.edit', $torre->id) }}" method="POST" class="d-flex align-items-center flex-grow-1 gap-2">
            @csrf
            @method('PUT')
            <span class="text-display me-2" id="name-display-{{ $torre->id }}">{{ $torre->nome }}</span>
            <input type="text" name="nome" value="{{ $torre->nome }}" class="form-control d-none w-auto me-2" id="name-input-{{ $torre->id }}">
            
            <span class="text-muted small" id="bloco-display-{{ $torre->id }}">{{ $torre->bloco->nome ?? 'N/A' }}</span>
            <select name="bloco_id" id="bloco-input-{{ $torre->id }}" class="form-select form-select-sm d-none w-auto">
              <option value="">Selecione um bloco</option>
              @isset($blocos)
                @foreach($blocos as $bloco)
                  <option value="{{ $bloco->id }}" {{ (string)$torre->bloco_id === (string)$bloco->id ? 'selected' : '' }}>
                    {{ $bloco->nome ?? "Bloco #{$bloco->id}" }}
                  </option>
                @endforeach
              @endisset
            </select>
          </form>
        </div>

        <div class="d-flex align-items-center">
          <button
            type="button"
            class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm me-1"
            id="edit-btn-{{ $torre->id }}"
            onclick="enableEdit({{ $torre->id }})"
          ></button>

          <button
            type="button"
            class="btn btn-secondary btn-sm bi bi-x m-1 shadow-sm d-none"
            id="cancel-btn-{{ $torre->id }}"
            onclick="cancelEdit({{ $torre->id }})"
            title="Cancelar edição"
          ></button>

          <form
            action="{{ route('torres.delete', $torre->id) }}"
            method="POST"
            class="d-inline ms-2"
            onsubmit="return confirm('Tem certeza que deseja deletar esta torre?')"
          >
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm bi bi-trash"></button>
          </form>
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

      editBtn.onclick = function() { enableEdit(id); };

      cancelBtn.classList.add('d-none');

      input.onkeydown = null;
    }
  </script>
</div>
@endsection