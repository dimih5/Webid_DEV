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

if (!defined('InWeBid')) exit();

function generate_id()
{
	global $_SESSION;
	if (!isset($_SESSION['SELL_auction_id']))
	{
		$auction_id = md5(uniqid(rand()));
		$_SESSION['SELL_auction_id'] = $auction_id;
	}
	else
	{
		$auction_id = $_SESSION['SELL_auction_id'];
	}
	return $auction_id;
}

function setvars()
{
	global $with_reserve, $reserve_price, $minimum_bid, $pict_url, $contr_url, $imgtype, $title, $subtitle, $description, $atype, $iquantity, $buy_now, $buy_now_price, $is_taxed, $tax_included, $additional_shipping_cost;
	global $duration, $relist, $increments, $customincrement, $shipping, $shipping_terms, $payment, $international, $sellcat1, $sellcat2, $buy_now_only, $a_starts, $shipping_cost, $is_bold, $is_highlighted, $is_featured, $start_now;
	global $_POST, $_SESSION, $system, $invited_emails, $extra_message;
	global $contract_products, $contract_specs_brands, $contract_quantity, $contract_price_unit, $contract_currency, $contract_amount, $contract_origin, $contract_packing, $contract_incoterms, $contract_delivery_dates, $contract_delivery_address, $contract_delivery_details, $contract_payment_condition, $contract_remarks_disclaimers;

	$with_reserve = (isset($_POST['with_reserve'])) ? $_POST['with_reserve'] : $_SESSION['SELL_with_reserve'];
	$reserve_price = (isset($_POST['reserve_price'])) ? $_POST['reserve_price'] : $_SESSION['SELL_reserve_price'];
	$minimum_bid = (isset($_POST['minimum_bid'])) ? $_POST['minimum_bid'] : $_SESSION['SELL_minimum_bid'];
	$default_minbid = ($system->SETTINGS['moneyformat'] == 1) ? 0.99 : '0,99';
	$minimum_bid = (empty($minimum_bid)) ? $default_minbid : $minimum_bid;
	$shipping_cost = (isset($_POST['shipping_cost'])) ? $_POST['shipping_cost'] : $_SESSION['SELL_shipping_cost'];
	$shipping_cost = (empty($shipping_cost)) ? 0 : $shipping_cost;
	$additional_shipping_cost = (isset($_POST['additional_shipping_cost'])) ? $_POST['additional_shipping_cost'] : $_SESSION['SELL_additional_shipping_cost'];
	$additional_shipping_cost = (empty($additional_shipping_cost)) ? 0 : $additional_shipping_cost;
	$imgtype = (isset($_POST['imgtype'])) ? $_POST['imgtype'] : $_SESSION['SELL_file_uploaded'];
	$title = (isset($_POST['title'])) ? $_POST['title'] : $_SESSION['SELL_title'];
	$subtitle = (isset($_POST['subtitle'])) ? $_POST['subtitle'] : $_SESSION['SELL_subtitle'];
	$description = (isset($_POST['description'])) ? $_POST['description'] : $_SESSION['SELL_description'];
	$pict_url = (isset($_POST['pict_url'])) ? $_POST['pict_url'] : $_SESSION['SELL_pict_url'];
	$contr_url = (isset($_POST['contr_url'])) ? $_POST['contr_url'] : $_SESSION['SELL_contr_url'];
	$atype = (isset($_POST['atype'])) ? $_POST['atype'] : $_SESSION['SELL_atype'];
	$iquantity = (int)(isset($_POST['iquantity'])) ? $_POST['iquantity'] : $_SESSION['SELL_iquantity'];
	$iquantity = (empty($iquantity)) ? 1 : round($iquantity);
	$buy_now = (isset($_POST['buy_now'])) ? $_POST['buy_now'] : $_SESSION['SELL_with_buy_now'];
	$buy_now_price = (isset($_POST['buy_now_price'])) ? $_POST['buy_now_price'] : $_SESSION['SELL_buy_now_price'];
	$duration = (isset($_POST['duration'])) ? $_POST['duration'] : $_SESSION['SELL_duration'];
	$relist = (isset($_POST['autorelist'])) ? $_POST['autorelist'] : $_SESSION['SELL_relist'];
	$increments = (isset($_POST['increments'])) ? $_POST['increments'] : $_SESSION['SELL_increments'];
	$customincrement = (isset($_POST['customincrement'])) ? $_POST['customincrement'] : $_SESSION['SELL_customincrement'];
	$shipping = (isset($_POST['shipping'])) ? $_POST['shipping'] : $_SESSION['SELL_shipping'];
	$shipping_terms = (isset($_POST['shipping_terms'])) ? $_POST['shipping_terms'] : $_SESSION['SELL_shipping_terms'];
	$payment = (isset($_POST['payment'])) ? $_POST['payment'] : $_SESSION['SELL_payment'];
	$payment = (is_array($payment)) ? $payment : array();
	$international = (isset($_POST['international'])) ? $_POST['international'] : ''; 
	$international = (isset($_SESSION['SELL_international']) && (!isset($_POST['action']) || $_POST['action'] != 2)) ? $_SESSION['SELL_international'] : $international; 
	$sellcat1 = $_SESSION['SELL_sellcat1'];
	$_SESSION['SELL_sellcat2'] = (isset($_SESSION['SELL_sellcat2'])) ? $_SESSION['SELL_sellcat2'] : '';
	$sellcat2 = $_SESSION['SELL_sellcat2'];
	$buy_now_only = (isset($_POST['buy_now_only'])) ? $_POST['buy_now_only'] : $_SESSION['SELL_buy_now_only'];
	$buy_now_only = (empty($buy_now_only)) ? 'n' : $buy_now_only;
	$a_starts = (isset($_POST['a_starts'])) ? $_POST['a_starts'] : $_SESSION['SELL_starts'];
	$is_bold = (isset($_POST['is_bold'])) ? 'y' : $_SESSION['SELL_is_bold'];
	$is_featured = (isset($_POST['is_featured'])) ? 'y' : $_SESSION['SELL_is_featured'];
	$is_highlighted = (isset($_POST['is_highlighted'])) ? 'y' : $_SESSION['SELL_is_highlighted'];
	$start_now = (isset($_POST['start_now'])) ? $_POST['start_now'] : '';
	$start_now = (isset($_SESSION['SELL_start_now']) && (!isset($_POST['action']) || $_POST['action'] != 2)) ? $_SESSION['SELL_start_now'] : $start_now;
	$invited_emails = (isset($_POST['invite'])) ? $_POST['invite'] : $_SESSION['SELL_invited_emails'];
	$extra_message = (isset($_POST['extra_message'])) ? $_POST['extra_message'] : $_SESSION['SELL_extra_message'];
	$is_taxed = (isset($_POST['is_taxed'])) ? 'y' : $_SESSION['SELL_is_taxed'];
	$tax_included = (isset($_POST['tax_included'])) ? 'y' : $_SESSION['SELL_tax_included'];
	if (isset($_POST['action']) && $_POST['action'] == 2)
	{
		$is_bold = (isset($_POST['is_bold'])) ? 'y' : 'n';
		$is_featured = (isset($_POST['is_featured'])) ? 'y' : 'n';
		$is_highlighted = (isset($_POST['is_highlighted'])) ? 'y' : 'n';
		$is_taxed = (isset($_POST['is_taxed'])) ? 'y' : 'n';
		$tax_included = (isset($_POST['tax_included'])) ? 'y' : 'n';
		$payment = (isset($_POST['payment'])) ? $payment : array();
	}
	
	$contract_products = (isset($_POST['contract_products'])) ? $_POST['contract_products'] : $_SESSION['contract_products'];
	$contract_specs_brands = (isset($_POST['contract_specs_brands'])) ? $_POST['contract_specs_brands'] : $_SESSION['contract_specs_brands'];
	$contract_quantity = (isset($_POST['contract_quantity'])) ? $_POST['contract_quantity'] : $_SESSION['contract_quantity'];
	$contract_price_unit = (isset($_POST['contract_price_unit'])) ? $_POST['contract_price_unit'] : $_SESSION['contract_price_unit'];
    $contract_currency = (isset($_POST['contract_currency'])) ? $_POST['contract_currency'] : $_SESSION['contract_currency']; // ?
    $contract_amount = (isset($_POST['contract_amount'])) ? $_POST['contract_amount'] : $_SESSION['contract_amount'];
    $contract_origin = (isset($_POST['contract_origin'])) ? $_POST['contract_origin'] : $_SESSION['contract_origin'];
    $contract_packing = (isset($_POST['contract_packing'])) ? $_POST['contract_packing'] : $_SESSION['contract_packing'];
    $contract_incoterms = (isset($_POST['contract_incoterms'])) ? $_POST['contract_incoterms'] : $_SESSION['contract_incoterms'];
    $contract_delivery_dates = (isset($_POST['contract_delivery_dates'])) ? $_POST['contract_delivery_dates'] : $_SESSION['contract_delivery_dates'];
    $contract_delivery_address = (isset($_POST['contract_delivery_address'])) ? $_POST['contract_delivery_address'] : $_SESSION['contract_delivery_address'];
    $contract_delivery_details = (isset($_POST['contract_delivery_details'])) ? $_POST['contract_delivery_details'] : $_SESSION['contract_delivery_details'];
    $contract_payment_condition = (isset($_POST['contract_payment_condition'])) ? $_POST['contract_payment_condition'] : $_SESSION['contract_payment_condition'];
    $contract_remarks_disclaimers = (isset($_POST['contract_remarks_disclaimers'])) ? $_POST['contract_remarks_disclaimers'] : $_SESSION['contract_remarks_disclaimers'];
}

