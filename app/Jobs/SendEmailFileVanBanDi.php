<?php

namespace App\Jobs;

use Modules\Admin\Entities\DonVi;

use App\Models\EmailDonVi;
use App\Models\EmailDonViNgoai;

use Modules\Admin\Entities\MailNgoaiThanhPho;
use Modules\VanBanDi\Entities\FileVanBanDi;
use Modules\VanBanDi\Entities\VanBanDi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail , auth;

class SendEmailFileVanBanDi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $type;
    protected $vanBanDiId;

    public function __construct($type, $vanBanDiId)
    {
        $this->type = $type;
        $this->vanBanDiId = $vanBanDiId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == VanBanDi::LOAI_VAN_BAN_GIAY_MOI) {
            $danhSachVanBanDi = VanBanDi::has('vanBanDiFileDaKy')
                ->with('vanBanDiFileDaKy', 'mailtrongtp', 'mailngoaitp')
                ->where('loai_van_ban_giay_moi', VanBanDi::LOAI_VAN_BAN_GIAY_MOI)
                ->get();
        } else {
            if (!empty($this->vanBanDiId)) {
                $danhSachVanBanDi = VanBanDi::has('vanBanDiFileDaKy')
                    ->with('vanBanDiFileDaKy', 'mailtrongtp', 'mailngoaitp')
                    ->where('id', $this->vanBanDiId)
                    ->get();
            } else {
                $danhSachVanBanDi = VanBanDi::has('vanBanDiFileDaKy')
                    ->with('vanBanDiFileDaKy', 'mailtrongtp', 'mailngoaitp')
                    ->get();
            }
        }
        if (count($danhSachVanBanDi) > 0) {
            foreach ($danhSachVanBanDi as $vanBanDi) {
//                if (!empty($vanBanDi->mailtrongtp)) {
//                    $this->createXML($vanBanDi);
//                    $this->sendEmail($vanBanDi);
//                }
                if (!empty($vanBanDi->mailngoaitp)) {
                    $this->createXMLNgoaiDonVi($vanBanDi);
                    $this->sendEmailNgoaiDonVi($vanBanDi);
                }

                // update file vb di
                $this->upDateFileVanBanDiDaGui($vanBanDi->vanBanDiFileDaKy);
            }
        }
    }

//    public function createXML($vanBanDi)
//    {
//        // cấu hình tạo file sdk
//        $xml = new \DOMDocument("1.0", 'UTF-8');
//        $STRLOAIVANBAN = $xml->createElement("STRLOAIVANBAN");
//        $STRLOAIVANBAN_Text = $xml->createTextNode($vanBanDi->loaivanban->ten_loai_van_ban ?? null);
//        $STRLOAIVANBAN->appendChild($STRLOAIVANBAN_Text);
//
//        $STRKYHIEU = $xml->createElement("STRKYHIEU");
//        $STRKYHIEU_Text = $xml->createTextNode($vanBanDi->vb_sothutu . '/' . $vanBanDi->vb_sokyhieu);
//        $STRKYHIEU->appendChild($STRKYHIEU_Text);
//
//        $STRTRICHYEU = $xml->createElement("STRTRICHYEU");
//        $STRTRICHYEU_Text = $xml->createTextNode($vanBanDi->vb_trichyeu);
//        $STRTRICHYEU->appendChild($STRTRICHYEU_Text);
//
//        $STRNGAYKY = $xml->createElement("STRNGAYKY");
//        $STRNGAYKY_Text = $xml->createTextNode(date('d/m/Y', strtotime($vanBanDi->vb_ngaybanhanh)));
//        $STRNGAYKY->appendChild($STRNGAYKY_Text);
//
//        $STRNGUOIKY = $xml->createElement("STRNGUOIKY");
//        $STRNGUOIKY_Text = $xml->createTextNode($vanBanDi->nguoidung2->ho_ten);
//        $STRNGUOIKY->appendChild($STRNGUOIKY_Text);
//
//        $STRCHUCDANH = $xml->createElement("STRCHUCDANH");
//        $STRCHUCDANH_Text = $xml->createTextNode($vanBanDi->chuc_vu);
//        $STRCHUCDANH->appendChild($STRCHUCDANH_Text);
//
//        $STRNOIGUI = $xml->createElement("STRNOIGUI");
//        $STRNOIGUI_Text = $xml->createTextNode($vanBanDi->checkSoVanBanDi());
//        $STRNOIGUI->appendChild($STRNOIGUI_Text);
//
//        $STRNGAYHOP = $xml->createElement("STRNGAYHOP");
//        $STRNGAYHOP_Text = $xml->createTextNode(!empty($vanBanDi->ngay_hop) ? date('d/m/Y', strtotime($vanBanDi->ngay_hop)) : null);
//        $STRNGAYHOP->appendChild($STRNGAYHOP_Text);
//
//        $STRDIADIEM = $xml->createElement("STRDIADIEM");
//        $STRDIADIEM_Text = $xml->createTextNode($vanBanDi->dia_diem_hop);
//        $STRDIADIEM->appendChild($STRDIADIEM_Text);
//
//        $STRTHOIGIANHOP = $xml->createElement("STRTHOIGIANHOP");
//        $STRTHOIGIANHOP_Text = $xml->createTextNode($vanBanDi->gio_hop);
//        $STRTHOIGIANHOP->appendChild($STRTHOIGIANHOP_Text);
//
//        $book = $xml->createElement("EXPORTMAIL");
//        $book->appendChild($STRLOAIVANBAN);
//        $book->appendChild($STRKYHIEU);
//        $book->appendChild($STRTRICHYEU);
//        $book->appendChild($STRNGAYKY);
//        $book->appendChild($STRNGUOIKY);
//        $book->appendChild($STRCHUCDANH);
//        $book->appendChild($STRNOIGUI);
//        $book->appendChild($STRNGAYHOP);
//        $book->appendChild($STRDIADIEM);
//        $book->appendChild($STRTHOIGIANHOP);
//
//        $xml->appendChild($book);
//        $xml->formatOutput = true;
//
//        $xml->save((public_path() . '/XMLOutput.sdk')) or die("Error");
//    }

