@extends('layouts.master')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">            
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_suppliers']}}</h3>
                                <div class="mb-0">{{__('page.total_suppliers')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-primary" data-feather="truck"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_customers']}}</h3>
                                <div class="mb-0">{{__('page.total_customers')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-warning" data-feather="activity"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_supply_count']}} / {{$return['total_supply_quantity']}}</h3>
                                <div class="mb-0">{{__('page.total_supplies')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-success" data-feather="codepen"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_product_count']}} / {{$return['total_product_quantity']}}</h3>
                                <div class="mb-0">{{__('page.total_products')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-info" data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
                    