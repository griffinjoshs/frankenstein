$(document).ready(function() {
    $("#tab-login").on('click', function () {
        $("#login").addClass('show').addClass('active')
        $("#register").removeClass('show').removeClass('active')
        $("#tab-login").addClass("active")
        $("#tab-register").removeClass("active")
    });

    $("#tab-register, #registerLink").on('click', function () {
        $("#register").addClass('show').addClass('active')
        $("#login").removeClass('show').removeClass('active')
        $("#tab-register").addClass("active")
        $("#tab-login").removeClass("active")
    });

    $(".logout-button").on("click", function() {
        logout();
    })


});

// FUNCTIONS -------------------------------------------------------------------

var systemMessageTimer = '',
systemMessageSlideOutHover = false;

var dev = false;

// NOTE: JS system message slide in
function systemMessageSlideIn(type, title, message, duration=5000, width=500, forceShow=0) {
    var messageId = Math.floor(Math.random() * 500),
    systemMessage = '',
    icon;

    if(type == 'info') {
        icon = 'fa-solid fa-bell';
    } else if(type == 'success') {
        icon = 'fa-solid fa-check';
    } else if(type == 'warning') {
        icon = 'fa-solid fa-circle-exclamation';
    } else if(type == 'error') {
        icon = 'fa-solid fa-circle-xmark';
    }
    systemMessage = `<div id="wrapper` + messageId + `" class="system-message-wrapper">
        <div id="system-message-container` + messageId + `" class="system-message-container ` + type + `">
            <div class="system-message-container-icon"><i class="` + icon + `"></i></div>
            <div class="system-message-container-info-wrap">
                <button class="system-message-container-close" onclick="systemMessageSlideOut(` + messageId + `)"><i class="fa-solid fa-xmark"></i></button>
                <h3>` + title + `</h3>
                <p>` + message + `</p>
            </div>
        </div>
    </div>`;
    $('#systemMessageContainer').prepend(systemMessage);
    $('.system-message-wrapper').fadeIn();
    $('#wrapper' + messageId).animate({width: width + 'px'});
    $('#system-message-container' + messageId).animate({width: width + 'px'});
    if (duration !== 0)
	{
		$('.system-message-wrapper').hover(function(){
	        systemMessageSlideOutHover=true;
	    },
	    function(){
	        systemMessageSlideOutHover=false;
	    });
        if(forceShow == 0) {
            systemMessageSlideOutTimer(duration, width, messageId, systemMessageSlideOutHover);
        }
	}
}

// NOTE: close JS system message
function systemMessageSlideOut(messageId,  width=500,) {
	if(messageId > 0) {
		$('#wrapper' + messageId).animate({width: '0px'});
		setTimeout(function() {
			$('#wrapper' + messageId).remove();
		}, 320);
	} else { // hide all
		$('.system-message-wrapper').animate({width: '0px'});
	setTimeout(function() {
		$('.system-message-wrapper').remove();
	}, 320);
	}
	
}

// NOTE: auto close JS system message based on duration set
function systemMessageSlideOutTimer(duration, w, messageId, systemMessageSlideOutHover=false)
{
	systemMessageTimer = setTimeout(function(){
		if (systemMessageSlideOutHover == false) {
			$('#wrapper' + messageId).animate({width: '0px'});
			setTimeout(function() {
				$('#wrapper' + messageId).remove();
			}, 320);
		} else {
			systemMessageSlideOutTimer(duration, w, messageId), systemMessageSlideOutHover;
		}
	}, duration);
}

// NOTE: validates all inputs in a form and returns the name of the input thats empty
function validateForm(formValues) {
    // console.log(formValues, "formValues");
    let validateError = [];
    $.each(formValues, function(i, item) {
        if(item.value == '') {
            validateError.push(item.name)
        }
    });
    if(validateError.length > 0) {
        return validateError;
    } else {
        return true;
    }
}

// NOTE: center a container based on the size of the screen
function sizeContainerToWindow(container) {
    if(container == undefined) {
        $("html").css("height", $(window).height());
        $("body").css("height", $(window).height());
    } else {
        container.css("height", $(window).height());
    }
}

// TODO: add a loader that hides on document ready.
function openGenericLoader() {
    $("#preloader").show();
}

function closeGenericLoader() {
    $("#preloader").hide();
}

function logout() {
    $.ajax({
        'url':'index.php?logout=true',
        'type':'GET',
        'success':function(data){
            window.location.reload();
        },'error':function(err) {
            console.log(err.responseText, "Error logging out: ");
        }
    });
}