<div class="sidebar">
    <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if(Auth::user()->user_type == "admin")
                <li class="nav-item mt-2">
                    <a href="{{route("admin.users")}}" class="nav-link nav-main-tab tabLink {{$tab != "users" ?: "active font-weight-bold"}}">
                        <i class="nav-icon fas fa-users fa-lg"></i>
                        <p>User Account</p>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a href="{{route("admin.maintenance")}}" class="nav-link nav-main-tab tabLink {{$tab != "maintenance" ?: "active font-weight-bold"}}">
                        <i class="nav-icon fas fa-cogs fa-lg"></i>
                        <p>Maintenance</p>
                    </a>
                </li>
            @endif

            <li class="nav-item mt-2">
                <a href="{{route("admin.members")}}" class="nav-link nav-main-tab tabLink {{$tab != "members" ?: "active font-weight-bold"}}">
                    <i class="nav-icon fas fa-users fa-lg"></i>
                    <p>Member Information</p>
                </a>
            </li>
        </ul>
    </nav>
</div>