<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['member_login'])==false) {
    print 'ログインされていません。<br />';
    print '<a href="../member_login/member_login.html">ログイン画面へ</a>';
    exit();
} else {
    print $_SESSION['user_info'][2];
    print 'さんログイン中<br/>';
    print '<br />';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title></title>
</head>
<body>

<?php

try {
    require_once('../common/common.php');
    $hashed_oldpass=$_POST['current_pass'];
    $hashed_newpass=password_hash($_POST['new_pass1'], PASSWORD_DEFAULT);
    
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
    
    $sql = 'SELECT password FROM employee WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_SESSION['user_info'][0];
    $stmt->execute($data);
    
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($hashed_oldpass, rec['password'])) {
        $sql = 'UPDATE employee SET password=? WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $data = [];
        $data[] = $hashed_newpass;
        $data[] = $_SESSION['user_info'][0];
        $stmt->execute($data);
    
        print '変更しました。<br />';
        print '<a href="../member_login/member_top.php">トップへ</a>';
    } else {
        print '変更できませんでした。<br />';
        print '<a href="edit_password.php">戻る</a>';
    }
    
    $dbh = null;
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}
?>

</body>
</html>