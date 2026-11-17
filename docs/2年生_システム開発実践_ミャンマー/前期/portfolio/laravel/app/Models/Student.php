<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo; // ← リレーションの型（BelongsTo）を使えるようにする


class Student extends Model
{
    use HasFactory;

    // ↓ この1行を追加する
    protected $fillable = ['name', 'email', 'score'];

    // 1件の生徒は1人のユーザーに属する、という関係（多対1）を表すメソッド
    // これを定義すると $student->user で「この生徒を登録したユーザー」を取得できる
    public function user(): BelongsTo
    {
        // belongsTo(User::class) … 「Userモデルに属している」という意味
        // ※ このテーブルの user_id カラムを見て、対応する users レコードを探しに行く
        return $this->belongsTo(User::class);
    }
}