<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Log;

class UserLogs extends Model
{
    protected $table = 'user_logs';

    protected $fillable = [
        'user_id',
        'content',
        'action'
    ];
    public static function saveUserLogs($action, $content)
    {
//        $user = auth::user()->id;
        $user = auth::user();
//        $content = json_encode($content);
//        $dataUserLogs = [
//            'user_id' => $user,
//            'content'=> $content,
//            'action'=> $action
//
//        ];

        $dataUserLogs = [$content];

        $userAction = $user->ho_ten."<br>".$action."<br>".now()."<br>";

        Log::channel('userAction')->info($userAction, $dataUserLogs);

        //$userLogs = new UserLogs();
        //$userLogs->fill($dataUserLogs);
//        $userLogs->save();
    }

    public function TenNguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'ho_ten');
    }

    public function chuyenDoiData($data)
    {
            $data2 = json_decode($data, true);
//            dd($data2);
        return $data2;
    }

}
