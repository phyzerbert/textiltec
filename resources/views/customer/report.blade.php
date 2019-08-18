<!DOCTYPE html>
<html>
<head>
	<title>Supplier Report</title>
</head>
<body>
    <h1 style="text-align: center">{{__('page.customer')}} {{__('page.report')}}</h1>
    <h4>{{__('page.name')}} : {{$customer->name}}</h4>
    <h4>{{__('page.company')}} : {{$customer->company}}</h4>
    <h4>{{__('page.email')}} : {{$customer->email}}</h4>
    <h4>{{__('page.phone_number')}} : {{$customer->name}}</h4>
    <h4>{{__('page.address')}} : {{$customer->address}}</h4>
    <h4>{{__('page.city')}} : {{$customer->city}}</h4>
    @php
        $sales_array = $customer->sales()->pluck('id');
        $total_sales = $customer->sales()->count();
        $total_amount = $customer->sales()->sum('grand_total');
        $paid = \App\Models\Payment::whereIn('sale_id', $sales_array)->sum('amount');
    @endphp
    <h4>{{__('page.total_amount')}} : {{number_format($total_amount)}}</h4>
    <h4>{{__('page.paid')}} : {{number_format($paid)}}</h4>
    <h4>{{__('page.balance')}} : {{number_format($total_amount - $paid)}}</h4>
</body>
</html>