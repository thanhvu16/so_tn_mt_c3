<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\EmailDonViNgoai;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\MailNgoaiThanhPho;
use DB;

class EmailDonViNgoaiHeThongController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $ten = $request->get('ten_don_vi');
        $email = $request->get('email');
        $madinhdanh = $request->get('ma_dinh_danh');
        $accepted = $request->get('accepted') ?? null;

        $danhSachEmails = MailNgoaiThanhPho::where(function ($query) use ($ten) {
            if (!empty($ten)) {
                return $query->where(DB::raw('lower(ten_don_vi)'), 'LIKE', "%" . mb_strtolower($ten) . "%");
            }
        })->where(function ($query) use ($madinhdanh) {
            if (!empty($madinhdanh)) {
                return $query->where('ma_dinh_danh', 'LIKE', "%$madinhdanh%");
            }
        })
            ->where(function ($query) use ($email) {
                if (!empty($email)) {
                    return $query->where('email', 'LIKE', "%$email%");
                }
            })
            ->where(function ($query) use ($accepted) {
                if (!empty($accepted)) {
                    if ($accepted == 1) {
                        return $query->where('accepted', $accepted);
                    } else {
                        return $query->whereNull('accepted');
                    }
                }
            })
            ->orderBy('ten_don_vi', 'asc')->paginate(PER_PAGE);

        $id = (int)$request->get('id');
        $email = null;
        if ($id) {
            $email = MailNgoaiThanhPho::where('id', $id)->first();
        }

        return view('admin::don-vi-ngoai-he-thong.index',
            compact('danhSachEmails', 'email'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $email = new MailNgoaiThanhPho();

        $email->ten_don_vi = $request->ten_Dv;
        $email->email = $request->email;
        $email->ma_dinh_danh = $request->ma_dinh_danh;
        $email->accepted = $request->get('accepted') ?? MailNgoaiThanhPho::EXCEPTED;
        $email->email = $request->get('email');
        $email->dia_chi = $request->get('dia_chi');
        $email->sdt = $request->get('sdt');
        $email->web = $request->get('web');
        $email->save();

        return redirect()->route('email-don-vi-ngoai-he-thong.index')->with('success','Thêm mới thành công !');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        $email = MailNgoaiThanhPho::where('id', $id)->first();

        return view('admin::don-vi-ngoai-he-thong.edit', compact('email'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $email = MailNgoaiThanhPho::where('id', $id)->first();

        $email->ten_don_vi = $request->ten_Dv;
        $email->email = $request->email;
        $email->ma_dinh_danh = $request->ma_dinh_danh;
        $email->accepted = $request->get('accepted') ?? MailNgoaiThanhPho::EXCEPTED;
        $email->email = $request->get('email');
        $email->dia_chi = $request->get('dia_chi');
        $email->sdt = $request->get('sdt');
        $email->web = $request->get('web');
        $email->save();

        return redirect()->route('email-don-vi-ngoai-he-thong.index')->with('success','Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        MailNgoaiThanhPho::where('id',$id)->delete();

        return redirect()-> back()->with('success', 'Xóa Thành công');
    }

    public function updateAll(Request $request)
    {
        $accpeted = $request->get('accepted') ?? null;

        $emailNgoai = MailNgoaiThanhPho::all();

        foreach ($emailNgoai as $email) {
            $email->accepted = $accpeted;
            $email->save();
        }

        return redirect()->route('email-don-vi-ngoai-he-thong.index')->with('success','Cập nhật thành công !');
    }
}
