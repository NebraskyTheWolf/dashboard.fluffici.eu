$(document).ready(function($) {
    axios.get('https://api.fluffici.eu').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            $('#version').text('Version : ' + response.version)
        }
    })

    setTimeout(() => {
        const display = document.getElementById('otp-expiration');

        if (display !== null) {
            startTimer(60 * 30, display);
        }

        console.log('Ping triggered! (foxing around giggles)')
    }, 500)
});

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
