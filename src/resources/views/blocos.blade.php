@extends('base')
@section('content')
<style>
    .bloco-card {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .bloco-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .bloco-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
    }
    
    .bloco-card:hover::before {
        opacity: 1;
    }
    
    .bloco-card .card-body {
        color: white;
        position: relative;
        z-index: 1;
    }
    
    .bloco-card .card-title {
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: white;
    }
    
    .bloco-card .card-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
    }
    
    .bloco-card .btn-warning {
        background-color: rgba(255, 193, 7, 0.9);
        border: none;
        transition: all 0.2s ease;
    }
    
    .bloco-card .btn-warning:hover {
        background-color: #ffc107;
        transform: scale(1.1);
    }
    
    .bloco-card .btn-danger {
        background-color: rgba(220, 53, 69, 0.9);
        border: none;
        transition: all 0.2s ease;
    }
    
    .bloco-card .btn-danger:hover {
        background-color: #dc3545;
        transform: scale(1.1);
    }
    
    .bloco-card .btn-success {
        background-color: rgba(25, 135, 84, 0.9);
        border: none;
        transition: all 0.2s ease;
    }
    
    .bloco-card .btn-success:hover {
        background-color: #198754;
        transform: scale(1.1);
    }
    
    .bloco-card .form-control {
        background-color: rgba(255, 255, 255, 0.95);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #333;
    }
    
    .bloco-card .form-control:focus {
        background-color: white;
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    }
    
    .text-display {
        color: white !important;
    }
    
    /* Alternate colors for variety */
    .bloco-card:nth-child(3n+1) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bloco-card:nth-child(3n+2) {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .bloco-card:nth-child(3n+3) {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .bloco-card:nth-child(3n+1):hover {
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
    }
    
    .bloco-card:nth-child(3n+2):hover {
        box-shadow: 0 12px 24px rgba(245, 87, 108, 0.4);
    }
    
    .bloco-card:nth-child(3n+3):hover {
        box-shadow: 0 12px 24px rgba(79, 172, 254, 0.4);
    }
</style>

<div class="container">
    <h2 class="mb-4">Blocos do Condomínio</h2>
    <a href="{{ route('blocos.create') }}" class="btn btn-primary mb-3">Novo Bloco</a>

    <div class="row">
        @foreach($blocos as $bloco)
            <div class="col-md-4">
                <div class="card mb-3 bloco-card">
                    <div class="card-body">
                        <form id="form-{{ $bloco->id }}" action="{{ route('blocos.edit', $bloco->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <h5 class="card-title">
                                    <span class="text-display" id="name-display-{{ $bloco->id }}">{{ $bloco->nome }}</span>
                                    <input type="text" name="nome" value="{{ $bloco->nome }}" class="form-control d-none w-50" id="name-input-{{ $bloco->id }}">

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm bi bi-pencil-square shadow-sm"
                                    id="edit-btn-{{ $bloco->id }}"
                                    onclick="enableEdit({{ $bloco->id }})"
                                ></button>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm bi bi-x m-1 shadow-sm d-none"
                                    id="cancel-btn-{{ $bloco->id }}"
                                    onclick="cancelEdit({{ $bloco->id }})"
                                    title="Cancelar edição"
                                ></button>

                                </h5>
                                <p class="card-text">Torres: {{ $bloco->qtdTorres ?? '0' }}</p>
                            </form>

                            <!-- Form de deletar separado -->
                            <form
                                action="{{ route('blocos.delete', $bloco->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Tem certeza que deseja deletar este bloco?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mt-3 bi bi-trash"></button>
                            </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function enableEdit(id) {
        const input = document.getElementById(`name-input-${id}`);
        const display = document.getElementById(`name-display-${id}`);
        const editBtn = document.getElementById(`edit-btn-${id}`);
        const cancelBtn = document.getElementById(`cancel-btn-${id}`);
        const form = document.getElementById(`form-${id}`);

        input.classList.remove('d-none');
        display.classList.add('d-none');

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
        const editBtn = document.getElementById(`edit-btn-${id}`);
        const cancelBtn = document.getElementById(`cancel-btn-${id}`);

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
@endsection
