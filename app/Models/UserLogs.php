<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;

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
        $user = auth::user()->id;
        $content = json_encode($content);
        $dataUserLogs = [
            'user_id' => $user,
            'content'=> $content,
            'action'=> $action

        ];

        $userLogs = new UserLogs();
        $userLogs->fill($dataUserLogs);
        $userLogs->save();
    }

    public function TenNguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function chuyenDoiData($data)
    {
            $data2 = json_decode($data, true);
//            dd($data2);
        return $data2;
    }

}
