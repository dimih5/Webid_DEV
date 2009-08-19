<?php
/***************************************************************************
 *   copyright				: (C) 2008 WeBid
 *   site					: http://www.webidsupport.com/
 ***************************************************************************/

/***************************************************************************
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version. Although none of the code may be
 *   sold. If you have been sold this script, get a refund.
 ***************************************************************************/

	define('InAdmin', 1);
include '../includes/common.inc.php';
include $include_path . 'functions_admin.php';
include 'loggedin.inc.php';

	$ABSOLUTEWIDTH = 650;


	#// Retrieve data
	$query = "SELECT year FROM " . $DBPrefix . "currentbrowsers WHERE (year<>".date("Y")."
			  OR (year=".date("Y")." AND month<>".date("m").")) GROUP BY year ORDER BY year desc";
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	while ($year = mysql_fetch_array($res))
	{
		$query = "SELECT * FROM " . $DBPrefix . "currentbrowsers WHERE year=".$year[year]." AND month<>'".date("m")."' GROUP BY month ORDER BY month desc";
		$r_ = mysql_query($query);
		$system->check_mysql($r_, $query, __LINE__, __FILE__);
		//print $year[year]."<BR>";
		while ($month = mysql_fetch_array($r_))
		{
			$YEARS[$year['year']][$month['month']] = $month['month'];
		}
	}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body style="margin:0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
	<td background="images/bac_barint.gif"><table width="100%" border="0" cellspacing="5" cellpadding="0">
		<tr> 
		  <td width="30"><img src="images/i_sta.gif" ></td>
		  <td class=white><?php echo $MSG['25_0023']; ?>&nbsp;&gt;&gt;&nbsp;<?php echo $MSG['5165']; ?></td>
		</tr>
	  </table></td>
  </tr>
  <tr>
	<td align="center" valign="middle">&nbsp;</td>
  </tr>
	<tr> 
	<td align="center" valign="middle">
  <table width=780 cellpadding=2 cellspacing=1 border=0 align="center">
	<tr bgcolor="#FFCC00">
	  <td align="center" colspan="2" bgcolor="#eeeeee">
		<p class="title" style="color:#000000">
		  <?php echo $MSG['5167']."<I>".$system->SETTINGS['sitename']."</I>"; ?>
		  </b> <BR>
		  <B>
		  <?php echo $MSG['5281']; ?>
		  </B></p>
		<p>
			<A HREF="viewaccessstats.php"><?php echo $MSG['5143']; ?></A> |
			<A HREF="viewdomainstats.php"><?php echo $MSG['5166']; ?></A> |
			<A HREF="viewplatformstats.php"><?php echo $MSG['5318']; ?></A>
			</p>
	  </td>
	</tr>
	<tr bgcolor=#FFFFFF>
	  <td width="80">&nbsp;</td>
	  <td width="692">&nbsp;</td>
	</tr>
	<tr bgcolor="#CCCCCC">
	  <td width="80" height="21">
		<b>
		<?php echo $MSG['5280']; ?>
		</b>  </td>
	  <td align=right height="21" width="692">
		<a href="viewbrowserstats.php">
		<?php echo $MSG['5282']; ?>
		</a>  </td>
	  <?php
	  	if (is_array($YEARS))
		{
			foreach ($YEARS as $k => $v)
			{
		?>
			<tr bgcolor=yellow>
				<td COLSPAN=2 >
				<B><?php echo $k; ?></B>
				</td>
			</tr>
			<?php
				foreach ($v as $t => $z)
				{
			?>
			<tr bgcolor=#eeeeee>
			  <td width="80"><b>
				<?php echo $t; ?>
				</b> </td>
			  <td width="692">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="91%">
			  <?php
			  	   $query = "SELECT browser,counter
				   			 FROM " . $DBPrefix . "currentbrowsers
				   		 	 WHERE year='$k' AND month='$t'";
				   $RR = mysql_query($query);
				   $system->check_mysql($RR, $query, __LINE__, __FILE__);
					while ($row = mysql_fetch_array($RR))
					{
						$query = "SELECT max(counter) as MAX, sum(counter) as TOTAL
								  FROM " . $DBPrefix . "currentbrowsers
								  WHERE year='$k' AND month='$t'";
						$R___ = mysql_query($query);
						$system->check_mysql($R___, $query, __LINE__, __FILE__);
						$MAX = mysql_result($R___,0,"MAX");
						$TOTAL = mysql_result($R___,0,"TOTAL");

						$WIDTH = ( $row['counter'] * $ABSOLUTEWIDTH ) / $MAX;
						$PERCENAGE = ceil(intval($row['counter'] * 100 / $TOTAL));
						print $row['browser'];
						?>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="2%">
							  <table border=0 callpadding=0 cellspacing=0 width=<?php echo intval($WIDTH); ?> bgcolor=#006699>
								<tr>
								  <td>&nbsp; </td>
								</tr>
							  </table>
							</td>
							<td width="98%">
							  &nbsp;<?php echo $PERCENAGE; ?>
							  % </td>
						  </tr>
						</table>
					<?php
					}
					?>
			</td>
		  </tr>
		</table>
			  </td>
			</tr>
			<?php
				}
		 	}
		}
	?>
  </table>
</td>
</tr>
</table>
</body>
</html>