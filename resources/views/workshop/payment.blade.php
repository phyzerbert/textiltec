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

            <h1 class="h3 mb-3">{{__('page.payment_list_of_workshop')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                            
                            <a href="{{route('workshop.receive', $id)}}" class="btn btn-sm btn-outline-info ml-1 mb-2 float-right">{{__('page.receive')}}</a>
                            <a href="javascript:;" class="btn btn-sm btn-info ml-1 mb-2 float-right">{{__('page.payment')}}</a>
                            <a href="{{route('workshop.order', $id)}}" class="btn btn-sm btn-outline-info ml-1 mb-2 float-right">{{__('page.production_order')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-colored thead-primary">
                                        <tr class="bg-blue">
                                            <th style="width:40px;">#</th>
                                            <th>{{__('page.date')}}</th>
                                            <th>{{__('page.reference_no')}}</th>
                                            <th>{{__('page.amount')}}</th> 
                                            <th>{{__('page.note')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @php
                                            $total_amount = 0;
                                        @endphp                               
                                        @forelse ($data as $item)
                                            @php
                                                $total_amount += $item->amount;
                                            @endphp
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
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" align="center">No Data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th>{{number_format($total_amount)}}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
                    