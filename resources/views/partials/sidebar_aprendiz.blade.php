<div class="sidebar bg-success text-white vh-100 d-flex flex-column p-3" style="width: 250px;">
    <div class="mb-4 text-center">
        <div class="rounded-circle bg-white mx-auto mb-2" style="width:60px; height:60px;">
            <i class="fas fa-user-graduate fa-2x text-success" style="line-height:60px;"></i>
        </div>
        <div class="fw-bold">{{ Auth::user()->name ?? 'Aprendiz' }}</div>
        <div class="small">Senaempresa</div>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ route('aprendiz.dashboard') }}" 
               class="nav-link text-white {{ request()->routeIs('aprendiz.dashboard') ? 'active bg-dark' : '' }}">
                <i class="fas fa-home me-2"></i>Inicio
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('aprendiz.mi-unidad') }}" 
               class="nav-link text-white {{ request()->routeIs('aprendiz.mi-unidad') ? 'active bg-dark' : '' }}">
                <i class="fas fa-industry me-2"></i>Mi Unidad Productiva
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('aprendiz.phases.index') }}" class="nav-link text-white {{ request()->routeIs('aprendiz.phases.*') ? 'active bg-dark' : '' }}">
                <i class="fas fa-project-diagram me-2"></i>Fases
            </a>
        </li>
        <li class="mb-1">
            <a href="#" class="nav-link text-white">
                <i class="fas fa-cubes me-2"></i>Subsistemas
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('aprendiz.progreso') }}" 
               class="nav-link text-white {{ request()->routeIs('aprendiz.progreso') ? 'active bg-dark' : '' }}">
                <i class="fas fa-chart-line me-2"></i>Mi Progreso
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('aprendiz.documentos') }}" 
               class="nav-link text-white {{ request()->routeIs('aprendiz.documentos') ? 'active bg-dark' : '' }}">
                <i class="fas fa-file-alt me-2"></i>Mis Documentos
            </a>
        </li>
    </ul>
    
    <!-- Botón de cerrar sesión -->
    <div class="mt-auto">
        <hr class="text-white-50">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="nav-link text-white">
            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
