$(".radio-andrei-label input").change(function() { 
	// alert('hello');

	$('.radio-andrei-label input').each(function () { 
		if ($(this).is(":checked")) { 
        $(this).parent().parent().css("background-color", "yellow");
    }
    else 
    	$(this).parent().parent().css("background-color", "white"); 
 });
});


function aud_play_pause(object) {
    var myAudio = object.querySelector(".xnine-player");
    var myIcon = object.querySelector(".control");
    if (myAudio.paused) {
        myIcon.className = "control glyphicon glyphicon-pause";
        myAudio.play();
    } else {
        myIcon.className = "control glyphicon glyphicon-play";
        myAudio.pause();
    }
}


