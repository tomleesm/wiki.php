<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AvailableSetting;

class Setting extends Model
{
    // for use firstOrNew()
    protected $fillable = ['key'];

    public static function set(AvailableSetting $key, $value)
    {
        $setting = new Setting();
        // strval(Enum) 會呼叫 __toString()，回傳 enum const 的 assign value
        $setting = $setting->firstOrNew([ 'key' => strval($key) ]);
        // 檢查設定值的型別是否正確
        // 把 $value 轉成字串形式，例如 true 轉成字串 'true'
        $setting->value = var_export($value, true);
        $setting->save();
    }
}
