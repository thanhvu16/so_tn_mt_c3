<li class="treeview {{ Route::is('phan-loai-van-ban.index') || Route::is('phan-loai-van-ban.da_phan_loai') ? 'active menu-open' : '' }}">
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
        <li class="{{ Route::is('phan-loai-van-ban.da_phan_loai') ? 'active' : '' }}"><a
                href="{{route('phan-loai-van-ban.da_phan_loai')}}"><i class="fa fa-circle-o"></i>Văn bản đã phân loại</a>
        </li>
    </ul>
</li>
