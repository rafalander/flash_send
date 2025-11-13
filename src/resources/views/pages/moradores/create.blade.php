@extends('layouts.base')
@section('content')
<div class="container mt-4">
	<div class="card col-md-8 mx-auto">
		<div class="card-header">
			<h5 class="mb-0">Cadastrar Morador</h5>
		</div>
		<div class="card-body">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form action="{{ route('moradores.store') }}" method="POST" novalidate>
				@csrf

				<div class="mb-3">
					<label for="nome" class="form-label">Nome</label>
					<input
						type="text"
						name="nome"
						id="nome"
						class="form-control @error('nome') is-invalid @enderror"
						value="{{ old('nome') }}"
						required
						maxlength="150"
						placeholder="Nome completo"
					>
					@error('nome')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="email" class="form-label">E-mail</label>
					<input
						type="email"
						name="email"
						id="email"
						class="form-control @error('email') is-invalid @enderror"
						value="{{ old('email') }}"
						required
						maxlength="150"
						placeholder="exemplo@dominio.com"
					>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="cpf" class="form-label">CPF</label>
					<input
						type="text"
						name="cpf"
						id="cpf"
						class="form-control @error('cpf') is-invalid @enderror"
						value="{{ old('cpf') }}"
						required
						maxlength="14"
						placeholder="000.000.000-00"
						data-mask="cpf"
					>
					@error('cpf')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="telefone" class="form-label">Telefone</label>
					<input
						type="text"
						name="telefone"
						id="telefone"
						class="form-control @error('telefone') is-invalid @enderror"
						value="{{ old('telefone') }}"
						maxlength="20"
						placeholder="(00) 00000-0000"
						data-mask="telefone"
					>
					@error('telefone')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="apartamento_id" class="form-label">Apartamento</label>
					<select
						name="apartamento_id"
						id="apartamento_id"
						class="form-select @error('apartamento_id') is-invalid @enderror"
						required
						@if($apartamentos->isEmpty()) disabled @endif
					>
						<option value="">Selecione um apartamento</option>
						@forelse($apartamentos as $apt)
							<option value="{{ $apt->id }}" {{ (string)old('apartamento_id') === (string)$apt->id ? 'selected' : '' }}>
								{{ $apt->numero }} — {{ optional($apt->torre)->nome ?? "Torre #{$apt->torre->id}" }}
								@if(optional($apt->torre)->bloco)
									({{ optional($apt->torre->bloco)->nome ?? "Bloco #" . optional($apt->torre->bloco)->id }})
								@endif
							</option>
						@empty
							<option value="" disabled>Nenhum apartamento disponível</option>
						@endforelse
					</select>
					@error('apartamento_id')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
					@if($apartamentos->isEmpty())
						<div class="alert alert-warning mt-2" role="alert">
							Nenhum apartamento cadastrado. <a href="{{ route('apartamentos.create') }}">Cadastre um apartamento</a> antes de criar moradores.
						</div>
					@endif
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary" @if($apartamentos->isEmpty()) disabled title="Cadastre um apartamento primeiro" @endif>Salvar</button>
					<a href="{{ route('moradores.index') }}" class="btn btn-secondary">Cancelar</a>
				</div>
			</form>
		</div>
	</div>
	</div>
	<div class="container mt-4 col-md-5 mx-auto">
		<h5 class="mb-3">Importar múltiplos Moradores</h5>
		<form action="{{ route('moradores.import') }}" method="POST" enctype="multipart/form-data" class="mb-3 d-flex align-items-center gap-2">
			@csrf
			<input type="file" name="file" class="form-control" accept=".csv,.xls,.xlsx" required>
			<button type="submit" class="btn btn-success w-auto" placeholder="importe um arquivo csv ou xls">Importar</button>
		</form>
		<div class="text-muted small">
			Campos esperados (por coluna): nome, email, cpf, telefone, numeroApt
		</div>
	</div>
@endsection
