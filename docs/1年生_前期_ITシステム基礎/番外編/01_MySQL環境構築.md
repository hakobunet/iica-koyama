# MySQL 環境構築

## 今回のゴール
- 自分の OS に合わせて MySQL を起動できる
- `todo_app` データベースを作成できる
- Python から MySQL に接続するための情報（接続文字列）がわかる

---

## OS によって手順が異なります

| OS | 使うツール | 特徴 |
|----|-----------|------|
| Windows | XAMPP | GUI で操作できて初心者向け |
| Mac | Homebrew + MySQL | ターミナルで操作する標準的な方法 |

---

## Windows — XAMPP を使う

### 1. XAMPP をダウンロード・インストール

1. [https://www.apachefriends.org/jp/index.html](https://www.apachefriends.org/jp/index.html) を開く
2. 「Windows 向け XAMPP」をダウンロード
3. インストーラーを実行（インストール先はデフォルト `C:\xampp` でOK）

### 2. MySQL を起動する

1. スタートメニューから「XAMPP Control Panel」を起動
2. `MySQL` の行の「Start」ボタンをクリック
3. 緑色になれば起動成功

```
┌─────────────────────────────────────┐
│  XAMPP Control Panel                │
│                                     │
│  Apache  [Start] [Admin] ...        │
│  MySQL   [Start] [Admin] ...  ← ここ│
│  ...                                │
└─────────────────────────────────────┘
         ↓ Start を押す

│  MySQL   [Stop]  [Admin] ...  ← 緑になればOK
```

### 3. データベースを作成する（phpMyAdmin）

1. ブラウザで `http://localhost/phpmyadmin` を開く
2. 左メニュー上部の「新規作成」をクリック
3. データベース名に `todo_app` と入力
4. 照合順序（文字コード）は `utf8mb4_unicode_ci` を選択
5. 「作成」ボタンをクリック

```
phpMyAdmin 画面
┌─────────────────────────────────────────────┐
│  新しいデータベースを作成する:               │
│  [todo_app        ] [utf8mb4_unicode_ci ▼]  │
│                                   [作成]    │
└─────────────────────────────────────────────┘
```

### 4. 接続情報の確認

XAMPP のデフォルト設定では以下になります。

| 項目 | 値 |
|------|-----|
| ホスト | localhost |
| ユーザー | root |
| パスワード | （空欄） |
| DB 名 | todo_app |

**`database.py` の接続文字列（Windows の場合）：**

```python
DATABASE_URL = "mysql+pymysql://root:@localhost/todo_app"
#                                   ↑ パスワードなし（コロンの後ろが空）
```

---

## Mac — Homebrew + MySQL を使う

### 1. Homebrew をインストール（未インストールの場合）

ターミナルを開いて以下を実行：

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

インストール確認：

```bash
brew --version
# Homebrew 4.x.x などと表示されればOK
```

### 2. MySQL をインストール

```bash
brew install mysql
```

### 3. MySQL を起動する

```bash
# MySQL を起動
brew services start mysql

# 起動確認
brew services list
# mysql  started  ... と表示されればOK
```

### 4. 初期設定（パスワード設定）

```bash
mysql_secure_installation
```

質問に答えていきます：

```
VALIDATE PASSWORD component: No（パスワード強度チェック。初回は n でOK）
New password: （任意のパスワードを入力 → 忘れないようにメモ！）
Re-enter new password: （同じパスワードを再入力）
Remove anonymous users: Yes（y を入力）
Disallow root login remotely: Yes（y を入力）
Remove test database: Yes（y を入力）
Reload privilege tables: Yes（y を入力）
```

### 5. データベースを作成する

```bash
# MySQL にログイン（設定したパスワードを入力）
mysql -u root -p

# ログイン成功すると以下のプロンプトが表示される
# mysql>
```

MySQL のプロンプトで以下を実行：

```sql
-- データベースを作成
CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 作成確認
SHOW DATABASES;
-- todo_app が表示されればOK

-- MySQL から抜ける
EXIT;
```

### 6. 接続情報の確認

| 項目 | 値 |
|------|-----|
| ホスト | localhost |
| ユーザー | root |
| パスワード | 手順 4 で設定したもの |
| DB 名 | todo_app |

**`database.py` の接続文字列（Mac の場合）：**

```python
DATABASE_URL = "mysql+pymysql://root:あなたのパスワード@localhost/todo_app"
#                                   ↑ 手順4で設定したパスワードに置き換える
```

---

## MySQL の基本操作コマンド（参考）

| 操作 | Windows（XAMPP Shell） | Mac（ターミナル） |
|------|----------------------|----------------|
| MySQL ログイン | XAMPP Control Panel → Shell → `mysql -u root` | `mysql -u root -p` |
| データベース一覧 | `SHOW DATABASES;` | `SHOW DATABASES;` |
| テーブル一覧 | `USE todo_app; SHOW TABLES;` | `USE todo_app; SHOW TABLES;` |
| MySQL 停止 | Control Panel → Stop | `brew services stop mysql` |

---

## トラブルシューティング

**「接続できない」と表示される場合**

- Windows: XAMPP Control Panel で MySQL が「Running（緑）」になっているか確認
- Mac: `brew services list` で mysql が `started` になっているか確認

**「Access denied for user 'root'」と表示される場合**

- Windows: `database.py` のパスワードが空になっているか確認（`root:@localhost`）
- Mac: パスワードが正しく入力されているか確認

---

> 前のステップ → [00_概要とシステム設計.md](./00_概要とシステム設計.md)
> 次のステップ → [02_Python_OOP入門.md](./02_Python_OOP入門.md)
