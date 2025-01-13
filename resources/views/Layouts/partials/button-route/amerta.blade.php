<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(2, 'amerta') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Amerta</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(2, 'amerta') }};">

        @role("kps")
        <li><a href="{{ route('am_nominasi_matkul') }}" class="{{ routeActive('am_nominasi_matkul') }}">Nominasi Mata Kuliah</a></li>
        <li><a href="{{ route('am_materi_promosi') }}" class="{{ routeActive('am_materi_promosi') }}">Materi Promosi</a></li>
        @endrole

        @role("dirpen")
        <li><a href="{{ route('am_nominasi_matkul') }}" class="{{ routeActive('am_nominasi_matkul') }}">Nominasi Mata Kuliah</a></li>
        <li><a href="{{ route('am_template_rps') }}" class="{{ routeActive('am_template_rps') }}">Template RPS</a></li>
        @endrole
    </ul> 
</li>