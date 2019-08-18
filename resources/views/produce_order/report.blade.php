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
    </style>
</head>
<body>
    <h1 class="text-center">{{__('page.production_order_report')}}</h1>
    <br />
    <h4>
        {{__('page.production_order_date')}} : <u class="value">{{date('Y-m-d H:i', strtotime($order->timestamp))}}</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {{__('page.reference_no')}} : <u class="value">{{$order->reference_no}}</u>
    </h4>
    
    <h4 class="mt-3">
        {{__('page.product_name')}} : <u class="value">{{$order->product->name}}</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {{__('page.quantity')}} : <u class="value">{{$order->quantity}}</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {{__('page.received_quantity')}} : <u class="value">{{$order->receptions()->sum('quantity')}}</u>
    </h4>
    <h4 class="mt-3">{{__('page.workshop')}} : <u class="value">{{$order->workshop->name}}</u></h4>
    <h4 class="mt-3">{{__('page.manufacturing_cost')}} : <u class="value">{{number_format($order->manufacturing_cost)}}</u></h4>
    
    <h3 class="mt-3">{{__('page.supplies_list')}}</h3>
    <table class="table table-bordered table-colored table-info">
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
</body>
</html>