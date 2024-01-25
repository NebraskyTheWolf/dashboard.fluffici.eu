$(document).ready(function($) {
    $("#error-mask").hide()

    window.Echo.connector.pusher.connection.bind('connecting', () => {
        $("#loader").show().css("display","inline").removeAttr('hidden')
        $("#loader-bar").show().removeAttr('hidden').css({
            'display': 'initial'
        })
        $("#loading-text").html('Connecting...')
        $("#loading").hide()
    });

    window.Echo.connector.pusher.connection.bind('connected', () => {
          $("#loader").remove()
          $("#loading").show().css("display","inline").removeAttr('hidden')
    });

    window.Echo.connector.pusher.connection.bind('error', () => {
          $("#loading").hide()
          $("#loader").show().removeAttr('hidden').css({
              'display': 'initial'
          })
          $("#error-mask").show().removeAttr('hidden').css({
              'display': 'initial'
          })
          $("#loader-bar").show().removeAttr('hidden').css({
              'display': 'initial'
          })
          $("#loading-text").html('A error occurred.')
    });

    window.Echo.connector.pusher.connection.bind('disconnected', () => {
          $("#loading").hide()
          $("#loader").show().removeAttr('hidden')
          $("#error-mask").show().removeAttr('hidden').css({
              'display': 'initial'
          })
          $("#loader-bar").show().removeAttr('hidden').css({
              'display': 'initial'
          })
          $("#loading-text").html('The server is currently down.')
    });
    axios.get('https://dashboard.rsiniya.uk/build').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            console.log(response.data.rev)
            $('#version').text('Version : ' + response.data.version)
            $('#rev').text('Rev : ' + response.data.rev.substring(0, 8))
        }
    })
    axios.get('https://autumn.rsiniya.uk').then(function (response) {
        if (response.status !== 200) {
            console.log('Cannot update fields for versioning.')
        } else {
            console.log(response.data.rev)
            $('#autumn').text('Autumn : ' + response.data.autumn)
        }
    })

    if (document.getElementById('isLogged').value == 1) {
        window.Echo.channel('statistics').listen('Statistics', (data) => {
            var beautify = JSON.stringify(data.data)
            var reverty = JSON.parse(beautify)

            for (var key in reverty) {
                document.getElementById(reverty[key].field).innerHTML =
                    `<p class="h3 text-black fw-light mt-auto" id="${reverty[key].field}">
                      ${reverty[key].result}
                    </p>`;
            }
        });

        console.log(document.getElementById('user_id').value)

        window.Echo.channel(`user.${document.getElementById('userId').value}`).listen('UserUpdated', (data) => {
            var beautify = JSON.stringify(data.data)
            var reverty = JSON.parse(beautify)

            for (var key in reverty) {
                switch (reverty[key].field) {
                    case 'persona-avatar':
                        document.getElementById('persona-avatar').innerHTML= `<img src="${reverty[key].result}" class="bg-light" id="persona-avatar" alt="persona-avatar">`;
                        break;
                    case 'persona-subtitle':
                        document.getElementById('persona-subtitle').innerHTML= `<small class="text-muted" id="persona-subtitle">${reverty[key].result}</small>`;
                        break;
                    case 'persona-title':
                        document.getElementById('persona-title').innerHTML= `<p class="mb-0" id="persona-title">${reverty[key].result}</p>`;
                        break;
                    default:
                        break;
                }
            }
        });
    }
});

function GetElementInsideContainer(containerID, childID) {
  var elm = document.getElementById(childID);
  var parent = elm ? elm.parentNode : {};
  return (parent.id && parent.id === containerID) ? elm : {};
}
