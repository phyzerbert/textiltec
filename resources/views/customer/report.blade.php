<!DOCTYPE html>
<html>
<head>
    <title>{{__('page.customer')}} {{__('page.report')}}</title>
    <link rel="stylesheet" href="{{asset('master/css/app.css')}}">
    <style>
        body {
            border: solid 1px black;
            padding: 10px;
        }
        .title {
            margin-top: 30px;
            text-align:center;
            font-size:30px;
            font-weight: 800;
            text-decoration:underline;
        }
        .value {
            font-size: 18px;
            font-weight: 500;
            text-decoration: underline;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #2d2d2d;
        }
        .table thead th {
            border-bottom: 2px solid #2d2d2d;
        }
        #table-customer {
            font-size: 18px;
            font-weight: 600;
            color: black;
        }
        #table-customer tbody td {
            height: 50px;
        }
        .footer-link {
            position: absolute;
            right:20px;
            bottom: 10px;
        }
    </style>
</head>
<body>
    <h1 class="title">{{__('page.customer')}} {{__('page.report')}}</h1>

    @php
        $purchases_array = $customer->product_sales()->pluck('id');
        $total_purchases = $customer->product_sales()->count();
        $total_amount = $customer->product_sales()->sum('grand_total');
        $paid = \App\Models\Payment::whereIn('paymentable_id', $purchases_array)->where('paymentable_type', 'App\Models\ProductSale')->sum('amount');
    @endphp

    <table class="w-100 mt-5" id="table-customer">
        <tbody>
            <tr>
                <td class="w-50">{{__('page.name')}} : <span class="value">{{$customer->name}}</span></td>
                <td class="w-50">{{__('page.company')}} : <span class="value">{{$customer->company}}</span></td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.email')}} : <span class="value">{{$customer->email}}</span></td>
                <td class="w-50">{{__('page.phone_number')}} : <span class="value">{{$customer->name}}</td>
            </tr>
            <tr>
                <td colspan="2" class="w-50">{{__('page.address')}} : <span class="value">{{$customer->address}}</td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.city')}} : <span class="value">{{$customer->city}}</span></td>
                <td class="w-50">{{__('page.total_amount')}} : <span class="value">{{number_format($total_amount)}}</span></td>
            </tr>
            <tr>
                <td class="w-50">{{__('page.paid')}} : <span class="value">{{number_format($paid)}}</span></td>
                <td class="w-50">{{__('page.balance')}} : <span class="value">{{number_format($total_amount - $paid)}}</span></td>
            </tr>
        </tbody>
    </table>
    <h3 class="mt-5" style="font-size: 24px; font-weight: 500;">{{__('page.sales')}}</h3>
    <table class="table">
        <thead class="table-primary">
            <tr class="bg-blue">
                <th style="width:25px;">#</th>
                <th>
                    {{__('page.date')}}
                </th>
                <th>{{__('page.reference_no')}}</th>
                <th>{{__('page.grand_total')}}</th>
                <th>{{__('page.paid')}}</th>
                <th>{{__('page.balance')}}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $footer_grand_total = $footer_paid = 0;
                $data = $customer->product_sales;
            @endphp
            @foreach ($data as $item)
                @php
                    $paid = $item->payments()->sum('amount');
                    $grand_total = $item->grand_total;
                    $footer_grand_total += $grand_total;
                    $footer_paid += $paid;
                @endphp
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="timestamp">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                    <td class="reference_no">{{$item->reference_no}}</td>
                    <td class="grand_total"> {{number_format($grand_total)}} </td>
                    <td class="paid"> {{ number_format($paid) }} </td>
                    <td class="balance" data-value="{{$grand_total - $paid}}"> {{number_format($grand_total - $paid)}} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">{{__('page.total')}}</td>
                <td>{{number_format($footer_grand_total)}}</td>
                <td>{{number_format($footer_paid)}}</td>
                <td>{{number_format($footer_grand_total - $footer_paid)}}</td>  
            </tr>
        </tfoot>
    </table>
    <h3 class="footer-link"><a href="http://textiltec.com" target="_blank">{{config('app.name')}}</a></h3>
</body>
</html>