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

            <h1 class="h3 mb-3">{{__('page.receive_report')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-colored thead-primary">
                                        <tr class="bg-blue">
                                            <th style="width:40px;">#</th>
                                            <th>{{__('page.date')}}</th>
                                            <th>{{__('page.quantity')}}</th> 
                                            <th>{{__('page.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="date">{{date('Y-m-d', strtotime($item->received_date))}}</td>
                                                <td class="quantity" data-value="{{$item->quantity}}">{{number_format($item->quantity)}}</td>
                                                <td class="py-1">
                                                    <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                    <a href="{{route('order_receive.delete', $item->id)}}" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="10" class="text-center">No Data</th>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="clearfix">
                                    <div class="mt-3 text-right">
                                        <a href="{{route('produce_order.index')}}" class="btn btn-oblong btn-primary mr-3"><i class="fa fa-plus"></i>  {{__('page.production_order')}}</a>                                       
                                        <a href="{{route('produce_order.create')}}" class="btn btn-oblong btn-success mg-r-30"><i class="fa fa-list"></i>  {{__('page.add_order')}}</a>
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
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('page.edit_order_receive')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form action="{{route('order_receive.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" class="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('page.date')}}</label>
                                <input class="form-control date" type="text" name="date" autocomplete="off" value="{{date('Y-m-d H:i')}}" placeholder="{{__('page.date')}}">
                            </div>                                                
                            <div class="form-group">
                                <label class="control-label">{{__('page.quantity')}}</label>
                                <input class="form-control quantity" type="text" name="quantity" placeholder="{{__('page.quantity')}}">
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
                let quantity = $(this).parents('tr').find(".quantity").data('value');
                $("#editModal input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .date").val(date);
                $("#editModal .quantity").val(quantity);
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
                    