@extends('layouts.base')
@section('content')


<style>
    .totalmoradores {
        background-color: #f1f1f1;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
        border-radius: 18px;
    }
  /* Alinhado ao visual da listagem de encomendas */
  .morador-item {
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
  <h2 class="mb-4">Moradores</h2>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('moradores.create') }}" class="btn btn-primary">Novo Morador</a>
    <x-count 
      :total="$moradores->count()" 
      label="Total:" 
    />

  </div>

  <x-search
    :action="route('moradores.search')" 
    placeholder="Buscar morador..."
  />

  <ul class="list-group">
    @foreach($moradores as $morador)
      <li class="list-group-item morador-item" id="morador-item-{{ $morador->id }}">
        <div class="row g-2 align-items-center">
          <!-- Conteúdo principal em formulário (nome, email, endereço, docs) -->
          <div class="col-md-10">
            <form id="form-mor-{{ $morador->id }}" action="{{ route('moradores.edit', $morador->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row g-2">
                <!-- Nome + Email -->
                <div class="col-md-4">
                  <div class="mb-1 text-truncate">
                    <i class="bi bi-person-fill text-primary me-1"></i>
                    <span class="info-destaque" id="nome-display-{{ $morador->id }}">{{ $morador->nome }}</span>
                    <input type="text" name="nome" value="{{ $morador->nome }}" class="form-control form-control-sm d-none" id="nome-input-{{ $morador->id }}" maxlength="100">
                  </div>
                  <div class="info-secundaria text-truncate">
                    <i class="bi bi-envelope me-1"></i>
                    <span id="email-display-{{ $morador->id }}">{{ $morador->email }}</span>
                    <input type="email" name="email" value="{{ $morador->email }}" class="form-control form-control-sm d-none" id="email-input-{{ $morador->id }}" maxlength="150">
                  </div>
                </div>

                <!-- Apartamento / Torre / Bloco -->
                <div class="col-md-4">
                  <div class="mb-1 info-secundaria text-truncate">
                    <i class="bi bi-building me-1"></i>
                    <span id="apartamento-display-{{ $morador->id }}">
                      Apt {{ optional($morador->apartamento)->numero ?? '—' }}
                      @if(optional($morador->apartamento)->torre)
                        | {{ $morador->apartamento->torre->nome ?? 'Torre' }}
                      @endif
                      @if(optional(optional($morador->apartamento)->torre)->bloco)
                        | {{ $morador->apartamento->torre->bloco->nome ?? 'Bloco' }}
                      @endif
                    </span>
                  </div>
                  <select name="apartamento_id" id="apartamento-input-{{ $morador->id }}" class="form-select form-select-sm d-none">
                    <option value="">Selecione um apartamento</option>
                    @isset($apartamentos)
                      @foreach($apartamentos as $apt)
                        <option value="{{ $apt->id }}" {{ (string)$morador->apartamento_id === (string)$apt->id ? 'selected' : '' }}>
                          {{ $apt->numero }} — {{ optional($apt->torre)->nome ?? 'Torre' }}{{ optional(optional($apt->torre)->bloco)->nome ? ' | ' . optional(optional($apt->torre)->bloco)->nome : '' }}
                        </option>
                      @endforeach
                    @endisset
                  </select>
                </div>

                <!-- Documentos e Contato -->
                <div class="col-md-4">
                  <div class="info-secundaria mb-1 text-truncate">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    <span id="cpf-display-{{ $morador->id }}">{{ $morador->cpf ?? '—' }}</span>
                    <input type="text" name="cpf" value="{{ $morador->cpf }}" class="form-control form-control-sm d-none" id="cpf-input-{{ $morador->id }}" maxlength="14">
                  </div>
                  <div class="info-secundaria text-truncate">
                    <i class="bi bi-telephone me-1"></i>
                    <span id="telefone-display-{{ $morador->id }}">{{ $morador->telefone ?? '—' }}</span>
                    <input type="text" name="telefone" value="{{ $morador->telefone }}" class="form-control form-control-sm d-none" id="telefone-input-{{ $morador->id }}" maxlength="20">
                  </div>
                </div>
              </div>

            </form>
          </div>

          <!-- Ações -->
          <div class="col-md-2 text-end">
            <div class="d-flex gap-2 justify-content-end">
              <button
                type="button"
                class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm"
                id="edit-btn-mor-{{ $morador->id }}"
                onclick="enableEditMor({{ $morador->id }})"
                title="Editar"
              ></button>

              <button
                type="button"
                class="btn btn-secondary btn-sm bi bi-x shadow-sm d-none"
                id="cancel-btn-mor-{{ $morador->id }}"
                onclick="cancelEditMor({{ $morador->id }})"
                title="Cancelar edição"
              ></button>

              <form
                action="{{ route('moradores.delete', $morador->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Tem certeza que deseja excluir este morador?')"
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
      <x-pagination :paginator="$moradores" :summary="false" align="center" />
  </div>

  <script>
    function enableEditMor(id) {
      const nomeInput = document.getElementById(`nome-input-${id}`);
      const nomeDisplay = document.getElementById(`nome-display-${id}`);
      const emailInput = document.getElementById(`email-input-${id}`);
      const emailDisplay = document.getElementById(`email-display-${id}`);
      const telefoneInput = document.getElementById(`telefone-input-${id}`);
      const cpfInput = document.getElementById(`cpf-input-${id}`);
      const telefoneDisplay = document.getElementById(`telefone-display-${id}`);
      const cpfDisplay = document.getElementById(`cpf-display-${id}`);
      const aptInput = document.getElementById(`apartamento-input-${id}`);
      const aptDisplay = document.getElementById(`apartamento-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-mor-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-mor-${id}`);
      const form = document.getElementById(`form-mor-${id}`);

      if (!nomeInput || !nomeDisplay || !editBtn || !cancelBtn || !form) return;

      nomeInput.classList.remove('d-none');
      nomeDisplay.classList.add('d-none');
      if (emailInput && emailDisplay) { emailInput.classList.remove('d-none'); emailDisplay.classList.add('d-none'); }
            if (cpfInput && cpfDisplay) { cpfInput.classList.remove('d-none'); cpfDisplay.classList.add('d-none'); }
            if (telefoneInput && telefoneDisplay) { telefoneInput.classList.remove('d-none'); telefoneDisplay.classList.add('d-none'); }
      if (aptInput && aptDisplay) { aptInput.classList.remove('d-none'); aptDisplay.classList.add('d-none'); }

      editBtn.classList.remove('btn-warning', 'bi-pencil-square');
      editBtn.classList.add('btn-success', 'bi-check-lg');
      editBtn.title = 'Salvar';
      editBtn.onclick = function() { form.submit(); };

      cancelBtn.classList.remove('d-none');

      nomeInput.focus();
      nomeInput.select();

      const keyHandler = function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          form.submit();
        } else if (e.key === 'Escape') {
          cancelEditMor(id);
        }
      };
      nomeInput.onkeydown = keyHandler;
      if (emailInput) emailInput.onkeydown = keyHandler;
            if (cpfInput) cpfInput.onkeydown = keyHandler;
            if (telefoneInput) telefoneInput.onkeydown = keyHandler;
      if (aptInput) aptInput.onkeydown = keyHandler;
    }

    function cancelEditMor(id) {
      const nomeInput = document.getElementById(`nome-input-${id}`);
      const nomeDisplay = document.getElementById(`nome-display-${id}`);
      const emailInput = document.getElementById(`email-input-${id}`);
      const emailDisplay = document.getElementById(`email-display-${id}`);
      const telefoneInput = document.getElementById(`telefone-input-${id}`);
      const cpfInput = document.getElementById(`cpf-input-${id}`);
      const telefoneDisplay = document.getElementById(`telefone-display-${id}`);
      const cpfDisplay = document.getElementById(`cpf-display-${id}`);
      const aptInput = document.getElementById(`apartamento-input-${id}`);
      const aptDisplay = document.getElementById(`apartamento-display-${id}`);
      const editBtn = document.getElementById(`edit-btn-mor-${id}`);
      const cancelBtn = document.getElementById(`cancel-btn-mor-${id}`);

      if (!nomeInput || !nomeDisplay || !editBtn || !cancelBtn) return;

      nomeInput.value = nomeDisplay.textContent.trim();
      if (emailInput) emailInput.value = emailDisplay.textContent.trim();
            if (cpfInput) cpfInput.value = cpfDisplay.textContent.trim();
            if (telefoneInput) telefoneInput.value = telefoneDisplay.textContent.trim();

      nomeInput.classList.add('d-none');
      nomeDisplay.classList.remove('d-none');
      if (emailInput && emailDisplay) { emailInput.classList.add('d-none'); emailDisplay.classList.remove('d-none'); }
            if (cpfInput && cpfDisplay) { cpfInput.classList.add('d-none'); cpfDisplay.classList.remove('d-none'); }
            if (telefoneInput && telefoneDisplay) { telefoneInput.classList.add('d-none'); telefoneDisplay.classList.remove('d-none'); }
      if (aptInput && aptDisplay) { aptInput.classList.add('d-none'); aptDisplay.classList.remove('d-none'); }

      editBtn.classList.remove('btn-success', 'bi-check-lg');
      editBtn.classList.add('btn-warning', 'bi-pencil-square');
      editBtn.title = 'Editar';
      editBtn.onclick = function() { enableEditMor(id); };

      cancelBtn.classList.add('d-none');

      nomeInput.onkeydown = null;
      if (emailInput) emailInput.onkeydown = null;
            if (cpfInput) cpfInput.onkeydown = null;
            if (telefoneInput) telefoneInput.onkeydown = null;
      if (aptInput) aptInput.onkeydown = null;
    }
  </script>
</div>
@endsection