
window._ = require('lodash');
window.axios = require('axios');

let user = document.head.querySelector('meta[name="user"]');

if (user) {
    let content = JSON.parse(user.content);
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + content.token;
    console.log("🚀 ~ file: bootstrap_app.js:10 ~ content.token:", content.token)
}
//window.axios.defaults.withCredentials = true;
//window.axios.defaults.headers.common['Access-Control-Allow-Credentials'] = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['crossDomain'] = true;
window.axios.defaults.headers.common['Access-Control-Allow-Headers'] = '*';
window.axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';
window.axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'GET, PUT, POST, DELETE, OPTIONS';
window.axios.defaults.preflightContinue = true;
//window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + '$2y$10$k3kn/ck2U6m2fxhgdira.OA80zKbZdxM3UbAlSwoRF4bLljMXFkv2';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.response.use( (response) => {
        return response;
    }, (error) => {

        if(error.response.status == 401)
        {
            window.location.replace("/login");
        }

        throw error;
});
