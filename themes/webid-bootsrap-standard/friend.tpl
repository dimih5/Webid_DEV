
<!-- <div class="form-actions"> <a class="btn btn-primary" href="item.php?id={ID}"> <i class="icon-chevron-left icon-white"></i> {L_138}</a> </div> -->
<!-- IF EMAILSENT eq '' -->
<div align="center" class="alert alert-info"> 
    <strong>{L_P6}</strong><br />
  <br />
</div>
<!-- ELSE -->
<div class="row">
  <div class="span8 offset2 well">
    <legend>Invite business relation</legend>
    <form class="form-horizontal" name="friend" action="friend.php" method="post">
      <input type="hidden" name="csrftoken" value="{_CSRFTOKEN}">
      <!-- IF ERROR ne '' -->
      <div align="center" class="alert alert-error">{ERROR}</div>
      <!-- ENDIF -->
      <br />
      <div class="control-group">
        <label class="control-label" for="inputEmail">{L_P1}</label>
        <div class="controls">
          <input type="text" name="contact_name" size="25" value="{CONTACT_NAME}">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">{L_P2}</label>
        <div class="controls">
          <input type="text" name="contact_email" size="25" value="{CONTACT_EMAIL}">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">{L_P3}</label>
        <div class="controls">
          <textarea name="contact_details" cols="30" rows="6">{CONTACT_DETAILS}</textarea>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">{L_P4}</label>
        <div class="controls">
          <textarea name="contact_description" cols="30" rows="6">{CONTACT_DESCRIPTION}</textarea>
        </div>
      </div>
      {CAPCHA}
      <div class="form-actions" style="margin-top:40px">
        <input type="hidden" name="id" value="{ID}">
        <input type="hidden" name="item_title" value="{TITLE}">
        <input type="hidden" name="action" value="sendmail">
        <input type="submit" name="" value="{L_5201}" class="btn btn-primary">
        <input type="reset" name="" value="{L_035}" class="btn">
      </div>
    </form>
  </div>
  <!-- ENDIF -->
</div>
</div>
