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
    <h1 class="text-center">{{__('page.production_order_report')}}</h1>
    <br />
    <div class="w-100">
        <div class="w-50 float-left">
            <h4>{{__('page.production_order_date')}} : <u class="value">{{date('Y-m-d H:i', strtotime($order->timestamp))}}</u></h4>
            <h4 class="mt-3">{{__('page.reference_no')}} : <u class="value">{{$order->reference_no}}</u></h4>
            <h4 class="mt-3">{{__('page.product_name')}} : <u class="value">{{$order->product->name}}</u></h4>
            <h4 class="mt-3">{{__('page.quantity')}} : <u class="value">{{$order->quantity}}</u></h4>
            <h4 class="mt-3">{{__('page.received_quantity')}} : <u class="value">{{$order->receptions()->sum('quantity')}}</u></h4>
            <h4 class="mt-3">{{__('page.workshop')}} : <u class="value">{{$order->workshop->name}}</u></h4>
            <h4 class="mt-3">{{__('page.manufacturing_cost')}} : <u class="value">{{number_format($order->manufacturing_cost)}}</u></h4>
            <h4 class="mt-3">{{__('page.responsibility')}} : <u class="value">{{$order->responsibility}}</u></h4>
            <h4 class="mt-3">{{__('page.description')}} : <u class="value">{{$order->description}}</u></h4>
        </div>
        <div class="w-50 float-right">
            <div class="card">
                <div class="card-body">
                    <img src="{{asset($order->main_image)}}" width="100%" alt="">
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>  
    <div class="">
        <h3 class="my-3">{{__('page.supplies_list')}}</h3>
        <table class="table table-bordered">
            <thead>
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
                @endphp
                @foreach ($order->supplies as $item)
                @php
                    $quantity = $item->quantity;
                    $cost = $item->cost;
                    $subtotal = $item->subtotal;

                    $total_quantity += $quantity;
                    $total_amount += $subtotal;
                @endphp
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>@isset($item->supply->name){{$item->supply->name}} ({{$item->supply->code}})@endisset</td>
                        <td>{{number_format($item->cost)}}</td>
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
                    <td colspan="4" style="text-align:right">{{__('page.supply_cost')}}</td>
                    <td>{{number_format($total_amount)}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right">{{__('page.manufacturing_cost')}} </td>
                    <td>{{number_format($order->manufacturing_cost)}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right">{{__('page.total_cost')}} </td>
                    <td>{{number_format($order->total_cost)}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="mt-3">
        <table class="table">
            <thead>
                <tr>
                    <td>{{__('page.total_supply_cost')}}</td>
                    <td>{{__('page.total_manufacturing_cost')}}</td>
                    <td>{{__('page.total_production_cost')}}</td>
                    <td>{{__('page.product_cost')}}</td>
                </tr>
            </thead>
        </table>
    </div>
    
</body>
</html>