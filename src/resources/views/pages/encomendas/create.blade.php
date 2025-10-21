@extends('layouts.base')
@section('content')
<div class="container mt-4">
	<div class="card col-md-8 mx-auto">
		<div class="card-header">
			<h5 class="mb-0">Cadastrar Encomenda</h5>
		</div>
		<div class="card-body">
			<form action="{{ route('encomendas.store') }}" method="POST" novalidate>
				@csrf

				<div class="mb-3">
					<label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
					<input
						type="text"
						name="descricao"
						id="descricao"
						class="form-control @error('descricao') is-invalid @enderror"
						value="{{ old('descricao') }}"
						required
						maxlength="255"
						placeholder="Descrição da encomenda"
					>
					@error('descricao')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="data_recebimento" class="form-label">Data de Recebimento <span class="text-danger">*</span></label>
					<input
						type="date"
						name="data_recebimento"
						id="data_recebimento"
						class="form-control @error('data_recebimento') is-invalid @enderror"
						value="{{ old('data_recebimento', date('Y-m-d')) }}"
						required
					>
					@error('data_recebimento')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="origem" class="form-label">Origem</label>
					<select
						name="origem"
						id="origem"
						class="form-select @error('origem') is-invalid @enderror"
					>
						<option value="">Selecione a origem (opcional)</option>
						<option value="Mercado Livre" {{ old('origem') === 'Mercado Livre' ? 'selected' : '' }}>Mercado Livre</option>
						<option value="Amazon" {{ old('origem') === 'Amazon' ? 'selected' : '' }}>Amazon</option>
						<option value="Shopee" {{ old('origem') === 'Shopee' ? 'selected' : '' }}>Shopee</option>
						<option value="Correios" {{ old('origem') === 'Correios' ? 'selected' : '' }}>Correios</option>
						<option value="Transportadora" {{ old('origem') === 'Transportadora' ? 'selected' : '' }}>Transportadora</option>
						<option value="Outros" {{ old('origem') === 'Outros' ? 'selected' : '' }}>Outros</option>
					</select>
					@error('origem')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-3">
					<label for="codigo_rastreamento" class="form-label">Código de Rastreamento</label>
					<div class="input-group">
						<input
							type="text"
							name="codigo_rastreamento"
							id="codigo_rastreamento"
							class="form-control @error('codigo_rastreamento') is-invalid @enderror"
							value="{{ old('codigo_rastreamento') }}"
							maxlength="100"
							placeholder="Digite ou escaneie o código"
						>
						<button type="button" class="btn btn-outline-secondary" onclick="scanBarcodeCreate()" title="Escanear código de barras/QR code">
							<i class="bi bi-upc-scan"></i> Escanear
						</button>
						@error('codigo_rastreamento')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-text">
						Clique em "Escanear" para usar a câmera do dispositivo
					</div>
				</div>

				<div class="mb-3">
					<label for="morador_id" class="form-label">Morador <span class="text-danger">*</span></label>
					<select
						name="morador_id"
						id="morador_id"
						class="form-select @error('morador_id') is-invalid @enderror"
						required
						@if($moradores->isEmpty()) disabled @endif
					>
						<option value="">Selecione um morador</option>
						@forelse($moradores as $mor)
							<option value="{{ $mor->id }}" {{ (string)old('morador_id') === (string)$mor->id ? 'selected' : '' }}>
								{{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero ?? '?' }}
								@if(optional($mor->apartamento)->torre)
									| {{ $mor->apartamento->torre->nome ?? 'Torre' }}
								@endif
								@if(optional(optional($mor->apartamento)->torre)->bloco)
									| {{ $mor->apartamento->torre->bloco->nome ?? 'Bloco' }}
								@endif
							</option>
						@empty
							<option value="" disabled>Nenhum morador disponível</option>
						@endforelse
					</select>
					@error('morador_id')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
					@if($moradores->isEmpty())
						<div class="alert alert-warning mt-2" role="alert">
							Nenhum morador cadastrado. <a href="{{ route('moradores.create') }}">Cadastre um morador</a> antes de criar encomendas.
						</div>
					@endif
				</div>

				<div class="mb-3">
					<div class="form-check">
						<input
							type="checkbox"
							name="retirada"
							id="retirada"
							class="form-check-input"
							value="1"
							{{ old('retirada') ? 'checked' : '' }}
						>
						<label class="form-check-label" for="retirada">
							Marcar como retirado
						</label>
					</div>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary" @if($moradores->isEmpty()) disabled title="Cadastre um morador primeiro" @endif>Salvar</button>
					<a href="{{ route('encomendas.index') }}" class="btn btn-secondary">Cancelar</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	function scanBarcodeCreate() {
		alert('Funcionalidade de scanner de código de barras/QR code será implementada com acesso à câmera do dispositivo.');
		// Future implementation: Use HTML5 getUserMedia API or a library like QuaggaJS/ZXing
		// This would open camera, scan barcode/QR code, and fill the codigo_rastreamento field
	}
</script>
@endsection
