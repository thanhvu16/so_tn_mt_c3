<li class="treeview {{ Route::is('van-ban-lanh-dao-xu-ly.index') || Route::is('phan-loai-van-ban.da_phan_loai') ? 'active menu-open' : '' }}">
    <a href="#">
        <i class="fa fa-th" aria-hidden="true"></i> <span>Hồ sơ công việc</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('van-ban-lanh-dao-xu-ly.index') ? 'active' : '' }}"><a
                href="{{ route('van-ban-lanh-dao-xu-ly.index') }}"><i class="fa fa-circle-o"></i>Văn bản chờ xử lý</a>
        </li>
        <li class="{{ Route::is('phan-loai-van-ban.da_phan_loai') ? 'active' : '' }}"><a
                href="{{ route('phan-loai-van-ban.da_phan_loai') }}"><i class="fa fa-circle-o"></i>Văn bản đã chỉ đạo</a>
        </li>
    </ul>
</li>
