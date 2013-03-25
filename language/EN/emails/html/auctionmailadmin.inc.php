<table border="0" width="100%">
	<tr>
		<td colspan="3" height="35"><div style="font-size: 14px; font-weight: bold;">An item has been listed on {SITENAME}!</div></td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 12px;">Hello <b>{C_NAME}</b>,</td>
	</tr>
	<tr>
		<td colspan="3" height="50" style="font-size: 12px; padding-right: 6px;">An item has been successfully listed on {SITENAME}. The item will display instantly in search results.<br>Here are the listing details:</td>
	</tr>
	<tr>
		<td width="9%" rowspan="2"><img border="0" src="{SITE_URL}{A_PICURL}"></td>
		<td width="55%" rowspan="2">
		<table border="0" width="100%">
			<tr>
				<td colspan="2" style="font-size: 12px;"><a href="{SITE_URL}item.php?id={A_ID}">{A_TITLE}</a></td>

			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">Auction type:</td>
				<td align="left" style="font-size: 12px;">{A_TYPE}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">Item #:</td>
				<td align="left" style="font-size: 12px;">{A_ID}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">Starting bid:</td>
				<td align="left" style="font-size: 12px;">{A_MINBID}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">Reserve price:</td>
				<td align="left" style="font-size: 12px;">{A_RESERVE}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">Buy Now price:</td>
				<td align="left" style="font-size: 12px;">{A_BNPRICE}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;">End time:</td>
				<td align="left" style="font-size: 12px;">{A_ENDS}</td>
			</tr>
			<tr>
				<td width="22%" style="font-size: 12px;"></td>
				<td align="left" style="font-size: 12px;"><a href="{SITE_URL}user_menu.php?">Goto My {SITENAME}</a></td>
			</tr>
		</table>
		</td>
	</tr>
</table>