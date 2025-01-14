<li class="dropdown">
    <a class="nav-link menu-title {{ prefixActive(1, 'agreement') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Agreements</span></a>
    <ul class="nav-submenu menu-content" style="display: {{ prefixBlock(1, 'agreement') }};">
        
    @role("gpc")
        <li><a href="{{ route('review_agreement') }}" class="{{ routeActive('review_agreement') }}">Review</a></li>
        <li><a href="{{ route('completed_agreement') }}" class="{{ routeActive('completed_agreement') }}">Completed</a></li>
        <li><a href="{{ route('view_database') }}" class="{{ routeActive('view_database') }}">Databases</a></li>
    @endrole

    @hasanyrole('gpc|wadek3')
        <li><a href="{{ route('view_pelaporan') }}" class="{{ routeActive('view_pelaporan') }}">Pelaporan</a></li>
    @endhasanyrole

    @role("gpc")
        <li><a href="{{ route('email_list') }}" class="{{ routeActive('email_list') }}">Email Notif List</a></li>
    @endrole
    </ul>
</li>