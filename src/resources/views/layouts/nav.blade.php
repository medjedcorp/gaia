<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Areas</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        {{-- <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li> <a href="{{ url('index') }}"><i class="bx bx-right-arrow-alt"></i>Default</a>
                </li>
                <li> <a href="{{ url('dashboard-alternate') }}"><i class="bx bx-right-arrow-alt"></i>Alternate</a>
                </li>
            </ul>
        </li> --}}
        {{-- <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Application</div>
            </a>
            <ul>
                <li> <a href="{{ url('app-emailbox') }}"><i class="bx bx-right-arrow-alt"></i>Email</a>
                </li>
                <li> <a href="{{ url('app-chat-box') }}"><i class="bx bx-right-arrow-alt"></i>Chat Box</a>
                </li>
                <li> <a href="{{ url('app-file-manager') }}"><i class="bx bx-right-arrow-alt"></i>File Manager</a>
                </li>
                <li> <a href="{{ url('app-contact-list') }}"><i class="bx bx-right-arrow-alt"></i>Contatcs</a>
                </li>
                <li> <a href="{{ url('app-to-do') }}"><i class="bx bx-right-arrow-alt"></i>Todo List</a>
                </li>
                <li> <a href="{{ url('app-invoice') }}"><i class="bx bx-right-arrow-alt"></i>Invoice</a>
                </li>
                <li> <a href="{{ url('app-fullcalender') }}"><i class="bx bx-right-arrow-alt"></i>Calendar</a>
                </li>
            </ul>
        </li> --}}
        <li class="menu-label">Main Menu</li>
        <li>
            <a href="{{ url('dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Home</div>
            </a>
        </li>
        <li>
            <a href="{{ url('lands/index') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-list-ul"></i>
                </div>
                <div class="menu-title">売土地一覧</div>
            </a>
        </li>
        <li class="menu-label">Search Menu</li>
        <li>
            <a href="{{ url('lands/map') }}">
                <div class="parent-icon"><i class="lni lni-map"></i>
                </div>
                <div class="menu-title">地図から探す</div>
            </a>
        </li>
        <li>
            <a href="{{ url('address') }}">
                {{-- <div class="parent-icon">
                    <img src="{{ asset('images/japan.svg') }}" alt="logo icon" style="max-width:24.5px;">
                </div> --}}
                <div class="parent-icon"><i class="fadeIn animated bx bx-world"></i>
                </div>
                <div class="menu-title">住所から探す</div>
            </a>
        </li>
        <li>
            <a href="{{ url('widgets') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-train"></i>
                </div>
                <div class="menu-title">沿線から探す</div>
            </a>
        </li>
        @can('isAdmin')
        <li class="menu-label">ADMIN MENU</li>
        <li>
            @inject ( 'BadgesService', 'App\Services\BadgesService' )
            <a href="{{ url('users') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-user-circle"></i>
                </div>
                <div class="menu-title d-flex align-items-center">ユーザ一覧&nbsp;<span class="badge bg-gradient-ibiza rounded-pill">{{ $BadgesService->approval() }}</span></div>
            </a>
        </li>
        <li>
            <a href="{{ url('users') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-link"></i>
                </div>
                <div class="menu-title">沿線管理</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.lands.index') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-edit"></i>
                </div>
                <div class="menu-title">売土地管理</div>
            </a>
        </li>
        @endcan
        @can('isSystem')
        <li class="menu-label">SYSTEM MENU</li>
        <li>
            <a href="{{ url('csv/train') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-upload"></i>
                </div>
                <div class="menu-title">鉄道会社マスタ</div>
            </a>
        </li>
        <li>
            <a href="{{ url('csv/line') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-upload"></i>
                </div>
                <div class="menu-title">沿線マスタ</div>
            </a>
        </li>
        <li>
            <a href="{{ url('csv/station') }}">
                <div class="parent-icon"><i class="fadeIn animated bx bx-upload"></i>
                </div>
                <div class="menu-title">駅マスタ</div>
            </a>
        </li>
        @endcan
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->