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

$colname_Recordset1 = "-1";
if (isset($_POST['keyword'])) {
  $colname_Recordset1 = $_POST['keyword'];
}
mysql_select_db($database_forum, $forum);
$query_Recordset1 = sprintf("SELECT * FROM topic WHERE Title LIKE %s OR Content LIKE %s ORDER BY TopicID DESC", GetSQLValueString("%" . $colname_Recordset1 . "%", "text"),GetSQLValueString("%" . $colname_Recordset1 . "%", "text"));
$Recordset1 = mysql_query($query_Recordset1, $forum) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_POST['keyword'])) {
  $colname_Recordset2 = $_POST['keyword'];
}
mysql_select_db($database_forum, $forum);
$query_Recordset2 = sprintf("SELECT topic.TopicID, topic.Title, reply.Content FROM topic INNER JOIN reply  ON topic.TopicID = reply.Reply_TopicID  WHERE reply.Content LIKE %s ORDER BY TopicID DESC", GetSQLValueString("%" . $colname_Recordset2 . "%", "text"));
$Recordset2 = mysql_query($query_Recordset2, $forum) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
</head>

<body>
<center>
  <h3>討論區首頁</h3>
</center>
<hr>
<p>&nbsp;</p>
<table width="50%" border="1" align="center">
  <tr>
    <td align="left" valign="middle"><a href="index.php">討論區首頁</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="80%" border="0" align="center">
    <tr>
      <td align="right" valign="middle">搜尋關鍵字: 
        <label for="keyword"></label>
      <input type="text" name="keyword" id="keyword">
      <input type="submit" name="button" id="button" value="送出"></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<?php do { ?>
  <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
  <table width="80%" border="1" align="center">
    <tr bgcolor="#0099FF">
      <td>於主題(<?php echo $row_Recordset1['Title']; ?>)</td>
      </tr>
    <tr>
      <td><table width="100%" border="0" align="center">
        <tr>
          <td align="left" valign="middle"><?php echo $row_Recordset1['Content']; ?></td>
          </tr>
        <tr>
          <td align="right" valign="middle"><a href="topic.php?TopicID=<?php echo $row_Recordset1['TopicID']; ?>">閱讀這篇主題</a></td>
          </tr>
        </table></td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
<?php do { ?>
  <table width="80%" border="1" align="center">
    <tr bgcolor="#0099FF">
      <td>於主題(<?php echo $row_Recordset2['Title']; ?>)</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center">
          <tr>
            <td align="left" valign="middle"><?php echo $row_Recordset2['Content']; ?></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><a href="topic.php?TopicID=<?php echo $row_Recordset2['TopicID']; ?>">閱讀這篇主題</a></td>
          </tr>
      </table></td>
    </tr>
  </table>
    <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
