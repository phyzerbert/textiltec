<form action="" method="POST" class="form-inline top-search-form float-left" id="searchForm">
    @csrf
    <input type="hidden" name="sort_by_date" value="{{$sort_by_date}}" id="search_sort_date" />
    <input type="text" class="form-control form-control-sm mr-sm-2 mb-2" name="reference_no" id="search_reference_no" value="{{$reference_no}}" placeholder="{{__('page.reference_no')}}">
    <select class="form-control form-control-sm mr-sm-2 mb-2 select2" name="product_id" id="search_product" data-placeholder="{{__('page.select_product')}}">
        <option label="{{__('page.select_product')}}"></option>
        @foreach ($products as $item)
            <option value="{{$item->id}}" @if ($product_id == $item->id) selected @endif>{{$item->name}}</option>
        @endforeach
    </select>
    <select class="form-control form-control-sm ml-2 mb-2" name="workshop_id" id="search_workshop">
        <option label="{{__('page.select_workshop')}}" hidden></option>
        @foreach ($workshops as $item)
            <option value="{{$item->id}}" @if ($workshop_id == $item->id) selected @endif>{{$item->name}}</option>
        @endforeach
    </select>
    <input type="text" class="form-control form-control-sm mb-2 mx-sm-2 mb-2" name="period" id="period" autocomplete="off" value="{{$period}}" placeholder="{{__('page.purchase_date')}}">
    <button type="submit" class="btn btn-sm btn-primary mb-2"><i class="fa fa-search"></i>&nbsp;&nbsp;{{__('page.search')}}</button>
    <button type="button" class="btn btn-sm btn-info ml-1 mb-2" id="btn-reset"><i class="fa fa-eraser"></i>&nbsp;&nbsp;{{__('page.reset')}}</button>
</form>