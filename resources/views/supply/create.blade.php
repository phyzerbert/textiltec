@extends('layouts.master')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Create Supply</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('supply.save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row my-5">
                            <div class="form-group col-md-4">
                                <label for="name">{{__('page.name')}}</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" required placeholder="{{__('page.name')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="code">{{__('page.code')}}</label>
                                <input type="text" class="form-control" name="code" id="code" value="{{old('code')}}" required placeholder="{{__('page.code')}}">
                            </div>                            
                            <div class="form-group col-md-4">
                                <label for="category">{{__('page.category')}}</label>
                                <select id="inputState" name="category" class="form-control" required>
                                    <option label="{{__('page.select_category')}}"></option>
                                    @foreach ($categories as $item)
                                        <option value="{{$item->id}}" @if(old('category_id') == $item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row my-5">
                            <div class="form-group col-md-4">
                                <label for="unit">{{__('page.unit')}}</label>
                                <input type="text" class="form-control" name="unit" id="unit" value="{{old('unit')}}" placeholder="{{__('page.unit')}}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cost">{{__('page.cost')}}</label>
                                <input type="number" class="form-control" name="cost" id="cost" value="{{old('cost')}}" min="0" required placeholder="{{__('page.cost')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="alert_quantity">{{__('page.alert_quantity')}}</label>
                                <input type="number" class="form-control" name="alert_quantity" value="{{old('alert_quantity')}}" id="alert_quantity" min="0" placeholder="{{__('page.alert_quantity')}}">
                            </div>
                        </div>

                        <div class="form-row my-5">
                            <div class="form-group col-md-4">
                                <label for="supplier">{{__('page.supplier')}}</label>
                                <select class="form-control select2" name="supplier" data-toggle="select2"  required>
                                    <option label="{{__('page.product_supplier')}}"></option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach                                    
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="color">{{__('page.color')}}</label>
                                <input type="text" class="form-control" name="color" id="color" value="{{old('alert_quantity')}}" placeholder="{{__('page.color')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="image">{{__('page.image')}}</label>
                                <label class="custom-file wd-100p">
                                    <input type="file" name="image" id="image" class="file-input-styled" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="form-group mg-b-10-force">
                            <label class="form-control-label" for="detail">{{__('page.detail')}}</label>
                            <textarea class="form-control" name="detail" id="detail" rows="5" placeholder="{{__('page.detail')}}">{{old('detail')}}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-lg btn-primary float-right"><i class="align-middle" data-feather="send"></i>&nbsp;&nbsp;{{__('page.save')}}</button>
                        <a href="{{route('supply.index')}}" class="btn btn-lg btn-success mr-2 float-right"><i class="align-middle" data-feather="list"></i>&nbsp;&nbsp;{{__('page.supply_list')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
</div>
@endsection

@section('script')
    <script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.file-input-styled').uniform({
                fileButtonClass: 'action btn bg-primary tx-white'
            });
            $('.select2').each(function() {
                $(this)
                    .wrap('<div class="position-relative"></div>')
                    .select2({
                        placeholder: '{!! __('page.product_supplier') !!}',
                        dropdownParent: $(this).parent()
                    });                    
            })
        });
    </script>
@endsection
                    