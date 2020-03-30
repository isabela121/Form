<?php require_once('Connections/MySql.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['text1'])) {
  $loginUsername=$_POST['text1'];
  $password=$_POST['text2'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "falha.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_MySql, $MySql);
  
  $LoginRS__query=sprintf("SELECT login, senha FROM login WHERE login=%s AND senha=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $MySql) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_GET['accesscheck']) && false) {
      $MM_redirectLoginSuccess = $_GET['accesscheck'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
<style type="text/css">
<!--
.aaa {
	text-align: center;
	font-weight: bold;
}
.aaa table tr td {
	font-weight: normal;
	text-align: center;
}
.aaa #form1 table tr td label #button {
	text-align: center;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body class="aaa">
<p>Login
</p>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
<table width="227" border="0" align="center">
  <tr>
    <td width="55">Login:</td>
    <td width="162">
      <span id="sprytextfield1">
        <label>
          <input type="text" name="text1" id="text1" />
        </label>
        <span class="textfieldRequiredMsg">*</span></span>
    </td>
  </tr>
  <tr>
    <td>Senha:</td>
    <td><span id="sprytextfield2">
      <label>
        <input type="password" name="text2" id="text2" />
      </label>
    <span class="textfieldRequiredMsg">*</span></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="reset" name="button" id="button" value="Limpar" />
       <input type="submit" name="button2" id="button2" value="Login" />
    </label></td>
  </tr>
</table>
<p><a href="registro.php">Registro</a></p>
<p>&nbsp;</p>
<p><br />
  Form Basic
    <br />
  Creditos: Rafael L. Dantas (rafael.lopesdantas@hotmail.com)</p>
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
//-->
</script>
</body>
</html>