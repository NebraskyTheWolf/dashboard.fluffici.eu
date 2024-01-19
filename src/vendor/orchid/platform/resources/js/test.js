const axios = require('axios')

axios.interceptors.response.use(
    function (response) {
        if (response.data.type !== null) {
            return Promise.reject({
                type: response.response.data.type
            })
        } else {
            return Promise.resolve(response);
        }
    },
    function (error) {
        // if the server throws an error (404, 500 etc.)
        return Promise.reject(error);
    }
);

function start() {
    const data = new FormData()

    axios.post("http://localhost:8080/autumn/attachments", data, (response) => {
        console.log(response.statusMessage)
    }).catch(error => {
        console.log(error.response.data.type)
    })
}

start()