<div class="modal fade" id="modal-them-van-ban-den">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-bold" id="exampleModalLabel">#Tìm văn bản đến</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('tim-kiem-van-ban-den.index') }}" id="form-search-van-ban-den">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="vb_so_den" class="col-form-label">Số đến văn bản</label>
                                    <input type="text" name="vb_so_den" class="form-control soden" value=""
                                           id="vb_so_den" placeholder="Số đến văn bản">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sokyhieu" class="col-form-label">Số ký hiệu</label>
                                    <input type="text" name="vb_so_ky_hieu" value=""
                                           class="form-control file_insert" id="sokyhieu" placeholder="Số ký hiệu">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sokyhieu" class="col-form-label ">Trích yếu</label>
                                    <input rows="3" class="form-control" placeholder="nội dung" name="vb_trich_yeu"
                                           type="text">
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary submit-form" name="search">Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-12 main-data">
                        <table class="table table-bordered table-striped dataTable mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="7%">
                                    <input id="check-all" type="checkbox" name="check_all_van_ban_den" value="">
                                </th>
                                <th class="text-center" width="15%">Số đến</th>
                                <th class="text-center" width="23%">Số ký hiệu</th>
                                <th class="text-center">Trích yếu</th>
                            </tr>
                            </thead>
                            <tbody class="show-row-van-ban-den">
                                <tr class="no-data">
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-add-van-ban-den">Thêm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>


