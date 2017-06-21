$(".question_answers input").change(function() { 

   choiceThumb();
   checkAnswered();
   		

});


function choiceThumb(){

    var types = new Array('qui','quand','ou');

    types.forEach(function(name) {
        

        $('[name="'+name+'"]').each(function () { 
           
            if ($(this).is(":checked")) { 
                $("#question_answer_"+$(this).val()).addClass('selected');

                var thumb_src = $('.question_answers #thumb_'+$(this).val()).attr('src');

            
                selectChoiceThumb(name, thumb_src);
            }
            else{            
                $("#question_answer_"+$(this).val()).removeClass('selected'); 
            }
        });

    })

}

function selectChoiceThumb(type, thumb_src){
     
    $('#choice_'+type+' img').attr('src',thumb_src);
}





function checkAnswered(){
    
    var qui = $('input[name=qui]:checked').val();    
    var quand = $('input[name=quand]:checked').val();
    var ou = $('input[name=ou]:checked').val();

    if(qui && quand && ou){
        $('#bt_submit').show();
        $('#bt_waiting').hide();
    }
    else{
        $('#bt_submit').hide();
        $('#bt_waiting').show();
    }
}



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


