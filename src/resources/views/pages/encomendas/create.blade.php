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
					<label for="descricao" class="form-label">Descrição</label>
					<input
						type="text"
						name="descricao"
						id="descricao"
						class="form-control @error('descricao') is-invalid @enderror"
						value="{{ old('descricao') }}"
						required
						maxlength="255"
						placeholder="Ex.: Caixa, envelope, pacote..."
					>
					@error('descricao')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="row g-3">
					<div class="col-md-6">
						<label for="data_recebimento" class="form-label">Data de recebimento</label>
						<input
							type="date"
							name="data_recebimento"
							id="data_recebimento"
							class="form-control @error('data_recebimento') is-invalid @enderror"
										value="{{ old('data_recebimento') }}"
										max="{{ now()->toDateString() }}"
							required
						>
						@error('data_recebimento')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>

					<div class="col-md-6">
						<label for="codigo_rastreamento" class="form-label">Código de rastreamento</label>
									<div class="input-group">
										<input
											type="text"
											name="codigo_rastreamento"
											id="codigo_rastreamento"
											class="form-control @error('codigo_rastreamento') is-invalid @enderror"
											value="{{ old('codigo_rastreamento') }}"
											maxlength="100"
											placeholder="Ex.: OO123456789BR"
											autocomplete="off"
											inputmode="text"
										>
										<button type="button" class="btn btn-outline-secondary" id="scan_codigo_btn" title="Ler com scanner">
											<i class="bi bi-upc-scan"></i>
										</button>
									</div>
									<div class="form-text" id="scan_help" hidden>
										Modo leitura habilitado. Aponte o leitor para o código e pressione Enter.
									</div>
						@error('codigo_rastreamento')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>

				<div class="row g-3 mt-1">
					<div class="col-md-6">
						<label for="origem" class="form-label">Origem</label>
						<input
							type="text"
							name="origem"
							id="origem"
							class="form-control @error('origem') is-invalid @enderror"
							value="{{ old('origem') }}"
							maxlength="150"
							placeholder="Ex.: Loja, remetente, transportadora"
						>
						@error('origem')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>

					<div class="col-md-6">
						<label for="morador_id" class="form-label">Morador</label>
						<select
							name="morador_id"
							id="morador_id"
							class="form-select @error('morador_id') is-invalid @enderror"
							required
							@if(empty($moradores) || (method_exists($moradores, 'isEmpty') && $moradores->isEmpty())) disabled @endif
						>
							<option value="">Selecione um morador</option>
							@isset($moradores)
								@forelse($moradores as $mor)
									<option value="{{ $mor->id }}" {{ (string)old('morador_id') === (string)$mor->id ? 'selected' : '' }}>
										{{ $mor->nome }} — Apt {{ optional($mor->apartamento)->numero }}
										@if(optional($mor->apartamento)->torre)
											| {{ optional($mor->apartamento->torre)->nome }}
										@endif
										@if(optional(optional($mor->apartamento)->torre)->bloco)
											| {{ optional(optional($mor->apartamento->torre)->bloco)->nome }}
										@endif
									</option>
								@empty
									<option value="" disabled>Nenhum morador disponível</option>
								@endforelse
							@endisset
						</select>
						@error('morador_id')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						@if(empty($moradores) || (method_exists($moradores, 'isEmpty') && $moradores->isEmpty()))
							<div class="alert alert-warning mt-2" role="alert">
								Nenhum morador cadastrado. <a href="{{ route('moradores.create') }}">Cadastre um morador</a> antes de criar encomendas.
							</div>
						@endif
					</div>
				</div>

				<div class="mt-3">
					<label class="form-label d-block">Retirada</label>
					<div class="form-check form-switch">
						<input type="hidden" name="retirada" value="0">
						<input class="form-check-input" type="checkbox" role="switch" id="retirada" name="retirada" value="1" {{ old('retirada') ? 'checked' : '' }}>
						<label class="form-check-label" for="retirada">Marque quando a encomenda já tiver sido retirada</label>
					</div>
					@error('retirada')
						<div class="invalid-feedback d-block">{{ $message }}</div>
					@enderror
				</div>

				<div class="d-flex gap-2 mt-4">
					<button type="submit" class="btn btn-primary" @if(empty($moradores) || (method_exists($moradores, 'isEmpty') && $moradores->isEmpty())) disabled title="Cadastre um morador primeiro" @endif>Salvar</button>
					<a href="{{ route('encomendas.index') }}" class="btn btn-secondary">Cancelar</a>
				</div>
			</form>
		</div>
	</div>
</div>
		<script>
			(function() {
				const scanBtn = document.getElementById('scan_codigo_btn');
				const codigoInput = document.getElementById('codigo_rastreamento');
				const help = document.getElementById('scan_help');
				if (scanBtn && codigoInput) {
					scanBtn.addEventListener('click', () => {
						// Ativa modo leitura: foca e seleciona o campo
						codigoInput.focus();
						codigoInput.select();
						codigoInput.dataset.scanActive = '1';
						if (help) {
							help.hidden = false;
							setTimeout(() => { help.hidden = true; }, 4000);
						}
					});

					// Ao pressionar Enter, finaliza o modo leitura
					codigoInput.addEventListener('keydown', (e) => {
						if (e.key === 'Enter' && codigoInput.dataset.scanActive === '1') {
							e.preventDefault();
							delete codigoInput.dataset.scanActive;
							// Avança para o próximo campo importante
							const next = document.getElementById('origem') || document.getElementById('morador_id');
							if (next) next.focus();
						}
					});
				}
			})();
		</script>
@endsection