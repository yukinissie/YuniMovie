<?php
// require 'password.php';   // password_hash()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
require_once('scripts/dbConfig.php');
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "root";  // ユーザー名
$db['pass'] = "root";  // ユーザー名のパスワード
$db['dbname'] = "yunimovie";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
  // 1. ユーザIDの入力チェック
  if (empty($_POST["username"])) {  // 値が空のとき
    $errorMessage = 'ユーザーIDが未入力です。';
  } else if (empty($_POST["password"])) {
    $errorMessage = 'パスワードが未入力です。';
  } else if (empty($_POST["password2"])) {
    $errorMessage = 'パスワードが未入力です。';
  }

  if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
    // 入力したユーザIDとパスワードを格納
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 2. ユーザIDとパスワードが入力されていたら認証する
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

    // 3. エラー処理
    try {
      $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

      $stmt = $pdo->prepare("INSERT INTO userData(name, password) VALUES (?, ?)");

      $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
      $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる
      mkdir(__DIR__ . "/movie/{$userid}", 0755, true);
      mkdir(__DIR__ . "/img/{$userid}", 0755, true);
      $signUpMessage = '登録が完了しました。';  // ログイン時に使用するID
    } catch (PDOException $e) {
      $errorMessage = 'データベースエラー';
      // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
      // echo $e->getMessage();
    }
  } else if($_POST["password"] != $_POST["password2"]) {
    $errorMessage = 'パスワードに誤りがあります。';
  }
}
?>

<?php require_once('scripts/templateEngine.php'); ?>
<!doctype html>
<html>
  <head>
    <?php
      $head_tpl = new TemplateEngine();
      $head_tpl->render('head.tpl');
    ?>
  </head>
  <body>
    <?php
      $header_tpl = new TemplateEngine(); 
      $header_tpl->render('header.tpl');
    ?>
    <div class="container">
      <h1>Create New Account</h1>
      <form id="loginForm" name="loginForm" action="" method="POST">
        <fieldset>
          <legend>Sign Up Form</legend>
          <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
          <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
          <label for="username">User Name</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>" class="form-control col-xs-12">
          <br>
          <label for="password">Password</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力" class="form-control col-xs-12">
          <br>
          <label for="password2">Password (Re type!)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力" class="form-control col-xs-12">
          <br>
          <input type="submit" id="signUp" name="signUp" value="Sign Up!" class="btn btn-primary float-right">
        </fieldset>
      </form>
      <br>
      <p>アカウントをお持ちですか？</p>
      <p><a href="signIn.php">サインインはこちら</a></p>
    </div>
    <?php 
      $bootstrap_javascript_tpl = new TemplateEngine;
      $bootstrap_javascript_tpl->render('bootstrapJavaScript.tpl');
    ?>
  </body>
</html>