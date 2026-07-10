# PHP開発サーバーの立て方

PHPファイルはブラウザで直接開いても実行されない（ソースがそのまま表示される/ダウンロードされる）。
PHPはサーバー側で解釈される言語なので、必ず `php -S` などのサーバー経由でアクセスする。

## 前提

- PHPがインストール済みであること（`php -v` で確認）
- ここでは `php` に組み込まれている開発用サーバーを使う（本番運用には向かない）
- 以下のコマンド中の `<ユーザー名>` は、自分のパソコンのユーザー名に置き換えること（例: `taro`, `hanako` など。Macなら `whoami` コマンドで確認できる）

## 方法1: studyディレクトリ直下で起動する

```bash
cd /Users/<ユーザー名>/Desktop/study
php -S localhost:8000
```

`cd`（change directory）はターミナルの「今いる場所（カレントディレクトリ）」を移動するコマンド。
`cd /Users/<ユーザー名>/Desktop/study` で `study` ディレクトリに移動してから `php -S` を実行することで、
`study` が起動場所＝Webのルートになる。

`php -S` を実行したディレクトリが「Webのルート（`/`）」になる。
`study/` 直下に `contact.php` があるので、URLはそのまま：

```
http://localhost:8000/contact.php
http://localhost:8000/1-3.php
http://localhost:8000/index.php
```

## 章ごとのディレクトリ構成でアクセスする

演習が増えてきたら、章ごとにサブディレクトリを切って整理する（例: `01-basics/`）。

```
study/
  index.php
  contact.php
  01-basics/
    1-1.php
    1-2.php
    1-3.php
  02-xxx/
    2-1.php
```

サーバーは引き続き **studyディレクトリ直下**で起動する（起動場所は変えない）。

```bash
cd /Users/<ユーザー名>/Desktop/study
php -S localhost:8000
```

起動ディレクトリがWebのルートになるため、サブディレクトリに入れたファイルは
**URLにそのディレクトリ名を含める**必要がある：

```
http://localhost:8000/index.php
http://localhost:8000/01-basics/1-3.php
http://localhost:8000/02-xxx/2-1.php
```

つまり「ファイルを置いたパス」＝「URLのパス」（`study/`からの相対パス）とそのまま対応する。

## サーバーの停止

サーバーを起動したターミナルで `Ctrl + C`。

## ポート番号について

`8000` の部分は空いていれば任意の番号を指定できる（例: `php -S localhost:8080`）。
