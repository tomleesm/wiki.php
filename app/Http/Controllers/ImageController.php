<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * 檢視圖片
     */
    public function show($id) {
        if( ! Str::isUuid($id) ) {
            return abort(404);
        }

        $image = Image::findOrFail($id);

        // 在瀏覽器中直接顯示圖片
        return response()->stream(function() use ($image) {
                   fpassthru($image->content);
               }, 200,
                   // content-type 設定成 image/* ，會跳出檔案儲存對話恇
                   ['Content-Type' => 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/tiff,image/webp']
               );
    }
}
