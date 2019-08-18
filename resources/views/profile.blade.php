@extends('layouts.master')

@section('content')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top" src="@if($user->picture) {{asset($user->picture)}} @else {{asset('images/avatar.png')}} @endif" alt="Supply Image">
                                    <div class="card-header text-center">
                                        <h4>{{$user->first_name}} {{$user->last_name}}</h4>
                                    </div>
                                    <div class="card-body text-center">
                                        <a href="{{route('supply.index')}}" class="btn btn-primary card-link"><i class="fa fa-home"></i> {{__('page.dashboard')}}</a>
                                        <a href="#" class="btn btn-success card-link" onclick="history.go(-1)"><i class="fa fa-undo"></i> {{__('page.back')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title mb-0 my-4"><i class="fa fa-user"></i> My Profile</h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{route('updateuser')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right">{{__('page.username')}}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="name" value="{{$user->name}}" placeholder="{{__('page.username')}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right">{{__('page.first_name')}}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="first_name" value="{{$user->first_name}}" placeholder="{{__('page.first_name')}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right">{{__('page.last_name')}}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="last_name" value="{{$user->last_name}}" placeholder="{{__('page.last_name')}}">
                                                </div>
                                            </div>                                            
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right" for="picture">{{__('page.picture')}}</label>
                                                <div class="col-sm-8">
                                                    <label class="custom-file wd-100p">
                                                        <input type="file" name="picture" id="picture" class="file-input-styled" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right">{{__('page.password')}}</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="password" placeholder="{{__('page.password')}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4 text-sm-right">{{__('page.confirm_password')}}</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{__('page.confirm_password')}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-8 ml-sm-auto text-right">
                                                    <button type="submit" class="btn btn-lg btn-primary w-50"><i class="fa fa-check"></i> {{__('page.save')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.file-input-styled').uniform({
            fileButtonClass: 'action btn bg-primary tx-white'
        });
    });
</script>
@endsection
                    