//    public function sendEmail($vanBanDi)
//    {
//        $fileVanBanDi = $vanBanDi->vanBanDiFileDaKy;
//        $attachments = [];
//        $XmlFileName = asset('XMLOutput.sdk');
//
//        if (count($fileVanBanDi) > 0) {
//            foreach ($fileVanBanDi as $file) {
//                $attachments[] = $file->getUrlFile();
//            }
//        }
//
//        foreach ($vanBanDi->mailtrongtp as $emailTrongDonVi) {
//
//            if (!empty($emailTrongDonVi->emailDonVi->email) && $emailTrongDonVi->emailDonVi->accepted == EmailDonVi::ACCEPTED) {
//                $subject = '(' . $vanBanDi->vb_sothutu . '/' . $vanBanDi->vb_sokyhieu . ') ' . $vanBanDi->vb_trichyeu; // nội dung gửi mail
//                $content = $vanBanDi->vb_trichyeu;
//                $toEmail = $emailTrongDonVi->emailDonVi->email;// mail nhận
//                $toName = $emailTrongDonVi->emailDonVi->ten_don_vi;
//
//                Mail::send('emails.gui_mail_van_ban_di', ['content' => $content], function ($message) use ($attachments, $toEmail, $toName, $subject, $XmlFileName) {
//                    $message->to($toEmail, $toName)->subject
//                    ($subject);
//                    if (count($attachments) > 0) {
//                        foreach ($attachments as $fileAttach) {
//                            $message->attach($fileAttach);
//                        }
//                    }
//                    $message->attach($XmlFileName);
//                });
//
//            }
//
//        }
//    }

    public function createXMLNgoaiDonVi($vanBanDi)
    {
//        $donViCap2 = Donvi::where('cap_don_vi', DON_VI_CAP_2)
//            ->where('trang_thai', Donvi::ACTIVE)
//            ->whereNull('deleted_at')
//            ->first();
//
        $donvigui = Donvi::where('id',auth::user()->don_vi_id)
            ->whereNull('deleted_at')
            ->first();

        $maDinhDanh = !empty($donvigui) ? $donvigui->ma_hanh_chinh :'';
        $tenDonVi = !empty($donvigui) ? $donvigui->ten_don_vi : '';

        $xml = new \DOMDocument("1.0", 'UTF-8');
        $STRMADONVI_NG = $xml->createElement('STRMADONVI');
        $STRMADONVI_NG_Text = $xml->createTextNode($maDinhDanh);// mã đơn vị gửi mail
        $STRMADONVI_NG->appendChild($STRMADONVI_NG_Text);

        $STRTENDONVI_NG = $xml->createElement('STRTENDONVI');
        $STRTENDONVI_NG_Text = $xml->createTextNode($tenDonVi);
        $STRTENDONVI_NG->appendChild($STRTENDONVI_NG_Text);

        $NOINHANVANBAN = $xml->createElement("NOINHANVANBAN");

        foreach ($vanBanDi->mailngoaitp as $key => $donViBenNgoai) {
//            dd($donViBenNgoai->laytendonvingoai);
            $STRMADONVI_NN = $xml->createElement('STRMADONVI');
            $STRMADONVI_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->ma_dinh_danh ?? 0);
            $STRMADONVI_NN->appendChild($STRMADONVI_NN_Text);

            $STRTENDONVI_NN = $xml->createElement('STRTENDONVI');
            $STRTENDONVI_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->ten_don_vi);
            $STRTENDONVI_NN->appendChild($STRTENDONVI_NN_Text);

            $STRDIACHI_NN = $xml->createElement('STRDIACHI');
            $STRDIACHI_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->dia_chi);
            $STRDIACHI_NN->appendChild($STRDIACHI_NN_Text);

            $STRDIENTHOAI_NN = $xml->createElement('STRDIENTHOAI');
            $STRDIENTHOAI_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->sdt);
            $STRDIENTHOAI_NN->appendChild($STRDIENTHOAI_NN_Text);

            $STRWEBSITE_NN = $xml->createElement('STRWEBSITE');
            $STRWEBSITE_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->web);
            $STRWEBSITE_NN->appendChild($STRWEBSITE_NN_Text);

            $STREMAIL_NN = $xml->createElement('STREMAIL');
            $STREMAIL_NN_Text = $xml->createTextNode($donViBenNgoai->laytendonvingoai->email);
            $STREMAIL_NN->appendChild($STREMAIL_NN_Text);

            $NOINHAN = $xml->createElement('NOINHAN');
            $ID = $xml->createAttribute('ID');
            $ID->value = $key + 1;
            $NOINHAN->appendChild($STRMADONVI_NN);
            $NOINHAN->appendChild($STRTENDONVI_NN);
            $NOINHAN->appendChild($STRDIACHI_NN);
            $NOINHAN->appendChild($STRDIENTHOAI_NN);
            $NOINHAN->appendChild($STRWEBSITE_NN);
            $NOINHAN->appendChild($STREMAIL_NN);
            $NOINHAN->appendChild($ID);
            $NOINHANVANBAN->appendChild($NOINHAN);
        }

        $STRLOAIVANBAN = $xml->createElement("STRLOAIVANBAN");
        $STRLOAIVANBAN_Text = $xml->createTextNode($vanBanDi->loaivanban->ten_loai_van_ban ?? null);
        $STRLOAIVANBAN->appendChild($STRLOAIVANBAN_Text);

        $STRKYHIEU = $xml->createElement("STRKYHIEU");
        $STRKYHIEU_Text = $xml->createTextNode($vanBanDi->so_di . '/' . $vanBanDi->so_ky_hieu);
        $STRKYHIEU->appendChild($STRKYHIEU_Text);

        $STRTRICHYEU = $xml->createElement("STRTRICHYEU");
        $STRTRICHYEU_Text = $xml->createTextNode($vanBanDi->trich_yeu);
        $STRTRICHYEU->appendChild($STRTRICHYEU_Text);

        $STRNGAYKY = $xml->createElement("STRNGAYKY");
        $STRNGAYKY_Text = $xml->createTextNode(date('d/m/Y', strtotime($vanBanDi->ngay_ban_hanh)));
        $STRNGAYKY->appendChild($STRNGAYKY_Text);

        $STRNGUOIKY = $xml->createElement("STRNGUOIKY");
        $STRNGUOIKY_Text = $xml->createTextNode($vanBanDi->nguoidung2->ho_ten);
        $STRNGUOIKY->appendChild($STRNGUOIKY_Text);

        $STRNOIGUI = $xml->createElement("STRNOIGUI");
        $STRNOIGUI_Text = $xml->createTextNode($vanBanDi->sovanban->ten_so_van_ban ?? '');
        $STRNOIGUI->appendChild($STRNOIGUI_Text);

