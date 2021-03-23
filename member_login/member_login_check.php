<?php
try {
    require_once('../common/common.php');

    $post = sanitize($_POST);

    $login_id=$post['id'];
    $login_pass=$post['pass'];

    $hashed_pass=password_hash($login_pass, PASSWORD_DEFAULT);

    $conn = mysqli_init();
    mysqli_ssl_set($conn, null, null, "../BaltimoreCyberTrustRoot.crt.pem", null, null);
    mysqli_real_connect($conn, 'cwphpmysql.mysql.database.azure.com', 'cwphpdb_test@cwphpmysql.mysql.database.azure.com', 'msPJRGsq7uKTUiksLXamW9pu7MrgULrzkhu2SipCl1ix4mvN4htBQh3Ya5FNEmft', 'testdb', 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno($conn)) {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }

    $dsn = 'mysql:host=cwphpmysql.mysql.database.azure.com;port=3306;dbname=testdb';
    $user = 'cwphpdb_test@cwphpmysql.mysql.database.azure.com';
    $password = 'msPJRGsq7uKTUiksLXamW9pu7MrgULrzkhu2SipCl1ix4mvN4htBQh3Ya5FNEmft';
    $options = array(
    PDO::MYSQL_ATTR_SSL_CA => '../BaltimoreCyberTrustRoot.crt.pem'
    );
    $dbh = new PDO($dsn, $user, $password, $options);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT * FROM employee WHERE email=? AND delete_flag=?';
    $stmt= $dbh->prepare($sql);
    $data[]=$login_id;
    $data[]=0;
    $stmt->execute($data);
    
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (password_verify($login_pass, rec['password']) == false || $rec == false) {
        print 'ログインできませんでした。<br />';
        print '<a href="member_login.html">戻る</a>';
        if ($rec == false) {
            $sql= 'INSERT INTO login_info(login_date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
            $stmt= $dbh->prepare($sql);
            $data=[];
            $data[]=date("Y/m/d H:i:s");
            $data[]=1;
            $data[]=$login_id;
            $data[]=$_SERVER["REMOTE_ADDR"];
            $data[]=$_SERVER['HTTP_USER_AGENT'];
            $stmt->execute($data);
    
            $dbh = null;
        } elseif (password_verify($login_pass, rec['password']) == false) {
            $sql= 'INSERT INTO login_info(login_date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
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
        $sql= 'INSERT INTO login_info(login_date,status,login_id,ip_address,browser_info) VALUES(?,?,?,?,?)';
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
        $_SESSION['user_info']=array($rec['id'],
        $rec['emp_number'],
        $rec['last_name']." ".$rec['first_name'],
        $login_id);
        header('Location:member_top.php');
        exit();
    }
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}
