@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Header reducido -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>¡Bienvenido, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted mb-0">Panel de Aprendiz</p>
                </div>
               
            </div>
        </div>

      
    </div>
    </div>
</div>
@endsection
