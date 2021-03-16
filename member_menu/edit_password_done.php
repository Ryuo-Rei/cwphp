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
    $hashed_oldpass=hash_password($_SESSION['old_password']);
    $hashed_newpass=hash_password($_SESSION['new_password']);
    var_dump($hashed_oldpass);
    
    $dsn = 'mysql:dbname=heroku_570d4cd36643e90;host=us-cdbr-east-03.cleardb.com;charset=utf8';
    $user = 'b69dbd841cab77';
    $password = '542709fe';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT password FROM employee WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $_SESSION['user_info'][0];
    $stmt->execute($data);
    
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($rec['password']);
    var_dump(password_verify($_SESSION['old_password'], $rec['password']));
    if (password_verify($_SESSION['old_password'], $rec['password'])) {
        $sql = 'UPDATE employee SET password=? WHERE id=?';
        $stmt = $dbh->prepare($sql);
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