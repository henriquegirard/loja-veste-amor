@extends('layout.default')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 text-primary">
                    <i class="fa-solid fa-file-pen me-2"></i> @yield('panel-description')
                </h4>
            </div>
            
            <div class="card-body p-4">
                @yield('form-open')
                
                @yield('panel-content')
                
                @yield('form-close')
            </div>
        </div>
    </div>
</div>
@stop
