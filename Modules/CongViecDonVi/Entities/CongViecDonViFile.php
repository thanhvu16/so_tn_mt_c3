<?php

namespace Modules\CongViecDonVi\Entities;

use Illuminate\Database\Eloquent\Model;
use File;

class CongViecDonViFile extends Model
{
    protected $table = 'cong_viec_don_vi_file';

    protected $fillable = [
        'cong_viec_don_vi_id',
        'ten_file',
        'url_file'
    ];


    public static function dinhKemFile($multiFiles, $txtFiles, $congViecDonViId)
    {
        $uploadPath = public_path(THU_MUC_CONG_VIEC_DON_VI);

        foreach ($multiFiles as $key => $getFile) {
            $typeArray = explode('.', $getFile->getClientOriginalName());
            $extFile = strtolower($typeArray[1]);
            $ten = !empty($txtFiles[$key]) ? strSlugFileName(strtolower($txtFiles[$key]), '_') . '.' . $extFile : null;

            $fileName = date('Y_m_d') . '_' . Time() . '_' . $getFile->getClientOriginalName();
            $url = THU_MUC_CONG_VIEC_DON_VI . '/' . $fileName;

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true, true);
            }

            $getFile->move($uploadPath, $fileName);

            $congViecDonViFile = new CongViecDonViFile();
            $congViecDonViFile->ten_file = isset($ten) ? $ten : $fileName;
            $congViecDonViFile->url_file = $url;
            $congViecDonViFile->cong_viec_don_vi_id = $congViecDonViId;
            $congViecDonViFile->save();
        }
    }

    public function getUrlFile()
    {
        return asset($this->url_file);

    }
}
