<?php

namespace Modules\CongViecDonVi\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class DonViPhoiHopGiaiQuyetFile extends Model
{
    protected $table = 'cong_viec_don_vi_phoi_hop_giai_quyet_file';

    protected $fillable = [
        'cong_viec_don_vi_phoi_hop_giai_quyet_id',
        'ten_file',
        'url_file'
    ];

    public static function dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $phoiHopGiaiQuyetId)
    {
        $uploadPath = public_path(THU_MUC_FILE_PHOI_HOP);

        foreach ($multiFiles as $key => $getFile) {

            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = THU_MUC_FILE_PHOI_HOP . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $giaiQuyetFile = new DonViPhoiHopGiaiQuyetFile();
            $giaiQuyetFile->ten_file = isset($ten) ? $ten : $fileName;
            $giaiQuyetFile->url_file = $url;
            $giaiQuyetFile->cong_viec_don_vi_phoi_hop_giai_quyet_id = $phoiHopGiaiQuyetId;
            $giaiQuyetFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
