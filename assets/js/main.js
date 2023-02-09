$(document).ready(function() {
    $("#tab-login").on('click', function () {
        $("#login").addClass('show').addClass('active')
        $("#register").removeClass('show').removeClass('active')
        $("#tab-login").addClass("active")
        $("#tab-register").removeClass("active")
    })

    $("#tab-register, #registerLink").on('click', function () {
        $("#register").addClass('show').addClass('active')
        $("#login").removeClass('show').removeClass('active')
        $("#tab-register").addClass("active")
        $("#tab-login").removeClass("active")
    })
    
});