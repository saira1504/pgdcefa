<div class="sidebar bg-success text-white vh-100 d-flex flex-column p-4">
    <div class="mb-5 text-center">
        <div class="rounded-circle bg-white mx-auto mb-3" style="width:80px; height:80px;">
            <i class="fas fa-user-shield fa-3x text-success" style="line-height:80px;"></i>
        </div>
        <div class="fw-bold fs-5">{{ Auth::user()->name ?? 'Superadmin' }}</div>
        <div class="small opacity-75">Superadministrador</div>
    </div>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="{{ route('superadmin.dashboard') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.dashboard') ? 'active bg-dark' : '' }}">
                <i class="fas fa-home me-3 fa-lg"></i>
                <span class="fs-6">Inicio</span>
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('superadmin.lista') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.lista') ? 'active bg-dark' : '' }}">
                <i class="fas fa-list me-3 fa-lg"></i>
                <span class="fs-6">Lista</span>
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('superadmin.unidades-productivas.index') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.unidades-productivas.index') ? 'active bg-dark' : '' }}">
                <i class="fas fa-industry me-3 fa-lg"></i>
                <span class="fs-6">Unidades Productivas</span>
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('superadmin.documentos') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.documentos') ? 'active bg-dark' : '' }}">
                <i class="fas fa-file-alt me-3 fa-lg"></i>
                <span class="fs-6">Documentos</span>
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('superadmin.resultados') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.resultados') ? 'active bg-dark' : '' }}">
                <i class="fas fa-chart-bar me-3 fa-lg"></i>
                <span class="fs-6">Resultados</span>
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('superadmin.reportes') }}" 
               class="nav-link text-white py-3 px-4 rounded-3 {{ request()->routeIs('superadmin.reportes') ? 'active bg-dark' : '' }}">
                <i class="fas fa-flag me-3 fa-lg"></i>
                <span class="fs-6">Reportes</span>
            </a>
        </li>
    </ul>
    
    <!-- Logout -->
    <div class="mt-auto pt-4">
        <hr class="text-white-50">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="nav-link text-white py-3 px-4 rounded-3">
            <i class="fas fa-sign-out-alt me-3 fa-lg"></i>
            <span class="fs-6">Cerrar Sesión</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
