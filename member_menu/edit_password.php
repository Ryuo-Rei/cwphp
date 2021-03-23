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

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>社員資格登録管理</title>
<script type="text/javascript"> 
function submitChk()
{
    if(pass_form.current_pass.value == "")
    {
        alert("現在のパスワードを入力してください。");
        return false;
    }
    if(pass_form.new_pass1.value == "")
    {
        alert("新しいパスワードを入力してください。");
        return false;
    }

    if(pass_form.new_pass1.value != pass_form.new_pass2.value)
    {
        alert("パスワードが一致していません。");
        return false;
    }

    if(pass_form.new_pass1.value.length < 12 || 100 < pass_form.new_pass1.value.length)
    {
        alert("パスワードは12文字以上100文字以下で入力してください。");
        return false;
    }

    const reg = new RegExp(/^[a-zA-Z0-9!"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~]/);
    if(reg.test(pass_form.new_pass1.value) == false)
    {
        alert("使用できない文字が含まれています。");
        return false;
    }

    const ratz = /[a-z]/, rAtZ = /[A-Z]/, r0t9 = /[0-9]/, symbol = /[!"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~]/;
    var count = 0;
    if(ratz.test(pass_form.new_pass1.value) == true)
    {
        count++;
    }
    if(rAtZ.test(pass_form.new_pass1.value) == true)
    {
        count++;
    }
    if(r0t9.test(pass_form.new_pass1.value) == true)
    {
        count++;
    }
    if(symbol.test(pass_form.new_pass1.value) == true)
    {
        count++;
    }
    if(count < 2)
    {
        alert("パスワードには半角英数字記号のうち2種類以上を含めてください。");
        return false;
    }
    var flag = confirm("変更してもよろしいですか？");
    return flag;
}

</script>                   
</head>
<body>

<br />
<form method="post" action="edit_password_done.php" name="pass_form">
現在のパスワードを入力してください。<br />
<input type="password" name="current_pass" style="width:300px"><br />
<br />
パスワードを入力してください。<br />
<input type="password" name="new_pass1" style="width:200px"><br />
パスワードを再度入力してください。<br />
<input type="password" name="new_pass2" style="width:200px"><br />
<br />

<input type="button" onclick="history.back()" value="戻る">
<input type="submit" onClick="return submitChk()" value="変更">
</form>

</body>
</html>