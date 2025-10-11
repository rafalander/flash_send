@extends('base')
@section('content')
<div class="container mt-4">
	<div class="card col-md-8 mx-auto">
		<div class="card-header">
			<h5 class="mb-0">Cadastrar Apartamento</h5>
		</div>
		<div class="card-body">
			<form action="{{ route('apartamentos.store') }}" method="POST" novalidate>
				@csrf

				<div class="mb-3">
					<label for="numero" class="form-label">Número do Apartamento</label>
					<input
						type="text"
						name="numero"
						id="numero"
						class="form-control @error('numero') is-invalid @enderror"
						value="{{ old('numero') }}"
						required
						maxlength="10"
						placeholder="Ex.: 101, 12B"
					>
					@error('numero')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="torre_id" class="form-label">Torre</label>
					<select
						name="torre_id"
						id="torre_id"
						class="form-select @error('torre_id') is-invalid @enderror"
						required
						@if($torres->isEmpty()) disabled @endif
					>
						<option value="">Selecione uma torre</option>
						@forelse($torres as $torre)
							<option value="{{ $torre->id }}" {{ (string)old('torre_id') === (string)$torre->id ? 'selected' : '' }}>
								{{ $torre->nome ?? "Torre #{$torre->id}" }}
								@if($torre->bloco)
									({{ $torre->bloco->nome ?? "Bloco #{$torre->bloco->id}" }})
								@endif
							</option>
						@empty
							<option value="" disabled>Nenhuma torre disponível</option>
						@endforelse
					</select>
					@error('torre_id')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
					@if($torres->isEmpty())
						<div class="alert alert-warning mt-2" role="alert">
							Nenhuma torre cadastrada. <a href="{{ route('torres.create') }}">Cadastre uma torre</a> antes de criar apartamentos.
						</div>
					@endif
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary" @if($torres->isEmpty()) disabled title="Cadastre uma torre primeiro" @endif>Salvar</button>
					<a href="{{ route('apartamentos.index') }}" class="btn btn-secondary">Cancelar</a>
				</div>
			</form>
		</div>
	</div>
	</div>
    <div class="container mt-4 col-md-5 mx-auto">
        <h5 class="mb-3">Importar multiplos Apartamentos</h5>
        <form action="{{ route('apartamentos.import') }}" method="POST" enctype="multipart/form-data" class="mb-3 d-flex align-items-center gap-2">
            @csrf
            <input type="file" name="file" class="form-control" accept=".csv,.xls,.xlsx" required>
            <button type="submit" class="btn btn-success w-auto" placeholder="importe um arquivo csv ou xls">Importar</button>
        </form>
		<div class="text-muted small">
			Campos esperados (por coluna): nome, torre_id.
		</div>
    </div>
@endsection