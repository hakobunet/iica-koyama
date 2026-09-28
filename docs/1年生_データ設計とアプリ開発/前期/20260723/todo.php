<?php
// データベース(school_db)に接続する
// PDOはPHPからデータベースを操作するための仕組み(クラス)
$pdo = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=school_db;charset=utf8mb4',
    'root',
    '' // XAMPP/DBngin のデフォルトは空パスワード
);

// todosテーブルがまだ存在しない場合だけ作成する
// IF NOT EXISTS を付けておくと、既にテーブルがあってもエラーにならず毎回安全に実行できる
$pdo->exec(
    "CREATE TABLE IF NOT EXISTS todos (
        id INT AUTO_INCREMENT PRIMARY KEY,      -- 各TODOを区別するための番号(自動で増える)
        title VARCHAR(255) NOT NULL,            -- TODOの内容(タイトル)
        is_done TINYINT(1) NOT NULL DEFAULT 0,  -- 完了なら1、未完了なら0
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- 作成日時(自動で記録される)
    )"
);

// フォームが送信された(POSTでアクセスされた)ときの処理
// このアプリでは「追加・編集・完了切替・削除」の4つの操作をすべてここで受け取る
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームのhiddenフィールド(action)で、どの操作をしたいかを判定する
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        // ---- 新規追加(Create) ----
        // 入力された文字列の前後の余分な空白を取り除く
        $title = trim($_POST['title'] ?? '');
        // 空文字(何も入力されていない)場合は登録しない
        if ($title !== '') {
            // ★SQLインジェクション対策として、値を直接SQL文に埋め込まず
            //   「:title」というプレースホルダを使い、あとから安全に値を渡す(プリペアドステートメント)
            $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
            $stmt->execute(['title' => $title]);
        }
    } elseif ($action === 'update') {
        // ---- 編集内容の保存(Update) ----
        // (int)を付けて数値に変換することで、意図しない文字列が紛れ込むのを防ぐ
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        if ($id > 0 && $title !== '') {
            $stmt = $pdo->prepare("UPDATE todos SET title = :title WHERE id = :id");
            $stmt->execute(['title' => $title, 'id' => $id]);
        }
    } elseif ($action === 'toggle') {
        // ---- 完了/未完了の切り替え(Update) ----
        $id = (int)($_POST['id'] ?? 0);
        // チェックボックスは「チェックが入っている時だけ」POSTデータに含まれる仕組みになっている
        // なので isset() で「送られてきたかどうか」を見て 1(完了) か 0(未完了) を決める
        $isDone = isset($_POST['is_done']) ? 1 : 0;
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE todos SET is_done = :is_done WHERE id = :id");
            $stmt->execute(['is_done' => $isDone, 'id' => $id]);
        }
    } elseif ($action === 'delete') {
        // ---- 削除(Delete) ----
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM todos WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
    }

    // 処理が終わったら同じページ(index.php)に再度アクセスし直させる
    // こうすることで、ブラウザの「再読み込み」をしても二重登録・二重削除が起きなくなる
    // (この仕組みを PRG: Post/Redirect/Get パターンと呼ぶ)
    header('Location: index.php');
    exit;
}

// URLに ?edit=数字 が付いていたら、その番号のTODOを編集モードで表示する
// 例: index.php?edit=3 なら id=3 の行だけ入力フォームになる
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

// ---- 一覧表示(Read) ----
// 新しく作ったTODOが上に来るように、作成日時の新しい順に並べ替えて全件取得する
$stmt = $pdo->query("SELECT * FROM todos ORDER BY created_at DESC");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>TODOアプリ</title>
<style>
    body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
    h1 { font-size: 1.4em; }
    ul { list-style: none; padding: 0; }
    li { display: flex; align-items: center; gap: 8px; padding: 6px 0; border-bottom: 1px solid #ddd; }
    li .title { flex: 1; }
    li.done .title { text-decoration: line-through; color: #999; }
    form.inline { display: contents; }
    input[type="text"] { flex: 1; }
    button { cursor: pointer; }
</style>
</head>
<body>

<h1>TODOアプリ</h1>

<!-- 新規追加フォーム: 送信するとaction=createとしてPHP側に届く -->
<form method="post" action="index.php">
    <input type="hidden" name="action" value="create">
    <input type="text" name="title" placeholder="新しいTODOを入力" required>
    <button type="submit">追加</button>
</form>

<ul>
<?php foreach ($todos as $todo): // 取得したTODOを1件ずつ順番に表示していく ?>
    <!-- 完了済み(is_doneが1)なら"done"クラスを付けて、CSSで取り消し線を表示する -->
    <li class="<?= $todo['is_done'] ? 'done' : '' ?>">
<?php if ((int)$todo['id'] === $editId): // URLの?edit=id と一致する行だけ編集フォームを表示 ?>
        <!-- 編集モード: 入力欄とタイトルを書き換えて保存する -->
        <form class="inline" method="post" action="index.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
            <!-- htmlspecialcharsで<や>などの特殊文字を無害化し、XSS(悪意あるスクリプト混入)を防ぐ -->
            <input type="text" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>
            <button type="submit">保存</button>
        </form>
        <a href="index.php">キャンセル</a>
<?php else: // 通常モード(一覧表示) ?>
        <!-- 完了チェックボックス: チェックを変えた瞬間にonchangeでフォームを自動送信する -->
        <form class="inline" method="post" action="index.php">
            <input type="hidden" name="action" value="toggle">
            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
            <input type="checkbox" name="is_done" onchange="this.form.submit()" <?= $todo['is_done'] ? 'checked' : '' ?>>
        </form>
        <span class="title"><?= htmlspecialchars($todo['title']) ?></span>
        <!-- 「編集」を押すと ?edit=id 付きでこのページに再アクセスし、上のif文がtrueになる -->
        <a href="?edit=<?= $todo['id'] ?>">編集</a>
        <!-- 削除フォーム: confirm()で確認ダイアログを出し、OKが押されたときだけ送信される -->
        <form class="inline" method="post" action="index.php" onsubmit="return confirm('削除しますか？');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
            <button type="submit">削除</button>
        </form>
<?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
