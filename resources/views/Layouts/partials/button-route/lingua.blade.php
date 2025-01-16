<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'lingua') }}" href="javascript:void(0)"><i data-feather="box"></i><span>lingua</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'lingua') }};">
        <li><a href="{{ route('li_periode') }}" class="{{ routeActive('li_periode') }}">Periode</a></li>
        <li><a href="{{ route('li_template_rps') }}" class="{{ routeActive('li_template_rps') }}">Template RPS</a></li>
        <li><a href="{{ route('li_materi_promosi') }}" class="{{ routeActive('li_materi_promosi') }}">Materi Promosi</a></li>
        <li><a href="{{ route('li_pendaftar') }}" class="{{ routeActive('li_pendaftar') }}">Pendaftar</a></li>
    </ul>
</li>