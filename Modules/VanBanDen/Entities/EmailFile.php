<?php

namespace Modules\VanBanDen\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
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
        if (is_dir('emailFile_' . date('Y')) == false) {
            mkdir('emailFile_' . date('Y'), 0777);
        }
        /* iterate through each attachment and save it */
        foreach ($attachments as $attachment) {

            $filename = iconv_mime_decode($attachment['name']);
            $filename = str_replace(' ', '-', $filename);
            if (empty($filename)) {
                $filename = $time . ".dat";
            }

            $fp = @fopen("emailFile_" . date('Y') . "/" . $key . '_' . strtotime($date_header) . '_' . $filename, "w+");
            if ($fp) {
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }

            $fullPdf = new EmailFile();
            $fullPdf->email_id = $getEmail->id;
            $fullPdf->duong_dan = $key . '_' . strtotime($date_header) . '_' . $filename;
            $fullPdf->ext =  self::filename_extension($filename);
            $fullPdf->save();
        }
    }

    function filename_extension($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return false;
        } else {
            return substr($filename, $pos + 1);
        }
    }
}
