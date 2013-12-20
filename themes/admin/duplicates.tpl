<!-- INCLUDE header.tpl -->
<script type="text/javascript">
$(document).ready(function() {
	$('#userfilter').change(function(){
		$('#filter').submit();
	});
});
</script>
    	<div style="width:25%; float:left;">
            <div style="margin-left:auto; margin-right:auto;">
            	<!-- INCLUDE sidebar-{CURRENT_PAGE}.tpl -->
            </div>
        </div>
    	<div style="width:75%; float:right;">
            <div class="main-box">
            	<h4 class="rounded-top rounded-bottom">{L_25_0010}&nbsp;&gt;&gt;&nbsp;{L_045}</h4>
<!-- IF ERROR ne '' -->
				<div class="error-box"><b>{ERROR}</b></div>
<!-- ENDIF -->
				<div class="plain-box">{TOTALUSERS} {L_301}</div>
                <table width="98%" cellpadding="0" cellspacing="0">
					<tr>
                        <th width="15%"><b>{L_P11}</b></th>
                        <th width="15%"><b>{L_P12}</b></th>
                        <th width="15%"><b>{L_P13}</b></th>
                        <th width="15%"><b>{L_P14}</b></th>
                        <th width="10%"><b>{L_297}</b></th>
                    </tr>
<!-- BEGIN users -->
                    <tr {users.BG}>
                        <td>
                        	<b>{users.USER_A_COMPANY}</b><br>
                            &nbsp;<a href="listauctions.php?uid={users.USER_A_ID}&offset={PAGE}" class="small">{L_5094}</a><br>
                            &nbsp;<a href="userfeedback.php?id={users.USER_A_ID}&offset={PAGE}" class="small">{L_503}</a><br>
                            &nbsp;<a href="viewuserips.php?id={users.USER_A_ID}&offset={PAGE}" class="small">{L_2_0004}</a>
                        </td>
                        <td>{users.USER_A_EMAIL}</td>
                        <td>
                            <b>{users.USER_B_COMPANY}</b><br>
                            &nbsp;<a href="listauctions.php?uid={users.USER_B_ID}&offset={PAGE}" class="small">{L_5094}</a><br>
                            &nbsp;<a href="userfeedback.php?id={users.USER_B_ID}&offset={PAGE}" class="small">{L_503}</a><br>
                            &nbsp;<a href="viewuserips.php?id={users.USER_B_ID}&offset={PAGE}" class="small">{L_2_0004}</a>
                        </td>
                        <td>{users.USER_B_EMAIL}</td>
                        <td nowrap>
                            <a href="duplicates.php?action=delete&id={users.ID}"><small>{L_008}</small></a>
                        </td>
                    </tr>
<!-- END users -->
                </table>
                <table width="98%" cellpadding="0" cellspacing="0" class="blank">
                    <tr>
                        <td align="center">
                            {L_5117}&nbsp;{PAGE}&nbsp;{L_5118}&nbsp;{PAGES}
                            <br>
                            {PREV}
<!-- BEGIN pages -->
                            {pages.PAGE}&nbsp;&nbsp;
<!-- END pages -->
                            {NEXT}
                        </td>
                    </tr>
				</table>
            </div>

                <div class="main-box">
                <form>
                    <label>User A</label>
                    <select name="user_a">
                        <!-- BEGIN form_users -->
                        <option value="{form_users.ID}">{form_users.COMPANY} ({form_users.EMAIL})</option>
                        <!-- END form_users -->
                    </select>
                    <label>User B</label>
                    <select name="user_b">
                        <!-- BEGIN form_users -->
                        <option value="{form_users.ID}">{form_users.COMPANY} ({form_users.EMAIL})</option>
                        <!-- END form_users -->
                    </select>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="csrftoken" value="{CSRF_TOKEN}">
                    <input type="submit" name="act" class="centre" value="Add">
                </form>
                </div>

        </div>
<!-- INCLUDE footer.tpl -->