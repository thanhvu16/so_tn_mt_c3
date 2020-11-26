<?php
namespace Modules\VanBanDi\Entities;

use App\Models\Filecanbogopyduthao;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanBoPhongDuThao extends Model
{

    protected $table = 'dtvb_y_kien_du_thao_phong';
    use SoftDeletes;

    protected $fillable = [
        'can_bo_id',
        'du_thao_vb_id',
        'trang_thai'

    ];
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'can_bo_id', 'id');
    }
    public function thongtinduthao()
    {
        return $this->belongsTo(Duthaovanbandi::class, 'du_thao_vb_id', 'id');
    }
    public function gopyFile()
    {
        return $this->hasMany(\Modules\VanBanDi\Entities\Filecanbogopyduthao::class, 'can_bo_gop_y', 'id')->where('trang_thai',1);
    }
    public function gopyFilecanbophong()
    {
        return $this->hasMany(\Modules\VanBanDi\Entities\Filecanbogopyduthao::class, 'can_bo_gop_y', 'id')->where('trang_thai',1);
    }

}
