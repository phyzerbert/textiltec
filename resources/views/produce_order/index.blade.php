@extends('layouts.master')

@section('style')
    <link href="{{asset('master/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><i class="fa fa-list"></i> {{__('page.production_order_list')}}</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        @include('elements.pagesize')                    
                        @include('produce_order.filter')
                        <a href="{{route('produce_order.create')}}" class="btn btn-success btn-sm float-right ml-3 mg-b-5" id="btn-add"><i class="fa fa-plus mg-r-2"></i> {{__('page.add_new')}}</a>                            
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
                                    <th>{{__('page.product')}}</th>
                                    <th>{{__('page.quantity')}}</th>
                                    <th>{{__('page.received_quantity')}}</th>
                                    <th>{{__('page.workshop')}}</th>
                                    <th>{{__('page.supply_cost')}}</th>
                                    <th>{{__('page.manufacturing_cost')}}</th>
                                    <th>{{__('page.production_cost')}}</th>
                                    <th>{{__('page.status')}}</th>
                                    <th>{{__('page.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $footer_supply_cost = $footer_manufacturing_cost = $footer_total_cost = 0;
                                @endphp
                                @foreach ($data as $item)
                                    @php
                                        $received_quantity = $item->receptions()->sum('quantity');
                                        $footer_supply_cost += $item->supply_cost * $item->quantity;
                                        $footer_manufacturing_cost += $item->manufacturing_cost * $item->quantity;
                                        $footer_total_cost += $item->total_cost * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                        <td class="timestamp">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                        <td class="reference_no">{{$item->reference_no}}</td>
                                        <td class="product" data-id="{{$item->product_id}}">{{$item->product->name}}</td>
                                        <td class="quantity" data-balance="{{$item->quantity - $received_quantity}}">{{$item->quantity}}</td>
                                        <td class="received_quantity">{{$received_quantity}}</td>
                                        <td class="workshop" data-id="{{$item->workshop_id}}">{{$item->workshop->name}}</td>
                                        <td class="supply_cost"> {{number_format($item->supply_cost * $item->quantity)}} </td>
                                        <td class="manufacturing_cost"> {{number_format($item->manufacturing_cost * $item->quantity)}} </td>
                                        <td class="production_cost"> {{number_format($item->total_cost * $item->quantity)}} </td>
                                        <td>
                                            @if ($received_quantity == 0)
                                                <span class="badge badge-danger">{{__('page.pending')}}</span>
                                            @elseif($received_quantity < $item->quantity)
                                                <span class="badge badge-primary">{{__('page.partial')}}</span>
                                            @else
                                                <span class="badge badge-success">{{__('page.received')}}</span>
                                            @endif
                                        </td>
                                        <td class="py-2" align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-paper-plane"></i> {{__('page.action')}}</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item px-3" href="{{route('produce_order.detail', $item->id)}}"><i class="align-middle" data-feather="eye"></i>  {{__('page.details')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('produce_order.report', $item->id)}}"><i class="align-middle mr-2 far fa-file-pdf"></i> {{__('page.report')}}</a>
                                                    <a class="dropdown-item px-3 btn-add-receive" href="#" data-id="{{$item->id}}"><i class="align-middle" data-feather="archive"></i> {{__('page.receive')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('produce_order.edit', $item->id)}}"><i class="align-middle" data-feather="edit"></i> {{__('page.edit')}}</a>
                                                    <a class="dropdown-item px-3" href="{{route('produce_order.delete', $item->id)}}"><i class="align-middle" data-feather="trash-2"></i> {{__('page.delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">{{__('page.total')}}</td>
                                    <td>{{number_format($footer_supply_cost)}}</td>
                                    <td>{{number_format($footer_manufacturing_cost)}}</td>
                                    <td>{{number_format($footer_total_cost)}}</td>
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
                                    'product_id' => $product_id,
                                    'workshop_id' => $workshop_id,
                                    'reference_no' => $reference_no,
                                    'period' => $period,
                                    'deadline' => $deadline,
                                    'keyword' => $keyword,
                                ])->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="receiveModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.receive_production_order')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('order_receive.create')}}" id="receive_form" method="post">
                    @csrf
                    <input type="hidden" class="produce_order_id" name="produce_order_id" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.receive_date')}}</label>
                            <input class="form-control receive_date" type="text" name="receive_date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.receive_date')}}">
                        </div>                                               
                        <div class="form-group">
                            <label class="control-label">{{__('page.quantity')}}</label>
                            <input class="form-control quantity" type="text" name="quantity" required placeholder="{{__('page.quantity')}}">
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
                    placeholder: "{!! __('page.select_product') !!}"
                });                    
        });

        $("#receive_form input.date").datetimepicker({
            dateFormat: 'yy-mm-dd',
        });
        
        $(".btn-add-receive").click(function(){
            // $("#payment_form input.form-control").val('');
            let id = $(this).data('id');
            let balance = $(this).parents('tr').find('.quantity').data('balance');
            $("#receive_form .produce_order_id").val(id);
            $("#receive_form .quantity").val(balance);
            $("#receiveModal").modal();
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
            $("#search_supplier").val('').change();
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
                    