<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">Blank Page</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        <li class="divider">Menu</li>

                        @php
                            $menu = HomeController::getMenu(url('/'));
                        @endphp

                        @foreach($menu as $item)
                            @php
                                $url_seq = @$menu['LIST'][Request::url()]['SEQUENCIA'];
                            @endphp

                            @if(!isset($item['namelist']))
                            <li class="{{ isset($item['FILHO']) ? 'parent' : '' }} {{ $url_seq == $item['data']['SEQUENCIA'] ? 'active' : '' }}">
                                <a href="{{ $item['data']['URL'] }}">
                                    <i class="icon mdi mdi-{{ $item['data']['ICON'] }}"></i>
                                    <span>{{ $item['data']['NOME'] }}</span>
                                </a>

                                @if(isset($item['FILHO']))
                                    <ul class="sub-menu">
                                    @foreach($item['FILHO'] as $item)
                                        <li class="{{ $url_seq == $item['SEQUENCIA'] ? 'active' : '' }}">
                                            <a href="{{ $item['URL'] }}">{{ $item['NOME'] }}</a>
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="progress-widget">
            <div class="progress-data"><span class="progress-value">60%</span><span class="name">Current Project</span></div>
            <div class="progress">
                <div style="width: 60%;" class="progress-bar progress-bar-primary"></div>
            </div>
        </div>
    </div>
</div>