function makesessions()
{
	global $with_reserve, $reserve_price, $minimum_bid, $pict_url, $contr_url, $imgtype, $title, $subtitle, $description, $atype, $iquantity, $buy_now, $buy_now_price, $is_taxed, $tax_included, $additional_shipping_cost;
	global $duration, $relist, $increments, $customincrement, $shipping, $shipping_terms, $payment, $international, $sendemail, $buy_now_only, $a_starts, $shipping_cost, $is_bold, $is_highlighted, $is_featured, $start_now, $_SESSION, $invited_emails, $extra_message;
	global $contract_products, $contract_specs_brands, $contract_quantity, $contract_price_unit, $contract_currency, $contract_amount, $contract_origin, $contract_packing, $contract_incoterms, $contract_delivery_dates, $contract_delivery_address, $contract_delivery_details, $contract_payment_condition, $contract_remarks_disclaimers;
	
	$_SESSION['SELL_with_reserve'] = $with_reserve;
	$_SESSION['SELL_reserve_price'] = $reserve_price;
	$_SESSION['SELL_minimum_bid'] = $minimum_bid;
	$_SESSION['SELL_shipping_cost'] = $shipping_cost;
	$_SESSION['SELL_additional_shipping_cost'] = $additional_shipping_cost;
	$_SESSION['SELL_file_uploaded'] = $imgtype;
	$_SESSION['SELL_title'] = $title;
	$_SESSION['SELL_subtitle'] = $subtitle;
	$_SESSION['SELL_description'] = $description;
	$_SESSION['SELL_pict_url'] = $pict_url;
	$_SESSION['SELL_contr_url'] = $contr_url;
	$_SESSION['SELL_atype'] = $atype;
	$_SESSION['SELL_iquantity'] = $iquantity;
	$_SESSION['SELL_with_buy_now'] = $buy_now;
	$_SESSION['SELL_buy_now_price'] = $buy_now_price;
	$_SESSION['SELL_duration'] = $duration;
	$_SESSION['SELL_relist'] = $relist;
	$_SESSION['SELL_increments'] = $increments;
	$_SESSION['SELL_customincrement'] = $customincrement;
	$_SESSION['SELL_shipping'] = $shipping;
	$_SESSION['SELL_shipping_terms'] = $shipping_terms;
	$_SESSION['SELL_payment'] = $payment;
	$_SESSION['SELL_international'] = $international;
	$_SESSION['SELL_buy_now_only'] = $buy_now_only;
	$_SESSION['SELL_starts'] = $a_starts;
	$_SESSION['SELL_is_bold'] = $is_bold;
	$_SESSION['SELL_is_highlighted'] = $is_highlighted;
	$_SESSION['SELL_is_featured'] = $is_featured;
	$_SESSION['SELL_start_now'] = $start_now;
	$_SESSION['SELL_extra_message'] = $extra_message;
	$_SESSION['SELL_invited_emails'] = $invited_emails;
	$_SESSION['SELL_is_taxed'] = $is_taxed;
	$_SESSION['SELL_tax_included'] = $tax_included;
	
    $_SESSION['SELL_contract_products'] = $contract_products;
    $_SESSION['SELL_contract_specs_brands'] = $contract_specs_brands;
    $_SESSION['SELL_contract_quantity'] = $contract_quantity;
    $_SESSION['SELL_contract_price_unit'] = $contract_price_unit;
    $_SESSION['SELL_contract_currency'] = $contract_currency;
    $_SESSION['SELL_contract_amount'] = $contract_amount;
    $_SESSION['SELL_contract_origin'] = $contract_origin;
    $_SESSION['SELL_contract_packing'] = $contract_packing;
    $_SESSION['SELL_contract_incoterms'] = $contract_incoterms;
    $_SESSION['SELL_contract_delivery_dates'] = $contract_delivery_dates;
    $_SESSION['SELL_contract_delivery_address'] = $contract_delivery_address;
    $_SESSION['SELL_contract_delivery_details'] = $contract_delivery_details;
    $_SESSION['SELL_contract_payment_condition'] = $contract_payment_condition;
    $_SESSION['SELL_contract_remarks_disclaimers'] = $contract_remarks_disclaimers;
}

