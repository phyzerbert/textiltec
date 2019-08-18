@extends('layouts.master')

@section('content')
    <div class="container p-0">
        <div class="container-fluid p-0">

            <h1 class="h2 mb-3">{{__('page.supply_category')}}</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-success btn-sm float-right mg-b-5" id="btn-add"><i class="align-middle" data-feather="plus"></i> Add New</button>
                        </div>
                        <div class="card-body table-responsive">                            
                            <table class="table table-bordered table-hover">
                                <thead class="thead-colored thead-primary">
                                    <tr class="bg-blue">
                                        <th class="wd-40">#</th>
                                        <th>{{__('page.name')}}</th>
                                        <th>{{__('page.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td class="name">{{$item->name}}</td>
                                            <td class="py-1">
                                                <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                <a href="{{route('scategory.delete', $item->id)}}" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
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
                            <h4 class="modal-title">{{__('page.add_category')}}</h4>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <form action="{{route('scategory.create')}}" id="create_form" method="post">
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
                            <h4 class="modal-title">{{__('page.edit_category')}}</h4>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <form action="{{route('scategory.edit')}}" id="edit_form" method="post">
                            @csrf
                            <input type="hidden" name="id" class="id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="control-label">{{__('page.name')}}</label>
                                    <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                                    <span id="edit_name_error" class="invalid-feedback">
                                        <strong></strong>
                                    </span>
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

    
@endsection

@section('script')
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
        })
    </script>
@endsection
                    