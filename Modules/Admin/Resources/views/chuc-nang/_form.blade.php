<form class="form-row"
      action="{{ isset($permission) ? route('chuc-nang.update', $permission->id) : route('chuc-nang.store') }}"
      method="post"
      enctype="multipart/form-data">
    @csrf
    @if(isset($permission))
        @method('PUT')
    @endif
    <div class="box-body">
        <div class="form-group col-md-12">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="name" class="col-form-label">Tên chức năng @include('admin::required')</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Nhập tên chức năng"
                           value="{{ old('name', isset($permission) ? $permission->name : '') }}" required="">
                    @error('name')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="submit"
                    class="btn btn-primary waves-effect text-uppercase btn-sm">{{ isset($permission) ? 'Cập nhật' : 'Thêm mới' }}</button>
            <a href="{{ route('chuc-nang.index') }}" title="hủy" class="btn btn-default btn-sm">Hủy</a>
        </div>
    </div>
</form>