function unsetsessions()
{
	global $_SESSION, $system;

	$_SESSION['SELL_with_reserve'] = '';
	$_SESSION['SELL_reserve_price'] = '';
	$_SESSION['SELL_minimum_bid'] = ($system->SETTINGS['moneyformat'] == 1) ? 0.99 : '0,99';
	$_SESSION['SELL_shipping_cost'] = 0;
	$_SESSION['SELL_additional_shipping_cost'] = 0;
	$_SESSION['SELL_file_uploaded'] = '';
	$_SESSION['SELL_title'] = '';
	$_SESSION['SELL_subtitle'] = '';
	$_SESSION['SELL_description'] = '';
	$_SESSION['SELL_pict_url'] = '';
	$_SESSION['SELL_pict_url_temp'] = '';
	$_SESSION['SELL_contr_url'] = '';
	$_SESSION['SELL_contr_url_temp'] = '';
	$_SESSION['SELL_atype'] = '';
	$_SESSION['SELL_iquantity'] = '';
	$_SESSION['SELL_with_buy_now'] = '';
	$_SESSION['SELL_buy_now_price'] = '';
	$_SESSION['SELL_duration'] = '';
	$_SESSION['SELL_relist'] = '';
	$_SESSION['SELL_increments'] = '';
	$_SESSION['SELL_customincrement'] = 0;
	$_SESSION['SELL_shipping'] = 1;
	$_SESSION['SELL_shipping_terms'] = '';
	$_SESSION['SELL_payment'] = array();
	$_SESSION['SELL_international'] = '';
	$_SESSION['SELL_sendemail'] = '';
	$_SESSION['SELL_starts'] = '';
	$_SESSION['SELL_action'] = '';
	$_SESSION['SELL_is_bold'] = 'n';
	$_SESSION['SELL_is_highlighted'] = 'n';
	$_SESSION['SELL_is_featured'] = 'n';
	$_SESSION['SELL_start_now'] = '';
	$_SESSION['SELL_invited_emails'] = '';
	$_SESSION['SELL_extra_message'] = '';
	$_SESSION['SELL_is_taxed'] = 'n';
	$_SESSION['SELL_tax_included'] = 'y';
	
	$_SESSION['SELL_contract_products'] = '';
    $_SESSION['SELL_contract_specs_brands'] = '';
    $_SESSION['SELL_contract_quantity'] = '';
    $_SESSION['SELL_contract_price_unit'] = '';
    $_SESSION['SELL_contract_currency'] = '';
    $_SESSION['SELL_contract_amount'] = '';
    $_SESSION['SELL_contract_origin'] = '';
    $_SESSION['SELL_contract_packing'] = '';
    $_SESSION['SELL_contract_incoterms'] = '';
    $_SESSION['SELL_contract_delivery_dates'] = '';
    $_SESSION['SELL_contract_delivery_address'] = '';
    $_SESSION['SELL_contract_delivery_details'] = '';
    $_SESSION['SELL_contract_payment_condition'] = '';
    $_SESSION['SELL_contract_remarks_disclaimers'] = '';
}

