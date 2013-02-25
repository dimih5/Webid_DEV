<div class="content">
<div class="tableContent2">
	<div class="titTable2 rounded-top rounded-bottom">
		{L_5030}
	</div>
	<div class="titTable3">
		<a href="{SITEURL}boards.php">{L_5058}</a>
	</div>
	<div class="padding center" align="center">
<!-- IF B_LOGGED_IN eq false -->
        <div class="error-box">
            {L_5056}
        </div>
<!-- ENDIF -->
		<table width="70%" cellspacing="0" cellpadding="6" bgcolor="#EEEEEE">
		<tr>
			<tr>
				<td width="100%" valign="top">{L_30_0181} {BOARD_NAME}</td>
            </tr>
            <!-- IF ERROR ne '' -->
            <tr>
                <td align="center">
                	<div class="error">{ERROR}</div>
                </td>
            </tr>
            <!-- ENDIF --> 
			</tr>
			<td align="center">
			<form name="messageboard" action="" method="post">
            	<input type="hidden" name="csrftoken" value="{_CSRFTOKEN}">
				<input type="hidden" name="action" value="insertmessage">
				<input type="hidden" name="board_id" value="{BOARD_ID}">
				<textarea name="newmessage" cols="60" rows="5"></textarea>
				<br>
				<input type="submit" name="Submit" value="{L_5057}" class="button"> <br>		
				<form name="emailboard" action="" method="post">
					<!-- IF CHECKSTATE eq 'falser' -->
					<input type="checkbox" name="emailcheckbox" value="0" onClick="this.form.submit()">{L_CM_2026_0001}
					<!-- ENDIF -->
					<!-- IF CHECKSTATE eq 'truer' -->
					<input type="hidden" name="emailcheckbox" value="1">
					<input type="checkbox" name="emailcheckbox" value="0" onClick="this.form.submit()" checked>{L_CM_2026_0001}
					<!-- ENDIF -->
				</form>
			</form>

			</td>
		</tr>
		</table>
		<br>
		<br>
		<table width="70%" cellspacing="0" cellpadding="2">
		<tr>
			<td colspan=2 valign="top" class="titTable4" bgcolor="#eeeeee">
				{L_5059}
			</td>
		</tr>
<!-- BEGIN msgs -->
		<tr>
			<td align="left" valign="top" width="100%" {msgs.BGCOLOUR}>
				{msgs.MSG}
			</td>
			<td valign="top" align="right" bgcolor="#eeeeee" nowrap="nowrap">
	<!-- IF msgs.USERNAME ne '' -->
				{L_5060} <b>{msgs.USERNAME}</b> - {msgs.POSTED}
	<!-- ELSE -->
				{L_5060} <b>{L_5061}</b> - {msgs.POSTED}
	<!-- ENDIF -->
			</td>
		</tr>
<!-- END msgs -->
		</table>
        <div class="padding centre">
            {L_5117}&nbsp;{PAGE}&nbsp;{L_5118}&nbsp;{PAGES}
            <br>
            {PREV}
<!-- BEGIN pages -->
            {pages.PAGE}&nbsp;
<!-- END pages -->
            {NEXT}
        </div>
	</div>
</div>
</div>