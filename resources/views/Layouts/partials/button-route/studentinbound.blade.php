<li class="dropdown">
    <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="box"></i><span>Student Inbound</span></a>
    <ul class="nav-submenu menu-content" style="display:">

       
        {{-- Dropdown Amerta --}}
        <li>
            <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                AMERTA<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
            </a>
            <ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'block' : 'none' }};">
                <li><a href="}" class="">Periode</a></li>
                <li><a href="" class="">Template RPS</a></li>
                <li><a href="" class="">Materi Promosi</a></li>
                <li><a href="" class="">PeNdaftar</a></li>
                <li><a href="" class="">Nominasi Mata Kuliah</a></li>
                <li><a href="" class="">Synced</a></li>
            </ul>
        </li>
        {{-- Dropdown Lingua --}}
        <li>
            <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                LINGUA<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
            </a>
            <ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'block' : 'none' }};">
                <li><a href="}" class="">Periode</a></li>
                <li><a href="" class="">Template RPS</a></li>
                <li><a href="" class="">Materi Promosi</a></li>
                <li><a href="" class="">PeNdaftar</a></li>
            </ul>
        </li>

        
            <li><a href="" class="">Program Fakultas</a></li>
            <li><a href="" class="">Program AGE</a></li>
            <li><a href="" class="">View Peserta</a></li>
        
     
            <li><a href="" class="">Approval Dana Bantuan</a></li>
            <li><a href="" class="">Approval Pelaporan</a></li>
            <li><a href="" class="">Target</a></li>
   

    </ul>
</li>