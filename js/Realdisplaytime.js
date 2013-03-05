$(document).ready(function () {

function padlength(what) {
	var output = (what.toString().length == 1) ? '0' + what : what;
	return output;
}

if ($('.ending_counter')[0]){
setInterval(function(){$('.ending_counter').each(function displaytime() {
var currenttime = 0; //reset counter
var currenttime = $(this).attr('time');
	currenttime -= 1;
	if (currenttime > 0) 
	{
		var hours = Math.floor(currenttime / 3600);
		var mins = Math.floor((currenttime - (hours * 3600)) / 60);
		var secs = Math.floor(currenttime - (hours * 3600) - (mins * 60));
		var timestring = padlength(hours) + ':' + padlength(mins) + ':' + padlength(secs);
		$(this).html(timestring);
		$(this).removeProp('time');
		$(this).attr('time', currenttime);
	}
	else 
	{
		$("#ending_counter").html('<div class="alert">{L_911}</div');
	}
});
}, 1000)
};
});