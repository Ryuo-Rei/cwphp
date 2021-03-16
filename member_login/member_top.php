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
<title>社員資格登録管理</title>
</head>
<body>

管理メニュー<br />
<br />
<a href="../member_menu/edit_password.php">パスワード変更</a><br />
<br />
<a href="member_logout.php">ログアウト</a><br />

</body>
</html>