<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;           // ← Student モデルを使えるようにする
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;

class StudentController extends Controller
{
    // -------------------------------------------------------
    // GET /api/students  → 全生徒の一覧を返す
    // -------------------------------------------------------
    public function index()
    {
        // 生徒データを取得するときに、投稿者（user）の情報も一緒に読み込んでおく
        // → こうしておかないと、生徒の人数分だけ「投稿者名を取る」SQLが発行されてしまう（N+1問題）
        $students = Student::with('user')->get();

        // 取得した生徒データを、レスポンス用の形（StudentResource）に変換して返す
        return StudentResource::collection($students);
    }

    // -------------------------------------------------------
    // POST /api/students  → 新しい生徒を登録する
    // -------------------------------------------------------
    public function store(Request $request)
    {
        // バリデーション：送られてきたデータの形式を確認する
        $validated = $request->validate([
            'name'  => 'required|max:100',          // 必須・100文字以内
            'email' => 'required|email|unique:students', // 必須・メール形式・重複不可
            'score' => 'required|integer|min:0|max:100', // 必須・整数・0〜100
        ]);

        // $request->user() … 今ログインしているユーザー（Sanctumが認証してくれている）
        // ->students()->create(...) … そのユーザーの生徒として新規作成する
        //   → Eloquentが自動で user_id にログインユーザーのIDを入れてくれる
        $student = $request->user()->students()->create($validated);

        // 201 Created（作成成功）のステータスコードで返す
        return response()->json([
            'status' => 'ok',
            'data'   => $student,
        ], 201);
    }

    // -------------------------------------------------------
    // GET /api/students/{id}  → id で指定した1人を返す
    // -------------------------------------------------------
    public function show($id)
    {
        return new StudentResource(Student::findOrFail($id));
    }

    // -------------------------------------------------------
    // PUT /api/students/{id}  → id で指定した生徒を更新する
    // -------------------------------------------------------
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id); // 見つからなければ自動で 404 を返す

        $request->validate([
            'name'  => 'sometimes|max:100',          // sometimes = 送られてきた場合だけチェック
            'score' => 'sometimes|integer|min:0|max:100',
        ]);

        $student->update($request->all()); // DB を更新

        return response()->json([
            'status' => 'ok',
            'data'   => $student,
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/students/{id}  → id で指定した生徒を削除する
    // -------------------------------------------------------
    public function destroy($id)
    {
        Student::findOrFail($id)->delete(); // 見つけて削除

        return response()->json([
            'status'  => 'ok',
            'message' => 'Deleted',
        ]);
    }
}