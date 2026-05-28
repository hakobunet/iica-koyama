# Python オブジェクト指向入門

## 今回のゴール
- 「クラス」と「インスタンス」の違いを説明できる
- `__init__` メソッドの役割を理解する
- クラスを使ってデータと処理をひとまとめにできる

---

## 1. オブジェクト指向とは？

プログラムを書くとき、**関係するデータと処理をひとまとめにする**考え方です。

### 比喩で理解する：「設計図」と「実物」

```
クラス（設計図）
  ┌─────────────────────┐
  │  タスク              │
  │                     │
  │  データ：            │
  │    - タイトル        │
  │    - 完了しているか  │
  │                     │
  │  できること：        │
  │    - 完了にする      │
  │    - 内容を表示する  │
  └─────────────────────┘
          ↓ この設計図をもとに実物を作る

インスタンス（実物）
  タスクA: "買い物に行く", 未完了
  タスクB: "宿題を終わらせる", 完了
  タスクC: "部屋を掃除する", 未完了
```

**クラス** = 設計図（1 つだけ存在する）
**インスタンス** = 設計図から作られた実物（いくつでも作れる）

---

## 2. クラスを書いてみる

### ステップ 1：最もシンプルなクラス

```python
# クラスの定義
class Task:
    pass  # まだ何もない
```

```python
# インスタンスを作る
task1 = Task()  # Task クラスからインスタンスを作成
task2 = Task()  # もう一つ作れる

print(task1)  # <__main__.Task object at 0x...>
print(task2)  # <__main__.Task object at 0x...> ← 別のインスタンス
```

---

### ステップ 2：データを持たせる（`__init__`）

`__init__` は **インスタンスを作るときに自動で呼ばれる特別なメソッド**です。
「コンストラクタ」とも呼ばれます。

```python
class Task:
    # インスタンスを作るときに自動で呼ばれる
    def __init__(self, title):
        # self = 「このインスタンス自身」を指す
        self.title = title       # タイトルを保存
        self.done = False        # 最初は未完了

# インスタンスを作る（このとき __init__ が呼ばれる）
task1 = Task("買い物に行く")
task2 = Task("宿題を終わらせる")

# インスタンスのデータにアクセスする
print(task1.title)  # 買い物に行く
print(task1.done)   # False
print(task2.title)  # 宿題を終わらせる
```

**ポイント：`self` とは？**
```
task1 = Task("買い物に行く")
          ↑
    このインスタンス（task1）が self になる

self.title = title
→ task1.title = "買い物に行く" と同じ意味
```

---

### ステップ 3：メソッドを追加する

メソッドとは、**クラスに定義した関数**のことです。

```python
class Task:
    def __init__(self, title):
        self.title = title
        self.done = False

    # 完了にするメソッド
    def complete(self):
        self.done = True
        print(f"「{self.title}」を完了にしました！")

    # 内容を表示するメソッド
    def show(self):
        status = "☑" if self.done else "□"
        print(f"{status} {self.title}")
```

```python
# 使ってみる
task1 = Task("買い物に行く")
task2 = Task("宿題を終わらせる")

task1.show()      # □ 買い物に行く
task2.show()      # □ 宿題を終わらせる

task2.complete()  # 「宿題を終わらせる」を完了にしました！

task1.show()      # □ 買い物に行く
task2.show()      # ☑ 宿題を終わらせる
```

---

## 3. クラスを使わない場合との比較

**クラスを使わない場合：**

```python
# データがバラバラで管理しにくい
title1 = "買い物に行く"
done1 = False

title2 = "宿題を終わらせる"
done2 = False

# 完了にする処理も別々に書く必要がある
done2 = True
print(f"「{title2}」を完了にしました！")

# タスクが増えると... title3, done3, title4, done4... きりがない
```

**クラスを使った場合：**

```python
# データと処理がひとまとめ！タスクが増えても管理しやすい
tasks = [
    Task("買い物に行く"),
    Task("宿題を終わらせる"),
    Task("部屋を掃除する"),
]

# 全部表示する
for task in tasks:
    task.show()
```

---

## 4. 今回のシステムで OOP を使う場所

OOP（オブジェクト指向プログラミング）は、今回のシステムの **2 つの重要な場所**で使います。

### ① データベースのテーブル定義（models.py）

```python
# テーブル = クラス、1行のデータ = インスタンス
class Task(Base):
    __tablename__ = "tasks"

    id = Column(Integer, primary_key=True)
    title = Column(String(200))
    done = Column(Boolean, default=False)
```

### ② API のデータ形式定義（schemas.py）

```python
# リクエスト/レスポンスのデータ形式をクラスで表現
class TaskCreate(BaseModel):
    title: str  # 受け取るデータ：タイトルだけ

class TaskResponse(BaseModel):
    id: int
    title: str
    done: bool  # 返すデータ：id・タイトル・完了フラグ
```

クラスを使うことで、**どんなデータを扱うのかが一目でわかる**コードになります。

---

## 5. 練習してみよう

以下のクラスを完成させてください。

```python
class Task:
    def __init__(self, title):
        self.title = title
        self.done = False

    def complete(self):
        # ここに「完了にする」処理を書く
        pass

    def show(self):
        # ここに「内容を表示する」処理を書く
        pass

# テスト
t = Task("テスト用タスク")
t.show()     # □ テスト用タスク  と表示されること
t.complete()
t.show()     # ☑ テスト用タスク  と表示されること
```

---

> 前のステップ → [01_MySQL環境構築.md](./01_MySQL環境構築.md)
> 次のステップ → [03_FastAPI入門.md](./03_FastAPI入門.md)
