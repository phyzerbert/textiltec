@extends('layouts.master')
@section('style')    
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
        <h1 class="h3 mb-3"><i class="fa fa-cubes"></i> {{__('page.production_order_details')}}</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">                        
                        <div class="row">
                            <div class="col-lg-4"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.product')}}</h2>
                                    </div>
                                    <div class="card-body">
                                        <img src="@if($order->main_image){{asset($order->main_image)}}@else{{asset('images/no-image-icon.png')}}@endif" alt="" class="img-fluid">
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{__('page.name')}} :  @isset($order->product->name){{$order->product->name}}@endisset</li>
                                        <li class="list-group-item">{{__('page.code')}} :  @isset($order->product->code){{$order->product->code}}@endisset</li>
                                        <li class="list-group-item">{{__('page.description')}} :  @isset($order->product->description){{$order->product->description}}@endisset</li>
                                        <li class="list-group-item">{{__('page.quantity')}} :  @isset($order->quantity){{$order->quantity}}@endisset</li>
                                    </ul>
                                </div> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.workshop')}}</h2>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{__('page.workshop')}} : {{$order->workshop->name}}</li>
                                    </ul>
                                </div> 
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title mb-0">{{__('page.reference')}}</h2>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{__('page.date')}} :  @isset($order->timestamp){{$order->timestamp}}@endisset</li>
                                        <li class="list-group-item">{{__('page.reference_no')}} :  @isset($order->reference_no){{$order->reference_no}}@endisset</li>
                                        <li class="list-group-item">{{__('page.responsibility')}} :  @isset($order->responsibility){{$order->responsibility}}@endisset</li>
                                        <li class="list-group-item">{{__('page.deadline')}} :  @isset($order->deadline){{$order->deadline}}@endisset Days</li>
                                        <li class="list-group-item">{{__('page.description')}} :  @isset($order->description){{$order->description}}@endisset</li>
                                    </ul>                                   
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <h3><i class="align-middle" data-feather="image"></i> {{__('page.image_gallery')}}</h3>
                                <div class="row"> 
                                    @php
                                        $image_array = array('jpg', 'png', 'jpeg', 'gif');
                                        $video_array = array('avi', 'mp4', 'mpg');                                
                                    @endphp                                  
                                    @foreach ($order->images as $item)  
                                        @php
                                            $filename = basename($item->path);
                                            $extension = pathinfo($item->path, PATHINFO_EXTENSION);
                                        @endphp                                      
                                        <div class="col-md-4">
                                            <div class="card card-body">
                                                @if (in_array($extension, $image_array))
                                                    <img class="gallery-image" width="100%" height="160" src="{{asset($item->path)}}" alt="" class="img-fluid" />
                                                @elseif(in_array($extension, $video_array))
                                                    <video class="gallery-video" width="100%" height="160" src="{{asset($item->path)}}" controls autoplay><source src="{{asset($item->path)}}"></video>
                                                @else
                                                    <a href="{{asset($item->path)}}" download>{{$filename}}</a>
                                                @endif
                                                    {{-- <img src="{{asset($item->path)}}" width="100%" alt="" class="img-fluid"> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <h3><i class="align-middle" data-feather="codepen"></i> {{__('page.supplies_list')}}</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-info">
                                            <tr>
                                                <th class="wd-40">#</th>
                                                <th>{{__('page.supply_name_code')}}</th>
                                                <th>{{__('page.cost')}}</th>
                                                <th>{{__('page.average_quantity')}}</th>
                                                <th>{{__('page.total_quantity')}}</th>
                                                <th>{{__('page.unit')}}</th>
                                                <th>{{__('page.subtotal')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;
                                            @endphp
                                            @foreach ($order->supplies as $item)
                                            @php
                                                $cost = $item->cost;
                                                $subtotal = $item->subtotal;            
                                                $total_amount += $subtotal;
                                            @endphp
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>@isset($item->supply->name){{$item->supply->name}} ({{$item->supply->code}})@endisset</td>
                                                    <td>{{number_format($item->cost)}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{$item->quantity * $order->quantity}}</td>
                                                    <td>@isset($item->supply->unit){{$item->supply->unit}}@endisset</td>
                                                    <td>{{number_format($item->subtotal)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="6" class="font-weight-bold" style="text-align:right">{{__('page.total')}} </td>
                                                <td>{{number_format($total_amount)}}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="tx-bold tx-black">
                                            <tr>
                                                <td colspan="6" class="font-weight-bold" style="text-align:right">{{__('page.supply_cost')}} </td>
                                                <td>{{number_format($total_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="font-weight-bold" style="text-align:right">{{__('page.manufacturing_cost')}} </td>
                                                <td>{{number_format($order->manufacturing_cost)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="font-weight-bold" style="text-align:right">{{__('page.total_cost')}} </td>
                                                <td>{{number_format($order->total_cost)}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12 col-md-6">
                                        <div class="card flex-fill">
                                            <div class="card-body py-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h3 class="mb-2">{{number_format($order->quantity * $total_amount)}}</h3>
                                                        <div class="mb-0">{{__('page.total_supply_cost')}}</div>
                                                    </div>
                                                    <div class="col-4 ml-auto text-right">
                                                        <div class="d-inline-block mt-2">
                                                            <i class="feather-lg text-primary" data-feather="truck"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card flex-fill">
                                            <div class="card-body py-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h3 class="mb-2">{{number_format($order->quantity * $order->manufacturing_cost)}}</h3>
                                                        <div class="mb-0">{{__('page.total_manufacture_cost')}}</div>
                                                    </div>
                                                    <div class="col-4 ml-auto text-right">
                                                        <div class="d-inline-block mt-2">
                                                            <i class="feather-lg text-warning" data-feather="activity"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card flex-fill">
                                            <div class="card-body py-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h3 class="mb-2">{{number_format($order->quantity * ($order->manufacturing_cost + $total_amount))}}</h3>
                                                        <div class="mb-0">{{__('page.total_production_cost')}}</div>
                                                    </div>
                                                    <div class="col-4 ml-auto text-right">
                                                        <div class="d-inline-block mt-2">
                                                            <i class="feather-lg text-danger" data-feather="dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card flex-fill">
                                            <div class="card-body py-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h3 class="mb-2">{{number_format($order->manufacturing_cost + $total_amount)}}</h3>
                                                        <div class="mb-0">{{__('page.product_cost')}}</div>
                                                    </div>
                                                    <div class="col-4 ml-auto text-right">
                                                        <div class="d-inline-block mt-2">
                                                            <i class="feather-lg text-info" data-feather="dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12 text-right">                                        
                                        <a href="{{route('produce_order.edit', $order->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i>  {{__('page.edit')}}</a>
                                        <a href="{{route('produce_order.index')}}" class="btn btn-success"><i class="fa fa-list"></i>  {{__('page.production_order_list')}}</a>
                                    </div>
                                </div>
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
    <script src="{{asset('master/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            
            $(".attachment").click(function(e){
                e.preventDefault();
                let path = $(this).data('value');
                // console.log(path)
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

            $(".gallery-image").click(function(){
                let path = $(this).attr('src');
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

            $(".gallery-video").click(function(){
                let path = $(this).attr('src');
                let content = `<video width="100%" height="100%" controls><source src="${path}"></video>`
                $("#image_preview").html(content);
                $("#attachModal").modal();
            });
            
        });
    </script>
@endsection
                    