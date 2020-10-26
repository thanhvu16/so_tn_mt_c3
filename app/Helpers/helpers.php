<?php

if (!function_exists('uploadFile')) {
    function uploadFile($inputFile, $uploadPath, $folderUploads, $urlFileInDB = null)
    {
        $fileName = date('Y_m_d') . '_' . Time() . '_' . $inputFile->getClientOriginalName();
        $urlFile = $folderUploads . '/' . $fileName;

        //delete file in db and update
        if ($urlFileInDB) {
            File::delete($urlFileInDB);
        }

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }

        $inputFile->move($uploadPath, $fileName);

        return $urlFile;
    }
}

if (!function_exists('getUrlFile')) {
    function getUrlFile($urlFile)
    {
        if (!empty($urlFile)) {

            return asset($urlFile);
        }
    }
}

if (!function_exists('getStatusLabel')) {
    function getStatusLabel($status)
    {
        if ($status == 1) {

            return '<span class="label label-pill label-sm label-success">Hoạt động</span>';
        }

        return '<span class="label label-pill label-sm label-danger">Không hoạt động</span>';
    }
}

if (!function_exists('canPermission')) {
    function canPermission($permission)
    {
        if (!Auth::user()->can($permission)) {
            return abort(403);
        }
    }
}

