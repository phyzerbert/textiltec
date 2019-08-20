@php
    $page = config('site.page');
@endphp
<nav class="sidebar sidebar-sticky">
    <div class="sidebar-content  js-simplebar">
        <a class="sidebar-brand border-bottom" href="{{route('home')}}">
            <i class="align-middle" data-feather="layers"></i>
            <span class="align-middle">{{config('app.name')}}</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-item @if($page == 'home') active @endif"><a href="{{route('home')}}" class="font-weight-bold sidebar-link"><i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span></a></li>
            @php
                $supply_items = ['supply_list', 'add_supply'];
            @endphp
            <li class="sidebar-item @if(in_array($page, $supply_items)) active @endif">
                <a href="#supplies" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="server"></i> <span class="align-middle">Supplies</span>
                </a>
                <ul id="supplies" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item @if($page == 'supply_list') active @endif"><a class="sidebar-link" href="{{route('supply.index')}}">Supply List</a></li>
                    <li class="sidebar-item @if($page == 'add_supply') active @endif"><a class="sidebar-link" href="{{route('supply.create')}}">Add Supply</a></li>
                </ul>
            </li>
            @php
                $purchase_items = ['purchase_list', 'add_purchase'];
            @endphp
            <li class="sidebar-item @if(in_array($page, $purchase_items)) active @endif">
                <a href="#purchases" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">{{__('page.purchase')}}</span>
                </a>
                <ul id="purchases" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item @if($page == 'purchase_list') active @endif"><a class="sidebar-link" href="{{route('purchase.index')}}">Purchase List</a></li>
                    <li class="sidebar-item @if($page == 'add_purchase') active @endif"><a class="sidebar-link" href="{{route('purchase.create')}}">Add Purchase</a></li>
                </ul>
            </li>
            @php
                $produce_order_items = ['produce_order_list', 'add_produce_order'];
            @endphp
            <li class="sidebar-item @if(in_array($page, $produce_order_items)) active @endif">
                <a href="#produce_orders" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">{{__('page.production_order')}}</span>
                </a>
                <ul id="produce_orders" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item @if($page == 'produce_order_list') active @endif"><a class="sidebar-link" href="{{route('produce_order.index')}}">Order List</a></li>
                    <li class="sidebar-item @if($page == 'add_produce_order') active @endif"><a class="sidebar-link" href="{{route('produce_order.create')}}">Add Order</a></li>
                </ul>
            </li>
            <li class="sidebar-item @if($page == 'product') active @endif"><a href="{{route('product.index')}}" class="font-weight-bold sidebar-link"><i class="align-middle" data-feather="box"></i> <span class="align-middle">{{__('page.product_management')}}</span></a></li>            
            @php
                $produce_order_items = ['product_sale_list', 'add_product_sale'];
            @endphp
            <li class="sidebar-item @if(in_array($page, $produce_order_items)) active @endif">
                <a href="#product_sale" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">{{__('page.product_sale')}}</span>
                </a>
                <ul id="product_sale" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item @if($page == 'product_sale_list') active @endif"><a class="sidebar-link" href="{{route('product_sale.index')}}">{{__('page.sales_list')}}</a></li>
                    <li class="sidebar-item @if($page == 'add_product_sale') active @endif"><a class="sidebar-link" href="{{route('product_sale.create')}}">{{__('page.add_sale')}}</a></li>
                </ul>
            </li>
            <li class="sidebar-item @if($page == 'user') active @endif"><a href="{{route('users.index')}}" class="font-weight-bold sidebar-link"><i class="align-middle" data-feather="users"></i> <span class="align-middle">{{__('page.user_management')}}</span></a></li>
            <li class="sidebar-item @if($page == 'supplier') active @endif"><a href="{{route('supplier.index')}}" class="font-weight-bold sidebar-link"><i class="align-middle" data-feather="truck"></i> <span class="align-middle">{{__('page.supplier_management')}}</span></a></li>
            <li class="sidebar-item @if($page == 'customer') active @endif"><a href="{{route('customer.index')}}" class="font-weight-bold sidebar-link"><i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">{{__('page.customer_management')}}</span></a></li>
            @php
                $settings_items = ['scategory', 'pcategory', 'workshop'];
            @endphp
            <li class="sidebar-item @if(in_array($page, $settings_items)) active @endif">
                <a href="#layouts" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="settings"></i> <span class="align-middle">{{__('page.settings')}}</span>
                </a>
                <ul id="layouts" class="sidebar-dropdown list-unstyled collapse ">
                    <li class="sidebar-item @if($page == 'pcategory') active @endif"><a class="sidebar-link" href="{{route('pcategory.index')}}">{{__('page.product_category')}}</a></li>
                    <li class="sidebar-item @if($page == 'scategory') active @endif"><a class="sidebar-link" href="{{route('scategory.index')}}">{{__('page.supply_category')}}</a></li>
                    <li class="sidebar-item @if($page == 'workshop') active @endif"><a class="sidebar-link" href="{{route('workshop.index')}}">{{__('page.workshop')}}</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>