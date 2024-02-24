importScripts("https://js.pusher.com/beams/service-worker.js");
window.navigator.serviceWorker.ready.then(serviceWorkerRegistration => {
    const beamsClient = new PusherPushNotifications.Client({
        instanceId: '63e32cff-b20c-4c92-bb49-0e40cfd1dbe3',
        serviceWorkerRegistration: serviceWorkerRegistration,
    })

    beamsClient.start().then(() => {
        console.log('Beams client started');
    }).catch(error => {
        console.error('Error starting Beams client:', error);
    });

    beamsClient.getRegistrationState()
        .then((state) => {
            let states = PusherPushNotifications.RegistrationState;
            switch (state) {
                case states.PERMISSION_DENIED: {
                    alert('Push notification permission denied.')
                    break;
                }
                case states.PERMISSION_GRANTED_REGISTERED_WITH_BEAMS: {
                    beamsClient.start()
                        .then(() => beamsClient.addDeviceInterest('dashboard'))
                        .then(() => console.log('Successfully registered and subscribed!'))
                        .catch(console.error);
                    break;
                }
                case states.PERMISSION_GRANTED_NOT_REGISTERED_WITH_BEAMS:
                case states.PERMISSION_PROMPT_REQUIRED: {
                    beamsClient.start()
                        .then(() => beamsClient.addDeviceInterest('dashboard'))
                        .then(() => console.log('Successfully registered and subscribed!'))
                        .catch(console.error);
                    break;
                }
            }
        }).catch((e) => console.error("Could not get registration state", e));
});
