<form class="form-row"
      action="{{ isset($role) ? route('vai-tro.update', $role->id) : route('vai-tro.store') }}"
      method="post"
      enctype="multipart/form-data">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Tên quyền hạn @include('admin::required')</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nhập tên quyền hạn"
                               value="{{ old('name', isset($role) ? $role->name : '') }}" required="" {{ isset($role) ? 'disabled' : '' }}>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-12 mb-1">
                        <label>Chức năng:</label>
                    </div>
                    @if (count($permissions) > 0)
                        @foreach($permissions as $key => $permission)
                            <div class="col-md-3 col-sm-6">
                                <label>
                                    <input type="checkbox" class="flat-red" name="permission[]" value="{{ $permission->id }}"
                                    {{ isset($role) && in_array($permission->id, $arrPermisson) ? 'checked' : '' }}
                                    >
                                    {{ ucfirst($permission->name) }}
                                </label>
                            </div>
                            @if (($key+1) % 4 == 0)
                                <div class="clearfix"></div>
                            @endif
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <button type="submit"
                    class="btn btn-primary waves-effect text-uppercase btn-sm">{{ isset($role) ? 'Cập nhật' : 'Thêm mới' }}</button>
            <a href="{{ route('vai-tro.index') }}" title="hủy" class="btn btn-default btn-sm">Hủy</a>
        </div>
    </div>
</form>
