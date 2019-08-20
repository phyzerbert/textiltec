@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-sm-8 col-md-7 col-lg-5 mx-auto d-table h-100">
            <div class="d-table-cell align-middle">
                <div class="card">
                    <div class="card-body">
                        <div class="m-sm-4">
                            <div class="text-center mb-3">
                                <img src="{{asset('images/avatar.png')}}" alt="Andrew Jones" class="img-fluid rounded-circle" width="100" height="100" style="border: solid 3px lightblue;" />
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('page.username')}}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{__('page.username')}}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>{{__('page.password')}}</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{__('page.password')}}" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div>
                                    <div class="custom-control custom-checkbox align-items-center">
                                        {{-- <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label text-small">Remember me</label> --}}
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-lg btn-primary" style="width:200px"><i class="" data-feather="log-in"></i> {{__('page.sign_in')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
