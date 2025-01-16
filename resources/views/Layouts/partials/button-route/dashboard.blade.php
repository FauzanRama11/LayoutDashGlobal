<li class="dropdown">
    <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="box"></i><span>Dashboard</span></a>
    <ul class="nav-submenu menu-content" style="display:">
        
        {{-- fa --}}
        <li><a href="" class="">Agreements</a></li>
        <li><a href="" class="">Acadmemic Peers</a></li>

        {{-- Dropdown Lingua --}}
        <li>
            <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                Mobility<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
            </a>
            <ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'block' : 'none' }};">
                <li><a href="}" class="">Student Inbound</a></li>
                <li><a href="" class="">Student Inbound</a></li>
                <li><a href="" class="">Staff Inbound</a></li>
            </ul>
        </li>

        <li><a href="" class="">Meetings</a></li>

    </ul>
</li>