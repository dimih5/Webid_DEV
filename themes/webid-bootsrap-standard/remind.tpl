<!-- IF TQTY gt 1 -->
<script type="text/javascript">

$(document).ready(function(){

	$("#qty").keydown(function(){

		$("#bidcost").text(($("#qty").val())*($("#bid").val()) + ' {CURRENCY}');

	});

	$("#bid").keydown(function(){

		$("#bidcost").text(($("#qty").val())*($("#bid").val()) + ' {CURRENCY}');

	});

});

</script>
<!-- ENDIF -->

<div class="content">
<div class="form-actions"> <a class="btn btn-primary" href="{SITEURL}item.php?id={ID}"><i class="icon-chevron-left icon-white"></i> {L_138}</a>{BID_HISTORY} </div>
<legend>
{ERROR}
</legend>
