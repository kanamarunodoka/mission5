<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5</title>
</head>
<body>
    <h1>簡易掲示板</br></h1>
    
<?php

	// DB接続設定

    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>
 <?php
    $sql = "CREATE TABLE IF NOT EXISTS tbtb"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date char(32),"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);
?>



 <?php


   
    if(!empty($_POST["edit"])&&!empty($_POST["pass_edit"])){
        
        $id = $_POST["edit"] ; // idがこの値のデータだけを抽出したい、とする
        $sql = 'SELECT * FROM tbtb WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll();
        foreach ($results as $row){
		    if($_POST['pass_edit']==$row['pass']){
                
                $editname=$row['name'];
                $editcomment=$row['comment'];
                $editnumber=$_POST["edit"];
                $oldpass=$_POST["pass_edit"];
            }
	    }
        
    }
    
//投稿機能+編集機能
    if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])){
        if(!empty($_POST["editNo"])){
            
            $editNo=$_POST['editNo'];
            
            $id = $editNo; //変更する投稿番号
	        $name = $_POST["name"];
	        $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	        $pass = $_POST["pass"];
	        $date = date("Y年m月d日 H時i分s秒");
	        
	        $sql = 'UPDATE tbtb SET name=:name,comment=:comment,pass=:pass,
	        date=:date WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            
             //編集機能    
        
        }else{
            
            $sql = $pdo -> prepare("INSERT INTO 
            tbtb (name, comment, date, pass) 
            VALUES (:name, :comment, :date, :pass)");
            
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $name = $_POST["name"];
	        $comment = $_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
	        $date = date("Y年m月d日 H時i分s秒");
	        $pass=$_POST["pass"];
            $sql -> execute();

        }
    }

   
  
	
   //削除機能
    if(!empty($_POST["delete"])&&!empty($_POST["pass_delete"])){
        $id = $_POST["delete"] ; 
        $sql = 'SELECT * FROM tbtb WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll();
        foreach ($results as $row){
		    if($_POST["pass_delete"]==$row['pass']){
            $id = $_POST["delete"];
        	$sql = 'delete from tbtb where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
     
            }
	    }
    }
      
     
   
   ?>
    <form action="" method="post">
        名前<input type="text" name="name" value="<?php if(!empty($_POST["edit"])){echo $editname;}?>"><br>
        コメント<input type="text" name="comment" value="<?php if(!empty($_POST["edit"])){echo $editcomment;}?>"><br>
        パスワード<input type="text" name="pass" value="<?php if(!empty($_POST["edit"])){echo $oldpass;}?>"> <br>
        <input type="hidden" name="editNo" value="<?php if(!empty($_POST["edit"])){echo $editnumber;}?>"
        >
   <input type="submit" name="送信"><br><br>
   </form>
   <form action="" method="post">
        削除番号<input type="number" name="delete">
        パスワード<input type="text" name="pass_delete">
       <input type="submit" value="削除">
       </form>
        <form action="" method="post">
        編集番号<input type="number" name="edit">
        パスワード<input type="text" name="pass_edit">
        <input type="submit" value="編集">
        
    </form>

<?php
   $sql = 'SELECT * FROM tbtb';
   $stmt = $pdo->query($sql);
   $results = $stmt->fetchAll();
   foreach ($results as $row){
       //$rowの中にはテーブルのカラム名が入る
       echo $row['id'].',';
       echo $row['name'].',';
       echo $row['comment'].',';
       echo $row['date'].'<br>';
   echo "<hr>";
   }
?> 
  </body>
</html>