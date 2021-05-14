
@extends('admin::layouts.master')
@section('page_title', 'Upload tài liệu tham khảo mới nhất')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Upload tài liệu tham khảo mới nhất</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ route('postTaiLieuThamKhao') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-3 form-group">
                                <input type="file" multiple name="ten_file[]" accept=".xlsx,.xls,.doc, .docx,.txt,.pdf"/></div>
                            <div class="col-md-3 form-group">
                                <button
                                    class="btn btn-primary" type="submit">
                                    <span>Tải lên</span></button>

                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script src="{{ asset('modules/quanlyvanban/js/app.js') }}"></script>
    <script type="text/javascript">
        function showModal() {
            $("#myModal").modal('show');
        }
    </script>
@endsection
