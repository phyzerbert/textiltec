<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>    
    <style>
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
    </style>
</head>
<body>
    <h1 style="text-align: center">{{__('page.customer')}} {{__('page.report')}}</h1>
    <h4>{{__('page.name')}} :  <span class="value">{{$customer->name}}</span></h4>
    <h4>{{__('page.company')}} :  <span class="value">{{$customer->company}}</span></h4>
    <h4>{{__('page.email')}} :  <span class="value">{{$customer->email}}</span></h4>
    <h4>{{__('page.phone_number')}} :  <span class="value">{{$customer->name}}</span></h4>
    <h4>{{__('page.address')}} :  <span class="value">{{$customer->address}}</span></h4>
    <h4>{{__('page.city')}} :  <span class="value">{{$customer->city}}</span></h4>
    @php
        $sales_array = $customer->product_sales()->pluck('id');
        $total_sales = $customer->product_sales()->count();
        $total_amount = $customer->product_sales()->sum('grand_total');
        $paid = \App\Models\Payment::whereIn('paymentable_id', $sales_array)->where('paymentable_type', 'App\Models\ProductSale')->sum('amount');
    @endphp
    <h4>{{__('page.total_amount')}} :  <span class="value">{{number_format($total_amount)}}</span></h4>
    <h4>{{__('page.paid')}} :  <span class="value">{{number_format($paid)}}</h4>
    <h4>{{__('page.balance')}} :  <span class="value">{{number_format($total_amount - $paid)}}</span></h4>
</body>
</html>