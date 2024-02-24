const pusher = new Pusher('a4c14476f0cf642e26e1', {
    cluster: 'eu'
});
$(document).ready(function($) {
    axios.get('https://dashboard.fluffici.eu/build/E').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            $('#version').text('Version : ' + response.data.version)
            $('#rev').text('Rev : ' + response.data.rev)
        }
    })

    const fiveMinutes = 60 * 30;
    const display = document.getElementById('otp-expiration');
    if (display !== null) {
        setTimeout(() => {
            startTimer(fiveMinutes, display);
        }, 1500)
    }
    pusher.connection.bind('state_change', function(states) {
        const prevState = states.previous;
        const currState = states.current;
        if (prevState === 'connected' && currState === 'disconnected') {
            console.log('Connection lost');
        } else if (prevState === 'disconnected' && currState === 'connected') {
            console.log('Connection established');
        }
    });
});

function enableNotifications() {
    beamsClient.start().then(() => console.log("Registered with beams!"));
}

/**
 * Starts a timer for the given duration and updates the display with the remaining time.
 *
 * @param {number} duration - The duration of the timer in seconds.
 * @param {HTMLElement} display - The element where the timer will be displayed.
 *
 * @return {void}
 */
function startTimer(duration, display) {
    let start = Date.now(),
        diff,
        minutes,
        seconds;

    function timer() {
        // get the number of seconds that have elapsed since
        // startTimer() was called
        diff = duration - (((Date.now() - start) / 1000) | 0);

        // does the same job as parseInt truncates the float
        minutes = (diff / 60) | 0;
        seconds = (diff % 60) | 0;

        minutes = minutes < 60 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.innerHTML = minutes + ":" + seconds;

        if (diff <= 0) {
            display.innerHTML = "Expired"
        }
    }
    // we don't want to wait a full second before the timer starts
    timer();
    setInterval(timer, 1000);
}
