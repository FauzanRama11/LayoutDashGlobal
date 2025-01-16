<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'mitra-akademik') }}" href="javascript:void(0)">
        <i data-feather="home"></i><span>Mitra Akademik</span>
    </a>                  
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'mitra-akademik') }};">
        <li><a href="{{ route('daftarmitra') }}" class="{{ routeActive('daftarmitra') }}">Daftar Mitra Akademik</a></li>
        <li><a href="{{ route('submitmitra') }}" class="{{ routeActive('submitmitra') }}">Submit Mitra Akademik</a></li>
        <li><a href="" class="">Engagement Plan</a></li>
        <li><a href="" class="">Vote</a></li>
    </ul>
</li>