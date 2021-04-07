<form class="form-row" action="{{ isset($user) ? route('nguoi-dung.update', $user->id) : route('nguoi-dung.store') }}"
      method="post"
      enctype="multipart/form-data">
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group col-md-3">
            <div id="avartar-img">
                <img id="avartar"
                     src="{{ isset($user) && !empty($user->anh_dai_dien) ? getUrlFile($user->anh_dai_dien) : asset('images/default-user.png') }}"
                     class="img-responsive" height="248px"
                     alt="anh-dai-dien" style="margin: auto">
                <div class="col-md-12 text-center">
                    <input type="file" name="anh_dai_dien" class="hidden" onchange="readURL(this,'#avartar');">
                    <button type="button" class="btn btn-primary mt-2 waves-effect btn-sm"
                            onclick="document.getElementsByName('anh_dai_dien')[0].click();">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Ảnh
                    </button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-9">
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="ho-ten">Họ tên @include('admin::required')</label>
                    <input type="text" name="ho_ten" id="ho-ten" class="form-control" placeholder="Nhập họ tên..."
                           value="{{ old('ho_ten', isset($user) ? $user->ho_ten : '') }}" required="">
                </div>
                <div class="form-group col-md-4">
                    <label for="username" class="col-form-label">Tài khoản @include('admin::required')</label>
                    <input type="text" id="username" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="Nhập tên tài khoản"
                           value="{{ old('username', isset($user) ? $user->username : '') }}" required="">
                    @error('username')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="password">Mật khẩu @include('admin::required')</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Nhập mật khẩu..." value="" {{ isset($user) ? '' : 'required' }}>
                </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="ma-nhan-su">Mã nhân sự</label>
                    <input type="text" name="ma_nhan_su" id="ma-nhan-su" placeholder="Nhập mã nhân sự..."
                           value="{{ old('ma_nhan_su', isset($user) ? $user->ma_nhan_su : '') }}"
                           class="form-control">
                </div>


                <div class="form-group col-md-4">
                    <label class="col-form-label" for="trinh-do">Trình độ</label>
                    <input type="text" name="trinh_do" id="trinh-do" placeholder="Nhập trình độ..."
                           value="{{ old('trinh_do', isset($user) ? $user->trinh_do : '') }}"
                           class="form-control">
                </div>


                <div class="form-group col-md-4">
                    <label class="col-form-label" for="cmnd">Số CMND/căn cước</label>
                    <input type="text" name="cmnd" id="cmnd" placeholder="Nhập số CMND"
                           value="{{ old('cmnd', isset($user) ? $user->cmnd : '') }}"
                           class="form-control">
                </div>


                <div class="form-group col-md-4">
                    <label class="col-form-label" for="don-vi">Đơn vị @include('admin::required')</label>
                    <select class="form-control select-option-don-vi select2" name="don_vi_id" {{ auth::user()->hasRole(QUAN_TRI_HT) ? null : 'required' }}>
                        <option value="">-- Chọn đơn vị --</option>
                        @if (count($danhSachDonVi) > 0)
                            @foreach($danhSachDonVi as $donVi)
                                <option value="{{ $donVi->id }}" {{ isset($user) && $donViId == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-4 show-phong-ban {{ isset($user) && $user->donVi && $user->donVi->parent_id != 0 ? 'show' : 'hide' }}">

                    <label class="col-form-label" for="phong-ban">Phòng ban</label>
                    <select class="form-control select2 select-phong-ban" name="phong_ban_id">
                        <option value="">-- Chọn phòng ban --</option>
                        @if (isset($danhSachPhongBan) && count($danhSachPhongBan) > 0)
                            @foreach($danhSachPhongBan as $donVi)
                                <option value="{{ $donVi->id }}" {{ isset($user) && $user->don_vi_id == $donVi->id ? 'selected' : '' }}>{{ $donVi->ten_don_vi }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label" for="chuc-vu">Chức vụ @include('admin::required')</label>
                    <select class="form-control select2" name="chuc_vu_id" {{ auth::user()->hasRole(QUAN_TRI_HT) ? null : 'required' }}>
                        <option value="">-- Chọn chức vụ --</option>
                        @if (count($danhSachChucVu) > 0)
                            @foreach($danhSachChucVu as $chucVu)
                                <option value="{{ $chucVu->id }}" {{ isset($user) && $user->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>{{ $chucVu->ten_chuc_vu }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @if (auth::user()->hasRole(QUAN_TRI_HT))
                    <div class="form-group col-md-4">
                        <label class="col-form-label" for="quyen-han">Quyền hạn</label>
                        <select class="form-control select2" name="role_id">
                            <option value="">-- Chọn quyền hạn --</option>
                            @if (count($roles) > 0)
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ isset($user) && $user->role_id == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endif

                <div class="form-group col-md-4">
                    <label class="col-form-label" for="email">Email @include('admin::required')</label>
                    <input type="text" name="email" id="email" placeholder="Nhập địa chỉ email..."
                           value="{{ old('email', isset($user) ? $user->email : null) }}"
                           class="form-control @error('email') is-invalid @enderror"
                           required>
                    @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="so-dien-thoai">Số điện thoại</label>
                    <input type="number" name="so_dien_thoai" id="so-dien-thoai" placeholder="Nhập SDT.."
                           value="{{ old('so_dien_thoai', isset($user) ? $user->so_dien_thoai : '') }}"
                           class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="chu_ky_chinh">Ảnh chữ ký chính</label>
                    <div>
                        <input type="file" name="chu_ky_chinh" class="form-control mb-2">
                    </div>
                    @if (isset($user) && $user->chu_ky_chinh)
                        <p>File: <a href="{{ getUrlFile($user->chu_ky_chinh) }}" target="popup"
                                    class="detail-file-name seen-new-window">Chữ ký chính</a></p>
                    @endif
                </div>
                @if (isset($user) && $user->id == auth::user()->id)
                    <div class="clearfix"></div>
                @endif
                <div class="col-md-4">
                    <label class="col-form-label" for="chu_ky_nhay">Ảnh chữ ký nháy</label>
                    <div>
                        <input type="file" name="chu_ky_nhay"
                               class="form-control mb-2">
                    </div>
                    @if (isset($user) && $user->chu_ky_nhay)
                        <p>File: <a href="{{ getUrlFile($user->chu_ky_nhay) }}" target="popup"
                                    class="detail-file-name seen-new-window">Chữ ký nháy</a></p>
                    @endif
                </div>
                <div class="form-group col-md-4 ">
                    <label class="col-form-label" for="so-dien-thoai-ky-sim">Số điện thoại ký sim</label>
                    <input type="number" name="so_dien_thoai_ky_sim" id="so-dien-thoai-ky-sim"
                           placeholder="Nhập sdt ký sim.."
                           value="{{ old('so_dien_thoai_ky_sim', isset($user) ? $user->so_dien_thoai_ky_sim : null) }}"
                           class="form-control">
                </div>
                @if(auth::user()->hasRole(QUAN_TRI_HT))
                    <div class="clearfix"></div>
                @endif
                <div class="col-md-4 form-group">
                    <label class="col-form-label" for="uu-tien">Vị trí sắp xếp</label>
                    <i class="color-red">(Số hiện tại {{ $viTriUuTien ?? 0 }})</i>
                    <input type="number" name="uu_tien" id="uu-tien" placeholder="Nhập vị trí sắp xếp..."
                           value="{{ old('uu_tien', isset($user) ? $user->uu_tien : '') }}"
                           class="form-control">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label" for="gioi_tinh">Giới tính</label>
                    <br>
                    <label>
                        <input type="radio" name="gioi_tinh" class="flat-red" value="1"
                               {{ isset($user) && $user->gioi_tinh == 1 ? 'checked' : '' }}
                               checked> Nam
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="gioi_tinh" class="flat-red"
                               value="2"
                            {{ isset($user) && $user->gioi_tinh == 2 ? 'checked' : '' }}
                        > Nữ
                    </label>
                </div>
                @if (auth::user()->hasRole(QUAN_TRI_HT))
                <div class="col-md-4">
                    <label class="col-form-label" for="trang_thai">Trạng thái</label>
                    <br>
                    <label>
                        <input type="radio" name="trang_thai" class="flat-red" value="1"
                            {{ isset($user) && $user->trang_thai == 1 ? 'checked' : 'checked' }}> Hoạt động
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="trang_thai" class="flat-red" value="2"
                            {{ isset($user) && $user->trang_thai == 2 ? 'checked' : '' }}
                        > Tạm khóa
                    </label>
                </div>
                @endif
                @if (auth::user()->hasRole(QUAN_TRI_HT))
                    <div class="clearfix"></div>
                    <div class="form-group col-md-12 mt-2">
                        <p>
                            <a class="" data-toggle="collapse" href="#collapse-permission" role="button" aria-expanded="false" aria-controls="collapse-permission">
                                Chức năng của người dùng <i class="fa fa-plus"></i>
                            </a>
                        </p>
                        <div class="collapse" id="collapse-permission">
                            @if (count($permissions) > 0)
                                @foreach($permissions as $key => $permission)
                                    <div class="col-md-4 col-sm-6">
                                        <label>
                                            <input type="checkbox" class="flat-red" name="permission[]" value="{{ $permission->name }}"
                                                {{ isset($user) && in_array($permission->id, $arrPermissionId) ? 'checked' : '' }}
                                            >
                                            {{ ucfirst($permission->name) }}
                                        </label>
                                    </div>
                                    @if (($key+1) % 3 == 0)
                                        <div class="clearfix"></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit"
                    class="btn btn-primary waves-effect text-uppercase btn-sm">{{ isset($user) ? 'Cập nhật' : 'Tạo mới tài khoản' }}</button>
            <a href="{{ route('nguoi-dung.index') }}" title="hủy" class="btn btn-default btn-sm">Hủy</a>
        </div>
    </div>
</form>
