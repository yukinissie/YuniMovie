<?php
require_once('dbConfig.php');

class User
{
  private $name;

  public function __construct($name) {
    $this->name = $name;
  }
  public function getName() {
    return $this->name;
  }
}

class UserManager
{
  public function signUp() {
    //データベースへ接続、テーブルがない場合は作成
    try {
      $pdo = new PDO(DSN, DB_USER, DB_PASS);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->exec("create table if not exists userDeta(
          id int not null auto_increment primary key,
          email varchar(255),
          password varchar(255),
          created timestamp not null default current_timestamp
        )");
    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    }


    //POSTのValidate。
    if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      echo '入力された値が不正です。';
      return false;
    }
    //パスワードの正規表現
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
      echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
      return false;
    }
    //DB内のメールアドレスを取得
    $stmt = $pdo->prepare("select email from userDeta where email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //DB内のメールアドレスと重複していない場合、登録する。
    if (!isset($row['email'])) {
      $stmt = $pdo->prepare("insert into userDeta(email, password) value(?, ?)");
      $stmt->execute([$email, $password]);
      echo "登録完了";
    } else {
      echo '既に登録されたメールアドレスです。';
      return false;
    }
  }
  public function delete() {

  }
}