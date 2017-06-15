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


