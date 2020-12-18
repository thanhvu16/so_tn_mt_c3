<?php

namespace App\Jobs;

use App\Models\EmailDonVi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\QuanLyVanBan\Entities\LogGuiMailVanBanDi;

class SendVanBanDiDonVi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $danhSachLogGuiMailVanBanDi = LogGuiMailVanBanDi::with('vanBanDi', 'emailTrongDonVi')
            ->whereNull('status')->get();

        if (count($danhSachLogGuiMailVanBanDi) > 0) {
            foreach ($danhSachLogGuiMailVanBanDi as $logGuiMailVanBanDi) {
                $vanBanDi = $logGuiMailVanBanDi->vanBanDi;
                $emailTrongDonVi = $logGuiMailVanBanDi->emailTrongDonVi;

                $fileVanBanDi = $vanBanDi->vanBanDiFilePdfDaKy;
                $attachments = [];
                if (count($fileVanBanDi) > 0) {
                    foreach ($fileVanBanDi as $file) {
                        $attachments[] = $file->getUrlFile();
                    }
                }

                // gui mail den don vi
                $this->sendEmail($vanBanDi, $attachments, $emailTrongDonVi);

                //update log gui mail den don vi
                $logGuiMailVanBanDi->status = LogGuiMailVanBanDi::DA_GUI;
                $logGuiMailVanBanDi->save();
            }
        }
    }


    public function sendEmail($vanBanDi, $attachments, $emailTrongDonVi)
    {
        if (!empty($emailTrongDonVi->email) && $emailTrongDonVi->accepted == EmailDonVi::ACCEPTED) {
            $subject = '(' . $vanBanDi->vb_sothutu . '/' . $vanBanDi->vb_sokyhieu . ') ' . strTrichYeu($vanBanDi->vb_trichyeu); // nội dung gửi mail
            $content = $vanBanDi->vb_trichyeu;
            $toEmail = $emailTrongDonVi->email;// mail nhận
            $toName = $emailTrongDonVi->ten_don_vi;

            Mail::send('emails.gui_mail_van_ban_di', ['content' => $content], function ($message) use ($attachments, $toEmail, $toName, $subject) {
                $message->to($toEmail, $toName)->subject
                ($subject);
                if (count($attachments) > 0) {
                    foreach ($attachments as $fileAttach) {
                        $message->attach($fileAttach);
                    }
                }
            });
        }
    }
}
