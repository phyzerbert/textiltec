@extends('layouts.master')
@section('style')    
    <link href="{{asset('master/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">  
    <link rel="stylesheet" href="{{asset('master/plugins/imageviewer/css/jquery.verySimpleImageViewer.css')}}">
    <style>
        #image_preview {
            max-width: 600px;
            height: 600px;
        }
        .image_viewer_inner_container {
            width: 100% !important;
        }
    </style>  
@endsection
@section('content')
    <div class="container-fluid p-0">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">{{__('page.payment_list')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-colored thead-primary">
                                    <tr class="bg-blue">
                                        <th style="width:40px;">#</th>
                                        <th>{{__('page.date')}}</th>
                                        <th>{{__('page.reference_no')}}</th>
                                        <th>{{__('page.amount')}}</th> 
                                        <th>{{__('page.note')}}</th>
                                        <th>{{__('page.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td class="date">{{date('Y-m-d H:i', strtotime($item->timestamp))}}</td>
                                            <td class="reference_no">{{$item->reference_no}}</td>
                                            <td class="amount" data-value="{{$item->amount}}">{{number_format($item->amount)}}</td>
                                            <td class="" data-path="{{$item->attachment}}">
                                                <span class="tx-info note">{{$item->note}}</span>&nbsp;
                                                @if($item->attachment != "")
                                                    <a href="#" class="attachment" data-value="{{asset($item->attachment)}}"><i class="fa fa-paperclip"></i></a>
                                                @endif
                                            </td>
                                            <td class="py-1">
                                                <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                <a href="{{route('payment.delete', $item->id)}}" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 mt-3 text-right">
                                    @if($type == 'purchase')
                                        <a href="{{route('purchase.create')}}" class="btn btn-oblong btn-primary mr-3"><i class="fa fa-plus"></i>  {{__('page.add_purchase')}}</a>                                       
                                        <a href="{{route('purchase.index')}}" class="btn btn-oblong btn-success mg-r-30"><i class="fa fa-list"></i>  {{__('page.purchases_list')}}</a>
                                    @elseif($type == 'sale')
                                        <a href="{{route('product_sale.create')}}" class="btn btn-oblong btn-primary mr-3"><i class="fa fa-plus"></i>  {{__('page.add_product_sale')}}</a>                                       
                                        <a href="{{route('product_sale.index')}}" class="btn btn-oblong btn-success mg-r-30"><i class="fa fa-list"></i>  {{__('page.product_sale_list')}}</a>
                                    @elseif($type == 'workshop')
                                        <a href="{{route('workshop.index')}}" class="btn btn-oblong btn-success mg-r-30"><i class="fa fa-list"></i>  {{__('page.workshop')}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- The Modal -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('page.edit_payment')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form action="{{route('payment.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" class="id">
                        <div class="modal-body">
                                <div class="form-group">
                                    <label class="control-label">{{__('page.date')}}</label>
                                    <input class="form-control date" type="text" name="date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}">
                                </div>                        
                                <div class="form-group">
                                    <label class="control-label">{{__('page.reference_no')}}</label>
                                    <input class="form-control reference_no" type="text" name="reference_no" placeholder="{{__('page.reference_number')}}">
                                </div>                                                
                                <div class="form-group">
                                    <label class="control-label">{{__('page.amount')}}</label>
                                    <input class="form-control amount" type="text" name="amount" placeholder="{{__('page.amount')}}">
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
                            <button type="submit" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="attachModal">
            <div class="modal-dialog" style="margin-top:17vh">
                <div class="modal-content">
                    <div id="image_preview"></div>
                    {{-- <img src="" id="attachment" width="100%" height="600" alt=""> --}}
                </div>
            </div>
        </div>
    
@endsection

@section('script')
    <script src="{{asset('master/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>
    <script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>  
    <script src="{{asset('master/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
    <script>
        $(document).ready(function(){

            $("#edit_form input.date").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });

            $('.file-input-styled').uniform({
                fileButtonClass: 'action btn bg-primary tx-white'
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let date = $(this).parents('tr').find(".date").text().trim();
                let reference_no = $(this).parents('tr').find(".reference_no").text().trim();
                let amount = $(this).parents('tr').find(".amount").data('value');
                let note = $(this).parents('tr').find(".note").text().trim();
                $("#editModal input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .date").val(date);
                $("#editModal .reference_no").val(reference_no);
                $("#editModal .amount").val(amount);
                $("#editModal .note").val(note);
                $("#editModal").modal();
            });

            $(".attachment").click(function(e){
                e.preventDefault();
                let path = $(this).data('value');
                console.log(path)
                // $("#attachment").attr('src', path);
                $("#image_preview").html('')
                $("#image_preview").verySimpleImageViewer({
                    imageSource: path,
                    frame: ['100%', '100%'],
                    maxZoom: '900%',
                    zoomFactor: '10%',
                    mouse: true,
                    keyboard: true,
                    toolbar: true,
                });
                $("#attachModal").modal();
            });
        })
    </script>
@endsection
                    