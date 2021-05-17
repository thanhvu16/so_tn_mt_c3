<?php

namespace App\Models;

use App\Libraries\Ultilities;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $table = 'user_devices';

    protected $fillable = [
        'token',
        'type',
        'user_id',
        'os_version',
        'app_version',
        'os_name',
        'api_version'
    ];


    /**
     * Rule Validate.
     *
     * @param object $user
     *
     * @return array []
     * */
    public function rule()
    {
        return [
            'token' => 'sometimes|nullable',
            'type' => 'required|in:1,2',
            'os_version' => 'required|max:20',
            'app_version' => 'required|max:20',
            'os_name' => 'required|max:20'
        ];
    }

    public function saveTokenDevice($request)
    {
        $user = $request->user();

        $conditions = [
            'user_id' => $user->id
        ];

        $device = [
            'token' => Ultilities::clearXSS($request->token),
            'type' => Ultilities::clearXSS($request->type),
            'user_id' => $user->id,
            'os_version' => Ultilities::clearXSS($request->os_version),
            'app_version' => Ultilities::clearXSS($request->app_version),
            'os_name' => Ultilities::clearXSS($request->os_name),
            'api_version' => API_VERSION,
        ];
        return $this->updateOrCreate($conditions, $device);
    }
}