function updateauction($type)
{
	global $_SESSION, $DBPrefix, $a_starts, $a_ends, $payment_text, $system, $fee;
	$extraquery = ($type == 2) ? 
		",relisted = relisted + 1,
		current_bid = 0,
		starts = '" . $a_starts . "',
		num_bids = 0" : '';
	$query =
		"UPDATE " . $DBPrefix . "auctions SET
		title = '" . $system->cleanvars($_SESSION['SELL_title']) . "',
		subtitle = '" . $system->cleanvars($_SESSION['SELL_subtitle']) . "',
		description = '" . addslashes($_SESSION['SELL_description']) . "',
		pict_url = '" . $system->cleanvars($_SESSION['SELL_pict_url']) . "',
		contr_url = '" . $system->cleanvars($_SESSION['SELL_contr_url']) . "',
		category = " . $_SESSION['SELL_sellcat1'] . ",
		secondcat = " . intval($_SESSION['SELL_sellcat2']) . ",
		starting_price = '" . $system->input_money(($_SESSION['SELL_buy_now_only'] == 'n') ? $_SESSION['SELL_minimum_bid'] : $_SESSION['SELL_buy_now_price']) . "',
		minimum_bid = '" . $system->input_money(($_SESSION['SELL_buy_now_only'] == 'n') ? $_SESSION['SELL_minimum_bid'] : $_SESSION['SELL_buy_now_price']) . "',		
		shipping_cost = '" . $system->input_money($_SESSION['SELL_shipping_cost']) . "',
		shipping_cost_additional = '" . $system->input_money($_SESSION['SELL_additional_shipping_cost']) . "',
		reserve_price = '" . $system->input_money(($_SESSION['SELL_with_reserve'] == 'yes') ? $_SESSION['SELL_reserve_price'] : 0) . "',
		buy_now = '" . $system->input_money(($_SESSION['SELL_with_buy_now'] == 'yes') ? $_SESSION['SELL_buy_now_price'] : 0) . "',
		bn_only = '" . $_SESSION['SELL_buy_now_only'] . "',
		auction_type = '" . $_SESSION['SELL_atype'] . "',
		duration = '" . $_SESSION['SELL_duration'] . "',
		increment = " . $system->input_money($_SESSION['SELL_customincrement']) . ",
		shipping = '" . $_SESSION['SELL_shipping'] . "',
		payment = '" . $payment_text . "',
		international = " . (($_SESSION['SELL_international']) ? 1 : 0) . ",
		ends = '" . $a_ends . "',
		photo_uploaded = " . (($_SESSION['SELL_file_uploaded'])? 1 : 0) . ",
		quantity = " . $_SESSION['SELL_iquantity'] . ",
		relist = " . intval($_SESSION['SELL_relist']) . ",
		shipping_terms = '" . $system->cleanvars($_SESSION['SELL_shipping_terms']) . "',
		closed = 0,
		bold = '" . $_SESSION['SELL_is_bold'] . "',
		highlighted = '" . $_SESSION['SELL_is_highlighted'] . "',
		featured = '" . $_SESSION['SELL_is_featured'] . "',
		tax = '" . $_SESSION['SELL_is_taxed'] . "',
		taxinc = '" . $_SESSION['SELL_tax_included'] . "',
		current_fee = current_fee + " . $fee;
		
	    // Add contract information if needed
		if($_SESSION['SELL_contract_products']) $query .= ', contract_products = "' . $_SESSION['SELL_contract_products'] . '"';	
		if($_SESSION['SELL_contract_specs_brands']) $query .= ', contract_specs_brands = "' . $_SESSION['SELL_contract_specs_brands'] . '"';
		if($_SESSION['SELL_contract_quantity']) $query .= ', contract_quantity = "' . $_SESSION['SELL_contract_quantity'] . '"';
		if($_SESSION['SELL_contract_price_unit']) $query .= ', contract_price_unit = "' . $_SESSION['SELL_contract_price_unit'] . '"';
		if($_SESSION['SELL_contract_currency']) $query .= ', contract_currency = "' . $_SESSION['SELL_contract_currency'] . '"';
		if($_SESSION['SELL_contract_amount']) $query .= ', contract_amount = "' . $_SESSION['SELL_contract_amount'] . '"';
		if($_SESSION['SELL_contract_origin']) $query .= ', contract_origin = "' . $_SESSION['SELL_contract_origin'] . '"';
		if($_SESSION['SELL_contract_packing']) $query .= ', contract_packing = "' . $_SESSION['SELL_contract_packing'] . '"';
		if($_SESSION['SELL_contract_incoterms']) $query .= ', contract_incoterms = "' . $_SESSION['SELL_contract_incoterms'] . '"';
		if($_SESSION['SELL_contract_delivery_dates']) $query .= ', contract_delivery_dates = "' . $_SESSION['SELL_contract_delivery_dates'] . '"';
		if($_SESSION['SELL_contract_delivery_address']) $query .= ', contract_delivery_address = "' . $_SESSION['SELL_contract_delivery_address'] . '"';
		if($_SESSION['SELL_contract_delivery_details']) $query .= ', contract_delivery_details = "' . $_SESSION['SELL_contract_delivery_details'] . '"';
		if($_SESSION['SELL_contract_payment_condition']) $query .= ', contract_payment_condition = "' . $_SESSION['SELL_contract_payment_condition'] . '"';
		if($_SESSION['SELL_contract_remarks_disclaimers']) $query .= ', contract_remarks_disclaimers = "' . $_SESSION['SELL_contract_remarks_disclaimers'] . '"';

		
		$query .= $extraquery;
		$query .= " WHERE id = " . $_SESSION['SELL_auction_id'];
	return $query;
}

