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

    /**
     * 新增圖片
     *
     * @return json
     */

    public function store(Request $request) {
        $requestImage = $request->file('image');

        // 檢查檔案類型是否爲圖片
        if( ! in_array($requestImage->getMimeType(), \App\Image::ACCEPTED_MINE_TYPES) ) {
            return  response()->json([
                'status'        => 'upload file fails',
                'error_code'    => $requestImage->getError(),
                'error_message' => 'uploaded file is not an image.',
            ]);
        }

        // 如果上傳失敗，回傳錯誤訊息
        if( ! $requestImage->isValid()) {
            return  response()->json([
                'status'        => 'upload file fails',
                'error_code'    => $requestImage->getError(),
                'error_message' => $requestImage->getErrorMessage(),
            ]);
        }

        // 儲存檔案到資料庫，回傳 id
        $id = Image::store($requestImage);

        return response()->json([
            'status'       => 'upload file successfully',
            'originalName' => $requestImage->getClientOriginalName(),
            'id'           => $id, // UUID
        ]);
    }
}
