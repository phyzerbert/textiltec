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

        <h1 class="h3 mb-3"><i class="align-middle" data-feather="box"></i> {{__('page.product_management')}}</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-success btn-sm float-right mg-b-5" id="btn-add"><i class="align-middle" data-feather="plus"></i> Add New</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-colored thead-primary">
                                    <tr class="bg-blue">
                                        <th class="wd-50">#</th>
                                        <th>{{__('page.name')}}</th>
                                        <th>{{__('page.code')}}</th>
                                        <th>{{__('page.category')}}</th>
                                        <th>{{__('page.price')}}</th>
                                        <th>{{__('page.quantity')}}</th>
                                        <th>{{__('page.unit')}}</th>
                                        <th>{{__('page.description')}}</th>
                                        <th>{{__('page.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach ($data as $item)
                                        @php
                                            $produce_order = $item->produce_order;
                                            $price = 0;
                                            if($produce_order){
                                                $produce_quantity = $produce_order->receptions->sum('quantity');
                                                $price = $produce_order->total_cost;
                                            }else {
                                                $produce_quantity = 0;
                                            }
                                            $sale_quantity = $item->sale_orders->sum('quantity');
                                            $quantity = $produce_quantity - $sale_quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td class="name py-1" data-value="{{$item->name}}">
                                                <img class="product_image" src="@if($item->image){{asset($item->image)}}@else{{asset('images/no-image-icon.png')}}@endif" width="48" height="48" class="rounded-circle mr-2" style="cursor:pointer;" alt="">
                                                {{$item->name}}
                                            </td>
                                            <td class="code">{{$item->code}}</td>
                                            <td class="category" data-value="{{$item->category_id}}">@isset($item->category->name){{$item->category->name}}@endisset</td>
                                            <td class="price" data-value="{{$item->price}}">{{number_format($price)}}</td>
                                            <td class="quantity">{{$quantity}}</td>
                                            <td class="unit">{{$item->unit}}</td>
                                            <td class="description">{{$item->description}}</td>
                                            <td class="py-1">
                                                <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                <a href="{{route('product.delete', $item->id)}}" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
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

    <!-- The Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_product')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('product.create')}}" id="create_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.code')}}</label>
                            <input class="form-control code" type="text" name="code" placeholder="{{__('page.code')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.unit')}}</label>
                            <input class="form-control unit" type="text" name="unit" placeholder="{{__('page.unit')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.category')}}</label>
                            <select name="category_id" class="form-control category_id">
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.description')}}</label>
                            <input class="form-control description" type="text" name="description" placeholder="{{__('page.description')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.image')}}</label>
                            <input class="form-control file-input-styled image" type="file" name="image">
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
                    <h4 class="modal-title">{{__('page.edit_product')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('product.edit')}}" id="edit_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.code')}}</label>
                            <input class="form-control code" type="text" name="code" placeholder="{{__('page.code')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.unit')}}</label>
                            <input class="form-control unit" type="text" name="unit" placeholder="{{__('page.unit')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.category')}}</label>
                            <select name="category_id" class="form-control category_id">
                                @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.description')}}</label>
                            <input class="form-control description" type="text" name="description" placeholder="{{__('page.description')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.image')}}</label>
                            <input class="form-control file-input-styled image" type="file" name="image">
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
    <script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script> 
    <script src="{{asset('master/plugins/imageviewer/js/jquery.verySimpleImageViewer.min.js')}}"></script> 
    <script>
        $(document).ready(function(){
            
            $('.file-input-styled').uniform({
                fileButtonClass: 'action btn bg-primary tx-white'
            });

            $("#btn-add").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addModal").modal();
            });

            $(".btn-edit").click(function(){
                let id = $(this).data("id");
                let name = $(this).parents('tr').find(".name").data('value');
                let category = $(this).parents('tr').find(".category").data('value');
                let code = $(this).parents('tr').find(".code").text().trim();
                let unit = $(this).parents('tr').find(".unit").text().trim();
                let description = $(this).parents('tr').find(".description").text().trim();
                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .name").val(name);
                $("#editModal .code").val(code);
                $("#editModal .unit").val(unit);
                $("#editModal .category_id").val(category).change();
                $("#editModal .description").val(description);
                $("#editModal").modal();
            });

            $(".product_image").click(function(e){
                e.preventDefault();
                let path = $(this).attr('src');                
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
                    