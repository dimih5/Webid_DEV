<?php
	function Rebuild($whatdoyouneed)
		{
			global $id, $system, $membertypes, $DBPrefix, $include_path, $uploaded_path, $MSG, $category_names, $user, $payment_methods, $ERRMSG, $template;
			$memtypesarr = array();
			
			// Get parameters from the URL
			if(is_array($membertypes))
			{
				foreach ($membertypes as $idm => $memtypearr)
				{
					$memtypesarr[$memtypearr['feedbacks']] = $memtypearr;
				}
				ksort($memtypesarr, SORT_NUMERIC);
			}

			if (!is_numeric($id)) $id = 0;
			$bidderarray = array();
			$bidderarraynum = 1;
			$catscontrol = new MPTTcategories();

			$_SESSION['CURRENT_ITEM'] = $id;
			$_SESSION['REDIRECT_AFTER_LOGIN'] = $system->SETTINGS['siteurl'] . 'item.php?id=' . $id;

			// get auction all needed data
			$query = "SELECT a.*, ac.counter, u.nick, u.reg_date, u.country, payment FROM " . $DBPrefix . "auctions a
					LEFT JOIN " . $DBPrefix . "users u ON (u.id = a.user)
					LEFT JOIN " . $DBPrefix . "auccounter ac ON (ac.auction_id = a.id)
					WHERE a.id = " . $id . " LIMIT 1";
			$result = mysql_query($query);
			$system->check_mysql($result, $query, __LINE__, __FILE__);
			if (mysql_num_rows($result) == 0)
			{
				$_SESSION['msg_title'] = $ERRMSG['622'];
				$_SESSION['msg_body'] = $ERRMSG['623'];
				header('location: message.php');
				exit;
			}
			$auction_data = mysql_fetch_row($result, 0);
			$category = $auction_data['category'];
			$auction_type = $auction_data['auction_type'];
			$ends = $auction_data['ends'];
			$start = $auction_data['starts'];
			$user_id = $auction_data['user'];
			$minimum_bid = $auction_data['minimum_bid'];
			$high_bid = $auction_data['current_bid'];
			$customincrement = $auction_data['increment'];
			$seller_reg = FormatDate($auction_data['reg_date'], '/', false);

			// sort out counter
			if (empty($auction_data['counter']))
			{
				$query = "INSERT INTO " . $DBPrefix . "auccounter VALUES (" . $id . ", 1)";
				$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
				$auction_data['counter'] = 1;
			}
			else
			{
				if (!isset($_SESSION['WEBID_VIEWED_AUCTIONS']))
				{
					$_SESSION['WEBID_VIEWED_AUCTIONS'] = array();
				}
				if (!in_array($id, $_SESSION['WEBID_VIEWED_AUCTIONS']))
				{
					$query = "UPDATE " . $DBPrefix . "auccounter set counter = counter + 1 WHERE auction_id = " . $id;
					$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
					$_SESSION['WEBID_VIEWED_AUCTIONS'][] = $id;
				}
			}
			if ($whatdoyouneed == 'auction_data')
			{
				return $auction_data;
			}

			// get watch item data
			if ($user->logged_in)
			{
				// Check if this item is not already added
				$query = "SELECT item_watch FROM " . $DBPrefix . "users WHERE id = " . $user->user_data['id'];
				$result = mysql_query($query);
				$system->check_mysql($result, $query, __LINE__, __FILE__);

				$watcheditems = trim(mysql_result($result, 0, 'item_watch'));
				$auc_ids = explode(' ', $watcheditems);
				if (in_array($id, $auc_ids))
				{
					$watch_var = 'delete';
					$watch_string = $MSG['5202_0'];
				}
				else
				{
					$watch_var = 'add';
					$watch_string = $MSG['5202'];
				}
			}
			else
			{
				$watch_var = '';
				$watch_string = '';
			}

			// get ending time
			$difference = $ends - time();
			$showendtime = false;
			$has_ended = false;
			if ($start > time())
			{
				$ending_time = '<span class="errfont">' . $MSG['668'] . '</span>';
			}
			elseif ($difference > 0)
			{
				$ending_time = '';
				$d = 0;
				$days_difference = floor($difference / 86400);
				if ($days_difference > 0)
				{
					$daymsg = ($days_difference == 1) ? $MSG['126b'] : $MSG['126'];
					$ending_time .= $days_difference . ' ' . $daymsg . ' ';
					$d++;
				}
				$difference = $difference % 86400;
				$hours_difference = floor($difference / 3600);
				if ($hours_difference > 0)
				{
					$ending_time .= $hours_difference . $MSG['25_0037'] . ' ';
					$d++;
				}
				$difference = $difference % 3600;
				$minutes_difference = floor($difference / 60);
				$seconds_difference = $difference % 60;
				if ($minutes_difference > 0 && $d < 2)
				{
					$ending_time .= $minutes_difference . $MSG['25_0032'] . ' ';
					$d++;
				}
				if ($seconds_difference > 0 && $d < 2)
				{
					$ending_time .= $seconds_difference . $MSG['25_0033'];
				}
				$showendtime = true;
			}
			else
			{
				$ending_time = '<span class="errfont">' . $MSG['911'] . '</span>';
				$has_ended = true;
			}

			// build bread crumbs
			$query = "SELECT left_id, right_id, level FROM " . $DBPrefix . "categories WHERE cat_id = " . $auction_data['category'];
			$res = mysql_query($query);
			$system->check_mysql($res, $query, __LINE__, __FILE__);
			$parent_node = mysql_fetch_assoc($res);

			$cat_value = '';
			$crumbs = $catscontrol->get_bread_crumbs($parent_node['left_id'], $parent_node['right_id']);
			for ($i = 0; $i < count($crumbs); $i++)
			{
				if ($crumbs[$i]['cat_id'] > 0)
				{
					if ($i > 0)
					{
						$cat_value .= ' > ';
					}
					$cat_value .= '<a href="' . $system->SETTINGS['siteurl'] . 'browse.php?id=' . $crumbs[$i]['cat_id'] . '">' . $category_names[$crumbs[$i]['cat_id']] . '</a>';
				}
			}

			$secondcat_value = '';
			if ($system->SETTINGS['extra_cat'] == 'y' && intval($auction_data['secondcat']) > 0)
			{
				$query = "SELECT left_id, right_id, level FROM " . $DBPrefix . "categories WHERE cat_id = " . $auction_data['secondcat'];
				$res = mysql_query($query);
				$system->check_mysql($res, $query, __LINE__, __FILE__);
				$parent_node = mysql_fetch_assoc($res);

				$crumbs = $catscontrol->get_bread_crumbs($parent_node['left_id'], $parent_node['right_id']);
				for ($i = 0; $i < count($crumbs); $i++)
				{
					if ($crumbs[$i]['cat_id'] > 0)
					{
						if ($i > 0)
						{
							$secondcat_value .= ' > ';
						}
						$secondcat_value .= '<a href="' . $system->SETTINGS['siteurl'] . 'browse.php?id=' . $crumbs[$i]['cat_id'] . '">' . $category_names[$crumbs[$i]['cat_id']] . '</a>';
					}
				}
			}

			// history
			$query = "SELECT b.*, u.nick, u.rate_sum FROM " . $DBPrefix . "bids b
					LEFT JOIN " . $DBPrefix . "users u ON (u.id = b.bidder)
					WHERE b.auction = " . $id . " ORDER BY b.bid DESC, b.quantity DESC, b.id DESC";
			$result_numbids = mysql_query($query);
			$system->check_mysql($result_numbids, $query, __LINE__, __FILE__);
			$num_bids = mysql_num_rows($result_numbids);
			$i = 0;
			$left = $auction_data['quantity'];
			$hbidder_data = array();
			while ($bidrec = mysql_fetch_assoc($result_numbids))
			{
				if (!isset($bidderarray[$bidrec['nick']]))
				{
					if ($system->SETTINGS['buyerprivacy'] == 'y' && $user->user_data['id'] != $auction_data['user'] && $user->user_data['id'] != $bidrec['bidder'])
					{
						$bidderarray[$bidrec['nick']] = $MSG['176'] . ' ' . $bidderarraynum;
						$bidderarraynum++;
					}
					else
					{
						$bidderarray[$bidrec['nick']] = $bidrec['nick'];
					}
				}
				if ($left > 0 && !in_array($bidrec['bidder'], $hbidder_data)) //store highest bidder details
				{
					$hbidder_data[] = $bidrec['bidder'];
					$fb_pos = $fb_neg = 0;
					// get seller feebacks
					$query = "SELECT rate FROM " . $DBPrefix . "feedbacks WHERE rated_user_id = " . $bidrec['bidder'];
					$result = mysql_query($query);
					$system->check_mysql($result, $query, __LINE__, __FILE__);
					// count numbers
					$fb_pos = $fb_neg = 0;
					while ($fb_arr = mysql_fetch_assoc($result))
					{
						if ($fb_arr['rate'] == 1)
						{
							$fb_pos++;
						}
						elseif ($fb_arr['rate'] == - 1)
						{
							$fb_neg++;
						}
					}

					$total_rate = $fb_pos - $fb_neg;

					foreach ($memtypesarr as $k => $l)
					{
						if ($k >= $total_rate || $i++ == (count($memtypesarr) - 1))
						{
							$buyer_rate_icon = $l['icon'];
							break;
						}
					}
					$template->assign_block_vars('high_bidders', array(
							'BUYER_ID' => $bidrec['bidder'],
							'BUYER_NAME' => $bidderarray[$bidrec['nick']],
							'BUYER_FB' => $bidrec['rate_sum'],
							'BUYER_FB_ICON' => (!empty($buyer_rate_icon) && $buyer_rate_icon != 'transparent.gif') ? '<img src="' . $system->SETTINGS['siteurl'] . 'images/icons/' . $buyer_rate_icon . '" alt="' . $buyer_rate_icon . '" class="fbstar">' : ''
							));
				}
				$template->assign_block_vars('bidhistory', array(
						'BGCOLOUR' => (!($i % 2)) ? '' : 'class="alt-row"',
						'ID' => $bidrec['bidder'],
						'NAME' => $bidderarray[$bidrec['nick']],
						'BID' => $system->print_money($bidrec['bid']),
						'WHEN' => ArrangeDateNoCorrection($bidrec['bidwhen'] + $system->tdiff) . ':' . gmdate('s', $bidrec['bidwhen']),
						'QTY' => $bidrec['quantity']
						));
				$left -= $bidrec['quantity'];
				$i++;
			}

			$userbid = false;
			if ($user->logged_in && $num_bids > 0)
			{
				// check if youve bid on this before
				$query = "SELECT bid FROM " . $DBPrefix . "bids WHERE auction = " . $id . " AND bidder = " . $user->user_data['id'] . " LIMIT 1";
				$result = mysql_query($query);
				$system->check_mysql($result, $query, __LINE__, __FILE__);
				if (mysql_num_rows($result) > 0)
				{
					if (in_array($user->user_data['id'], $hbidder_data))
					{
						$yourbidmsg = $MSG['25_0088'];
						$yourbidclass = 'yourbidwin';
						if ($difference <= 0 && $auction_data['reserve_price'] > 0 && $auction_data['current_bid'] < $auction_data['reserve_price'])
						{
							$yourbidmsg = $MSG['514'];
							$yourbidclass = 'yourbidloss';
						}
						elseif ($difference <= 0 || $auction_data['bn_only'] == 'y')
						{
							$yourbidmsg = $MSG['25_0089'];
						}
					}
					else
					{
						$yourbidmsg = $MSG['25_0087'];
						$yourbidclass = 'yourbidloss';
					}
					$userbid = true;
				}
			}

			// sort out user questions
			$query = "SELECT id FROM " . $DBPrefix . "messages WHERE reply_of = 0 AND public = 1 AND question = " . $id;
			$res = mysql_query($query);
			$system->check_mysql($res, $query, __LINE__, __FILE__);
			$num_questions = mysql_num_rows($res);
			while ($row = mysql_fetch_assoc($res))
			{
				$template->assign_block_vars('questions', array()); // just need to create the block
				$query = "SELECT sentfrom, message FROM " . $DBPrefix . "messages WHERE question = " . $id . " AND reply_of = " . $row['id'] . " OR id = " . $row['id'] . " ORDER BY sentat ASC";
				$res_ = mysql_query($query);
				$system->check_mysql($res_, $query, __LINE__, __FILE__);
				while ($row_ = mysql_fetch_assoc($res_))
				{
					$template->assign_block_vars('questions.conv', array(
							'MESSAGE' => $row_['message'],
							'BY_WHO' => ($user_id == $row_['sentfrom']) ? $MSG['125'] : $MSG['555']
							));
				}
			}

			$high_bid = ($num_bids == 0) ? $minimum_bid : $high_bid;
			$high_bid = abs($high_bid);

			if ($customincrement == 0)
			{
				// Get bid increment for current bid and calculate minimum bid
				$query = "SELECT increment FROM " . $DBPrefix . "increments WHERE
				((low <= " . $high_bid . " AND high >= " . $high_bid . ") OR
				(low < " . $high_bid . " AND high < " . $high_bid . ")) ORDER BY increment DESC";
				$result_incr = mysql_query($query);
				$system->check_mysql($result_incr, $query, __LINE__, __FILE__);
				if (mysql_num_rows($result_incr) != 0)
				{
					$increment = mysql_result($result_incr, 0, 'increment');
				}
			}
			else
			{
				$increment = $customincrement;
			}

			if ($auction_type == 2)
			{
				$increment = 0;
			}

			if ($customincrement > 0)
			{
				$increment = $customincrement;
			}

			if ($num_bids == 0 || $auction_type == 2)
			{
				$next_bidp = $minimum_bid;
			}
			else
			{
				$next_bidp = $high_bid + $increment;
			}

			$view_history = '';
			if ($num_bids > 0 && !isset($_GET['history']))
			{
				$view_history = '<a href="' . $system->SETTINGS['siteurl'] . 'item.php?id=' . $id . '&history=view#history">' . $MSG['105'] . '</a>';
			}
			elseif (isset($_GET['history']))
			{
				$view_history = '<a href="' . $system->SETTINGS['siteurl'] . 'item.php?id=' . $id . '">' . $MSG['507'] . '</a>';
			}
			$min_bid = $system->print_money($minimum_bid);
			$high_bid = $system->print_money($high_bid);
			if ($difference > 0)
			{
				$next_bid = $system->print_money($next_bidp);
			}
			else
			{
				$next_bid = '--';
			}



			// get seller feebacks
			$query = "SELECT rate FROM " . $DBPrefix . "feedbacks WHERE rated_user_id = " . $user_id;
			$result = mysql_query($query);
			$system->check_mysql($result, $query, __LINE__, __FILE__);
			$num_feedbacks = mysql_num_rows($result);
			// count numbers
			$fb_pos = $fb_neg = 0;
			while ($fb_arr = mysql_fetch_assoc($result))
			{
				if ($fb_arr['rate'] == 1)
				{
					$fb_pos++;
				}
				elseif ($fb_arr['rate'] == - 1)
				{
					$fb_neg++;
				}
			}

			$total_rate = $fb_pos - $fb_neg;

			if ($total_rate > 0)
			{
				$i = 0;
				foreach ($memtypesarr as $k => $l)
				{
					if ($k >= $total_rate || $i++ == (count($memtypesarr) - 1))
					{
						$seller_rate_icon = $l['icon'];
						break;
					}
				}
			}

			// Pictures Gallery
			$K = 0;
			$UPLOADED_PICTURES = array();
			if (file_exists($uploaded_path . $id))
			{
				$dir = @opendir($uploaded_path . $id);
				if ($dir)
				{
					while ($file = @readdir($dir))
					{
						if ($file != '.' && $file != '..' && strpos($file, 'thumb-') === false)
						{
							$UPLOADED_PICTURES[$K] = $file;
							$K++;
						}
					}
					@closedir($dir);
				}
				$GALLERY_DIR = $id;

				if (is_array($UPLOADED_PICTURES))
				{
					foreach ($UPLOADED_PICTURES as $k => $v)
					{
						$TMP = @getimagesize($uploaded_path . $id . '/' . $v);
						if ($TMP[2] >= 1 && $TMP[2] <= 3)
						{
							$template->assign_block_vars('gallery', array(
									'V' => $v
									));
						}
					}
				}
			}
			// Contracts
			$K = 0;
			$UPLOADED_CONTRACTS = array();
			if (file_exists($uploaded_path . $id . "/contracts/"))
			{
				$dir = @opendir($uploaded_path . $id . "/contracts");
				if ($dir)
				{
					while ($file = @readdir($dir))
					{
						if ($file != '.' && $file != '..' && strpos($file, 'thumb-') === false)
						{
							$UPLOADED_CONTRACTS[$K] = $file;
							$K++;
						}
					}
					@closedir($dir);
				}
				$CONTRACT_DIR = $id;
			}
			// Participants
			$query = "SELECT name FROM " . $DBPrefix . "users";
			$res = mysql_query($query);
			$system->check_mysql($res, $query, __LINE__, __FILE__);
			$i = 2;
			while($userfile = mysql_fetch_assoc($res))
			{
				$i++;
				$template->assign_block_vars('newsticker', array(
						'NAME' => $userfile['name'],
						'ID' => $i
						));
			}
			// payment methods
			$payment = explode(',', $auction_data['payment']);
			$query = "SELECT * FROM " . $DBPrefix . "gateways";
			$res = mysql_query($query);
			$system->check_mysql($res, $query, __LINE__, __FILE__);
			$gateways_data = mysql_fetch_assoc($res);
			$gateway_list = explode(',', $gateways_data['gateways']);
			$p_first = true;
			foreach ($gateways_data as $k => $v)
			{
				$v = strtolower($v);
				if ($gateways_data[$k] == 1 && in_array($v, $payment))
				{
					if (!$p_first)
					{
						$payment_methods .= ', ';
					}
					else
					{
						$p_first = false;
					}
					$k = str_replace('_active', '', $k);
					$payment_methods .= $system->SETTINGS['gatways'][$k];
				}
			}
			
			$payment_options = unserialize($system->SETTINGS['payment_options']);
			if(is_array($payment_options))
			{
				foreach ($payment_options as $k => $v)
				{
					if (in_array($k, $payment))
					{
						if (!$p_first)
						{
							$payment_methods .= ', ';
						}
						else
						{
							$p_first = false;
						}
						$payment_methods .= $v;
					}
				}
			}

			if (!$has_ended)
			{
				$bn_link = ' <a href="' . $system->SETTINGS['siteurl'] . 'buy_now.php?id=' . $id . '"><img border="0" align="absbottom" alt="' . $MSG['496'] . '" src="' . get_lang_img('buy_it_now.gif') . '"></a>';
			}

			$page_title = $auction_data['title'];

			$sslurl = ($system->SETTINGS['usersauth'] == 'y' && $system->SETTINGS['https'] == 'y') ? str_replace('http://', 'https://', $system->SETTINGS['siteurl']) : $system->SETTINGS['siteurl'];
			$sslurl = (!empty($system->SETTINGS['https_url'])) ? $system->SETTINGS['https_url'] : $sslurl;

			$shipping = '';
			if ($auction_data['shipping'] == 1)
				$shipping = $MSG['033'];
			elseif ($auction_data['shipping'] == 2)
				$shipping = $MSG['032'];
			elseif ($auction_data['shipping'] == 3)
				$shipping = $MSG['867'];
				
			$vararray = array(
					'ID' => $auction_data['id'],
					'TITLE' => $auction_data['title'],
					'SUBTITLE' => $auction_data['subtitle'],
					'AUCTION_DESCRIPTION' => stripslashes($auction_data['description']),
					'PIC_URL' => $uploaded_path . $id . '/' . $auction_data['pict_url'],
					'CONTR_URL' => $uploaded_path . $id . '/contracts/' . $auction_data['contr_url'],
					'SHIPPING_COST' => $system->print_money($auction_data['shipping_cost']),
					'ADDITIONAL_SHIPPING_COST' => $system->print_money($auction_data['shipping_cost_additional']),
					'COUNTRY' => $auction_data['country'],
					'QTY' => $auction_data['quantity'],
					'ENDS' => $ending_time,
					'ENDS_IN' => ($ends - time()),
					'ICSENDS' => $auction_data['ends'],
					'ICSSTART' => $auction_data['starts'],
					'STARTTIME' => ArrangeDateNoCorrection($start + $system->tdiff),
					'ENDTIME' => ArrangeDateNoCorrection($ends + $system->tdiff),
					'BUYNOW1' => $auction_data['buy_now'],
					'BUYNOW2' => ($auction_data['buy_now'] > 0) ? $system->print_money($auction_data['buy_now']) . $bn_link : $system->print_money($auction_data['buy_now']),
					'NUMBIDS' => $num_bids,
					'MINBID' => $min_bid,
					'MAXBID' => $high_bid,
					'NEXTBID' => $next_bid,
					'INTERNATIONAL' => ($auction_data['international'] == 1) ? $MSG['033'] : $MSG['043'],
					'SHIPPING' => $shipping,
					'SHIPPINGTERMS' => nl2br($auction_data['shipping_terms']),
					'PAYMENTS' => $payment_methods,
					'AUCTION_VIEWS' => $auction_data['counter'],
					'AUCTION_TYPE' => ($auction_data['bn_only'] == 'n') ? $system->SETTINGS['auction_types'][$auction_type] : $MSG['933'],
					'ATYPE' => $auction_type,
					'THUMBWIDTH' => $system->SETTINGS['thumb_show'],
					'VIEW_HISTORY1' => (empty($view_history)) ? '' : $view_history . ' | ',
					'VIEW_HISTORY2' => $view_history,
					'TOPCATSPATH' => ($system->SETTINGS['extra_cat'] == 'y' && isset($_SESSION['browse_id']) && $_SESSION['browse_id'] == $auction_data['secondcat']) ? $secondcat_value : $cat_value,
					'CATSPATH' => $cat_value,
					'SECCATSPATH' => $secondcat_value,
					'CAT_ID' => $auction_data['category'],
					'UPLOADEDPATH' => $uploaded_path,
					'BNIMG' => get_lang_img('buy_it_now.gif'),

					'SELLER_REG' => $seller_reg,
					'SELLER_ID' => $auction_data['user'],
					'SELLER_NICK' => $auction_data['nick'],
					'SELLER_TOTALFB' => $total_rate,
					'SELLER_FBICON' => (!empty($seller_rate_icon) && $seller_rate_icon != 'transparent.gif') ? '<img src="' . $system->SETTINGS['siteurl'] . 'images/icons/' . $seller_rate_icon . '" alt="' . $seller_rate_icon . '" class="fbstar">' : '',
					'SELLER_NUMFB' => $num_feedbacks,
					'SELLER_FBPOS' => ($num_feedbacks > 0) ? '(' . ceil($fb_pos * 100 / $num_feedbacks) . '%)' : '100%',
					'SELLER_FBNEG' => ($fb_neg > 0) ? $MSG['5507'] . ' (' . ceil($fb_neg * 100 / $total_rate) . '%)' : '0',
					'CONDITIONSURL' => $uploaded_path . 'users/' . $auction_data['user'] . '/conditions/' . $auction_data['conditions'],
					'B_HASCONDITIONS' => strlen($auction_data['conditions']) > 3 ? true : false,

					'WATCH_VAR' => $watch_var,
					'WATCH_STRING' => $watch_string,

					'YOURBIDMSG' => (isset($yourbidmsg)) ? $yourbidmsg : '',
					'YOURBIDCLASS' => (isset($yourbidclass)) ? $yourbidclass : '',
					'BIDURL' => $sslurl,
					
					'B_REALTIMEDATA' => ($system->SETTINGS['realtimedata'] == 'y'),
					'B_HASENDED' => $has_ended,
					'B_CANEDIT' => ($user->logged_in && $user->user_data['id'] == $auction_data['user'] && $num_bids == 0 && $difference > 0),
					'B_CANCONTACTSELLER' => (($system->SETTINGS['contactseller'] == 'always' || ($system->SETTINGS['contactseller'] == 'logged' && $user->logged_in)) && (!$user->logged_in || $user->user_data['id'] != $auction_data['user'])),
					'B_HASIMAGE' => (!empty($auction_data['pict_url'])),
					'B_NOTBNONLY' => ($auction_data['bn_only'] == 'n'),
					'B_ORCHECK' => $auction_data['user'] == $user->user_data['id'] ? false : true,
					'B_HASRESERVE' => ($auction_data['reserve_price'] > 0 && $auction_data['reserve_price'] > $auction_data['current_bid']),
					'B_BNENABLED' => ($system->SETTINGS['buy_now'] == 2),
					'B_HASGALELRY' => (count($UPLOADED_PICTURES) > 0),
					'B_HASCONTRACTS' => (count($UPLOADED_CONTRACTS) > 0),
					'B_SHOWHISTORY' => (isset($_GET['history']) && $num_bids > 0),
					'B_BUY_NOW' => ($auction_data['buy_now'] > 0 && ($auction_data['bn_only'] == 'y' || $auction_data['bn_only'] == 'n' && ($auction_data['num_bids'] == 0 || ($auction_data['reserve_price'] > 0 && $auction_data['current_bid'] < $auction_data['reserve_price'])))),
					'B_BUY_NOW_ONLY' => ($auction_data['bn_only'] == 'y'),
					'B_ADDITIONAL_SHIPPING_COST' => ($auction_data['auction_type'] == '2'),
					'B_USERBID' => $userbid,
					'B_BIDDERPRIV' => ($system->SETTINGS['buyerprivacy'] == 'y' && (!$user->logged_in || ($user->logged_in && $user->user_data['id'] != $auction_data['user']))),
					'B_HASBUYER' => (count($hbidder_data) > 0),
					'B_COUNTDOWN' => ($system->SETTINGS['hours_countdown'] > (($ends - time()) / 3600)),
					'B_HAS_QUESTIONS' => ($num_questions > 0),
					'B_CAN_BUY' => $user->can_buy && !($start > time()),
					'B_SHOWENDTIME' => $showendtime,
					
					'CONTRACT_PRODUCTS'                 => $auction_data['contract_products'],
    				'CONTRACT_SPECS_BRANDS'             => $auction_data['contract_specs_brands'],
    				'CONTRACT_QUANTITY'                 => $auction_data['contract_quantity'],
    				'CONTRACT_PRICE_UNIT'               => $auction_data['contract_price_unit'],
    				'CONTRACT_CURRENCY'                 => $auction_data['contract_currency'],
    				'CONTRACT_AMOUNT'                   => $auction_data['contract_amount'],
    				'CONTRACT_ORIGIN'                   => $auction_data['contract_origin'],
    				'CONTRACT_PACKING'                  => $auction_data['contract_packing'],
    				'CONTRACT_INCOTERMS'                => $auction_data['contract_incoterms'],
    				'CONTRACT_DELIVERY_DATES'           => $auction_data['contract_delivery_dates'],
    				'CONTRACT_DELIVERY_ADDRESS'         => $auction_data['contract_delivery_address'],
    				'CONTRACT_DELIVERY_DETAILS'         => $auction_data['contract_delivery_details'],
    				'CONTRACT_PAYMENT_CONDITION'        => $auction_data['contract_payment_condition'],
    				'CONTRACT_REMARKS_DISCLAIMERS'      => $auction_data['contract_remarks_disclaimers']
    				
					);
			unset($_SESSION['browse_id']);
			session_write_close();
			return $vararray;
		}
	
?>