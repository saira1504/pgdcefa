<div id="areas-filter" class="card shadow-sm border-0 mb-3 mx-4">
    <div class="card-body py-3 d-flex align-items-center">
        <form method="GET" action="{{ route('admin.listado_maestro') }}" class="d-flex align-items-center w-100">
            <label class="me-2 mb-0 small text-muted">Área:</label>
            <select name="area" class="form-select form-select-sm me-2" onchange="this.form.submit()" style="max-width: 280px;">
                <option value="">Todas</option>
                @php
                    $areasList = isset($areas) ? $areas : \App\Models\Area::where('activo', true)->orderBy('nombre')->get();
                    $selectedAreaId = $areaId ?? request('area');
                @endphp
                @foreach($areasList as $area)
                    <option value="{{ $area->id }}" {{ (string)$selectedAreaId === (string)$area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
                @endforeach
            </select>
            @if(!empty($selectedAreaId))
                <a href="{{ route('admin.listado_maestro') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            @endif
        </form>
    </div>
 </div>


