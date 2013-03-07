<div class="content">
  <div class="span12">
    <div class="span4"> {L_5030}<br/>
	<class="span3"> <a href="{SITEURL}boards.php">{L_5058}</a> </div>
    <div class="span12 offset1" >
      <table width="70%" cellspacing="0" cellpadding="6" bgcolor="#EEEEEE">
        <tr>
		<div class="span12 offset2"><h2>{L_30_0181}: {BOARD_NAME}</h2></div>
        <td align="center"><form name="messageboard" action="" method="post">
              <input type="hidden" name="csrftoken" value="{_CSRFTOKEN}">
              <input type="hidden" name="action" value="insertmessage">
              <input type="hidden" name="board_id" value="{BOARD_ID}">
              <!-- IF B_LOGGED_IN eq false -->
              <p class="errfont">{L_5056}</p>
              <!-- ENDIF -->
              <textarea name="newmessage" cols="60" rows="5"></textarea>
              <br>
              <input type="submit" name="Submit" value="{L_5057}" class="button">
			  <hr />
			  <form name="emailboard" action="" method="post">
					<!-- IF CHECKSTATE eq 'falser' -->
					<input type="checkbox" name="emailcheckbox" value="0" onClick="this.form.submit()">{L_CM_2026_0001}
					<!-- ENDIF -->
					<!-- IF CHECKSTATE eq 'truer' -->
					<input type="hidden" name="emailcheckbox" value="1">
					<input type="checkbox" name="emailcheckbox" value="0" onClick="this.form.submit()" checked>{L_CM_2026_0001}
					<!-- ENDIF -->
				</form>
			  <hr />
            </form></td>
        </tr>
      </table>
	  <div class="span8 offset3"><h3>{L_5059}</h3></div>
      <table width="70%" cellspacing="0" cellpadding="2">
      <div class="span8"><hr /></div>
        <!-- BEGIN msgs -->
        <tr>
          
          <div class="span10"><!-- IF msgs.USERNAME ne '' -->
            {L_5060} <b>{msgs.USERNAME}</b> - {msgs.POSTED}
            <!-- ELSE -->
            {L_5060} <b>{L_5061}</b> - {msgs.POSTED}
            <!-- ENDIF -->
			<div class="span5"> {msgs.MSG}
          </div></div>
		  <div class="span8"><hr /></div>
        </tr>
		<br />

        <!-- END msgs -->
      </table>
      <div class="padding centre"> {L_5117}&nbsp;{PAGE}&nbsp;{L_5118}&nbsp;{PAGES} <br>
        {PREV}
        <!-- BEGIN pages -->
        {pages.PAGE}&nbsp;
        <!-- END pages -->
        {NEXT} </div>
    </div>
  </div>
</div>
