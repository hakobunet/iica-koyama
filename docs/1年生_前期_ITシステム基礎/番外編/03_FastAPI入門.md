# FastAPI 入門

## 今回のゴール
- FastAPI でシンプルな API サーバーを起動できる
- `@app.get` などのデコレータの意味を理解する
- Swagger UI でブラウザから API をテストできる

---

## 1. FastAPI とは？

**FastAPI = Python で API サーバーを作るためのフレームワーク**

フレームワークとは、「よく使う機能がまとまった便利な道具箱」です。

```
あなたが書くコード（main.py）
    ↓ FastAPI が読み込む
FastAPI（フレームワーク）
    ↓ uvicorn が動かす
uvicorn（サーバー）
    ↓ HTTP でやり取り
ブラウザ / フロントエンド
```

---

## 2. Hello World を作る

まず最もシンプルな API サーバーを作ってみましょう。

### main.py

```python
from fastapi import FastAPI

# FastAPI のインスタンスを作る（アプリの本体）
app = FastAPI()

# "/" にアクセスしたときの処理
@app.get("/")
def read_root():
    return {"message": "Hello, World!"}
```

### 起動する

**Windows / Mac 共通：**
```bash
uvicorn main:app --reload
```

```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

ブラウザで `http://localhost:8000` を開くと：
```json
{"message": "Hello, World!"}
```

---

## 3. デコレータを理解する

コードに出てきた `@app.get("/")` を **デコレータ** といいます。

```python
@app.get("/")          # ← デコレータ
def read_root():       # ← この関数に「/ への GET リクエストが来たら呼ぶ」という設定をしている
    return {...}
```

### HTTP メソッドとデコレータの対応

| やりたいこと | HTTP メソッド | デコレータ |
|------------|-------------|-----------|
| データを取得する | GET | `@app.get(...)` |
| データを新規作成する | POST | `@app.post(...)` |
| データを更新する | PUT | `@app.put(...)` |
| データを削除する | DELETE | `@app.delete(...)` |

---

## 4. パスパラメータ（URL に値を含める）

```python
@app.get("/tasks/{task_id}")
def get_task(task_id: int):  # URL の {task_id} が引数として渡ってくる
    return {"task_id": task_id, "title": "サンプルタスク"}
```

`http://localhost:8000/tasks/3` にアクセスすると：
```json
{"task_id": 3, "title": "サンプルタスク"}
```

---

## 5. Swagger UI — API のテスト画面

FastAPI の便利な機能のひとつが **Swagger UI** です。
コードを書くだけで、**ブラウザで API をテストできる画面が自動生成**されます。

`http://localhost:8000/docs` を開く：

```
┌────────────────────────────────────────────┐
│  FastAPI                                   │
│                                            │
│  GET  /        Hello World を返す          │
│  ▼ Try it out                              │
│    [Execute]                               │
│                                            │
│  Response:                                 │
│  {"message": "Hello, World!"}              │
└────────────────────────────────────────────┘
```

**開発中は Swagger UI で動作確認すると便利です。**

---

## 6. タスク管理 API の骨格を作る

実際のシステムに向けて、タスク管理 API の骨格を作ります。

```python
# main.py
from fastapi import FastAPI
from fastapi.staticfiles import StaticFiles

app = FastAPI()

# static フォルダの HTML ファイルを配信する設定
app.mount("/static", StaticFiles(directory="static"), name="static")

# ルート（/）にアクセスしたら index.html を表示する
from fastapi.responses import FileResponse

@app.get("/")
def read_index():
    return FileResponse("static/index.html")


# --- タスク関連の API（後のステップで完成させる） ---

@app.get("/tasks")
def get_tasks():
    # タスクの一覧を返す（後でDBと連携）
    return []

@app.post("/tasks")
def create_task():
    # タスクを作成する（後でDBと連携）
    return {"message": "タスクを作成しました"}

@app.put("/tasks/{task_id}")
def toggle_task(task_id: int):
    # タスクの完了/未完了を切り替える（後でDBと連携）
    return {"message": f"タスク {task_id} を更新しました"}

@app.delete("/tasks/{task_id}")
def delete_task(task_id: int):
    # タスクを削除する（後でDBと連携）
    return {"message": f"タスク {task_id} を削除しました"}
```

### 起動して確認

**Windows / Mac 共通：**
```bash
uvicorn main:app --reload
```

`http://localhost:8000/docs` を開いて、4 つのエンドポイントが表示されることを確認しましょう。

```
GET    /tasks      タスク一覧取得
POST   /tasks      タスク作成
PUT    /tasks/{id} 完了切り替え
DELETE /tasks/{id} タスク削除
```

---

## 7. ここまでのポイント整理

```
① FastAPI のインスタンスを作る
   app = FastAPI()

② デコレータで URL とメソッドを指定する
   @app.get("/tasks")

③ その下の関数が実際の処理になる
   def get_tasks():
       return [...]

④ 関数が return した値が JSON としてレスポンスになる
   return [{"id": 1, "title": "買い物"}]
   → ブラウザに [{"id": 1, "title": "買い物"}] が返る
```

---

> 前のステップ → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
> 次のステップ → [04_データベース連携.md](./04_データベース連携.md)
