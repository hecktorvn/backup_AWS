<nav class="navbar navbar-expand fixed-top be-top-header">
    <div class="container-fluid">
        <div class="be-navbar-header"><a href="index.html" class="navbar-brand"></a>
        </div>
        <div class="be-right-navbar">
            <ul class="nav navbar-nav float-right be-user-nav">
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><img src="assets/img/avatar.png" alt="Avatar"><span class="user-name">Túpac Amaru</span></a>
                    <div role="menu" class="dropdown-menu">
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->NOME }}</div>
                            <div class="user-position online">@lang('layout.header.user.avaliable')</div>
                        </div>

                        <a href="pages-profile.html" class="dropdown-item"><span class="icon mdi mdi-face"></span>@lang('layout.header.user.account')</a>
                        <a href="#" class="dropdown-item"><span class="icon mdi mdi-settings"></span>@lang('layout.header.user.settings')</a>
                        <a href="/logout" class="dropdown-item"><span class="icon mdi mdi-power"></span>@lang('layout.header.user.logout')</a>
                    </div>
                </li>
            </ul>
            <div class="page-title"><span>Blank Page</span></div>
            <ul class="nav navbar-nav float-right be-icons-nav">
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span class="icon mdi mdi-notifications"></span><span class="indicator"></span></a>
                    <ul class="dropdown-menu be-notifications">
                        <li>
                            <div class="title">@lang('layout.header.notifications.title')<span class="badge badge-pill">3</span></div>
                            <div class="list">
                                <div class="be-scroller">
                                    <div class="content">
                                        <ul>
                                            <li class="notification notification-unread">
                                                <a href="#">
                                                    <div class="image"><img src="assets/img/avatar2.png" alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">Jessica Caruso</span> accepted your invitation to join the team.</div><span class="date">2 min ago</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="notification">
                                                <a href="#">
                                                    <div class="image"><img src="assets/img/avatar3.png" alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">Joel King</span> is now following you</div><span class="date">2 days ago</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="notification">
                                                <a href="#">
                                                    <div class="image"><img src="assets/img/avatar4.png" alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">John Doe</span> is watching your main repository</div><span class="date">2 days ago</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="notification">
                                                <a href="#">
                                                    <div class="image"><img src="assets/img/avatar5.png" alt="Avatar"></div>
                                                    <div class="notification-info"><span class="text"><span class="user-name">Emily Carter</span> is now following you</span><span class="date">5 days ago</span></div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="footer"> <a href="#">@lang('layout.header.notifications.all')</a></div>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" role="button" aria-expanded="false" class="nav-link be-toggle-right-sidebar">
                        <span class="icon mdi mdi-apps"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