function addauction()
{
	global $DBPrefix, $_SESSION, $user, $a_starts, $a_ends, $payment_text, $system, $fee;

	// set values
	$min_bid = $system->input_money(($_SESSION['SELL_buy_now_only'] == 'n') ? $_SESSION['SELL_minimum_bid'] : $_SESSION['SELL_buy_now_price']);
	$reserve_price = $system->input_money(($_SESSION['SELL_with_reserve'] == 'yes') ? $_SESSION['SELL_reserve_price'] : 0);
	$bn_price = $system->input_money(($_SESSION['SELL_with_buy_now'] == 'yes') ? $_SESSION['SELL_buy_now_price'] : 0);
	$query =  
        "INSERT INTO " . $DBPrefix . "auctions (
            user,
	        title,
	        subtitle,
	        starts,
	        description,
	        pict_url,
	        contr_url,
	        category,
	        secondcat,
	        minimum_bid,
	        shipping_cost,
	        shipping_cost_additional,
	        reserve_price,
	        buy_now,
	        auction_type,
	        duration,
	        increment,
	        shipping,
	        payment,
	        international,
	        ends,
	        photo_uploaded,
	        quantity,
	        relist,
	        shipping_terms,
	        bn_only,
	        bold,
	        highlighted,
	        featured,
	        current_fee,
	        tax,
	        taxinc,
	        conditions";
	        
	        if($_SESSION['SELL_contract_products']) $query .= ', contract_products';	
    		if($_SESSION['SELL_contract_specs_brands']) $query .= ', contract_specs_brands';
    		if($_SESSION['SELL_contract_quantity']) $query .= ', contract_quantity';
    		if($_SESSION['SELL_contract_price_unit']) $query .= ', contract_price_unit';
    		if($_SESSION['SELL_contract_currency']) $query .= ', contract_currency';
    		if($_SESSION['SELL_contract_amount']) $query .= ', contract_amount';
    		if($_SESSION['SELL_contract_origin']) $query .= ', contract_origin';
    		if($_SESSION['SELL_contract_packing']) $query .= ', contract_packing';
    		if($_SESSION['SELL_contract_incoterms']) $query .= ', contract_incoterms';
    		if($_SESSION['SELL_contract_delivery_dates']) $query .= ', contract_delivery_dates';
    		if($_SESSION['SELL_contract_delivery_address']) $query .= ', contract_delivery_address';
    		if($_SESSION['SELL_contract_delivery_details']) $query .= ', contract_delivery_details';
    		if($_SESSION['SELL_contract_payment_condition']) $query .= ', contract_payment_condition';
    		if($_SESSION['SELL_contract_remarks_disclaimers']) $query .= ', contract_remarks_disclaimers';
    		
    $query .= "
        ) VALUES (" . 
            $user->user_data['id'] .
            ",  '" . $system->cleanvars($_SESSION['SELL_title']) . "'" .
            ", '" . $system->cleanvars($_SESSION['SELL_subtitle']) . "'" .
            ", '" .  $a_starts . "', '" . addslashes($_SESSION['SELL_description']) . "'" .
            ", '" . $system->cleanvars($_SESSION['SELL_pict_url']) . "'" .
            ", '" . $system->cleanvars($_SESSION['SELL_contr_url']) . "'" .
            ",  " . $_SESSION['SELL_sellcat1'] . "" .
            ",   " . intval($_SESSION['SELL_sellcat2']) . "" .
            ",  '" . $min_bid . "'" .
            ", '" . $system->input_money($_SESSION['SELL_shipping_cost']) . "'" .
            ", '" . $system->input_money($_SESSION['SELL_additional_shipping_cost']) . "'" .
            ", '" . $reserve_price . "'" .
            ", '" . $bn_price . "'" .
            ", '" . $_SESSION['SELL_atype'] . "'" .
            ", '" . $_SESSION['SELL_duration'] . "'" .
            ", '" . $system->input_money($_SESSION['SELL_customincrement']) . "'" .
            ", '" . $_SESSION['SELL_shipping'] . "'" .
            ", '" . $payment_text . "'" .
            ",  " . (($_SESSION['SELL_international']) ? 1 : 0) . "" .
            ",  '" . $a_ends . "'" .
            ",  " . (($_SESSION['SELL_file_uploaded']) ? 1 : 0) . "" .
            ",   " . $_SESSION['SELL_iquantity'] . "" .
            ",   " . intval($_SESSION['SELL_relist']) . "" .
            ",  '" . $system->cleanvars($_SESSION['SELL_shipping_terms']) . "'" .
            ", '" . $_SESSION['SELL_buy_now_only'] . "'" .
            ", '" . $_SESSION['SELL_is_bold'] . "'" .
            ", '" . $_SESSION['SELL_is_highlighted'] . "'" .
            ", '" . $_SESSION['SELL_is_featured'] . "'" .
            ",  " . $fee . "" .
            ",  '" . $_SESSION['SELL_is_taxed'] . "'" .
            ", '" . $_SESSION['SELL_tax_included'] . "'" .
            ", '" . $user->getActiveConditions() . "'";
    
    if($_SESSION['SELL_contract_products']) $query .= ', "' . $_SESSION['SELL_contract_products'] . '"';	
	if($_SESSION['SELL_contract_specs_brands']) $query .= ', "' . $_SESSION['SELL_contract_specs_brands'] . '"';
	if($_SESSION['SELL_contract_quantity']) $query .= ', "' . $_SESSION['SELL_contract_quantity'] . '"';
	if($_SESSION['SELL_contract_price_unit']) $query .= ', "' . $_SESSION['SELL_contract_price_unit'] . '"';
	if($_SESSION['SELL_contract_currency']) $query .= ', "' . $_SESSION['SELL_contract_currency'] . '"';
	if($_SESSION['SELL_contract_amount']) $query .= ', "' . $_SESSION['SELL_contract_amount'] . '"';
	if($_SESSION['SELL_contract_origin']) $query .= ', "' . $_SESSION['SELL_contract_origin'] . '"';
	if($_SESSION['SELL_contract_packing']) $query .= ', "' . $_SESSION['SELL_contract_packing'] . '"';
	if($_SESSION['SELL_contract_incoterms']) $query .= ', "' . $_SESSION['SELL_contract_incoterms'] . '"';
	if($_SESSION['SELL_contract_delivery_dates']) $query .= ', "' . $_SESSION['SELL_contract_delivery_dates'] . '"';
	if($_SESSION['SELL_contract_delivery_address']) $query .= ', "' . $_SESSION['SELL_contract_delivery_address'] . '"';
	if($_SESSION['SELL_contract_delivery_details']) $query .= ', "' . $_SESSION['SELL_contract_delivery_details'] . '"';
	if($_SESSION['SELL_contract_payment_condition']) $query .= ', "' . $_SESSION['SELL_contract_payment_condition'] . '"';
	if($_SESSION['SELL_contract_remarks_disclaimers']) $query .= ', "' . $_SESSION['SELL_contract_remarks_disclaimers'] . '"';
    		
    $query .= ")";
        
    return $query;
}

