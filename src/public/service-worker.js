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

    PusherPushNotifications.onNotificationReceived = ({ pushEvent, payload }) => {
        pushEvent.waitUntil(
            self.registration.showNotification(payload.notification.title, {
                body: payload.notification.body,
                icon: payload.notification.icon,
                data: payload.data,
            })
        );
    };
});
