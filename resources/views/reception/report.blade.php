<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('page.production_order_report')}}</title>
    <link rel="stylesheet" href="{{asset('master/css/app.css')}}">
    <style>
        body {
            border: solid 1px #454545;
        }
        .value {
            font-size: 18px;
            font-weight: 500;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #2d2d2d;
        }
        .table td,.table th {
            border: none;
        }
        .w-30 {
            width: 33.33333333333333%;
        }
    </style>
</head>
<body>
    <h1 class="text-center mt-5">TextilTec</h1>
    <br />
    @php
        $produce_order = $reception->produce_order;
        $workshop = $produce_order->workshop->name ?? '';
    @endphp
    <div class="w-100 px-3">
        <table class="table">
            <tr>
                <th>{{__('page.production_order')}}</th>
                <th>{{__('page.date')}}</th>
                <th>{{__('page.workshop')}}</th>
            </tr>
            <tr>
                <th>{{$reception->produce_order->reference_no ?? ''}}</th>
                <th>{{date('d/m/Y', strtotime($reception->receive_date))}}</th>
                <th>{{$workshop}}</th>
            </tr>
        </table>
    </div>
    <div class="px-3 mt-4">
        <h3 class="my-3">{{__('page.receive_report')}}</h3>
        <table class="table" id="receive_table">
            <tr>
                <th>{{__('page.quantity')}}</th>
                <th>{{__('page.manufacturing_cost')}}</th>
                <th>{{__('page.balance')}}</th>
            </tr>
            <tr>
                <td>{{$reception->quantity}}</td>
                <td>{{number_format($produce_order->manufacturing_cost)}}</td>
                <td>{{number_format($produce_order->manufacturing_cost * $reception->quantity)}}</td>
            </tr>
            <tr>
                <td><div class="border" style="height: 30px; width:150px;"></div></td>
                <td><div class="border" style="height: 30px; width:150px;"></div></td>
                <td><div class="border" style="height: 30px; width:150px;"></div></td>
            </tr>
        </table>
    </div>
    
</body>
</html>