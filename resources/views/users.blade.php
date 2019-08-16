@extends('layouts.master')

@section('content')
    <div class="container p-0">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">User Management</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-success btn-sm float-right mg-b-5" id="btn-add"><i class="align-middle" data-feather="plus"></i> Add New</button>
                        </div>
                        <div class="card-body">                            
                            <table class="table table-bordered table-hover">
                                <thead class="thead-colored thead-primary">
                                    <tr class="bg-blue">
                                        <th class="wd-40">#</th>
                                        <th>{{__('page.username')}}</th>
                                        <th>{{__('page.first_name')}}</th>
                                        <th>{{__('page.last_name')}}</th>
                                        <th>{{__('page.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                                
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                            <td class="name">{{$item->name}}</td>
                                            <td class="first_name">{{$item->first_name}}</td>
                                            <td class="last_name">{{$item->last_name}}</td>
                                            <td class="py-1">
                                                <a href="#" class="btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="left" title="{{__('page.edit')}}"><i class="align-middle" data-feather="edit"></i></a>
                                                <a href="{{route('user.delete', $item->id)}}" class="ml-2" data-toggle="tooltip" data-placement="left" title="{{__('page.delete')}}" onclick="return window.confirm('{{__('page.are_you_sure')}}')"><i class="align-middle" data-feather="trash-2"></i></a>
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
                                    {!! $data->appends(['name' => $name])->links() !!}
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
                    <h4 class="modal-title">{{__('page.add_new_user')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="create_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.username')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="Username">
                            <span class="invalid-feedback name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.first_name')}}</label>
                            <input class="form-control first_name" type="text" name="first_name" placeholder="{{__('page.first_name')}}">
                            <span class="invalid-feedback first_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.last_name')}}</label>
                            <input class="form-control last_name" type="text" name="last_name" placeholder="{{__('page.last_name')}}">
                            <span class="invalid-feedback last_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password')}}</label>
                            <input type="password" name="password" class="form-control password" placeholder="{{__('page.password')}}">
                            <span class="invalid-feedback password_error">
                                <strong></strong>
                            </span>
                        </div>    
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password_confirm')}}</label>
                            <input type="password" name="password_confirmation" class="form-control confirm_password" placeholder="{{__('page.password_confirm')}}">
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button type="button" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.edit_user')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="edit_form" method="post">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.username')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="Username">
                            <span class="invalid-feedback name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.first_name')}}</label>
                            <input class="form-control first_name" type="text" name="first_name" placeholder="{{__('page.first_name')}}">
                            <span class="invalid-feedback first_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.last_name')}}</label>
                            <input class="form-control last_name" type="text" name="last_name" placeholder="{{__('page.last_name')}}">
                            <span class="invalid-feedback last_name_error">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password')}}</label>
                            <input type="password" name="password" class="form-control password" placeholder="{{__('page.password')}}">
                            <span class="invalid-feedback password_error">
                                <strong></strong>
                            </span>
                        </div>    
                        <div class="form-group password-field">
                            <label class="control-label">{{__('page.password_confirm')}}</label>
                            <input type="password" name="password_confirmation" class="form-control confirm_password" placeholder="{{__('page.password_confirm')}}">
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <button type="button" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{__('page.close')}}</button>
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
                let first_name = $(this).parents('tr').find(".first_name").text().trim();
                let last_name = $(this).parents('tr').find(".last_name").text().trim();
                $("#edit_form input.form-control").val('');
                $("#editModal .id").val(id);
                $("#editModal .name").val(name);
                $("#editModal .first_name").val(first_name);
                $("#editModal .last_name").val(last_name);
                $("#editModal").modal();
            });

            $("#btn_create").click(function(){  
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('user.create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        if(data == 'success') {
                            alert('Created successfully.');
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                        $("#ajax-loading").hide();
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#create_form .name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#create_form .name_error').show();
                                $('#create_form .name').focus();
                            }

                            if(messages.password) {
                                $('#create_form .password_error strong').text(data.responseJSON.errors.password[0]);
                                $('#create_form .password_error').show();
                                $('#create_form .password').focus();
                            }
                        }
                    }
                });
            });

            $("#btn_update").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('user.edit')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#edit_form').serialize(),
                    success : function(data) {
                        console.log(data);
                        if(data == 'success') {
                            alert('Updated successfully.');
                            window.location.reload();
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                        $("#ajax-loading").hide();
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#edit_form .name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#edit_form .name_error').show();
                                $('#edit_form .name').focus();
                            }

                            if(messages.password) {
                                $('#edit_form .password_error strong').text(data.responseJSON.errors.password[0]);
                                $('#edit_form .password_error').show();
                                $('#edit_form .password').focus();
                            }
                        }
                    }
                });
            });
        })
    </script>
@endsection
                    