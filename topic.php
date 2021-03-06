<?php
#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_Recordset3,$totalRows_Recordset3;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
	if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0)
	{
		if ($pageNum_Recordset1 > ceil($max_links/2))
		{
			$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1)
			{
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}
		else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($HTTP_GET_VARS)){
				$_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_Recordset3") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Recordset3=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_Recordset3) + 1;
					$max_l = ($a*$maxRows_Recordset3 >= $totalRows_Recordset3) ? $totalRows_Recordset3 : ($a*$maxRows_Recordset3);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_Recordset3=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Recordset3=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
?>
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
if (isset($_GET['TopicID'])) {
  $colname_Recordset1 = $_GET['TopicID'];
}
mysql_select_db($database_forum, $forum);
$query_Recordset1 = sprintf("SELECT * FROM topic WHERE TopicID = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $forum) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$maxRows_Recordset3 = 3;
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

$colname_Recordset3 = "-1";
if (isset($_GET['TopicID'])) {
  $colname_Recordset3 = $_GET['TopicID'];
}
mysql_select_db($database_forum, $forum);
$query_Recordset3 = sprintf("SELECT * FROM reply WHERE Reply_TopicID = %s ORDER BY ID ASC", GetSQLValueString($colname_Recordset3, "int"));
$query_limit_Recordset3 = sprintf("%s LIMIT %d, %d", $query_Recordset3, $startRow_Recordset3, $maxRows_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $forum) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);

if (isset($_GET['totalRows_Recordset3'])) {
  $totalRows_Recordset3 = $_GET['totalRows_Recordset3'];
} else {
  $all_Recordset3 = mysql_query($query_Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($all_Recordset3);
}
$totalPages_Recordset3 = ceil($totalRows_Recordset3/$maxRows_Recordset3)-1;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style type="text/css">
.sas {
	color: #FFF;
}
</style>
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
    <td align="right" valign="middle"><a href="add.php">發表主題</a> <a href="search.php">搜尋</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="50%" border="0" align="center">
  <tr>
    <td align="left" valign="middle">主題:<?php echo $row_Recordset1['Title']; ?></td>
    <td align="right" valign="middle"><a href="reply.php?TopicID=<?php echo $row_Recordset1['TopicID']; ?>">回覆主題</a></td>
  </tr>
</table>
<table width="50%" border="1" align="center">
  <tr bgcolor="#0066CC">
    <td width="15%" align="center" valign="middle" class="sas">發表人</td>
    <td align="center" valign="middle" class="sas">內容</td>
  </tr>
  <tr>
    <td width="15%" rowspan="2" align="center" valign="middle"><p><?php echo $row_Recordset1['Nickname']; ?></p>
    <p><a href="del.php?TopicID=<?php echo $row_Recordset1['TopicID']; ?>"><img src="img/icon_delete.gif" width="15" height="18"></a><a href="mailto:<?php echo $row_Recordset1['Email']; ?>"><img src="img/icon_email.gif" width="30" height="18"></a></p></td>
    <td valign="middle"><?php echo $row_Recordset1['Content']; ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle">發表時間:<?php echo $row_Recordset1['time']; ?>&nbsp;&nbsp; </td>
  </tr>
</table>
<?php do { ?>
    <?php if ($totalRows_Recordset3 > 0) { // Show if recordset not empty ?>
      <table width="50%" border="1" align="center">
        <tr>
          <td width="15%" rowspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><p><?php echo $row_Recordset3['Nickname']; ?></p>
            <p><a href="del01.php?ID=<?php echo $row_Recordset3['ID']; ?>&TopicID=<?php echo $row_Recordset1['TopicID']; ?>"><img src="img/icon_delete.gif" width="15" height="18"></a><a href="mailto:<?php echo $row_Recordset3['Email']; ?>"><img src="img/icon_email.gif" width="30" height="18"></a></p></td>
          <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $row_Recordset3['Content']; ?></td>
        </tr>
        <tr>
          <td align="right" valign="middle" bgcolor="#CCCCCC">發表時間:&nbsp;<?php echo $row_Recordset3['Time']; ?> &nbsp;</td>
        </tr>
      </table>
      <?php } // Show if recordset not empty ?>
<?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
<?php if ($totalRows_Recordset3 > 0) { // Show if recordset not empty ?>
  <table width="50%" border="1" align="center">
    <tr>
      <td align="right" valign="middle"><?php 
# variable declaration
$prev_Recordset3 = "« previous";
$next_Recordset3 = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_Recordset3 = buildNavigation($pageNum_Recordset3,$totalPages_Recordset3,$prev_Recordset3,$next_Recordset3,$separator,$max_links,true); 

print $pages_navigation_Recordset3[0]; 
?>
      <?php print $pages_navigation_Recordset3[1]; ?> <?php print $pages_navigation_Recordset3[2]; ?></td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset3);
?>
