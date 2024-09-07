jQuery(document).ready(function($) {
    console.log("Countdown script loaded!");

    // Set the date we're counting down to
    var countDownDate = new Date("Dec 31, 2024 23:59:59").getTime();

    // Update the count down every 1 second
    var countdownfunction = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="countdown"
        $("#countdown").html(days + "d " + hours + "h " +
            minutes + "m " + seconds + "s ");

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(countdownfunction);
            $("#countdown").html("EXPIRED");
        }
    }, 1000);
});
