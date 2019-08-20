@extends('layouts.master')

@section('style')
    <link href="{{asset('master/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><i class="fa fa-list"></i> Product Sale List</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @include('elements.pagesize')                    
                        @include('product_sale.filter')
                        <a href="{{route('product_sale.create')}}" class="btn btn-success btn-sm float-right ml-3 mg-b-5" id="btn-add"><i class="fa fa-plus mg-r-2"></i> {{__('page.add_new')}}</a>                            
                        {{-- @include('elements.keyword') --}}
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-colored thead-primary">
                                <tr class="bg-blue">
                                    <th style="width:40px;">#</th>
                                    <th>
                                        {{__('page.date')}}
                                        <span class="sort-date float-right">
                                            @if($sort_by_date == 'desc')
                                                <i class="fa fa-angle-up"></i>
                                            @elseif($sort_by_date == 'asc')
                                                <i class="fa fa-angle-down"></i>
                                            @endif
                                        </span>
                                    </th>
                                    <th>{{__('page.reference_no')}}</th>
                                    <th>{{__('page.customer')}}</th>
                                    <th>{{__('page.sale_status')}}</th>
                                    <th>{{__('page.grand_total')}}</th>
                                    <th>{{__('page.paid')}}</th>
                                    <th>{{__('page.balance')}}</th>
                                    <th>{{__('page.payment_status')}}</th>
                                    <th>{{__('page.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $footer_grand_total = $footer_paid = 0;
                                @endphp
                                @foreach ($data as $item)
                                    @php
                                        $paid = $item->payments()->sum('amount');
                                        $grand_total = $item->grand_total;
                                        $footer_grand_total += $grand_total;
                                        $footer_paid += $paid;
                                    @endphp
                                    <tr>
                                        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                        <td class="timestamp">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                        <td class="reference_no">{{$item->reference_no}}</td>
                                        <td class="customer" data-id="{{$item->customer_id}}">{{$item->customer->company}}</td>
                                        <td class="status">
                                            @if ($item->status == 1)
                                                <span class="badge badge-success">{{__('page.received')}}</span>
                                            @elseif($item->status == 0)
                                                <span class="badge badge-primary">{{__('page.pending')}}</span>
                                            @endif
                                        </td>
                                        <td class="grand_total"> {{number_format($grand_total)}} </td>
                                        <td class="paid"> {{ number_format($paid) }} </td>
                                        <td class="balance" data-value="{{$grand_total - $paid}}"> {{number_format($grand_total - $paid)}} </td>
                                        <td>
                                            @if ($paid == 0)
                                                <span class="badge badge-danger">{{__('page.pending')}}</span>
                                            @elseif($paid < $grand_total)
                                                <span class="badge badge-primary">{{__('page.partial')}}</span>
                                            @else
                                                <span class="badge badge-success">{{__('page.paid')}}</span>
                                            @endif
                                        </td>
                                        <td class="py-2" align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-paper-plane"></i> {{__('page.action')}}</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item px-3" href="{{route('product_sale.detail', $item->id)}}"><i class="align-middle" data-feather="eye"></i> {{__('page.details')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('product_sale.report', $item->id)}}"><i class="align-middle mr-2 far fa-file-pdf"></i> {{__('page.report')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('payment.index', ['sale', $item->id])}}"><i class="align-middle" data-feather="dollar-sign"></i> {{__('page.payment_list')}}</a>
                                                    <a class="dropdown-item px-3 btn-add-payment" href="#" data-id="{{$item->id}}"><i class="align-middle" data-feather="credit-card"></i> {{__('page.add_payment')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('product_sale.edit', $item->id)}}"><i class="align-middle" data-feather="edit"></i> {{__('page.edit')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('product_sale.delete', $item->id)}}"><i class="align-middle" data-feather="trash-2"></i> {{__('page.delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">{{__('page.total')}}</td>
                                    <td>{{number_format($footer_grand_total)}}</td>
                                    <td>{{number_format($footer_paid)}}</td>
                                    <td>{{number_format($footer_grand_total - $footer_paid)}}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>                
                        <div class="clearfix mt-2">
                            <div class="float-left" style="margin: 0;">
                                <p>{{__('page.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('page.items')}}</p>
                            </div>
                            <div class="float-right" style="margin: 0;">
                                {!! $data->appends([
                                    'customer_id' => $customer_id,
                                    'reference_no' => $reference_no,
                                    'period' => $period,
                                ])->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="paymentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_payment')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('payment.create')}}" id="payment_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="sale">
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
                            <input type="file" name="attachment" id="file2" class="file-input-styled">
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
<script src="{{asset('master/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script>
<script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.select2').each(function() {
            $(this)
                .wrap('<div class="position-relative mb-2" style="width: 200px;"></div>')
                .select2({
                    width: '100%',
                    placeholder: '{!! __('page.customer') !!}'
                });                    
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

        $("#period").dateRangePicker({
            autoClose: false,
        });

        $("#pagesize").change(function(){
            $("#pagesize_form").submit();
        });

        $("#keyword_filter").change(function(){
            $("#keyword_filter_form").submit();
        });

        $("#btn-reset").click(function(){
            $("#search_company").val('');
            $("#search_store").val('');
            $("#search_customer").val('').change();
            $("#search_reference_no").val('');
            $("#period").val('');
        });
        var toggle = 'desc';
        if($("#search_sort_date").val() == 'desc'){
            toggle = true;
        } else {
            toggle = false;
        }


        $(".sort-date").click(function(){
            let status = $("#search_sort_date").val();
            if (status == 'asc') {
                $("#search_sort_date").val('desc');
            } else {
                $("#search_sort_date").val('asc');
            }
            $("#searchForm").submit();
        })
    });
</script>
@endsection
                    