<header class="main-nav">
    <div class="sidebar-user text-start">
        <a href="user-profile"> <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->nama }}</h6></a>
        <p class="mb-0 font-roboto">Email</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>General</h6>
                        </div>

                        @if(Auth::check())
                            @if(Auth::user()->username == 'gmp')

                                <li><a href="" class="">Home</a></li>

                                @includeIf('layouts.partials.button-route.staffinbound')

                                @includeIf('layouts.partials.button-route.studentinbound')

                                @includeIf('layouts.partials.button-route.studentoutbound')

                                <li><a href="" class="">Tagged Meetings</a></li>

                            @elseif(substr(Auth::user()->username, 0, 2) === 'fa')

                                <li><a href="" class="">Home</a></li>

                                @includeIf('layouts.partials.button-route.mitraakademik')

                                @includeIf('layouts.partials.button-route.staffinbound')

                                @includeIf('layouts.partials.button-route.studentoutbound')

                                @includeIf('layouts.partials.button-route.studentinbound')

                                @includeIf('layouts.partials.button-route.other')
                                
                                @includeIf('layouts.partials.button-route.dashboard')

                            @endif

                        @endif
                        
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
