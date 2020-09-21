<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Image
{
    /**
     * Eloquent 用 PDO 存檔時，都是用 PDO::PARAM_STR 儲存
     * 但是存二進位檔案需要改用 PDO::PARAM_LOB，所以使用底層的 PDO
     * 雖然有元件 https://packagist.org/packages/ooxif/laravel-query-param
     * 但是只能用在 Laravel framework 5
     */

    /**
     * 把上傳的圖片存到資料庫
     *
     * @return UUID $id
     */
    public static function store(\Illuminate\Http\UploadedFile $image) {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO images
                                       (id, content, original_name, created_at, updated_at)
                                VALUES (?, ?, ?, ?, ?)");

        // 從檔案暫存路徑讀取二進位檔案
        $binary = file_get_contents($image->path());

        // 主鍵使用有時間順序的 UUID ，搜尋會比較快
        $id = (string) Str::orderedUuid();
        // 客戶端原始檔名
        $originalName = $image->getClientOriginalName();
        // bindParam() 只能放變數
        $now = now();

        $stmt->bindParam(1, $id); // 主鍵
        $stmt->bindParam(2, $binary, \PDO::PARAM_LOB); // 圖片二進位內容
        $stmt->bindParam(3, $originalName); // 客戶端原始檔名
        $stmt->bindParam(4, $now); // 新增時間
        $stmt->bindParam(5, $now); // 更新時間

        $pdo->beginTransaction();
        $stmt->execute();
        $pdo->commit();

        return $id;
    }

    /**
     * 抓取資料庫中的圖片檔
     */
    public static function find($id) {
        $pdo = DB::connection()->getPdo();

        $stmt = $pdo->prepare("SELECT id, content, original_name
                               FROM images WHERE id = ?");
        $stmt->execute([ $id ]);
        // 和 Eloquent 一樣的 $model->property 格式
        $result = $stmt->fetch(\PDO::FETCH_OBJ);

        // 如果找不到檔案
        if($result === false) return null;
        return $result;
    }
}