function addoutstanding()
{
	global $DBPrefix, $fee_data, $user, $system, $fee, $_SESSION;
	return "INSERT INTO " . $DBPrefix . "useraccounts (auc_id,user_id,date,setup,featured,bold,highlighted,subtitle,relist,reserve,buynow,image,extcat,total,paid) VALUES
	('" . $_SESSION['SELL_auction_id'] . "','" . $user->user_data['id'] . "', '" . time() . "', '" . $fee_data['setup'] . "', '" . $fee_data['hpfeat_fee'] . "', '" . $fee_data['bolditem_fee'] . "', '" . $fee_data['hlitem_fee'] . "', '" . $fee_data['subtitle_fee'] . "', '" . $fee_data['relist_fee'] . "', '" . $fee_data['rp_fee'] . "', '" . $fee_data['buyout_fee'] . "', '" . $fee_data['picture_fee'] . "', '" . $fee_data['excat_fee'] . "', '" . $fee . "', 0)";
}

function remove_bids($auction_id)
{
	global $DBPrefix, $system;
	$query = "DELETE FROM " . $DBPrefix . "bids WHERE auction = " . $auction_id;
	$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
}

function get_fee($minimum_bid, $just_fee = true)
{
	global $system, $DBPrefix, $buy_now_price, $reserve_price, $is_bold, $is_highlighted, $is_featured, $_SESSION, $subtitle, $sellcat2, $relist;

	$query = "SELECT * FROM " . $DBPrefix . "fees ORDER BY type, fee_from ASC";
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);

	$fee_value = 0;
	$fee_data = array();
	while ($row = mysql_fetch_assoc($res))
	{
		if ($minimum_bid >= $row['fee_from'] && $minimum_bid <= $row['fee_to'] && $row['type'] == 'setup')
		{
			if ($row['fee_type'] == 'flat')
			{
				$fee_data['setup'] = $row['value'];
				$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
			}
			else
			{
				$tmp = bcdiv($row['value'], '100', $system->SETTINGS['moneydecimals']);
				$tmp = bcmul($tmp, $minimum_bid, $system->SETTINGS['moneydecimals']);
				$fee_data['setup'] = $tmp;
				$fee_value = bcadd($fee_value, $tmp, $system->SETTINGS['moneydecimals']);
			}
		}
		if ($row['type'] == 'buyout_fee' && $buy_now_price > 0)
		{
			$fee_data['buyout_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'rp_fee' && $reserve_price > 0)
		{
			$fee_data['rp_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'bolditem_fee' && $is_bold == 'y')
		{
			$fee_data['bolditem_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'hlitem_fee' && $is_highlighted == 'y')
		{
			$fee_data['hlitem_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'hpfeat_fee' && $is_featured == 'y')
		{
			$fee_data['hpfeat_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'picture_fee' && count($_SESSION['UPLOADED_PICTURES']) > 0)
		{
			$tmp = bcmul(count($_SESSION['UPLOADED_PICTURES']), $row['value'], $system->SETTINGS['moneydecimals']);
			$fee_data['picture_fee'] = $tmp;
			$fee_value = bcadd($fee_value, $tmp, $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'subtitle_fee' && !empty($subtitle))
		{
			$fee_data['subtitle_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'excat_fee' && $sellcat2 > 0)
		{
			$fee_data['excat_fee'] = $row['value'];
			$fee_value = bcadd($fee_value, $row['value'], $system->SETTINGS['moneydecimals']);
		}
		if ($row['type'] == 'relist_fee' && $relist > 0)
		{
			$fee_data['relist_fee'] = ($row['value'] * $relist);
			$fee_value = bcadd($fee_value, ($row['value'] * $relist), $system->SETTINGS['moneydecimals']);
		}
	}

	if ($_SESSION['SELL_action'] == 'edit')
	{
		global $user;

		$query = "SELECT * FROM " . $DBPrefix . "useraccounts WHERE auc_id = " . $_SESSION['SELL_auction_id'] . " AND user_id = " . $user->user_data['id'];
		$res = mysql_query($query);
		$system->check_mysql($res, $query, __LINE__, __FILE__);
		// build an array full of everything the user has been charged for the auction do far
		$past_fees = array();
		while($row = mysql_fetch_assoc($res, 0))
		{
			foreach ($row as $k => $v)
			{
				if (isset($past_fees[$k]))
				{
					$past_fees[$k] += $v;
				}
				else
				{
					$past_fees[$k] = $v;
				}
			}
		}

		$diff = 0; // difference from last payment
		$fee_data['setup'] = 0; // shouldn't have to pay setup for an edit...
		$diff = bcadd($diff, $past_fees['setup'], $system->SETTINGS['moneydecimals']);
		if ($past_fees['bold'] == $fee_data['bolditem_fee'])
		{
			$diff = bcadd($diff, $fee_data['bolditem_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['bolditem_fee'] = 0;
		}
		if ($past_fees['highlighted'] == $fee_data['hlitem_fee'])
		{
			$diff = bcadd($diff, $fee_data['hlitem_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['hlitem_fee'] = 0;
		}
		if ($past_fees['subtitle'] == $fee_data['subtitle_fee'])
		{
			$diff = bcadd($diff, $fee_data['subtitle_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['subtitle_fee'] = 0;
		}
		if ($past_fees['relist'] == $fee_data['relist_fee'])
		{
			$diff = bcadd($diff, $fee_data['relist_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['relist_fee'] = 0;
		}
		if ($past_fees['reserve'] == $fee_data['rp_fee'])
		{
			$diff = bcadd($diff, $fee_data['rp_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['rp_fee'] = 0;
		}
		if ($past_fees['buynow'] == $fee_data['buyout_fee'])
		{
			$diff = bcadd($diff, $fee_data['buyout_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['buyout_fee'] = 0;
		}
		if ($past_fees['image'] == $fee_data['picture_fee'])
		{
			$diff = bcadd($diff, $fee_data['picture_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['picture_fee'] = 0;
		}
		if ($past_fees['extcat'] == $fee_data['excat_fee'])
		{
			$diff = bcadd($diff, $fee_data['excat_fee'], $system->SETTINGS['moneydecimals']);
			$fee_data['excat_fee'] = 0;
		}
		$fee_value = bcsub($fee_value, $diff, $system->SETTINGS['moneydecimals']);
		if ($fee_value < 0)
		{
			$fee_value = 0;
		}
	}

	if ($just_fee)
	{
		$return = $fee_value;
	}
	else
	{
		$return = array($fee_value, $fee_data);
	}

	return $return;
}

function update_cat_counters($add, $category)
{
	global $_SESSION, $DBPrefix, $system, $catscontrol;

	$query = "SELECT left_id, right_id, level FROM " . $DBPrefix . "categories WHERE cat_id = " . $category;
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	$parent_node = mysql_fetch_assoc($res);
	$crumbs = $catscontrol->get_bread_crumbs($parent_node['left_id'], $parent_node['right_id']);

	$addsub = ($add) ? '+' : '-';
	for ($i = 0; $i < count($crumbs); $i++)
	{
		$query = "UPDATE " . $DBPrefix . "categories SET sub_counter = sub_counter " . $addsub . " 1 WHERE cat_id = " . $crumbs[$i]['cat_id'];
		$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
	}
}

function get_category_string($sellcat)
{
	global $DBPrefix, $system, $catscontrol, $category_names;

	if (empty($sellcat) || !isset($sellcat))
		return '';

	$query = "SELECT left_id, right_id, level FROM " . $DBPrefix . "categories WHERE cat_id = " . intval($sellcat);
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	$parent_node = mysql_fetch_assoc($res);

	$TPL_categories_list = '';
	$crumbs = $catscontrol->get_bread_crumbs($parent_node['left_id'], $parent_node['right_id']);
	for ($i = 0; $i < count($crumbs); $i++)
	{
		if ($crumbs[$i]['cat_id'] > 0)
		{
			if ($i > 0)
			{
				$TPL_categories_list .= ' &gt; ';
			}
			$TPL_categories_list .= $category_names[$crumbs[$i]['cat_id']];
		}
	}
	return $TPL_categories_list;
}

function check_gateway($gateway)
{
	global $user;
	if ($gateway == 'paypal' && !empty($user->user_data['paypal_email']))
		return true;
	if ($gateway == 'authnet' && !empty($user->user_data['authnet_id']) && !empty($user->user_data['authnet_pass']))
		return true;
	if ($gateway == 'worldpay' && !empty($user->user_data['worldpay_id']))
		return true;
	if ($gateway == 'moneybookers' && !empty($user->user_data['moneybookers_email']))
		return true;
	if ($gateway == 'toocheckout_id' && !empty($user->user_data['toocheckout_id']))
		return true;
	return false;
}
?>