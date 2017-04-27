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
  $insertSQL = sprintf("INSERT INTO reply (Reply_TopicID, Content, Nickname, Email, `Time`) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Reply_TopicID'], "int"),
                       GetSQLValueString($_POST['Content'], "text"),
                       GetSQLValueString($_POST['Nickname'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Time'], "date"));

  mysql_select_db($database_forum, $forum);
  $Result1 = mysql_query($insertSQL, $forum) or die(mysql_error());

  $insertGoTo = "topic.php";
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
<title>無標題文件</title>
<script src="tinymce/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script>
</head>

<body>
<center>
  <h3>討論區首頁</h3>
</center>
<hr>
<p></p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="50%" border="1" align="center">
    <tr>
      <td valign="middle" bgcolor="#CCCCCC">暱稱:
        <label for="Nickname"></label>
      <input type="text" name="Nickname" id="Nickname"></td>
      <td valign="middle" bgcolor="#CCCCCC">E_MAIL:
        <label for="Email"></label>
      <input type="text" name="Email" id="Email"></td>
    </tr>
    <tr>
      <td colspan="2" valign="middle" bgcolor="#CCCCCC"><label for="Content"></label>
      <textarea name="Content" id="Content"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" valign="middle" bgcolor="#CCCCCC"><input name="Time" type="hidden" id="Time" value="<?php echo date("Y:m:d H:i:s"); ?>">
      <input type="submit" name="button" id="button" value="送出">
      <input name="Reply_TopicID" type="hidden" id="Reply_TopicID" value="<?php echo $_GET['TopicID']; ?>"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p></p>
</body>
</html>