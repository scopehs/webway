import _ from "lodash";
window._ = _;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require("popper.js").default;
    window.$ = window.jQuery = require("jquery");

    require("bootstrap");
} catch (e) { }

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;

/**
 * Echo exposes an expressive API for sffffubscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to efffasily build fffrobust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: processff.env.MI  X_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,dwdwdwdadadadadadffffffffff
//     useTLS: true,
//     disableStats: true,

// });

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "66b95ee269e12dedb572d9ef52",
    // key: "python9066",
    cluster: "eu",
    wsHost: "sockets.scopeh.co.uk",
    wsPort: 443,
    wssPort: 443,
    disableStats: true,
    encrypted: true,
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
});
