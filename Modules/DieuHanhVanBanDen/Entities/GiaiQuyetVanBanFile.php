<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class GiaiQuyetVanBanFile extends Model
{
    protected $table = 'dhvbd_giai_quyet_van_ban_file';

    protected $fillable = [
        'giai_quyet_van_ban_id',
        'ten_file',
        'url_file'
    ];

    public static function dinhKemFileGiaiQuyet($multiFiles, $txtFiles, $giaiQuyetVanBanId)
    {
        $uploadPath = public_path(UPLOAD_GIAI_QUYET_VAN_BAN_DEN);

        foreach ($multiFiles as $key => $getFile) {

            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = UPLOAD_GIAI_QUYET_VAN_BAN_DEN . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $giaiQuyetFile = new GiaiQuyetVanBanFile();
            $giaiQuyetFile->ten_file = isset($ten) ? $ten : $fileName;
            $giaiQuyetFile->url_file = $url;
            $giaiQuyetFile->giai_quyet_van_ban_id = $giaiQuyetVanBanId;
            $giaiQuyetFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
