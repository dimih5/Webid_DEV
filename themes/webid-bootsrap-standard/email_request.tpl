<script type="text/javascript">

function SubmitFriendForm() {

    document.friend.submit();

}



function ResetFriendForm() {

    document.friend.reset();

}

</script>



<div class="form-actions"> <a class="btn btn-primary" href="profile.php?user_id={USERID}"><i class="icon-chevron-left icon-white"></i> {L_2000}</a> </div>

<!-- IF B_FLASH_MSG -->
    <div class="span6 offset3 well flash-msg {FLASH_MSG_CLASS}">
        <p>{FLASH_MSG}</p>
    </div>
<!-- ENDIF -->
    
<div class="row">

  <div class="span6 offset3 well">

    <legend> {L_2001} {COMPANY}</legend>

    <!-- IF MESSAGE ne '' -->

    <div align="center" class="alert alert-info">{MESSAGE}</div>

    <!-- ELSE -->

    <FORM class="form-horizontal" NAME="sendemail" ACTION="email_request.php?user_id={USERID}" METHOD=POST>

      <!-- IF ERROR ne '' -->

      <div class="alert alert-error"> {ERROR} </div>

      <!-- ENDIF -->

      <!-- IF B_LOGGED_IN eq false -->

      <div class="control-group">

        <label class="control-label" for="inputEmail">{L_006}</label>

        <div class="controls">

          <INPUT TYPE="text" NAME="sender_email" SIZE="25" VALUE="">

        </div>

      </div>

      <!-- ENDIF -->

      <div class="control-group">

        <label class="control-label" for="inputEmail">{L_2002}</label>

        <div class="controls">

          <TEXTAREA NAME="sender_question" COLS="35" ROWS="6">{SELLER_QUESTION}</TEXTAREA>

        </div>

      </div>

	  <INPUT TYPE="hidden" name="csrftoken" value="{_CSRFTOKEN}"> 

      <INPUT TYPE="hidden" NAME="action" VALUE="{L_2003}">

      <div class="form-actions">

      <INPUT TYPE=submit NAME="" VALUE="{L_5201}"  class="btn btn-primary" />

      <INPUT TYPE=reset NAME="" VALUE="{L_035}" class="btn">

      <p>

        </TD>

      </p>

    </FORM>

    <!-- ENDIF -->

  </div>

</div>

</div>

