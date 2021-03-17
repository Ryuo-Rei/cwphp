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

$dsn = 'mysql:dbname=heroku_570d4cd36643e90;host=us-cdbr-east-03.cleardb.com;charset=utf8';
$user = 'b69dbd841cab77';
$password = '542709fe';
$dbh = new PDO($dsn, $user, $password);
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
        alert("パスワードには半角英数字記号のうち2種類を含めてください。");
        return false;
    }
    var flag = confirm("変更してもよろしいですか？");
    return flag;
}

</script>                   
</head>
<body>

<?php

$_SESSION['old_password'] = pass_form.current_pass.value;
$_SESSION['new_password'] = pass_form.new_pass1.value;

?>

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