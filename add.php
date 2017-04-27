<?php require_once('Connections/forum.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO topic (Title, Content, Nickname, Email, `time`) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['Content'], "text"),
                       GetSQLValueString($_POST['Nickname'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['time'], "date"));

  mysql_select_db($database_forum, $forum);
  $Result1 = mysql_query($insertSQL, $forum) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>討論區</title>
 <script src="tinymce/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script>
</head>

<body>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="70%" border="1" align="center">
    <tr>
      <td align="left" valign="middle">暱稱:
        <label for="Nickname"></label>
      <input name="Nickname" type="text" id="Nickname" size="20"></td>
      <td align="left" valign="middle">E_MAIL:
        <label for="Email"></label>
      <input name="Email" type="text" id="Email" size="30"></td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle">主題:
        <label for="Title"></label>
      <input name="Title" type="text" id="Title" size="100"></td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle"><label for="Content"></label>
      <textarea name="Content" id="Content"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle"><strong>
        <input name="time" type="hidden" id="time" value="<?php echo date("Y:m:d H:i:s"); ?>">
      </strong><input type="submit" name="button" id="button" value="送出"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>