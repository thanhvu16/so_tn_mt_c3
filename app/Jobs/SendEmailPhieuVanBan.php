<?php

namespace App\Jobs;

use App\Models\EmailDonViNgoai;
use App\Models\QlvbVbDenDonVi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\DieuHanhVanBanDen\Entities\ChuyenNhanPhieuChuyenVanBan;
use Modules\DieuHanhVanBanDen\Entities\ChuyenVanBanDenDonVi;
use Mail;
use Modules\DieuHanhVanBanDen\Entities\PhieuChuyenVanBanDenDonVi;

class SendEmailPhieuVanBan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $phieuChuyenVanBanDenDonViId;
    protected  $userId;

    public function __construct($phieuChuyenVanBanDenDonViId, $userId)
    {
        $this->phieuChuyenVanBanDenDonViId = $phieuChuyenVanBanDenDonViId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phieuChuyenVanBanDenDonVi = ChuyenVanBanDenDonVi::where('id', $this->phieuChuyenVanBanDenDonViId)
            ->whereNull('status')
            ->first();

        if ($phieuChuyenVanBanDenDonVi) {

            $danhSachDonVi = $phieuChuyenVanBanDenDonVi->donViNgoai();
            $danhSachVanBanDen = $phieuChuyenVanBanDenDonVi->vanBanDenDonVi();
            $attachments = [];
            $filePhieuChuyen = $phieuChuyenVanBanDenDonVi->phieuChuyenPdf->getUrlFile();

            if (count($danhSachVanBanDen) > 0) {
                foreach ($danhSachVanBanDen as $vanBanDenDonVi) {
                    if ($vanBanDenDonVi->vanBanDen->vanBanDenFile) {
                        foreach($vanBanDenDonVi->vanBanDen->vanBanDenFile as  $file) {
                            $attachments[] = $file->getUrlFile();
                        }
                    }
                }
            }

            if (count($danhSachDonVi) > 0) {
                foreach ($danhSachDonVi as $donVi) {
                    $this->sendEmail($phieuChuyenVanBanDenDonVi, $donVi, $attachments, $filePhieuChuyen);
                    // update phieu chuyen vb
                    $this->updatePhieuChuyenVanBan($donVi->id, $phieuChuyenVanBanDenDonVi->van_ban_den_don_vi_id);
                }
            }


            //update da phat hanh
            $phieuChuyenVanBanDenDonVi->status = ChuyenVanBanDenDonVi::DA_PHAT_HANH;
            $phieuChuyenVanBanDenDonVi->save();

            //update chuyen nhan phieu chuyen
            $chuyenNhanVanBan = ChuyenNhanPhieuChuyenVanBan::where('chuyen_van_ban_den_don_vi_id', $this->phieuChuyenVanBanDenDonViId)
                ->where('can_bo_nhan_id', $this->userId)
                ->whereNull('status')
                ->first();
            if (!empty($chuyenNhanVanBan)) {
                $chuyenNhanVanBan->status = ChuyenNhanPhieuChuyenVanBan::STATUS_DA_CHUYEN;
                $chuyenNhanVanBan->save();
            }

            //update van ban den don vi
            if (count($phieuChuyenVanBanDenDonVi->vanBanDenDonVi()) > 0) {
                foreach ($phieuChuyenVanBanDenDonVi->vanBanDenDonVi() as $vanBanDenDonVi) {
                    $vanBanDenDonVi->active = QlvbVbDenDonVi::ACTIVE_CHUYEN_VB;
                    $vanBanDenDonVi->save();
                }
            }
        }

    }

    public function sendEmail($phieuChuyenVanBanDenDonVi, $donVi, $attachments, $filePhieuChuyen)
    {

        if (!empty($donVi->email) && $donVi->accepted == EmailDonViNgoai::ACCEPTED) {
            $subject = '(' . $phieuChuyenVanBanDenDonVi->so_cong_van . '/PC-VP) Phiếu chuyển VP UBND TP Hà Nội'; // nội dung gửi mail
            $content = 'Phiếu chuyển VP UBND TP Hà Nội gửi đơn vị ' . $donVi->ten_don_vi;
            $toEmail = $donVi->email;// mail nhận
            $toName = $donVi->ten_don_vi;

            Mail::send('emails.gui_mail_van_ban_di', ['content' => $content], function ($message) use ($attachments, $toEmail, $toName, $subject, $filePhieuChuyen)
            {
                $message->to($toEmail, $toName)->subject
                ($subject);
                $message->attach($filePhieuChuyen);
                if (count($attachments) > 0) {
                    foreach ($attachments as $fileAttach) {
                        $message->attach($fileAttach);
                    }
                }
            });

        }
    }

    public function updatePhieuChuyenVanBan($donViId, $arrVanBanDenDonVi)
    {
        $phieuChuyenVanBan = PhieuChuyenVanBanDenDonVi::where('don_vi_id', $donViId)
            ->whereIn('van_ban_den_don_vi_id', $arrVanBanDenDonVi)
            ->whereNull('status')
            ->update(['status' => PhieuChuyenVanBanDenDonVi::STATUS_PHAT_HANH]);
    }
}
