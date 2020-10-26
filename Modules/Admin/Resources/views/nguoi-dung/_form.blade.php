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
                    <label class="col-form-label" for="ho-ten">Họ tên @include('admin::required')</label>
                    <input type="text" name="ho_ten" id="ho-ten" class="form-control" placeholder="Nhập họ tên..."
                           value="{{ old('ho_ten', isset($user) ? $user->ho_ten : '') }}" required="">
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
                    <label class="col-form-label" for="don-vi">Đơn vị</label>
                    <select class="form-control select2" name="don_vi_id">
                        <option value="">-- Chọn đơn vị --</option>
                        @if (count($danhSachDonVi) > 0)
                            @foreach($danhSachDonVi as $donVi)
                                <option value="{{ $donVi->id }}" {{ isset($user) && $user->don_vi_id == $donVi->id ? 'selected' : '' }}>{{ $chucVu->ten_don_vi }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label" for="chuc-vu">Chức vụ</label>
                    <select class="form-control select2" name="chuc_vu_id">
                        <option value="">-- Chọn chức vụ --</option>
                        @if (count($danhSachChucVu) > 0)
                            @foreach($danhSachChucVu as $chucVu)
                                <option value="{{ $chucVu->id }}" {{ isset($user) && $user->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>{{ $chucVu->ten_chuc_vu }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @if (auth::user()->checkRole())
                    <div class="form-group col-md-4">
                        <label class="col-form-label" for="quyen-han">Quyền hạn</label>
                        <select class="form-control select2" name="role_id">
                            <option value="">-- Chọn quyền hạn --</option>
                            @if (count($roles) > 0)
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ isset($user) && $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
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
                           required {{ isset($user) ? 'disabled' : '' }}>
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
                <div class="clearfix"></div>
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
                <div class="form-group col-md-4">
                    <label class="col-form-label" for="so-dien-thoai-ky-sim">Số điện thoại ký sim</label>
                    <input type="number" name="so_dien_thoai_ky_sim" id="so-dien-thoai-ky-sim"
                           placeholder="Nhập sdt ký sim.."
                           value="{{ old('so_dien_thoai_ky_sim', isset($user) ? $user->so_dien_thoai_ky_sim : null) }}"
                           class="form-control">
                </div>
                <div class="clearfix"></div>
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
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit"
                    class="btn btn-primary waves-effect text-uppercase btn-sm">{{ isset($user) ? 'Cập nhật' : 'Tạo mới tài khoản' }}</button>
            <a href="{{ route('nguoi-dung.index') }}" title="hủy" class="btn btn-default btn-sm">Hủy</a>
        </div>
    </div>
</form>
