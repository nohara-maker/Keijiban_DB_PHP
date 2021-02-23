<?php
// DB接続設定
$dsn ='****';//データベース名
$user = '****';//ユーザー名
$password = '****';//パスワード
$pdo = new PDO($dsn, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS posts"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date char(32),"
	. "password char(16)"
	.");";
	$stmt = $pdo->query($sql);

//POST受信
$get_name = $_POST["name"];
$get_comment = $_POST["comment"];
$get_date = date("Y年m月d日 H:i:s");
$get_password = $_POST["password"];
$edit_n = $_POST["edit_n"];
//投稿処理
if($get_name && $get_comment &&
$get_password && !$edit_n) {
    $sql = $pdo -> prepare(
    "INSERT INTO posts (name, comment, date, password)
    VALUES (:name, :comment, :date, :password)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $name = "$get_name";
    $comment = "$get_comment";
    $date = "$get_date";
    $password = "$get_password";
    $sql -> execute();
}

//削除処理
$delete = $_POST["delete"];
$delete_password = $_POST["delete_password"];

if($delete && $delete_password){
    $id = (int)$delete;
    $password = (string)$delete_password;
    $sql = 'delete from posts where id=:id AND password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
}

//データ編集
$edit = $_POST["edit"];
$edit_password = $_POST["edit_password"];
//編集番号取得
if($edit && $edit_password){
    $id = (int)$edit;
    $password = (string)$edit_password;
    $stmt = $pdo->prepare("SELECT * FROM posts 
    WHERE id=:id AND password=:password");
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
    $stmt->bindParam(':password',$password,PDO::PARAM_STR);
    $stmt->execute();
    $element = $stmt->fetch();
    $edName = $element['name'];
    $edComment = $element['comment'];
    $edit_n = $element['id'];
}

//編集機能
if($get_name && $get_comment && $edit_n){
    $id = (int)$edit_n;
    $name = (string)$get_name;
    $comment = (string)$get_comment;
    $date = (string)$get_date;
    $sql = 'UPDATE posts SET name=:name,
    comment=:comment, date=:date WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $edit_n = null;
}

//データレコードの表示
$sql = 'SELECT * FROM posts';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-36</title>
</head>
<body>
<form action="" method="post">
    名前:
    <input type="text" name="name" value="<?php echo $edName;?>">
    <br>
    コメント:
    <input type="text" name="comment" value="<?php echo $edComment;?>">
    <br>
    パスワード:
    <input type="text" name="password">
    <input type="submit" name="submit">
    <br>
    <input type="text" name="edit_n" value="<?php echo $edit_n;?>">
</form>
<hr>
<form action="" method="post">
    削除番号:
    <input type="text" name="delete">
    <br>
    パスワード:
    <input type="text" name="delete_password">
    <input type="submit" name="submit">
</form>
<hr>
<form action="" method="post">
    編集番号:
    <input type="text" name="edit">
    <br>
    パスワード:
    <input type="text" name="edit_password">
    <input type="submit" name="submit">
</form>
<hr>
<?php
foreach ($results as $row){
	echo $row['id'].'.'.$row['name'].' '.$row['comment'].' '.
	$row['date'].'<br>';
}
?>
</body>
</html>