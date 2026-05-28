# CRUD API の実装

## 今回のゴール
- Pydantic スキーマクラスでデータ形式を定義できる
- GET / POST / PUT / DELETE の 4 つの API を実装できる
- Swagger UI で各 API の動作を確認できる

---

## 1. CRUD とは？

**CRUD** = データ操作の 4 基本パターンの頭文字

| 文字 | 操作 | SQL | HTTP メソッド |
|------|------|-----|-------------|
| C | Create（作成） | INSERT | POST |
| R | Read（取得） | SELECT | GET |
| U | Update（更新） | UPDATE | PUT |
| D | Delete（削除） | DELETE | DELETE |

---

## 2. schemas.py — API のデータ形式を定義する

**Pydantic（パイダンティック）** は、クラスを使ってデータの形式を定義するライブラリです。
FastAPI と組み合わせると、**リクエスト/レスポンスのデータを自動で検証**してくれます。

```python
# schemas.py

from pydantic import BaseModel

# タスク作成時に受け取るデータ
class TaskCreate(BaseModel):
    title: str  # タイトルだけ受け取る（id や done はサーバーが決める）

# API のレスポンスで返すデータ
class TaskResponse(BaseModel):
    id: int
    title: str
    done: bool

    # SQLAlchemy のモデルオブジェクトをそのまま渡せるようにする設定
    class Config:
        from_attributes = True
```

### なぜスキーマを別ファイルに書くのか？

```
models.py  → データベースの形（どう保存するか）
schemas.py → API の形（どのデータを受け取り/返すか）

例：タスク作成時
  受け取る（TaskCreate）: title だけ
  返す（TaskResponse）:   id + title + done
  保存（Task モデル）:    id + title + done（id は自動採番）
```

---

## 3. routers/tasks.py — 4 つの API を実装する

```python
# routers/tasks.py

from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from database import get_db
from models import Task
from schemas import TaskCreate, TaskResponse

# ルーター = URL の集まり（ここでは /tasks に関する API をまとめる）
router = APIRouter(prefix="/tasks", tags=["tasks"])
```

---

### R — タスク一覧取得（GET /tasks）

```python
# ⚠️ "/" ではなく "" にする（"/" にすると /tasks → /tasks/ へリダイレクトが発生する）
@router.get("", response_model=list[TaskResponse])
def get_tasks(db: Session = Depends(get_db)):
    """全タスクを取得して返す"""
    tasks = db.query(Task).all()  # SELECT * FROM tasks
    return tasks
```

**`Depends(get_db)` とは？**
```
db: Session = Depends(get_db)
↑
「get_db 関数の結果（DB セッション）を db に渡してください」
という意味。API が呼ばれるたびに DB への接続を準備してくれる。
```

---

### C — タスク作成（POST /tasks）

```python
@router.post("", response_model=TaskResponse)
def create_task(task_in: TaskCreate, db: Session = Depends(get_db)):
    """新しいタスクを作成する"""
    # 受け取ったデータ（TaskCreate）を Task モデルのインスタンスに変換
    new_task = Task(title=task_in.title)

    db.add(new_task)    # DB に追加する準備
    db.commit()         # DB に保存（INSERT 実行）
    db.refresh(new_task)  # DB が自動で設定した id などを取得
    return new_task
```

---

### U — 完了切り替え（PUT /tasks/{task_id}）

```python
@router.put("/{task_id}", response_model=TaskResponse)
def toggle_task(task_id: int, db: Session = Depends(get_db)):
    """タスクの完了/未完了を切り替える"""
    # 対象のタスクを取得
    task = db.query(Task).filter(Task.id == task_id).first()

    # タスクが見つからない場合は 404 エラー
    if task is None:
        raise HTTPException(status_code=404, detail="タスクが見つかりません")

    # done を反転させる（True → False、False → True）
    task.done = not task.done

    db.commit()         # DB に保存（UPDATE 実行）
    db.refresh(task)
    return task
```

---

### D — タスク削除（DELETE /tasks/{task_id}）

```python
@router.delete("/{task_id}")
def delete_task(task_id: int, db: Session = Depends(get_db)):
    """タスクを削除する"""
    task = db.query(Task).filter(Task.id == task_id).first()

    if task is None:
        raise HTTPException(status_code=404, detail="タスクが見つかりません")

    db.delete(task)     # 削除する準備
    db.commit()         # DB から削除（DELETE 実行）
    return {"message": f"タスク '{task.title}' を削除しました"}
```

---

## 4. main.py にルーターを登録する

```python
# main.py（完成版）

from fastapi import FastAPI
from fastapi.staticfiles import StaticFiles
from fastapi.responses import FileResponse
from database import engine, Base
import models
from routers import tasks  # ← 追加

# テーブルを自動作成
Base.metadata.create_all(bind=engine)

app = FastAPI()

# ルーターを登録（/tasks 以下の API が有効になる）
app.include_router(tasks.router)  # ← 追加

app.mount("/static", StaticFiles(directory="static"), name="static")

@app.get("/")
def read_index():
    return FileResponse("static/index.html")
```

---

## 5. ファイル構成（現時点）

```
my_todo_app/
├── main.py         ← ルーター登録を追加済み
├── database.py     ← DB 接続設定
├── models.py       ← Task テーブル定義
├── schemas.py      ← ★ 今回作成：データ形式定義
├── routers/
│   ├── __init__.py ← 空ファイル（フォルダを Python パッケージとして認識させる）
│   └── tasks.py    ← ★ 今回作成：CRUD API
└── static/
    └── index.html  ← 次のステップで作成
```

`routers/__init__.py` は空ファイルでOKです：

**Windows:**
```cmd
type nul > routers\__init__.py
```

**Mac:**
```bash
touch routers/__init__.py
```

---

## 6. Swagger UI で動作確認

**Windows / Mac 共通：**
```bash
uvicorn main:app --reload
```

`http://localhost:8000/docs` を開いて確認する。

**確認手順（POST → GET → PUT → DELETE の順）：**

1. **POST /tasks** をクリック → `Try it out` → Request body に入力：
   ```json
   {"title": "買い物に行く"}
   ```
   → `Execute` → Response に `{"id": 1, "title": "買い物に行く", "done": false}` が返ること

2. **GET /tasks** をクリック → `Try it out` → `Execute`
   → 作成したタスクが一覧で返ること

3. **PUT /tasks/{task_id}** をクリック → `task_id` に `1` を入力 → `Execute`
   → `"done": true` に変わること

4. **DELETE /tasks/{task_id}** をクリック → `task_id` に `1` を入力 → `Execute`
   → 削除メッセージが返ること

---

> 前のステップ → [04_データベース連携.md](./04_データベース連携.md)
> 次のステップ → [06_フロントエンド連携.md](./06_フロントエンド連携.md)