//        $INTSOTO = $xml->createElement('INTSOTO');
//        $INTSOTO_Text = $xml->createTextNode($vanBanDi->vb_soTrang);
//        $INTSOTO->appendChild($INTSOTO_Text);

        $STRHANXULY = $xml->createElement('STRHANXULY');
        $STRHANXULY_Text = $xml->createTextNode('');
        $STRHANXULY->appendChild($STRHANXULY_Text);

        $STRTHUOCLOAIVANBAN = $xml->createElement('STRTHUOCLOAIVANBAN');
        $STRTHUOCLOAIVANBAN_Text = $xml->createTextNode($vanBanDi->loaivanban->ten_loai_van_ban ?? 0);
        $STRTHUOCLOAIVANBAN->appendChild($STRTHUOCLOAIVANBAN_Text);

//        if (!empty($vanBanDi->van_ban_den_don_vi_id) && !empty($vanBanDi->vanBanDenDonVi)) {
//            $ma = 1;
//            $ten = 'Đã trả lời';
//            $vanBanDen = $vanBanDi->vanBanDenDonVi;
//            $PHANHOIVANBAN = $xml->createElement("PHANHOIVANBAN");
//
//            $STRSOKYHIEU_VB = $xml->createElement('STRSOKYHIEU');
//            $STRSOKYHIEU_VB_Text = $xml->createTextNode($vanBanDen->vb_so_ky_hieu);
//            $STRSOKYHIEU_VB->appendChild($STRSOKYHIEU_VB_Text);
//
//            $STRNGAYKY_VB = $xml->createElement('STRNGAYKY');
//            $STRNGAYKY_VB_Text = $xml->createTextNode(date('d/m/Y', strtotime($vanBanDen->vb_ngay_ban_hanh)));
//            $STRNGAYKY_VB->appendChild($STRNGAYKY_VB_Text);
//
//            $STRMACOQUANBANHANH_VB = $xml->createElement('STRMACOQUANBANHANH');
//            $STRMACOQUANBANHANH_VB_Text = $xml->createTextNode('');
//            $STRMACOQUANBANHANH_VB->appendChild($STRMACOQUANBANHANH_VB_Text);
//
//            $STRCOQUANBANHANH_VB = $xml->createElement('STRCOQUANBANHANH');
//            $STRCOQUANBANHANH_VB_Text = $xml->createTextNode($vanBanDen->co_quan_ban_hanh_id);
//            $STRCOQUANBANHANH_VB->appendChild($STRCOQUANBANHANH_VB_Text);
//
//            $VANBAN = $xml->createElement('VANBAN');
//            $ID_VB = $xml->createAttribute('ID');
//            $ID_VB->value = 1;
//            $VANBAN->appendChild($STRSOKYHIEU_VB);
//            $VANBAN->appendChild($STRNGAYKY_VB);
//            $VANBAN->appendChild($STRMACOQUANBANHANH_VB);
//            $VANBAN->appendChild($STRCOQUANBANHANH_VB);
//            $VANBAN->appendChild($ID_VB);
//            $PHANHOIVANBAN->appendChild($VANBAN);
//
//
//        } else {
//            $ma = 0;
//            $ten = 'Văn bản mới';
//        }
                 $ma = 0;
            $ten = 'Văn bản mới';
        $STRMANGHIEPVU = $xml->createElement('STRMANGHIEPVU');
        $STRMANGHIEPVU_Text = $xml->createTextNode($ma);
        $STRMANGHIEPVU->appendChild($STRMANGHIEPVU_Text);

        $STRTENNGHIEPVU = $xml->createElement('STRTENNGHIEPVU');
        $STRTENNGHIEPVU_Text = $xml->createTextNode($ten);
        $STRTENNGHIEPVU->appendChild($STRTENNGHIEPVU_Text);

        $STRLYDO = $xml->createElement('STRLYDO');

        $STRDONVIXULY = $xml->createElement('STRDONVIXULY');

        $STRCANBOXULY = $xml->createElement('STRCANBOXULY');

        $STRDIENTHOAI = $xml->createElement('STRDIENTHOAI');

        $STREMAIL = $xml->createElement('STREMAIL');

        $book = $xml->createElement("EXPORTMAIL");
        $NOIGUI = $xml->createElement("NOIGUI");
        $NOIGUI->appendChild($STRMADONVI_NG);
        $NOIGUI->appendChild($STRTENDONVI_NG);

        $NOIDUNGVANBAN = $xml->createElement("NOIDUNGVANBAN");
        $NOIDUNGVANBAN->appendChild($STRLOAIVANBAN);
        $NOIDUNGVANBAN->appendChild($STRKYHIEU);
        $NOIDUNGVANBAN->appendChild($STRTRICHYEU);
        $NOIDUNGVANBAN->appendChild($STRNGAYKY);
        $NOIDUNGVANBAN->appendChild($STRNGUOIKY);
        $NOIDUNGVANBAN->appendChild($STRNOIGUI);
