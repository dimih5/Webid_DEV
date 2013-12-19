<!-- INCLUDE header.tpl -->
    	<div style="width:25%; float:left;">
            <div style="margin-left:auto; margin-right:auto;">
            	<!-- INCLUDE sidebar-{CURRENT_PAGE}.tpl -->
            </div>
        </div>
    	<div style="width:75%; float:right;">
            <div class="main-box">
            	<h4 class="rounded-top rounded-bottom">{L_25_0010}&nbsp;&gt;&gt;&nbsp;{L_045}</h4>
            </div>

<!-- IF B_FLASH_MSG -->
    <div class="span6 offset3 well flash-msg {FLASH_MSG_CLASS}">
        <p>{FLASH_MSG}</p>
    </div>
<!-- ENDIF -->
    
<div class="row">

  <div class="span6 offset3 well">

    <FORM class="form-horizontal" NAME="sendemail" ACTION="bulkemail.php" METHOD=POST>

      <!-- IF ERROR ne '' -->

      <div class="alert alert-error"> {ERROR} </div>

      <!-- ENDIF -->
      
      <div class="control-group">

        <label class="control-label" for="inputSubject">Subject:</label>

        <div class="controls">

          <INPUT TYPE="text" NAME="subject" SIZE="25" VALUE="">

        </div>

      </div>

      <div class="control-group">

        <label class="control-label" for="inputEmail">{L_2002}</label>

        <div class="controls">

          <TEXTAREA NAME="msg" COLS="80" ROWS="20"></TEXTAREA>

        </div>

      </div>

	  <INPUT TYPE="hidden" name="csrftoken" value="{_CSRFTOKEN}"> 

      <INPUT TYPE="hidden" NAME="action" VALUE="bulkemail">

      <div class="form-actions">

      <INPUT TYPE=submit NAME="" VALUE="Send to all users"  class="btn btn-primary" />

      <p>

        </TD>

      </p>

    </FORM>


  </div>

</div>

</div>

            </div>
        </div>
<!-- INCLUDE footer.tpl -->