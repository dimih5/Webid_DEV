<div class="content">
	<div class="tableContent2">
		<div class="titTable2">
			{TITLE}
		</div>
		<div class="table2">
			<form action="{ASSLURL}buy_now.php?action=buy&id={ID}" method="POST">
				<table border=0 width="100%" cellspacing="0" cellpadding="4">
					<tr>
						<td align=right width="40%"><b>{L_017} :</b></td>
						<td width="60%">{TITLE}</td>
					</tr>
					<tr>
						<td align=right width="50%"><b>{L_125} :</b></td>
						<td>{SELLER} {SELLERNUMFBS} {FBICON}</td>
					</tr>
					<tr>
						<td align=right width="40%"><b>{L_497} :</b></td>
						<td>{BN_PRICE}</td>
					</tr>
<!-- IF ERROR ne '' -->
					<tr>
						<td colspan=2 align="center" class="errfont">{ERROR}</td>
					</tr>
<!-- ENDIF -->
					<tr>
						<td colspan=2 align="center">&nbsp;</td>
					</tr>
<!-- IF B_NOTBOUGHT -->
					<tr>
						<td align="right">{L_003}</td>
						<td><b>{YOURUSERNAME}</b></td>
					</tr>
	<!-- IF B_USERAUTH -->
					<tr>
						<td align="right">{L_004} </td>
						<td>
							<input type="password" name="password" size=15 maxlength=15 value="" />
						</td>
					</tr>
	<!-- ENDIF -->
				</table>
				<div style="text-align:center">
					<input type="submit" name="" value="{L_496}" class="button" />
				</div>
<!-- ELSE -->
				<tr>
					<td colspan="2" align="center">{L_498}</td>
				</tr>
				</table>
<!-- ENDIF -->
			</form>
		</div>
	</div>
</div>
