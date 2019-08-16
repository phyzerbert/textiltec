@extends('layouts.master')

@section('content')
    <div class="container p-0">
        <h1 class="h3 mb-3">Supply Details</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top" src="@if($supply->image) {{asset($supply->image)}} @else {{asset('images/no-image-icon.png')}} @endif" alt="Supply Image">
                                    <div class="card-header text-center">
                                        <h4>{{$supply->name}}( {{$supply->code}} )</h4>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">{{__('page.note')}}</h5>
                                        <p class="card-text">{{$supply->detail}}</p>
                                        <a href="{{route('supply.index')}}" class="card-link">Supply List</a>
                                        <a href="{{route('supply.create')}}" class="card-link">Add Supply</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0 my-4">Supply Details</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item font-weight-bold">{{__('page.name')}} : {{$supply->name}}</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.code')}} : {{$supply->code}}</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.category')}} : @isset($supply->category->name){{$supply->category->name}}@endisset</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.cost')}} : {{number_format($supply->cost)}}</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.unit')}} : {{$supply->unit}}</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.quantity')}} : {{$supply->purchase_orders()->sum('quantity')}}</li>
                                        <li class="list-group-item font-weight-bold">{{__('page.alert_quantity')}} : {{$supply->alert_quantity}}</li>
                                    </ul>
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
            
        });
    </script>
@endsection
                    