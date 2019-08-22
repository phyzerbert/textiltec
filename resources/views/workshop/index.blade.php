@extends('layouts.master')
@section('style')
    <link href="{{asset('master/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container p-0">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><i class="align-middle" data-feather="layers"></i> {{__('page.workshop_management')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-success btn-sm float-right mg-b-5" id="btn-add"><i class="align-middle" data-feather="plus"></i> {{__('page.add_new')}}</button>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-colored thead-primary">
                                        <tr class="bg-blue">
                                            <th class="wd-50">#</th>
                                            <th>{{__('page.name')}}</th>
                                            <th>{{__('page.count_of_production')}}</th>
                                            <th>{{__('page.manufactured')}}</th>
                                            <th>{{__('page.paid')}}</th>
                                            <th>{{__('page.balance')}}</th>
                                            <th>{{__('page.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                
                                        @foreach ($data as $item)
                                            @php
                                                $produce_count = $item->produce_orders()->count();
                                                $produce_quantity = $item->produce_orders->sum('quantity');
                                                $produce_orders_array = $item->produce_orders->pluck('id');
                                                $produce_manufactured = $item->produce_orders->sum(function ($order) {
                                                    return $order->quantity * $order->manufacturing_cost;
                                                });
                                                // $purchases = $item->produce_orders->sum('production_cost');
                                                $products_array = $item->products()->pluck('id');
                                                $sales_count = \App\Models\SaleOrder::whereIn('product_id', $products_array)->distinct('product_id')->count();
                                                $sales_quantity = \App\Models\SaleOrder::whereIn('product_id', $products_array)->sum('quantity');

                                                $mod_paid = \App\Models\Payment::whereIn('paymentable_id', $produce_orders_array)->where('paymentable_type', "App\Models\ProduceOrder");
                                                $paid = $mod_paid->sum('amount');

                                                $balance = $produce_manufactured - $paid;
                                            @endphp
                                            <tr>
                                                <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                                <td class="name">{{$item->name}}</td>
                                                <td class="produce_count">{{$produce_count}}</td>
                                                <td class="manufactured" data-value="{{$produce_manufactured}}">{{number_format($produce_manufactured)}}</td>
                                                <td class="paid">{{number_format($paid)}}</td>
                                                <td class="balance" data-value="{{$produce_manufactured}}">{{number_format($balance)}}</td>
                                                <td class="py-1">
                                                    <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                    <a href="{{route('workshop.delete', $item->id)}}" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>                
                                <div class="clearfix mt-2">
                                    <div class="float-left" style="margin: 0;">
                                        <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                                    </div>
                                    <div class="float-right" style="margin: 0;">
                                        {!! $data->appends([])->links() !!}
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- The Modal -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('page.add_workshop')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form action="{{route('workshop.create')}}" id="create_form" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('page.name')}}</label>
                                <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                            </div>
                        </div>    
                        <div class="modal-footer">
                            <button type="submit" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('page.edit_workshop')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form action="{{route('workshop.edit')}}" id="edit_form" method="post">
                        @csrf
                        <input type="hidden" name="id" class="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('page.name')}}</label>
                                <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                            </div>
                        </div>  
                        <div class="modal-footer">
                            <button type="submit" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="paymentModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('page.add_payment')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form action="{{route('payment.create')}}" id="payment_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="workshop">
                        <input type="hidden" class="product_sale_id" name="paymentable_id" />
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('page.date')}}</label>
                                <input class="form-control date" type="text" name="date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}">
                            </div>                        
                            <div class="form-group">
                                <label class="control-label">{{__('page.reference_no')}}</label>
                                <input class="form-control reference_no" type="text" name="reference_no" required placeholder="{{__('page.reference_no')}}">
                            </div>                                                
                            <div class="form-group">
                                <label class="control-label">{{__('page.amount')}}</label>
                                <input class="form-control amount" type="text" name="amount" required placeholder="{{__('page.amount')}}">
                            </div>                                               
                            <div class="form-group">
                                <label class="control-label">{{__('page.attachment')}}</label>
                                <input type="file" name="attachment" id="file2" class="file-input-styled" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{__('page.note')}}</label>
                                <textarea class="form-control note" type="text" name="note" placeholder="{{__('page.note')}}"></textarea>
                            </div> 
                        </div>    
                        <div class="modal-footer">
                            <button type="submit" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
@endsection

@section('script')
    <script src="{{asset('master/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>
    <script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#btn-add").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addModal").modal();
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let name = $(this).parents('tr').find(".name").text().trim();
                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .name").val(name);
                $("#editModal").modal();
            });

            $("#payment_form input.date").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });
            
            $(".btn-add-payment").click(function(){
                // $("#payment_form input.form-control").val('');
                let id = $(this).data('id');
                let balance = $(this).parents('tr').find('.balance').data('value');
                $("#payment_form .product_sale_id").val(id);
                $("#payment_form .amount").val(balance);
                $("#paymentModal").modal();
            });

            $('.file-input-styled').uniform({
                fileButtonClass: 'action btn bg-primary tx-white'
            });
        })
    </script>
@endsection
                    