<?php

namespace Modules\DieuHanhVanBanDen\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class VanBanTraLaiFile extends Model
{
    protected $table = 'dhvbd_van_ban_tra_lai_file';

    protected $fillable = [
        'van_ban_tra_lai_id',
        'ten_file',
        'url_file'
    ];

    public static function dinhKemFile($multiFiles, $txtFiles, $vanBanTraLaiId)
    {
        $uploadPath = public_path(UPLOAD_FILE_VAN_BAN_DEN_TRA_LAI);

        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = !empty($txtFiles[$key]) ? strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile : null;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = UPLOAD_FILE_VAN_BAN_DEN_TRA_LAI . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $giaiQuyetFile = new VanBanTraLaiFile();
            $giaiQuyetFile->ten_file = isset($ten) ? $ten : $getFile->getClientOriginalName();
            $giaiQuyetFile->url_file = $url;
            $giaiQuyetFile->van_ban_tra_lai_id = $vanBanTraLaiId;
            $giaiQuyetFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
