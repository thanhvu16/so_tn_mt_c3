<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class PhoiHopGiaiQuyetFile extends Model
{
    protected $table = 'dhvbd_phoi_hop_giai_quyet_file';

    protected $fillable = [
        'phoi_hop_giai_quyet_id',
        'ten_file',
        'url_file'
    ];

    public static function dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $phoiHopGiaiQuyetId)
    {
        $uploadPath = public_path(UPLOAD_GIAI_QUYET_VAN_BAN_DEN);

        foreach ($multiFiles as $key => $getFile) {

            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = !empty($txtFiles[$key]) ? strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile : null;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = UPLOAD_GIAI_QUYET_VAN_BAN_DEN . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $giaiQuyetFile = new PhoiHopGiaiQuyetFile();
            $giaiQuyetFile->ten_file = isset($ten) ? $ten : $getFile->getClientOriginalName();
            $giaiQuyetFile->url_file = $url;
            $giaiQuyetFile->phoi_hop_giai_quyet_id = $phoiHopGiaiQuyetId;
            $giaiQuyetFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
