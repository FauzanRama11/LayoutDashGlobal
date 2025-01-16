<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'student-outbound') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Student Outbound</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'student-outbound') }};">
        
        {{-- fa --}}
        <li><a href="{{ route('stuout_program_fak') }}" class="{{ routeActive('stuout_program_fak') }}">Program Fakultas</a></li>
        <li><a href="{{ route('stuout_program_age') }}" class="{{ routeActive('stuout_program_age') }}">Program AGE</a></li>
        <li><a href="{{ route('stuout_view_peserta') }}" class="{{ routeActive('stuout_view_peserta') }}">View Peserta</a></li>

        @role("gmp")
            <li><a href="{{ route('stuout_approval_dana') }}" class="{{ routeActive('stuout_approval_dana') }}">Approval Dana Bantuan</a></li>
            <li><a href="{{ route('stuout_approval_pelaporan') }}" class="{{ routeActive('stuout_approval_pelaporan') }}">Approval Pelaporan</a></li>
            <li><a href="{{ route('stuout_pengajuan_setneg') }}" class="{{ routeActive('stuout_pengajuan_setneg') }}">Pengajuan SETNEG</a></li>
            <li><a href="{{ route('stuout_target') }}" class="{{ routeActive('stuout_target') }}">Target</a></li>
        @endrole
    </ul>
</li>