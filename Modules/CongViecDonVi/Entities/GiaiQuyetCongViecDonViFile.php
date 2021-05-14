<?php

namespace Modules\CongViecDonVi\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class GiaiQuyetCongViecDonViFile extends Model
{
    protected $table = 'giai_quyet_cong_viec_don_vi_file';

    protected $fillable = [
        'giai_quyet_cong_viec_don_vi_id',
        'ten_file',
        'url_file'
    ];

    public static function dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetCongViecDonViFile)
    {
        $uploadPath = public_path(THU_MUC_CONG_VIEC_DON_VI);

        foreach ($multiFiles as $key => $getFile) {

            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = !empty($txtFiles[$key]) ? strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile : null;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = THU_MUC_CONG_VIEC_DON_VI . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $giaiQuyetFile = new GiaiQuyetCongViecDonViFile();
            $giaiQuyetFile->ten_file = isset($ten) ? $ten : $fileName;
            $giaiQuyetFile->url_file = $url;
            $giaiQuyetFile->giai_quyet_cong_viec_don_vi_id = $giaiQuyetCongViecDonViFile;
            $giaiQuyetFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
