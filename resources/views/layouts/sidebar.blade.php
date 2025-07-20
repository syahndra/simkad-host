<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo/simkad.svg') }}" alt="logo" />
        </a>
    </div>
    @php
    $role = Auth::user()->roleUser;
    @endphp

    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item @if ($menu == 'dashboard') active @endif">
                <a href="{{ route('dashboard') }}">
                    <i class="lni lni-dashboard icon"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            {{-- Untuk superadmin saja --}}
            @if ($role == 'superadmin')
            <li class="nav-item @if ($menu == 'layanan') active @endif">
                <a href="{{ route('layanan.index') }}">
                    <i class="lni lni-laptop-phone icon"></i>
                    <span class="text">Daftar Layanan</span>
                </a>
            </li>

            <li class="nav-item nav-item-has-children">
                <a href="#0" class="@if ($menu != 'wilayah') collapsed @endif" data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_kategoriwilayah" aria-controls="ddmenu_kategoriwilayah"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="lni lni-map icon"></i>
                    <span class="text">Kategori Wilayah</span>
                </a>
                <ul id="ddmenu_kategoriwilayah" class="@if ($menu != 'wilayah') collapse @endif dropdown-nav">
                    <li>
                        <a href="{{ route('kecamatan.index') }}"
                            class="@if ($title == 'Daftar Kecamatan') active @endif">Kecamatan</a>
                    </li>
                    <li>
                        <a href="{{ route('desa.index') }}"
                            class="@if (Str::contains($title, 'Desa')) active @endif">Desa</a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Untuk superadmin dan admin --}}
            @if (in_array($role, ['superadmin', 'admin']))
            <li class="nav-item nav-item-has-children">
                <a href="#0" class="@if ($menu != 'user') collapsed @endif" data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_user" aria-controls="ddmenu_user" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="lni lni-users icon"></i>
                    <span class="text">Manajemen User</span>
                </a>
                <ul id="ddmenu_user" class="@if ($menu != 'user') collapse @endif dropdown-nav">
                    @if ($role == 'superadmin')
                    <li>
                        <a href="{{ route('admin.index') }}"
                            class="@if (Str::contains($title, 'Admin')) active @endif">Admin</a>
                    </li>
                    @endif
                    @if ($role == 'admin')
                    <li>
                        <a href="{{ route('operatorDinas.index') }}"
                            class="@if (Str::contains($title, 'Operator Dinas')) active @endif">Operator Dinas</a>
                    </li>
                    <li>
                        <a href="{{ route('operatorKec.index') }}"
                            class="@if (Str::contains($title, 'Operator Kecamatan')) active @endif">Operator Kecamatan</a>
                    </li>
                    <li>
                        <a href="{{ route('operatorDesa.index') }}"
                            class="@if (Str::contains($title, 'Operator Desa')) active @endif">Operator Desa</a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item nav-item-has-children">
                <a href="#0" class="@if ($menu != 'ajuanDafduk' && $menu != 'ajuanCapil') collapsed @endif" data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_report" aria-controls="ddmenu_report" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="lni lni-postcard icon"></i>
                    <span class="text">Report</span>
                </a>
                <ul id="ddmenu_report" class="@if ($menu != 'ajuanDafduk' && $menu != 'ajuanCapil') collapse @endif dropdown-nav">
                    <li>
                        <a href="{{ route('ajuanDafduk.index') }}"
                            class="@if ($menu == 'ajuanDafduk') active @endif">Pengajuan Dafduk</a>
                    </li>
                    <li>
                        <a href="{{ route('ajuanCapil.index') }}"
                            class="@if ($menu == 'ajuanCapil') active @endif">Pengajuan Capil</a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Untuk operatorDesa, operatorKecamatan, opDinDafduk --}}
            @if (in_array($role, ['operatorDesa', 'operatorKecamatan', 'opDinDafduk']))
            <li class="nav-item @if ($menu == 'ajuanDafduk') active @endif">
                <a href="{{ route('ajuanDafduk.index') }}">
                    <i class="lni lni-postcard icon"></i>
                    <span class="text">Pengajuan Dafduk</span>
                </a>
            </li>
            @endif

            {{-- Untuk operatorDesa dan verifCapil --}}
            @if (in_array($role, ['operatorDesa', 'opDinCapil']))
            <li class="nav-item @if ($menu == 'ajuanCapil') active @endif">
                <a href="{{ route('ajuanCapil.index') }}">
                    <i class="lni lni-certificate icon"></i>
                    <span class="text">Pengajuan Capil</span>
                </a>
            </li>
            @endif

            <span class="divider">
                <hr />
            </span>

            <li class="nav-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="lni lni-exit icon"></i>
                    <span class="text">Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

</aside>