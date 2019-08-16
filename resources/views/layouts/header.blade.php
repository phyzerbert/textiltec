@php
    $user = Auth::user();
@endphp
<nav class="navbar navbar-expand navbar-light bg-white sticky-top">
    <a class="sidebar-toggle d-flex mr-3">
        <i class="align-self-center" data-feather="menu"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle ml-2 d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle mt-n1" data-feather="settings"></i>
                    </div>
                </a>
                <a class="nav-link nav-link-user dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                    <img src="@if($user->picture){{asset($user->picture)}} @else {{asset('images/avatar.png')}} @endif" class="avatar img-fluid rounded mr-1" alt="{{$user->first_name}} {{$user->last_name}}" /> <span class="text-dark">{{$user->first_name}} {{$user->last_name}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="align-middle" data-feather="user"></i> {{__('page.profile')}}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                    ><i class="align-middle" data-feather="log-out"></i> {{__('page.sign_out')}}</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>