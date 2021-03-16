<?php

try {
    require_once('../common/common.php');

    $post = sanitize($_POST);

    $login_id=$post['id'];
    $login_pass=$post['pass'];

    $hashed_pass=hash_password($login_pass);

    $dsn = 'mysql:dbname=heroku_570d4cd36643e90;host=us-cdbr-east-03.cleardb.com;charset=utf8';
    $user = 'b69dbd841cab77';
    $password = '542709fe';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT * FROM employee WHERE email=?';
    $stmt= $dbh->prepare($sql);
    $data[]=$login_id;
    $stmt->execute($data);
    
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($hashed_pass != $rec['password'] || $rec == false) {
        print 'ログインできませんでした。<br />';
        print '<a href="member_login.html">戻る</a>';
        if ($rec == false) {
            $sql= 'INSERT INTO login_info(date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
            $stmt= $dbh->prepare($sql);
            $data=[];
            $data[]=date("Y/m/d H:i:s");
            $data[]=1;
            $data[]=$login_id;
            $data[]=$_SERVER["REMOTE_ADDR"];
            $data[]=$_SERVER['HTTP_USER_AGENT'];
            $stmt->execute($data);
    
            $dbh = null;
        } else if ($hashed_pass != $rec['password']) {
            $sql= 'INSERT INTO login_info(date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
            $stmt= $dbh->prepare($sql);
            $data=[];
            $data[]=date("Y/m/d H:i:s");
            $data[]=2;
            $data[]=$login_id;
            $data[]=$_SERVER["REMOTE_ADDR"];
            $data[]=$_SERVER['HTTP_USER_AGENT'];
            $stmt->execute($data);

            $dbh = null;
        }
    } else {
        session_start();
        $sql= 'INSERT INTO login_info(date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
        $stmt= $dbh->prepare($sql);
        $data=[];
        $data[]=date("Y/m/d H:i:s");
        $data[]=0;
        $data[]=$login_id;
        $data[]=$_SERVER["REMOTE_ADDR"];
        $data[]=$_SERVER['HTTP_USER_AGENT'];
        $stmt->execute($data);

        $dbh = null;

        $_SESSION['member_login']=1;
        $_SESSION['user_info']=array($rec['id'], $rec['emp_number'], $rec['last_name']." ".$rec['first_name'], $login_id);
        header('Location:member_top.php');
        exit();
    }
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}
