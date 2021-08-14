<?php
$dsn='データベース名';
$user = 'ユーザー名';
$password = 'パスワード名';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
/* テーブル作成*/
$sql = "CREATE TABLE IF NOT EXISTS tb5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT"
    .");";
$stmt = $pdo->query($sql);

/*パスワード用*/
$sql2 = "CREATE TABLE IF NOT EXISTS tbp"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "pass TEXT"
    .");";
$stmt = $pdo->query($sql2);

$sql = 'SELECT * FROM tb5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
$i=0;
foreach ($results as $row){
    $b[$i]=$row['id'];
    $i=$i+1;
}
    $data0="名前";
    $data1="コメント";
    $num1=null;
    if(isset($_POST["submit3"])){
        if($_POST["num1"]==null){
            echo "編集したい番号を選んでください.<br>";
        }elseif($_POST["pass2"]==null){
            echo "パスワードを入力してください.<br>";
        }elseif(in_array($_POST["num1"], $b)){
            
            $num1=$_POST["num1"];
            $password=$_POST["pass2"];
            $id =$num1;
            $sql2 = 'SELECT * FROM tbp WHERE id=:id ';
            $stmt = $pdo->prepare($sql2);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();                            
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $datap=$row['pass'];
            }
            if($password==$datap){
                $sql = 'SELECT * FROM tb5 WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    $data0=$row['name'];
                    $data1=$row['comment'];
                }
            }else{
                echo "パスワードが合いません<br>";
            }
            
        }else{
            echo "存在しない番号です<br>";
        }
    }
    
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="" method="post" >
        <input type="text" name="name" value="<?php echo $data0;?>"><br>
        <input type="text" name="str" value="<?php echo $data1;?>">
        <input type="hidden" name="num0" value="<?php echo $num1;?>"><br>
        <input type="text" name="pass0" placeholder="パスワード">
        <input type="submit" name="submit"><br>
        <input type="number" name="num"  placeholder="削除対象番号入力"><br>
        <input type="text" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit2" value="削除"><br>
        <input type="number" name="num1" placeholder="編集したい番号を入力"><br>
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit3"value="編集"><br>
    </form>
</body>
</html>
<?php
$sql = 'SELECT * FROM tb5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
$i=0;
foreach ($results as $row){
    $b[$i]=$row['id'];
    $i=$i+1;
}


    if(isset($_POST["submit"])){
        if (isset($_POST["str"])) {
            if($_POST["str"]==null){
                echo "コメント欄が空欄です.<br>";
            }else if($_POST["name"]==null){
                echo "名前の欄が空欄です.<br>";
            }else{
                $str = $_POST["str"]; 
                $name1=$_POST["name"];
                $date1 = date("Y年m月d日 H時i分s秒");
                $password=$_POST["pass0"];
                
                if($_POST["num0"]!=null){
                        /*編集*/
                    $id = $_POST["num0"];
                    $name =$name1;
                    $comment = $str;
                    $date=$date1;
                    $sql = 'UPDATE tb5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo "編集しました<br>";
                }else if($_POST["pass0"]==null){
                        echo "パスワードを入力してください.<br>";
                }else{
                        /*新規書き込み*/
                    
                    $sql = $pdo -> prepare("INSERT INTO tb5 (name, comment, date) VALUES (:name, :comment, :date)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql ->bindParam(':date', $date, PDO::PARAM_STR);
                    $name = $name1;
                    $comment = $str;
                    $date=$date1;
                    $sql -> execute();
                    /*パスワード*/
                    $sql = $pdo -> prepare("INSERT INTO tbp (pass) VALUES (:pass)");
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $pass=$password;
                    $sql -> execute();
                    echo "<br>ファイルの書き込み成功<br><br>";
                }
            }
        }
    }
 
    if(isset($_POST["submit2"])){
        
        if($_POST["num"]==null){
            echo "削除したい番号を選んでください";
        }elseif($_POST["pass1"]==null){
            echo "パスワードを入力してください.<br>";
        }else if(in_array($_POST["num"], $b)){
            $id =$_POST["num"];
            $password=$_POST["pass1"];
            
            $id =$id;
            $sql2 = 'SELECT * FROM tbp WHERE id=:id ';
            $stmt = $pdo->prepare($sql2);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();                            
            $results1 = $stmt->fetchAll();
            foreach ($results1 as $row){
                $datap=$row['pass'];
            }
            
            if($password==$datap){
                $id =$_POST["num"];
                $sql = 'delete from tb5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                echo "<br>".$id."番の行を削除しました<br>";
            }else{
                echo "パスワード間違えてます<br>";
            }
        }else{
            echo "存在しない番号です.<br>";
        }
    }
    /*表示*/
    $sql = 'SELECT * FROM tb5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' , ';
        echo $row['name'].' , ';
        echo $row['comment'].' , ';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
        
    
    
        
   ?>
    
    




