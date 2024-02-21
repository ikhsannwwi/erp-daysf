<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="/">
                        <img src="{{ array_key_exists('logo_app_admin', $settings) ? img_src($settings['logo_app_admin'], 'settings') : '' }}"
                            width="125px" height="46px" alt="">
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ Route::is('admin.dashboard*') ? 'active' : '' }} ">
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.kategori*', 'admin.member*', 'admin.supplier*', 'admin.gudang*', 'admin.satuan*', 'admin.toko*') && !Route::is('admin.satuan_konversi*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.kategori*', 'admin.member*', 'admin.supplier*', 'admin.gudang*', 'admin.satuan*', 'admin.toko*') && !Route::is('admin.satuan_konversi*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.kategori*') ? 'active' : '' }}">
                            <a href="{{route('admin.kategori')}}">Kategori</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.satuan*') && !Route::is('admin.satuan_konversi*') ? 'active' : '' }}">
                            <a href="{{ route('admin.satuan') }}">Satuan</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.gudang*') ? 'active' : '' }}">
                            <a href="{{ route('admin.gudang') }}">Gudang</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.member*') ? 'active' : '' }}">
                            <a href="{{ route('admin.member') }}">Member</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.supplier*') ? 'active' : '' }}">
                            <a href="{{ route('admin.supplier') }}">Supplier</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.toko*') ? 'active' : '' }}">
                            <a href="{{ route('admin.toko') }}">Toko</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  {{ Route::is('admin.produk*')  && !Route::is('admin.produksi*') ? 'active' : '' }}">
                    <a href="{{ route('admin.produk') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Produk</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.satuan_konversi*', 'admin.transaksi_stok*','admin.penyesuaian_stok*', 'admin.stok_opname_gudang*') && !Route::is('admin.transaksi_stok_toko*', 'admin.penyesuaian_stok_toko*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Track Gudang</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.satuan_konversi*', 'admin.transaksi_stok*','admin.penyesuaian_stok*', 'admin.stok_opname_gudang*') && !Route::is('admin.transaksi_stok_toko*', 'admin.penyesuaian_stok_toko*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.satuan_konversi*') ? 'active' : '' }}">
                            <a href="{{route('admin.satuan_konversi')}}">Satuan Konversi</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.transaksi_stok*') && !Route::is('admin.transaksi_stok_toko*') ? 'active' : '' }}">
                            <a href="{{route('admin.transaksi_stok')}}">Transaksi Stok</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.penyesuaian_stok*') ? 'active' : '' }}">
                            <a href="{{route('admin.penyesuaian_stok')}}">Penyesuaian Stok</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.stok_opname_gudang*') ? 'active' : '' }}">
                            <a href="{{route('admin.stok_opname_gudang')}}">Stok Opname Gudang</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.transaksi_stok_toko*', 'admin.penyesuaian_stok_toko*', 'admin.stok_opname_toko*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Track Toko</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.transaksi_stok_toko*', 'admin.penyesuaian_stok_toko*', 'admin.stok_opname_toko*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.transaksi_stok_toko*') ? 'active' : '' }}">
                            <a href="{{route('admin.transaksi_stok_toko')}}">Transaksi Stok</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.penyesuaian_stok_toko*') ? 'active' : '' }}">
                            <a href="{{route('admin.penyesuaian_stok_toko')}}">Penyesuaian Stok</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.stok_opname_toko*') ? 'active' : '' }}">
                            <a href="{{route('admin.stok_opname_toko')}}">Stok Opname Toko</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.transaksi_penjualan*', 'admin.pembelian*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Data Transaksi</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.transaksi_penjualan*', 'admin.pembelian*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.transaksi_penjualan*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.transaksi_penjualan') }}">Transaksi Penjualan</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.pembelian*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.pembelian') }}">Pembelian</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.users*') ? 'active' : '' }}">
                            <a href="component-alert.html">Penjualan</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.users*') ? 'active' : '' }}">
                            <a href="component-alert.html">Surat Jalan</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.users*') ? 'active' : '' }}">
                            <a href="component-alert.html">Invoice Penjualan</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  {{ Route::is('admin.formula*') ? 'active' : '' }}">
                    <a href="{{ route('admin.formula') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Formula</span>
                    </a>
                </li>

                <li class="sidebar-item  {{ Route::is('admin.produksi*') ? 'active' : '' }}">
                    <a href="{{ route('admin.produksi') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Produksi</span>
                    </a>
                </li>

                <li class="sidebar-title">Other</li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.karyawan*', 'admin.departemen*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>SDM</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.karyawan*', 'admin.departemen*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.departemen*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.departemen') }}">Departemen</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.karyawan*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.karyawan') }}">Karyawan</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.users*', 'admin.user_groups*', 'admin.operator_kasir*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.users*', 'admin.user_groups*', 'admin.operator_kasir*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.user_groups*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.user_groups') }}">User Groups</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.users*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.users') }}">Users</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.operator_kasir*') ? 'active' : '' }} ">
                            <a href="{{ route('admin.operator_kasir') }}">Operator Kasir</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.logSystems*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Systems</span>
                    </a>
                    <ul class="submenu" style="display: {{ Route::is('admin.logSystems*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.logSystems*') ? 'active' : '' }}">
                            <a href="{{ route('admin.logSystems') }}">Logs</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item  {{ Route::is('admin.profile*') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile', auth()->user() ? auth()->user()->kode : '') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ Route::is('admin.settings*','admin.module*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="submenu " style="display: {{ Route::is('admin.settings*','admin.module*') ? 'block' : 'none' }};">
                        <li class="submenu-item {{ Route::is('admin.settings*') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}">Setting General</a>
                        </li>
                        <li class="submenu-item {{ Route::is('admin.module*') ? 'active' : '' }}">
                            <a href="{{ route('admin.module') }}">Modul Management</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
