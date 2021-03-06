<?php

namespace Modules\LayVanBanTuEmail\Http\Controllers;


use App\User;
use http\Env;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\DonVi;
use Modules\LayVanBanTuEmail\Entities\EmailDonVi;
use Modules\LayVanBanTuEmail\Entities\GetEmail;
use Auth;
use Modules\VanBanDen\Entities\EmailFile;

class LayVanBanTuEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = auth::user();
        if ($user->hasRole([VAN_THU_DON_VI, VAN_THU_HUYEN])) {

            if ($user->hasRole(VAN_THU_HUYEN)) {
                $lanhDaoSo = User::role([CHU_TICH])
                    ->whereHas('donVi', function ($query) {
                        return $query->whereNull('cap_xa');
                    })->first();

                $donViId = $lanhDaoSo->don_vi_id ?? null;
            } else {
                $donViId = $user->donVi->parent_id;
            }

            $donVi = DonVi::where('id', $donViId)->where('status_email', DonVi::STATUS_EMAIL_ACTIVE)
                ->select('id', 'email', 'password')->first();

            if (empty($donVi)) {
                return back()->with('warning', 'Vui lòng cấu hình email trước khi quét văn bản từ hòm thư công.');
            }

            set_time_limit(3000);
//            if (\env('APP_ENV') == 'local') {
            $hostname = '{mail.thudo.gov.vn:995/pop3/ssl/novalidate-cert/notls}';
//            } else {
//                $hostname = '{mail.hanoi.gov.vn:993/imap/ssl/novalidate-cert/notls}';
//            }

            $username = $donVi->email;
            $password = $donVi->password;

            $time = time() - 7200;
            $maxDate = GetEmail::orderBy('mail_date', 'DESC')
                ->select('id', 'mail_date')->first();

            /* try to connect */
//            $date = '31 May 2021';

            $date = null;
            if ($maxDate) {
                $date = date("j F Y", strtotime($maxDate->mail_date));
            }

            $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Email: ');

            $emails = imap_search($inbox, 'UNSEEN');

            if (!empty($date)) {
                $emails = imap_search($inbox, 'SINCE "' . $date . '"');
            }

            if ($emails) {

                rsort($emails);

                foreach ($emails as $key => $email_number) {
                    $header = imap_fetchheader($inbox, $email_number);
                    $to_header = explode("\n", imap_fetchheader($inbox, $email_number));
                    $cut_header = explode(' ', trim($to_header[5]));

                    if (count($cut_header) == 7 || count($cut_header) == 6 || count($cut_header) == 8) {
                        if (!empty($cut_header[1])) {
                            //26 mar 2021 11:41:17 +0700
                            $date_header = $cut_header[1] . ' ' . $cut_header[2] . ' ' . $cut_header[3] . ' ' . $cut_header[4];
                        } else {
                            //mar 2021 11:41:17 +0700
                            $date_header = $cut_header[2] . ' ' . $cut_header[3] . ' ' . $cut_header[4] . ' ' . $cut_header[5];
                        }
                        $overview = imap_fetch_overview($inbox, $email_number, 0);

                        $arr['mail_subject'] = $this->decode($overview[0]->subject);
                        $arr['mail_from'] = $this->decode($overview[0]->from);
                        $arr['mail_date'] = date('Y-m-d H:i:s', strtotime($date_header));

                        $kiemtra = GetEmail::where([
                            'mail_subject' => $arr['mail_subject'],
                            'mail_from' => $arr['mail_from'],
                            'mail_date' => $arr['mail_date'],
                        ])->count();

                        if ($kiemtra == 0) {
                            $message = imap_fetchbody($inbox, $email_number, 2);
                            /* get mail structure */
                            $structure = imap_fetchstructure($inbox, $email_number);
                            $attachments = array();
                            /* if any attachments found... */
                            if (isset($structure->parts) && count($structure->parts)) {
                                for ($i = 0; $i < count($structure->parts); $i++) {
                                    $attachments[$i] = array(
                                        'is_attachment' => false,
                                        'filename' => '',
                                        'name' => '',
                                        'attachment' => ''
                                    );
                                    if ($structure->parts[$i]->ifdparameters) {
                                        foreach ($structure->parts[$i]->dparameters as $object) {
                                            if (strtolower($object->attribute) == 'filename') {
                                                $attachments[$i]['is_attachment'] = true;
                                                $attachments[$i]['filename'] = $object->value;
                                            }
                                        }
                                    }
                                    if ($structure->parts[$i]->ifparameters) {
                                        foreach ($structure->parts[$i]->parameters as $object) {
                                            if (strtolower($object->attribute) == 'name') {
                                                $attachments[$i]['is_attachment'] = true;
                                                $attachments[$i]['name'] = $object->value;
                                            }
                                        }
                                    }
                                    if ($attachments[$i]['is_attachment']) {
                                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);
                                        /* 4 = QUOTED-PRINTABLE encoding */
                                        if ($structure->parts[$i]->encoding == 3) {
                                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                                        } /* 3 = BASE64 encoding */
                                        elseif ($structure->parts[$i]->encoding == 4) {
                                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                        }
                                    }
                                }
                            }


                            //toàn viết
                            if (count($attachments) > 0) {
                                $emailDonVi = EmailDonVi::where('email', $arr['mail_from'])->first();
                                $noigui = $emailDonVi->mail_group ?? 4;
                                $data = array(
                                    'mail_subject' => $arr['mail_subject'],
                                    'mail_from' => $arr['mail_from'],
                                    'mail_date' => $arr['mail_date'],
//                                    'mail_attachment' => $arr['mail_attachment'],
//                                    'mail_pdf' => $arr['mail_attachment1'],
//                                    'mail_doc' => $arr['mail_attachment2'],
//                                    'mail_xls' => $arr['mail_attachment3'],
                                    'noigui' => $noigui,
                                    'mail_active' => 1
                                );
                                $getEmail = new GetEmail();
                                $getEmail->fill($data);
                                $getEmail->save();
//
                                EmailFile::saveAttmentFile($getEmail, $attachments, $key, $date_header);
//
                            }


//                            $time = time();
//                            /* iterate through each attachment and save it */
//                            foreach ($attachments as $attachment) {
//                                if ($attachment['is_attachment'] == 1) {
//                                    $filename = iconv_mime_decode($attachment['name']);
//                                    $filename = str_replace(' ', '-', $filename);
//                                    if (empty($filename)) $filename = $time . ".dat";
//                                    if (is_dir('emailFile_' . date('Y')) == false) {
//                                        mkdir('emailFile_' . date('Y'), 0777);
//                                    }
//                                    // download and save pdf
//                                    if ('pdf' === $this->filename_extension($filename) || 'PDF' === $this->filename_extension($filename)) {
//                                        //$filename = str_replace('.sdk','.xml',$filename);
//                                        $fp = @fopen("emailFile_" . date('Y') . "/" . $key . '_' . strtotime($date_header) . '_' . $filename, "w+");
//                                        if ($fp) {
//                                            fwrite($fp, $attachment['attachment']);
//                                            fclose($fp);
//                                        }
//                                        $arr['mail_attachment1'] = $key . '_' . strtotime($date_header) . '_' . $filename;
//                                        // viết vào đây
//
//
//
//                                    }
//
//                                    // download and save doc docx
//                                    if ('doc' === $this->filename_extension($filename) || 'DOC' === $this->filename_extension($filename) || 'DOCX' === $this->filename_extension($filename) || 'docx' === $this->filename_extension($filename)) {
//                                        $fp = @fopen("emailFile_" . date('Y') . "/" . $key . '_' . strtotime($date_header) . '_' . $filename, "w+");
//                                        if ($fp) {
//                                            fwrite($fp, $attachment['attachment']);
//                                            fclose($fp);
//                                        }
//
//                                        $arr['mail_attachment2'] = $key . '_' . strtotime($date_header) . '_' . $filename;
//                                    } else {
//                                        $arr['mail_attachment2'] = '';
//                                    }
//
//                                    // download and save xls xlsx
//                                    if ('xls' === $this->filename_extension($filename) || 'XLS' === $this->filename_extension($filename) || 'XLSX' === $this->filename_extension($filename) || 'xlsx' === $this->filename_extension($filename)) {
//                                        $fp = @fopen("emailFile_" . date('Y') . "/" . $key . '_' . strtotime($date_header) . '_' . $filename, "w+");
//                                        if ($fp) {
//                                            fwrite($fp, $attachment['attachment']);
//                                            fclose($fp);
//                                        }
//                                        $arr['mail_attachment3'] = $key . '_' . strtotime($date_header) . '_' . $filename;
//                                    } else {
//                                        $arr['mail_attachment3'] = '';
//                                    }
//
//
//                                    // download and save xml
//                                    $arr['mail_attachment'] = '';
//                                    if ('sdk' === $this->filename_extension($filename) || 'SDK' === $this->filename_extension($filename)) {
//                                        $filename = str_replace('.sdk', '.xml', $filename);
//                                        $fp = @fopen("emailFile_" . date('Y') . "/" . $key . '_' . strtotime($date_header) . '_' . $filename, "w+");
//
//                                        if ($fp) {
//                                            @fwrite($fp, mb_convert_encoding($attachment['attachment'], 'UTF-8', 'UCS-2LE,UTF-16LE,ASCII,JIS,UTF-8,EUC-JP,SJIS'));//UTF-16LE
//                                            //fwrite($fp,$attachment['attachment']);
//                                            fclose($fp);
//                                        }
//                                        $arr['mail_attachment'] = $key . '_' . strtotime($date_header) . '_' . $filename;
//                                    }
//                                }
//
//                            }
//                            if (!empty($arr['mail_attachment1'])) {
//                                $emailDonVi = EmailDonVi::where('email', $arr['mail_from'])->first();
//                                $noigui = $emailDonVi->mail_group ?? 4;
//                                $data = array(
//                                    'mail_subject' => $arr['mail_subject'],
//                                    'mail_from' => $arr['mail_from'],
//                                    'mail_date' => $arr['mail_date'],
//                                    'mail_attachment' => $arr['mail_attachment'],
//                                    'mail_pdf' => $arr['mail_attachment1'],
//                                    'mail_doc' => $arr['mail_attachment2'],
//                                    'mail_xls' => $arr['mail_attachment3'],
//                                    'noigui' => $noigui,
//                                    'mail_active' => 1
//                                );
//                                $getEmail = new GetEmail();
//                                $getEmail->fill($data);
//                                $getEmail->save();
//
//
//                            }
//                            $arr['mail_attachment'] = '';
//                            $arr['mail_attachment1'] = '';
//                            $arr['mail_attachment2'] = '';
//                            $arr['mail_attachment3'] = '';

                            imap_clearflag_full($inbox, $email_number, "\\Seen");
                        }
                    }
                }
            }
            /* close the connection */
            imap_close($inbox);

            return redirect()->route('dsvanbandentumail')->with('success', 'Cập nhật thành công.');
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

    function upperListEncode()
    { //convert mb_list_encodings() to uppercase
        $encodes = mb_list_encodings();
        foreach ($encodes as $encode) $tencode[] = strtoupper($encode);
        return $tencode;
    }

    function decode($string)
    {
        $tabChaine = imap_mime_header_decode($string);
        $texte = '';
        for ($i = 0; $i < count($tabChaine); $i++) {

            switch (strtoupper($tabChaine[$i]->charset)) { //convert charset to uppercase
                case 'UTF-8':
                    $texte .= $tabChaine[$i]->text; //utf8 is ok
                    break;
                case 'DEFAULT':
                    $texte .= $tabChaine[$i]->text; //no convert
                    break;
                default:
                    if (in_array(strtoupper($tabChaine[$i]->charset), $this->upperListEncode())) //found in mb_list_encodings()
                    {
                        $texte .= mb_convert_encoding($tabChaine[$i]->text, 'UTF-8', $tabChaine[$i]->charset);
                    } else { //try to convert with iconv()
                        $ret = iconv($tabChaine[$i]->charset, "UTF-8", $tabChaine[$i]->text);
                        if (!$ret) $texte .= $tabChaine[$i]->text;  //an error occurs (unknown charset)
                        else $texte .= $ret;
                    }
                    break;
            }
        }
        return $texte;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('layvanbantuemail::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('layvanbantuemail::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('layvanbantuemail::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
