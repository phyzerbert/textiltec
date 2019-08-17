<!DOCTYPE html>
<html>
<head>
	<title>Supplier Report</title>
</head>
<body>
    <h1 style="text-align: center">{{__('page.supplier')}} {{__('page.report')}}</h1>
    <h4>{{__('page.name')}} : {{$supplier->name}}</h4>
    <h4>{{__('page.company')}} : {{$supplier->company}}</h4>
    <h4>{{__('page.email')}} : {{$supplier->email}}</h4>
    <h4>{{__('page.phone_number')}} : {{$supplier->name}}</h4>
    <h4>{{__('page.address')}} : {{$supplier->address}}</h4>
    <h4>{{__('page.city')}} : {{$supplier->city}}</h4>
    @php
        $purchases_array = $supplier->purchases()->pluck('id');
        $total_purchases = $supplier->purchases()->count();
        $total_amount = $supplier->purchases()->sum('grand_total');
        $paid = \App\Models\Payment::whereIn('purchase_id', $purchases_array)->sum('amount');
    @endphp
    <h4>{{__('page.total_amount')}} : {{number_format($total_amount)}}</h4>
    <h4>{{__('page.paid')}} : {{number_format($paid)}}</h4>
    <h4>{{__('page.balance')}} : {{number_format($total_amount - $paid)}}</h4>
</body>
</html>