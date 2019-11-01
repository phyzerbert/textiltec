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

            <h1 class="h3 mb-3">{{__('page.receive_of_workshop')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                            
                            <a href="javascript:;" class="btn btn-sm btn-info ml-1 mb-2 float-right">{{__('page.receive')}}</a>
                            <a href="{{route('workshop.payment', $id)}}" class="btn btn-sm btn-outline-info ml-1 mb-2 float-right">{{__('page.payment')}}</a>
                            <a href="{{route('workshop.order', $id)}}" class="btn btn-sm btn-outline-info ml-1 mb-2 float-right">{{__('page.production_order')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-colored thead-primary">
                                        <tr class="bg-blue">
                                            <th style="width:40px;">#</th>
                                            <th>{{__('page.date')}}</th>
                                            <th>{{__('page.quantity')}}</th> 
                                        </tr>
                                    </thead>
                                    <tbody>                            
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="date">{{date('Y-m-d H:i', strtotime($item->receive_date))}}</td>
                                                <td class="quantity" data-value="{{$item->quantity}}">{{number_format($item->quantity)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="10" class="text-center">No Data</th>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
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

            $(".attachment").click(function(e){
                e.preventDefault();
                let path = $(this).data('value');
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
                    