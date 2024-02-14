
$(document).ready(function($) {
    axios.get('https://dashboard.fluffici.eu/build').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            console.log(response.data.rev)
            $('#version').text('Version : ' + response.data.version)
            $('#rev').text('Rev : ' + response.data.rev)
        }
    })
    axios.get('https://autumn.fluffici.eu').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            $('#autumn').text('Autumn : ' + response.data.autumn)
        }
    })

    const fiveMinutes = 60 * 30;
    const display = document.getElementById('otp-expiration');

    setTimeout(() => {
        startTimer(fiveMinutes, display);
    }, 1500)
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
            // add one second so that the count down starts at the full duration
            // example 05:00 not 04:59
            start = Date.now() + 1000;
        }
    }
    // we don't want to wait a full second before the timer starts
    timer();
    setInterval(timer, 1000);
}
