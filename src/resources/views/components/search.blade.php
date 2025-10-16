<div class="searchComponent w-25">
  <form action="{{ $action }}" method="GET" class="mb-3">
    <div class="input-group">
      <input
        type="text"
        name="search"
        class="form-control"
        placeholder="{{ $placeholder ?? 'Buscar...' }}"
        value="{{ request('search') }}"
      >
      <button class="btn btn-outline-primary bi-search" type="submit"></button>
    </div>
  </form>
</div>
