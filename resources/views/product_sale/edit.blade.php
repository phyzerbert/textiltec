@extends('layouts.master')
@section('style')
    <link href="{{asset('master/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.css')}}" rel="stylesheet">
    <script src="{{asset('master/plugins/vuejs/vue.js')}}"></script>
    <script src="{{asset('master/plugins/vuejs/axios.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><i class="fa fa-cubes"></i> Edit Product Sale</h1>
        {{-- @dump($sale->orders) --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form-layout form-layout-1" action="{{route('product_sale.update')}}" method="POST" enctype="multipart/form-data" id="app">
                            @csrf
                            <input type="hidden" name="id" class="id" id="product_sale_id" value="{{$sale->id}}">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.date')}} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="date" id="product_sale_date" v-model="date" placeholder="{{__('page._date')}}" autocomplete="off" required>
                                        @error('date')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.reference_number')}} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="reference_number"  v-model="reference_no" required placeholder="{{__('page.reference_number')}}">
                                        @error('reference_number')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.customer')}} <span class="text-danger">*</span></label>
                                        <div class="input-group">                                  
                                            <select class="form-control select2" name="customer" id="search_customer" required data-placeholder="{{__('page.select_customer')}}">
                                                <option label="{{__('page.select_customer')}}"></option>
                                                @foreach ($customers as $item)
                                                    <option value="{{$item->id}}" @if($sale->customer_id == $item->id) selected @endif>{{$item->company}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-primary btn-rounded tx-white ml-1" id="btn-add-customer"><i class="fa fa-plus"></i></button>
                                            </div>  
                                        </div>
                                        @error('customer')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.attachment')}}</label>
                                        <input type="file" name="attachment" id="file2" class="file-input-styled">
                                    </div>
                                    {{-- <div class="form-group">
                                        <label class="col-form-label">{{__('page.credit_days')}}</label>
                                        <input type="number" class="form-control" name="credit_days" min=0 v-model="credit_days" required placeholder="{{__('page.credit_days')}}" />
                                    </div> --}}
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.status')}} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" v-model="status" data-placeholder="{{__('page.status')}}">
                                            <option label="{{__('page.status')}}" hidden></option>
                                            <option value="0">{{__('page.pending')}}</option>
                                            <option value="1">{{__('page.received')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('page.note')}}:</label>
                                        <textarea class="form-control" name="note" v-model="note" rows="5" placeholder="{{__('page.note')}}"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div>
                                        <h5 class="my-1" style="float:left">{{__('page.order_items')}}</h5>
                                        {{-- <button type="button" class="btn btn-primary mg-b-10 add-product" style="float:right">ADD</button> --}}
                                        <a href="#" class="btn btn-sm btn-primary mb-2 add-product" style="float:right" @click="add_item()" title="{{__('page.right_ctrl_key')}}"><div><i class="fa fa-plus"></i>{{__('page.add')}}</div></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-colored table-success" id="product_order_table">
                                            <thead>
                                                <tr>
                                                    <th>{{__('page.name')}}</th>
                                                    <th>{{__('page.price')}}</th>
                                                    <th>{{__('page.quantity')}}</th>
                                                    <th>{{__('page.subtotal')}}</th>
                                                    <th style="width:30px"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="top-search-form">
                                                <tr v-for="(item,i) in order_items" :key="i">
                                                    <td>
                                                        <select class="form-control form-control-sm product" name="product_id[]" v-model="item.product_id" @change="get_product(i)">
                                                            <option value="" hidden>{{__('page.select_product')}}</option>
                                                            <option :value="product.id" v-for="(product, i) in products" :key="i">@{{product.name}}(@{{product.code}})</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control form-control-sm price" name="price[]" v-model="item.price" required placeholder="{{__('page.price')}}" /></td>
                                                    <td><input type="number" class="form-control form-control-sm quantity" name="quantity[]" v-model="item.quantity" required placeholder="{{__('page.quantity')}}" /></td>
                                                    <td class="subtotal">
                                                        @{{formatPrice(item.sub_total)}}
                                                        <input type="hidden" name="subtotal[]" :value="item.sub_total" />
                                                        <input type="hidden" name="order_id[]" :value="item.order_id" />
                                                    </td>
                                                    <td align="center">
                                                        <a href="#" class="btn btn-sm btn-success remove-product" @click="remove(i)"><i class="fa fa-times"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2">{{__('page.total')}}</td>
                                                    <td class="total_quantity">@{{total.quantity}}</td>
                                                    <td class="total" colspan="2">@{{formatPrice(total.price)}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="row my-3">                        
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-5 text-sm-right">{{__('page.discount')}} :</label>
                                                <div class="col-md-7">
                                                    <input type="text" name="discount_string" class="form-control" v-model="discount_string" placeholder="{{__('page.discount')}}">
                                                    <input type="hidden" name="discount" :value="discount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-5 text-sm-right">{{__('page.shipping')}} :</label>
                                                <div class="col-md-7">
                                                    <input type="text" name="shipping_string" class="form-control" v-model="shipping_string" placeholder="{{__('page.shipping')}}">
                                                    <input type="hidden" name="shipping" :value="shipping">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-5 text-sm-right">{{__('page.returns')}} :</label>
                                                <div class="col-md-7">
                                                    <input type="number" name="returns" class="form-control" min="0" v-model="returns" placeholder="{{__('page.returns')}}">
                                                    <input type="hidden" name="grand_total" :value="grand_total">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-right">Purchase: @{{formatPrice(total.price)}} - Discount: @{{formatPrice(discount)}} - Shipping: @{{formatPrice(shipping)}} - Returns: @{{formatPrice(returns)}} = Grand Total: @{{formatPrice(grand_total)}}</p>
                                        </div>
                                    </div>

                                    
                                    <div class="form-layout-footer mt-5 text-right">
                                        <button type="submit" class="btn btn-lg btn-primary mr-2"><i class="fa fa-check"></i>  {{__('page.save')}}</button>
                                        <a href="{{route('product_sale.index')}}" class="btn btn-lg btn-warning"><i class="fa fa-times"></i>  {{__('page.cancel')}}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('page.add_customer')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="" id="create_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">{{__('page.name')}}</label>
                            <input class="form-control name" type="text" name="name" placeholder="{{__('page.name')}}">
                            <span id="name_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.company')}}</label>
                            <input class="form-control company" type="text" name="company" placeholder="{{__('page.company_name')}}">
                            <span id="company_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.email')}}</label>
                            <input class="form-control email" type="email" name="email" placeholder="{{__('page.email_address')}}">
                            <span id="email_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.phone_number')}}</label>
                            <input class="form-control phone_number" type="text" name="phone_number" placeholder="{{__('page.phone_number')}}">
                            <span id="phone_number_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.address')}}</label>
                            <input class="form-control address" type="text" name="address" placeholder="{{__('page.address')}}">
                            <span id="address_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.city')}}</label>
                            <input class="form-control city" type="text" name="city" placeholder="{{__('page.city')}}">
                            <span id="city_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('page.note')}}</label>
                            <textarea class="form-control note" name="note" rows="3" placeholder="{{__('page.note')}}"></textarea>
                            <span id="note_error" class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button type="button" id="btn_create" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;{{__('page.save')}}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;{{__('page.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{asset('master/plugins/uniform/uniform.min.js')}}"></script>    
    <script src="{{asset('master/plugins/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('master/plugins/jquery-ui/timepicker/jquery-ui-timepicker-addon.min.js')}}"></script>    
    <script src="{{ asset('master/js/product_sale_edit_order_items.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.file-input-styled').uniform({
                fileButtonClass: 'action btn bg-primary tx-white'
            });
            $('.select2').each(function() {
                $(this)
                    .wrap('<div class="position-relative" style="width: calc(100% - 40px)"></div>')
                    .select2({
                        width: 'resolve',
                        placeholder: '{!! __('page.product_customer') !!}'
                    });                    
            });
            $("#product_sale_date").datetimepicker({
                dateFormat: 'yy-mm-dd',
            });
            $(".expiry_date").datepicker({
                dateFormat: 'yy-mm-dd',
            });

            $("#btn-add-customer").click(function(){
                $("#create_form input.form-control").val('');
                $("#create_form .invalid-feedback strong").text('');
                $("#addCustomerModal").modal();
            });

            $("#btn_create").click(function(){
                $("#ajax-loading").show();
                $.ajax({
                    url: "{{route('customer.product_sale_create')}}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#create_form').serialize(),
                    success : function(data) {
                        $("#ajax-loading").hide();
                        if(data.id != null) {
                            $("#addCustomerModal").modal('hide');
                            $("#search_customer").append(`
                                <option value="${data.id}">${data.company}</option>
                            `).val(data.id);
                        }
                        else if(data.message == 'The given data was invalid.') {
                            alert(data.message);
                        }
                    },
                    error: function(data) {
                        $("#ajax-loading").hide();
                        console.log(data.responseJSON);
                        if(data.responseJSON.message == 'The given data was invalid.') {
                            let messages = data.responseJSON.errors;
                            if(messages.name) {
                                $('#name_error strong').text(data.responseJSON.errors.name[0]);
                                $('#name_error').show();
                                $('#create_form .name').focus();
                            }
                            
                            if(messages.company) {
                                $('#company_error strong').text(data.responseJSON.errors.company[0]);
                                $('#company_error').show();
                                $('#create_form .company').focus();
                            }

                            if(messages.email) {
                                $('#email_error strong').text(data.responseJSON.errors.email[0]);
                                $('#email_error').show();
                                $('#create_form .email').focus();
                            }

                            if(messages.phone_number) {
                                $('#phone_number_error strong').text(data.responseJSON.errors.phone_number[0]);
                                $('#phone_number_error').show();
                                $('#create_form .phone_number').focus();
                            }

                            if(messages.address) {
                                $('#address_error strong').text(data.responseJSON.errors.address[0]);
                                $('#address_error').show();
                                $('#create_form .address').focus();
                            }

                            if(messages.city) {
                                $('#city_error strong').text(data.responseJSON.errors.city[0]);
                                $('#city_error').show();
                                $('#create_form .city').focus();
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
                    