<li class="dropdown">
    <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="box"></i><span>Student Outbound</span></a>
    <ul class="nav-submenu menu-content">
        <li><a href="" class="">Program Fakultas</a></li>
        <li><a href="" class="">Applicant</a></li>

        {{-- Dropdown Internal --}}
        <li>
            <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                Internal<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
            </a>
            <ul class="nav-sub-childmenu submenu-content">
                <li><a href="" class="">Research/Academic Lectures</a></li>
                <li><a href="" class="">Adjucant Professor</a></li>
                <li><a href="" class="">Adjucant Professor for Research Center</a></li>
                <li><a href="" class="">Visiting Fellow for Research Center</a></li>
                <li><a href="" class="">PGWT</a></li>
                <li><a href="" class="">Visit Unair</a></li>
            </ul>
        </li>

        {{-- Dropdown Eksternal --}}
        <li>
            <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                Eksternal<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
            </a>
            <ul class="nav-sub-childmenu submenu-content">
                <li><a href="}" class="">Academic Lectures</a></li>
                <li><a href="" class="">Airlangga Post-Doctoral</a></li>
                <li><a href="" class="">Visiting Fellow</a></li>
            </ul>
        </li>

        <li><a href="" class="">View Applicant</a></li>
    </ul>
</li>