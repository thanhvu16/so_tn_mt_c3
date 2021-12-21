<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
use File;

class EmailFile extends Model
{

    protected $table = 'file_pdf_hom_thu_cong';

    public function getUrlFile()
    {
        return asset($this->duong_dan);
    }

    public static function saveAttmentFile($getEmail, $attachments, $key, $date_header)
    {
        $time = time();
        $uploadPath = THU_MUC_FILE_DIEN_TU;
        if (!File::exists(public_path(THU_MUC_FILE_DIEN_TU))) {
            File::makeDirectory(public_path(THU_MUC_FILE_DIEN_TU), 0777, true, true);
        }
        /* iterate through each attachment and save it */
        foreach ($attachments as $attachment) {

            $filename = iconv_mime_decode($attachment['name']);
            $filename = str_replace(' ', '-', $filename);
            if (empty($filename)) {
                $filename = $time . ".dat";
            }

            $fp = fopen(public_path(THU_MUC_FILE_DIEN_TU . "/" . $key . '_' . strtotime($date_header) . '_' . $filename), "w+");
            if ($fp) {
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }

            $fullPdf = new EmailFile();
            $fullPdf->email_id = $getEmail->id;
            $fullPdf->duoi_file_pdf = $filename;
            $fullPdf->duong_dan = THU_MUC_FILE_DIEN_TU . '/' . $key . '_' . strtotime($date_header) . '_' . $filename;
            $fullPdf->duoi_file = strtolower(self::filename_extension($filename));
            $fullPdf->save();

            if ('sdk' === self::filename_extension($filename) || 'SDK' === self::filename_extension($filename)) {
                $getEmail->mail_attachment = THU_MUC_FILE_DIEN_TU . '/' . $key . '_' . strtotime($date_header) . '_' . $filename;
                $getEmail->save();
            }
        }
    }

    public static function filename_extension($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return false;
        } else {
            return substr($filename, $pos + 1);
        }
    }
}
