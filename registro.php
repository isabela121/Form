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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="index.php";
  $loginUsername = $_POST['login'];
  $LoginRS__query = sprintf("SELECT login FROM login WHERE login=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_MySql, $MySql);
  $LoginRS=mysql_query($LoginRS__query, $MySql) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
	echo 'Usuario '. $loginUsername .' não está disponivel!';
   // header ("Location: $MM_dupKeyRedirect");
   exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO login (login, senha, email) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['senha'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_MySql, $MySql);
  $Result1 = mysql_query($insertSQL, $MySql) or die(mysql_error());
  header ("Location: login.php");
}

mysql_select_db($database_MySql, $MySql);
$query_Registro = "SELECT * FROM login";
$Registro = mysql_query($query_Registro, $MySql) or die(mysql_error());
$row_Registro = mysql_fetch_assoc($Registro);
$totalRows_Registro = mysql_num_rows($Registro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registro</title>
<style type="text/css">
<!--
.aaa {
	text-align: center;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-color: #FFF;
}
-->
</style></head>

<body class="aaa">
<p>Registro</p>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline" bgcolor="#FFF">
      <td align="right" nowrap="nowrap">Login:</td>
      <td><span id="sprytextfield1">
        <label>
          <input type="text" name="login" id="login" />
        </label>
      <span class="textfieldRequiredMsg">*</span></span></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFF">
      <td align="right" nowrap="nowrap">Senha:</td>
      <td><span id="sprytextfield2">
        <label>
          <input type="password" name="senha" id="senha" />
        </label>
      <span class="textfieldRequiredMsg">*</span></span></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFF">
      <td align="right" nowrap="nowrap">Verf. Senha:</td>
      <td><span id="spryconfirm1">
        <label>
          <input type="password" name="text1" id="text1" />
        </label>
      <span class="confirmRequiredMsg">*</span><span class="confirmInvalidMsg">O valores não são correspondentes.</span></span></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFF">
      <td align="right" nowrap="nowrap">Email:</td>
      <td><span id="sprytextfield3">
      <label>
        <input type="text" name="email" id="email" />
      </label>
      <span class="textfieldRequiredMsg">*</span><span class="textfieldInvalidFormatMsg">*</span></span></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFF">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td><input type="submit" value="Registrar" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "senha", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "email", {validateOn:["blur"]});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Registro);
?>
