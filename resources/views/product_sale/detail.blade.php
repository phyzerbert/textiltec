@extends('layouts.master')
@section('style')
    <style>
        .purchase-image {
            width: 45px;
            height: 45px;
            margin-right: 7px;
            cursor: pointer;
        }
    </style> 
@endsection
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><i class="fas fa-cubes"></i> {{__('page.product_sale_details')}}</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">                        
                        <div class="row">
                            <div class="col-lg-4"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.customer')}}</h2>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{__('page.name')}} :  @isset($sale->customer->name){{$sale->customer->name}}@endisset</li>
                                        <li class="list-group-item">{{__('page.email')}} :  @isset($sale->customer->email){{$sale->customer->email}}@endisset</li>
                                        <li class="list-group-item">{{__('page.phone')}} :  @isset($sale->customer->phone_number){{$sale->customer->phone_number}}@endisset</li>
                                        <li class="list-group-item">{{__('page.note')}} :  @isset($sale->customer->note){{$sale->customer->note}}@endisset</li>
                                    </ul>
                                </div> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.reference')}}</h2>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{__('page.number')}} : {{$sale->reference_no}}</li>
                                        <li class="list-group-item">{{__('page.date')}}: {{$sale->timestamp}}</li>
                                        <li class="list-group-item">
                                            {{__('page.status')}}: 
                                            @if($sale->status == 0)
                                                <span class="badge badge-warning">{{__('page.pending')}}</span>
                                            @elseif($sale->status == 1)
                                                <span class="badge badge-warning">{{__('page.received')}}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item">
                                            {{__('page.attachment')}} : 
                                            @if ($sale->attachment != "")
                                                <img src="{{asset($sale->attachment)}}" href="{{asset($sale->attachment)}}" class="purchase-image border rounded" alt="">
                                            @endif
                                        </li>
                                    </ul>
                                </div> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.note')}}</h2>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            {{$sale->note}}
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <h3><i class="far fa-list-alt"></i> {{__('page.products_list')}}</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-info">
                                            <tr>
                                                <th class="wd-40">#</th>
                                                <th>{{__('page.product_name_code')}}</th>
                                                <th>{{__('page.price')}}</th>
                                                <th>{{__('page.quantity')}}</th>
                                                <th>{{__('page.subtotal')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;
                                                $paid = $sale->payments()->sum('amount');
                                            @endphp
                                            @foreach ($sale->orders as $item)
                                            @php
                                                $quantity = $item->quantity;
                                                $price = $item->price;
                                                $subtotal = $item->subtotal;
            
                                                $total_quantity += $quantity;
                                                $total_amount += $subtotal;
                                            @endphp
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>@isset($item->product->name){{$item->product->name}} ({{$item->product->code}})@endisset</td>
                                                    <td>{{number_format($item->price)}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{number_format($item->subtotal)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" class="tx-bold" style="text-align:right">{{__('page.total')}} (COP)</td>
                                                <td>{{$total_quantity}}</td>
                                                <td>{{number_format($total_amount)}}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="tx-bold tx-black">
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.discount')}} </td>
                                                <td>
                                                    @if(strpos( $sale->discount_string , '%' ) !== false)
                                                        {{$sale->discount_string}} ({{number_format($sale->discount)}})
                                                    @else
                                                        {{number_format($sale->discount)}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.shipping')}} </td>
                                                <td>
                                                    @if(strpos( $sale->shipping_string , '%' ) !== false)
                                                        {{$sale->shipping_string}} ({{number_format($sale->shipping)}})
                                                    @else
                                                        {{number_format($sale->shipping)}}
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.returns')}}</td>
                                                <td>
                                                    {{number_format($sale->returns)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.total_amount')}} </td>
                                                <td>{{number_format($sale->grand_total)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.paid')}} </td>
                                                <td>{{number_format($paid)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align:right">{{__('page.balance')}} </td>
                                                <td>{{number_format($sale->grand_total - $paid)}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                <div class="row mt-5">
                                    <div class="col-md-12 text-right">
                                        <a href="{{route('product_sale.index')}}" class="btn btn-success"><i class="fas fa-shopping-bag"></i>  {{__('page.product_sale')}}</a>
                                        <a href="{{route('payment.index', ['sale', $sale->id])}}" class="btn btn-info"><i class="fa fa-list"></i>  {{__('page.payment_list')}}</a>
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
    <script src="{{asset('master/plugins/ezview/EZView.js')}}"></script>
    <script>
        $(document).ready(function(){            
            if($(".purchase-image").length) {
                $(".purchase-image").EZView();
            }
        });
    </script>
@endsection
                    