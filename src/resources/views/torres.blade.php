@extends('base')
@section('content')
<div class="container">
  <h2 class="mb-4">Torres do Condomínio</h2>

  <a href="{{ route('torres.create') }}" class="btn btn-primary mb-3">Nova Torre</a>

  <table class="table table-striped table-bordered">
    <thead class="table-light">
      <tr>
        <th>Nome</th>
        <th>Nome do bloco</th>
        <th style="width: 150px;" class="text-center">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($torres as $torre)
        <tr>
          <td>
            <form id="form-{{ $torre->id }}" action="{{ route('torres.edit', $torre->id) }}" method="POST" class="d-inline">
              @csrf
              @method('PUT')
              <span class="text-display" id="name-display-{{ $torre->id }}">{{ $torre->nome }}</span>
              <input type="text" name="nome" value="{{ $torre->nome }}" class="form-control d-none" id="name-input-{{ $torre->id }}">
            </form>
          </td>
          <td>{{ $torre->bloco->nome ?? 'N/A' }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center align-items-center">
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
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <script>
    function enableEdit(id) {
      const input = document.getElementById(`name-input-${id}`);
      const display = document.getElementById(`name-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-${id}`);
      const form = document.getElementById(`form-${id}`);

      if (!input || !display || !editBtn || !cancelBtn || !form) return;

      input.classList.remove('d-none');
      display.classList.add('d-none');

      // change edit btn to submit/check
      editBtn.classList.remove('btn-warning', 'bi-pencil-square');
      editBtn.classList.add('btn-success', 'bi-check-lg');

      // when clicked now, submit the related form
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
      const editBtn = document.getElementById(`edit-btn-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-${id}`);
      const form = document.getElementById(`form-${id}`);

      if (!input || !display || !editBtn || !cancelBtn || !form) return;

      input.value = display.textContent.trim();

      input.classList.add('d-none');
      display.classList.remove('d-none');

      editBtn.classList.remove('btn-success', 'bi-check-lg');
      editBtn.classList.add('btn-warning', 'bi-pencil-square');

      editBtn.onclick = function() { enableEdit(id); };

      cancelBtn.classList.add('d-none');

      input.onkeydown = null;
    }
  </script>
</div>
@endsection