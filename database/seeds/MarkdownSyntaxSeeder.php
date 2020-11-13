<?php

use Illuminate\Database\Seeder;
use App\Article;
use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkdownSyntaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 新增條目 Markdown Syntax
        $path = __DIR__ . '/markdown-syntax.md';

        $article          = new Article();
        $article->title   = 'Markdown Syntax';
        $article->content = file_get_contents($path);
        $article->is_restricted = true;
        $article->role_id = Role::ADMINISTRATOR;
        $article->save();

        // 新增條目 include:中高運量鐵路系統
        $path = __DIR__ . '/include.md';

        $article          = new Article();
        $article->title   = 'include:中高運量鐵路系統';
        $article->content = file_get_contents($path);
        $article->is_restricted = true;
        $article->role_id = Role::ADMINISTRATOR;
        $article->save();

        // 新增內含的圖片
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("INSERT INTO images
                                       (id, content, original_name, created_at, updated_at)
                                VALUES (?, ?, ?, ?, ?)");

        // 從檔案暫存路徑讀取二進位檔案
        $binary = file_get_contents(__DIR__ . '/Markdown-mark.png');

        // 主鍵使用有時間順序的 UUID ，搜尋會比較快
        $id = '91ffa9b4-7872-4231-9e73-6066b7af09ca';
        // 客戶端原始檔名
        $originalName = 'Markdown-mark.png';
        // bindParam() 只能放變數作爲參數，所以不要 inline temp
        $now = now();

        $stmt->bindParam(1, $id); // 主鍵
        $stmt->bindParam(2, $binary, \PDO::PARAM_LOB); // 圖片二進位內容
        $stmt->bindParam(3, $originalName); // 客戶端原始檔名
        $stmt->bindParam(4, $now); // 新增時間
        $stmt->bindParam(5, $now); // 更新時間

        try {
            $pdo->beginTransaction();
            $stmt->execute();
            $pdo->commit();
        } catch(\Exception $e) {
            Log::critical('unable to save image into database !');
            Log::critical($e->getMessage());
            $pdo->rollBack();
        }

    }
}
