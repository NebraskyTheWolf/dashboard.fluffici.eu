$(document).ready(function($) {
    axios.get('https://dashboard.fluffici.eu/build/E').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            $('#version').text('Version : ' + response.data.version)
            $('#rev').text('Rev : ' + response.data.rev)
        }
    })


    /**
     * Represents a channel that listens for whisper 'ping' event and triggers a timer when received.
     *
     * @property {string} channel - The channel name to join.
     * @property {function} listenForWhisper - A function to listen for the 'ping' whisper event
     *                                         and execute the provided callback function.
     * @property {function} startTimer - A function to start the timer.
     * @property {function} display - The display element.
     *
     */
    const channel = window.Echo.join(`countdown`).listenForWhisper('ping', (data) => {
        const display = document.getElementById('otp-expiration');

        if (display !== null) {
            startTimer(data.pong, display);
        }
        console.log('Ping triggered! (foxing around giggles)')
    })

    setTimeout(() => channel.trigger('ping', { pong: 60 * 30 }), 1500)
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
