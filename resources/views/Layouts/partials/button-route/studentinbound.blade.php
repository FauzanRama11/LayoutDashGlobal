<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'student-inbound') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Student Inbound</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'student-inbound') }};">

            @role("gmp")
            {{-- Dropdown Amerta --}}
            <li>
                <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['am_materi_promosi','am_template_rps','am_pendaftar','am_periode','am_synced_data','am_nominasi_matkul']) ? 'active' : '' }}" href="javascript:void(0)">
                    AMERTA<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                </a>
                <ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['am_materi_promosi','am_template_rps','am_pendaftar','am_periode','am_synced_data','am_nominasi_matkul']) ? 'block' : 'none'}};">
                    <li><a href="{{ route('am_periode') }}" class="{{ routeActive('am_periode') }}">Periode</a></li>
                    <li><a href="{{ route('am_template_rps') }}" class="{{ routeActive('am_template_rps') }}">Template RPS</a></li>
                    <li><a href="{{ route('am_materi_promosi') }}" class="{{ routeActive('am_materi_promosi') }}">Materi Promosi</a></li>
                    <li><a href="{{ route('am_pendaftar') }}" class="{{ routeActive('am_pendaftar') }}">Pendaftar</a></li>
                    <li><a href="{{ route('am_nominasi_matkul') }}" class="{{ routeActive('am_nominasi_matkul') }}">Nominasi Mata Kuliah</a></li>
                    <li><a href="{{ route('am_synced_data') }}" class="{{ routeActive('am_synced_data') }}">Synced</a></li>
                </ul>
            </li>
            {{-- Dropdown Lingua --}}
            <li>
                <a class="submenu-title  {{ in_array(Route::currentRouteName(), ['li_materi_promosi','li_pendaftar','li_periode','li_template_rps']) ? 'active' : '' }}" href="javascript:void(0)">
                    LINGUA<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                </a>
                <ul class="nav-sub-childmenu submenu-content" style="display: {{ in_array(Route::currentRouteName(), ['li_materi_promosi','li_pendaftar','li_periode','li_template_rps']) ? 'block' : 'none'}};">
                    <li><a href="{{ route('li_periode') }}" class="{{ routeActive('li_periode') }}">Periode</a></li>
                    <li><a href="{{ route('li_template_rps') }}" class="{{ routeActive('li_template_rps') }}">Template RPS</a></li>
                    <li><a href="{{ route('li_materi_promosi') }}" class="{{ routeActive('li_materi_promosi') }}">Materi Promosi</a></li>
                    <li><a href="{{ route('li_pendaftar') }}" class="{{ routeActive('li_pendaftar') }}">Pendaftar</a></li>
                </ul>
            </li>
        @endrole

        {{-- fa --}}
        <li><a href="{{ route('stuin_program_fak') }}" class="{{ routeActive('stuin_program_fak') }}">Program Fakultas</a></li>
        <li><a href="{{ route('stuin_program_age') }}" class="{{ routeActive('stuin_program_age') }}">Program AGE</a></li>
        <li><a href="{{ route('stuin_view_peserta') }}" class="{{ routeActive('stuin_view_peserta') }}">View Peserta</a></li>

        @role("gmp")
            <li><a href="{{ route('stuin_approval_dana') }}" class="{{ routeActive('stuin_approval_dana') }}">Approval Dana Bantuan</a></li>
            <li><a href="{{ route('stuin_approval_pelaporan') }}" class="{{ routeActive('stuin_approval_pelaporan') }}">Approval Pelaporan</a></li>
            <li><a href="{{ route('stuin_target') }}" class="{{ routeActive('stuin_target') }}">Target</a></li>
        @endrole

    </ul>
</li>