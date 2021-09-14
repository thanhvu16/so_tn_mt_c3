@if(auth::user()->id == 10551)
<li class="treeview {{ Route::is('phan-loai-van-ban.index') || Route::is('phan-loai-van-ban.da_phan_loai')
 || Route::is('phan-loai-van-ban-phoi-hop.index') || Route::is('van-ban-phoi-hop.da_phan_loai') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-laptop" aria-hidden="true"></i> <span>Phân loại văn bản</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('phan-loai-van-ban.index') ? 'active' : '' }}"><a
                href="{{route('phan-loai-van-ban.index')}}"><i class="fa fa-circle-o"></i>Văn bản chờ phân loại</a>
        </li>
{{--        <li class="{{ Route::is('phan-loai-van-ban.da_phan_loai') ? 'active' : '' }}"><a--}}
{{--                href="{{route('phan-loai-van-ban.da_phan_loai')}}"><i class="fa fa-circle-o"></i>Văn bản đã phân loại</a>--}}
{{--        </li>--}}
        @if (isset(auth::user()->donVi) && auth::user()->donVi->parent_id != 0)
            <hr class="hr-line">
            <li class="{{ Route::is('phan-loai-van-ban-phoi-hop.index') ? 'active' : '' }}"><a
                    href="{{route('phan-loai-van-ban-phoi-hop.index')}}"><i class="fa fa-circle-o"></i>VB phối hợp chờ phân loại</a>
            </li>
            <li class="{{ Route::is('van-ban-phoi-hop.da_phan_loai') ? 'active' : '' }}"><a
                    href="{{route('van-ban-phoi-hop.da_phan_loai', 'chuyen_tiep=1')}}"><i class="fa fa-circle-o"></i>VB phối hợp đã phân loại</a>
            </li>
        @endif
    </ul>
</li>
@endif
<li class="treeview {{ Route::is('phan_loai_giay_moi') || Route::is('phan-loai-van-ban.da_phan_loai')
 || Route::is('phan-loai-giay-moi.da_phan_loai')  ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-laptop" aria-hidden="true"></i> <span>Phân loại giấy mời</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('phan_loai_giay_moi') ? 'active' : '' }}"><a
                href="{{route('phan_loai_giay_moi','type=1')}}"><i class="fa fa-circle-o"></i>Giấy mời chờ phân loại</a>
        </li>
{{--        <li class="{{ Route::is('phan-loai-giay-moi.da_phan_loai') ? 'active' : '' }}"><a--}}
{{--                href="{{route('phan-loai-giay-moi.da_phan_loai','type=1')}}"><i class="fa fa-circle-o"></i>Giấy mời đã phân loại</a>--}}
{{--        </li>--}}

    </ul>
</li>
