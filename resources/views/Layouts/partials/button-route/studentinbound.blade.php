<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'student-inbound') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Student Inbound</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'student-inbound') }};">

            @role("gmp")
            {{-- Dropdown Amerta --}}
            <li>
                <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['tab-bootstrap','tab-material']) ? 'active' : '' }}" href="javascript:void(0)">
                    AMERTA<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                </a>
                <ul class="nav-sub-childmenu submenu-content">
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
                <ul class="nav-sub-childmenu submenu-content">
                    <li><a href="}" class="">Periode</a></li>
                    <li><a href="" class="">Template RPS</a></li>
                    <li><a href="" class="">Materi Promosi</a></li>
                    <li><a href="" class="">PeNdaftar</a></li>
                </ul>
            </li>
        @endrole

        {{-- fa --}}
        <li><a href="{{ route('program_fak') }}" class="{{ routeActive('program_fak') }}">Program Fakultas</a></li>
        <li><a href="{{ route('program_age') }}" class="{{ routeActive('program_age') }}">Program AGE</a></li>
        <li><a href="" class="">View Peserta</a></li>

        @role("gmp")
            <li><a href="" class="">Approval Dana Bantuan</a></li>
            <li><a href="" class="">Approval Pelaporan</a></li>
            <li><a href="" class="">Target</a></li>
        @endrole

    </ul>
</li>