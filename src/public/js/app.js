$(document).ready(function($) {
    axios.get('https://dashboard.fluffici.eu/build').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            console.log(response.data.rev)
            $('#version').text('Version : ' + response.data.version)
            $('#rev').text('Rev : ' + response.data.rev.substring(0, 8))
        }
    })
    axios.get('https://autumn.fluffici.eu').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            console.log(response.data.rev)
            $('#autumn').text('Autumn : ' + response.data.autumn)
        }
    })
});
