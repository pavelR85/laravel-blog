@extends('template')



@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
            <div class="card border border-light-subtle rounded-3 shadow-sm mt-5">
                <div class="card-body p-3 p-md-4 p-xl-5">
                    @yield('form-content')
                </div>
            </div>
        </div>
    </div>
@endsection
