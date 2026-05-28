# タスク管理アプリ — 起動手順

番外編の検証用サンプルシステムです。

## 前提条件

- Python 3.x がインストール済み
- MySQL が起動済み（Mac: `brew services start mysql`）
- データベース `todo_app` が作成済み（下記参照）

---

## 初回セットアップ

### 1. データベースを作成する

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Python パッケージをインストールする

#### Mac

Mac では `pip3 install` を直接実行するとエラーになります。
**仮想環境（venv）** を使ってインストールしてください。

```bash
# todo_app フォルダ内で実行
python3 -m venv venv

# 仮想環境を有効化
source venv/bin/activate

# 有効化後にインストール（プロンプトに (venv) が付いていればOK）
pip install fastapi uvicorn sqlalchemy pymysql
```

#### Windows

Windows では仮想環境なしで直接インストールできます。

```cmd
pip install fastapi uvicorn sqlalchemy pymysql
```

仮想環境を使いたい場合（推奨）：

```cmd
python -m venv venv
venv\Scripts\activate
pip install fastapi uvicorn sqlalchemy pymysql
```

---

## サーバーの起動

#### Mac

**ターミナルを開くたびに仮想環境の有効化が必要です。**

```bash
# ① todo_app フォルダに移動
cd /path/to/todo_app

# ② 仮想環境を有効化
source venv/bin/activate

# ③ サーバー起動
uvicorn main:app --reload
```

#### Windows

```cmd
rem ① todo_app フォルダに移動
cd \path\to\todo_app

rem ② サーバー起動（仮想環境を使う場合は先に venv\Scripts\activate を実行）
uvicorn main:app --reload
```

起動成功時の表示：
```
INFO:     Uvicorn running on http://127.0.0.1:8000
INFO:     Application startup complete.
```

**④ ブラウザで以下の URL を開く**

| URL | 内容 |
|-----|------|
| http://localhost:8000 | タスク管理アプリ（画面） |
| http://localhost:8000/docs | Swagger UI（API テスト画面） |

> サーバーを**起動したままにした状態**でブラウザを開いてください。
> ターミナルを閉じるとサーバーが止まり、画面も表示されなくなります。

---

## トラブルシューティング

**`ERROR: [Errno 48] Address already in use` と表示される場合**

ポート 8000 が他のプロセスに使われています。以下で解決できます。

```bash
# 方法①: 使用中のプロセスを終了する
pkill -f uvicorn

# 方法②: 別のポートで起動する
uvicorn main:app --reload --port 8001
# → http://localhost:8001 でアクセス
```

---

## ファイル構成

```
todo_app/
├── main.py          起動ファイル
├── database.py      DB接続設定
├── models.py        テーブル定義
├── schemas.py       データ形式定義
├── routers/
│   └── tasks.py     CRUD API
└── static/
    └── index.html   フロントエンド画面
```

---

## データベース接続設定の変更

`database.py` の接続文字列を環境に合わせて変更してください。

```python
# Mac（パスワードなし）
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"

# Mac（パスワードあり）
DATABASE_URL = "mysql+pymysql://root:あなたのパスワード@localhost/todo_app"

# Windows（XAMPP）
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
```

---

## 詳細な解説資料

`../番外編/` フォルダの資料を参照してください。

| ファイル | 内容 |
|---------|------|
| 00_概要とシステム設計.md | システム全体の説明 |
| 01_MySQL環境構築.md | MySQL の構築手順 |
| 02_Python_OOP入門.md | クラス・OOP の解説 |
| 03_FastAPI入門.md | FastAPI の基本 |
| 04_データベース連携.md | SQLAlchemy の解説 |
| 05_CRUD_API実装.md | API 実装の解説 |
| 06_フロントエンド連携.md | HTML/JS の解説 |
