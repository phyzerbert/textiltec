@extends('layouts.master')

@section('content')
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Supplies</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        @include('elements.pagesize')
                        @include('supply.filter')
                        <a href="{{route('supply.create')}}" class="btn btn-success btn-sm float-right tx-white mg-b-5" id="btn-add"><i class="fa fa-plus mg-r-2"></i> Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-colored thead-primary">
                                    <tr class="bg-blue">
                                        <th style="width:30px;">#</th>
                                        <th>{{__('page.code')}}</th>
                                        <th>{{__('page.category')}}</th>
                                        <th>{{__('page.name')}}</th>
                                        <th>{{__('page.color')}}</th>
                                        <th>{{__('page.cost')}}</th>
                                        <th>{{__('page.unit')}}</th>
                                        <th>{{__('page.quantity')}}</th>
                                        <th>{{__('page.alert_quantity')}}</th>
                                        <th>{{__('page.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach ($data as $item)
                                        @php
                                            $purchase_quantity = $item->purchase_orders()->sum('quantity');

                                            // $produce_quantity = $item->produce_orders()->sum('quantity');
                                            $produce_quantity = $item->produce_orders->sum(function($supply){
                                                $product_quantity = $supply->produce_order->quantity;
                                                return $supply->quantity * $product_quantity;
                                            });
                                            $quantity = $purchase_quantity - $produce_quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td class="code">{{$item->code}}</td>
                                            <td class="category">@isset($item->category->name){{$item->category->name}}@endisset</td>
                                            <td class="name">{{$item->name}}</td>
                                            <td class="color">{{$item->color}}</td>
                                            <td class="cost">{{number_format($item->cost)}}</td>
                                            <td class="unit">{{$item->unit}}</td>
                                            <td class="quantity @if($quantity <= $item->alert_quantity) text-danger @endif">{{$quantity}}</td>
                                            <td class="alert_quantity">{{$item->alert_quantity}}</td>
                                            <td class="py-1">
                                                <a href="{{route('supply.detail', $item->id)}}" class="btn-detail" data-toggle="tooltip" data-placement="left" title="{{__('page.details')}}"><i class="align-middle" data-feather="eye"></i></a>
                                                <a href="{{route('supply.edit', $item->id)}}" class="ml-2 btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                <a href="{{route('supply.delete', $item->id)}}" class="ml-2" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>                
                            <div class="clearfix mt-2">
                                <div class="float-left" style="margin: 0;">
                                    <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                                </div>
                                <div class="float-right" style="margin: 0;">
                                    {!! $data->appends(['code' => $code, 'name' => $name, 'category_id' => $category_id])->links() !!}
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
    <script>
        $(document).ready(function(){
            $("#btn-reset").click(function(){
                $("#search_code").val('');
                $("#search_name").val('');
                $("#search_category").val('');
            });

            $("#pagesize").change(function(){
                $("#pagesize_form").submit();
            });
        })
    </script>
@endsection
                    