//        $NOIDUNGVANBAN->appendChild($INTSOTO);
        $NOIDUNGVANBAN->appendChild($STRHANXULY);
        $NOIDUNGVANBAN->appendChild($STRTHUOCLOAIVANBAN);

        $NGHIEPVUVANBAN = $xml->createElement("NGHIEPVUVANBAN");
        $NGHIEPVUVANBAN->appendChild($STRMANGHIEPVU);
        $NGHIEPVUVANBAN->appendChild($STRTENNGHIEPVU);
        $NGHIEPVUVANBAN->appendChild($STRLYDO);

        $THONGTINNGUOIGUI = $xml->createElement("THONGTINNGUOIGUI");
        $THONGTINNGUOIGUI->appendChild($STRDONVIXULY);
        $THONGTINNGUOIGUI->appendChild($STRCANBOXULY);
        $THONGTINNGUOIGUI->appendChild($STRDIENTHOAI);
        $THONGTINNGUOIGUI->appendChild($STREMAIL);

        $book->appendChild($NOIGUI);

        $book->appendChild($NOINHANVANBAN);

        $book->appendChild($NOIDUNGVANBAN);
        $book->appendChild($NGHIEPVUVANBAN);
//        if (!empty($vanBanDi->van_ban_den_don_vi_id) && !empty($vanBanDi->vanBanDenDonVi)) {
//            $book->appendChild($PHANHOIVANBAN);
//        }
        $book->appendChild($THONGTINNGUOIGUI);

        $xml->appendChild($book);
        $xml->formatOutput = true;

        $xml->save((public_path() . '/XMLOutput_N.sdk')) or die("Error");
    }

    public function sendEmailNgoaiDonVi($vanBanDi)
    {
        $fileVanBanDi = $vanBanDi->vanBanDiFileDaKy;
        $attachments = [];
        $XmlFileName = asset('XMLOutput_N.sdk');

        if (count($fileVanBanDi) > 0) {
            foreach ($fileVanBanDi as $file) {
                $attachments[] = $file->getUrlFile();
            }
        }

        foreach ($vanBanDi->mailngoaitp as $emailNgoaiDonVi) {
            if (!empty($emailNgoaiDonVi->laytendonvingoai->email) && $emailNgoaiDonVi->laytendonvingoai->accepted == MailNgoaiThanhPho::ACCEPTED) {
                $subject = '(' . $vanBanDi->so_di . '/' . $vanBanDi->so_ky_hieu . ') ' . $vanBanDi->trich_yeu; // nội dung gửi mail
                $content = $vanBanDi->trich_yeu;
                $toEmail = $emailNgoaiDonVi->laytendonvingoai->email;// mail nhận
                $toName = $emailNgoaiDonVi->laytendonvingoai->ten_don_vi;
//                Mail::send('emails.gui_mail_van_ban_di', ['content' => $content], function ($message) use ($attachments, $toEmail, $toName, $subject, $XmlFileName) {
//                    $message->to($toEmail, $toName)->subject
//                    ($subject);
//                    if (count($attachments) > 0) {
//                        foreach ($attachments as $fileAttach) {
//                            $message->attach($fileAttach);
//                        }
//                    }
//                    $message->attach($XmlFileName);
//                });
                Mail::Send('emails.gui_mail_van_ban_di', ['content' => $content], function ($message) use ($attachments, $toEmail, $toName, $subject, $XmlFileName) {
                    $message->to($toEmail, $toName)->subject
                    ($subject);
                        if (count($attachments) > 0) {
                            foreach ($attachments as $fileAttach) {
                                $message->attach($fileAttach);
                            }
                        }
                    $message->attach($XmlFileName);
                });
            }

        }
    }

    public function upDateFileVanBanDiDaGui($fileVanBanDi)
    {
        //update file van ban di
        foreach ($fileVanBanDi as $file) {
            $file->trang_thai_gui = FileVanBanDi::TRANG_THAI_DA_GUI;
            $file->save();
        }
    }
}
