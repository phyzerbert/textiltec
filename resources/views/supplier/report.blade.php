<!DOCTYPE html>
<html>
<head>
    <title>Supplier Report</title>
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
    <h1 style="text-align:center">{{__('page.supplier')}} {{__('page.report')}}</h1>

    <h3>{{__('page.name')}} : <span class="value">{{$supplier->name}}</span></h3>
    <h3>{{__('page.company')}} : <span class="value">{{$supplier->company}}</span></h3>
    <h3>{{__('page.email')}} : <span class="value">{{$supplier->email}}</span></h3>
    <h3>{{__('page.phone_number')}} : <span class="value">{{$supplier->name}}</span></h3>
    <h3>{{__('page.address')}} : <span class="value">{{$supplier->address}}</span></h3>
    <h3>{{__('page.city')}} : <span class="value">{{$supplier->city}}</span></h3>
    @php
        $purchases_array = $supplier->purchases()->pluck('id');
        $total_purchases = $supplier->purchases()->count();
        $total_amount = $supplier->purchases()->sum('grand_total');
        $paid = \App\Models\Payment::whereIn('paymentable_id', $purchases_array)->where('paymentable_type', 'App\Models\Purchase')->sum('amount');
    @endphp
    <h3>{{__('page.total_amount')}} : <span class="value">{{number_format($total_amount)}}</span></h3>
    <h3>{{__('page.paid')}} : <span class="value">{{number_format($paid)}}</span></h3>
    <h3>{{__('page.balance')}} : <span class="value">{{number_format($total_amount - $paid)}}</span></h3>
</body>
</html>