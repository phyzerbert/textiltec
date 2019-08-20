<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('page.production_order_report')}}</title>
    <link rel="stylesheet" href="{{asset('master/css/app.css')}}">
    <style>
        .value {
            font-size: 18px;
            font-weight: 500;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #2d2d2d;
        }
        .table thead th {
            border-bottom: 2px solid #2d2d2d;
        }
    </style>
</head>
<body>    
    <br />
    <div class="w-100">
        <div class="w-50 float-left">
            <h1 style="font-size:30px;">{{__('page.product_sale_report')}}</h1>
            <h4 class="mt-3">{{__('page.sale_date')}} : <u class="value">{{date('Y-m-d H:i', strtotime($sale->timestamp))}}</u></h4>
            <h4 class="mt-3">{{__('page.reference_no')}} : <u class="value">{{$sale->reference_no}}</u></h4>
            <h4 class="mt-3">{{__('page.customer')}} : <u class="value">{{$sale->customer->company}}</u></h4>
            
            <h4 class="mt-3">{{__('page.note')}} : <u class="value">{{$sale->note}}</u></h4>
        </div>
        <div class="w-50 float-right">
            <div class="card">
                <div class="card-body">
                    <img src="@if($sale->attachment){{asset($sale->attachment)}}@else({{asset('images/no-image-icon.png')}})@endif" width="100%" alt="">
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>  
    <div class="">
        <h3 class="my-3">{{__('page.products_list')}}</h3>
        <table class="table">
            <thead class="table-primary" style="">
                <tr>
                    <th class="wd-40">#</th>
                    <th>{{__('page.product_name_code')}}</th>
                    <th>{{__('page.cost')}}</th>
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
                        <td>{{number_format($price)}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{number_format($item->subtotal)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="tx-bold" style="text-align:right">{{__('page.total')}} </td>
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
                    <td colspan="4" style="text-align:right">{{__('page.returns')}} </td>
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

    <h1 class="text-right mr-3">{{config('app.name')}}</h1>
    
</body>
</html>