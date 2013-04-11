<?php
/***************************************************************************
 *   copyright				: (C) 2008 - 2013 WeBid
 *   site					: http://www.webidsupport.com/
 ***************************************************************************/

/***************************************************************************
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version. Although none of the code may be
 *   sold. If you have been sold this script, get a refund.
 ***************************************************************************/
	include 'common.php';
	include $include_path . 'itemarray.php';

function sendarray()
{
	global $requested;
	global $checkcount;
	global $gottenarray;
	global $starttime;
	while ($requested)
	{
		set_time_limit(40);
		$vararray = Rebuild('');
		unset($vararray['ENDS']);
		unset($vararray['TOPCATSPATH']);
		unset($vararray['ENDS_IN']);
		unset($vararray['AUCTION_VIEWS']);
		unset($vararray['PAYMENTS']);
		if ($starttime > time()-30)
		{
				if ($checkcount > 0)
				{
					if (is_array($gottenarray) && !empty($gottenarray))
					{


						if ($gottenarray != $vararray)
						{
							echo json_encode($vararray);
							$requested = false;
						}
						else
						{
							usleep(1000000);
							$requested = true;
						}
					}
					else
					{
						echo json_encode($vararray);
						$requested = false;
					}
				}
				else
				{
					echo json_encode($vararray);
					$requested = false;
				}
		}
		else
		{
			echo json_encode($vararray);
			$requested = false;
		}
	}
}
$requested = ($_REQUEST['requested']);
if (!empty($_REQUEST['sendarray']))
{
	$gottenarray = json_decode($_REQUEST['sendarray'], true);
}
else
{
	$gottenarray = array();
}
$checkcount = ($_REQUEST['check']);
$id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
$starttime = time();
sendarray();
?>
