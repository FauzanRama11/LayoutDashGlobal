<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'mahasiswa-inbound') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Program</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'mahasiswa-inbound') }};">
    <li><a href="{{ route('program.inbound', ['params'=>'active']) }}" 
       class="{{ request()->route('params') == 'active' ? 'active' : '' }}">Active Program</a></li>  

<li><a href="{{ route('program.inbound', ['params'=>'finished']) }}" 
       class="{{ request()->route('params') == 'finished' ? 'active' : '' }}">Finished Program</a></li>  

    </ul>
</li>