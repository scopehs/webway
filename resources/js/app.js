/**
 * First we will loffffad all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
// import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;
// window.Vue = require('vue');
import { createApp } from "vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import router from "./router";
import store from "@/store.js";
import App from "./views/App.vue";
import { Quasar, Notify, LoadingBar } from "quasar";
import quasarLang from "quasar/lang/en-GB";
import quasarIconSet from "quasar/icon-set/svg-fontawesome-v6";
import VNetworkGraph from "v-network-graph";
import "v-network-graph/lib/style.css";
import JsonExcel from "vue-json-excel3";

// import "@quasar/extras/material-icons/material-icons.css";
// import "@quasar/extras/material-icons-outlined/material-icons-outlined.css";
// import "@quasar/extras/material-icons-round/material-icons-round.css";
// import "@quasar/extras/material-icons-sharp/material-icons-sharp.css";
// import "@quasar/extras/material-symbols-outlined/material-symbols-outlined.css";
// import "@quasar/extras/material-symbols-rounded/material-symbols-rounded.css";
// import "@quasar/extras/material-symbols-sharp/material-symbols-sharp.css";
import "@quasar/extras/fontawesome-v6/fontawesome-v6.css";
import "quasar/src/css/index.sass";

const app = createApp(App);
app.component("DownloadExcel", JsonExcel);
app.use(router);
// window.Echo = new Echo({
//     broadcaster: "pusher",
//     // key: "66b95ee269e12dedb572d9ef52",
//     key: "python9066",
//     cluster: "eu",
//     wsHost: "sockets.scopeh.co.uk",
//     wsPort: 443,
//     wssPort: 443,
//     disableStats: true,
//     encrypted: true,
//     forceTLS: true,
//     enabledTransports: ["ws", "wss"],
// });

app.use(Quasar, {
    plugins: { Notify, LoadingBar }, // import Quasar plugins and add here
    lang: quasarLang,
    iconSet: quasarIconSet,
    /*
  config: {
    brand: {
      // primary: '#e46262',
      // ... or all other brand colors
    },
    notify: {...}, // default set of options for Notify Quasar plugin
    loading: {...}, // default set of options for Loading Quasar plugin
    loadingBar: { ... }, // settings for LoadingBar Quasar plugin
    // ..and many more (check Installation card on each Quasar component/directive/plugin)
  }
  */
});
app.use(store);
app.use(VNetworkGraph);
// app.use(LaravelPermissionToVueJS);
app.provide("can", function (value) {
    if (window.Laravel.jsPermissions == 0) {
        return false;
    }
    let permissions = window.Laravel.jsPermissions.permissions;
    let _return = false;
    if (!Array.isArray(permissions)) {
        return false;
    }
    if (value.includes("|")) {
        value.split("|").forEach(function (item) {
            if (permissions.includes(item.trim())) {
                _return = true;
            }
        });
    } else if (value.includes("&")) {
        _return = true;
        value.split("&").forEach(function (item) {
            if (!permissions.includes(item.trim())) {
                _return = false;
            }
        });
    } else {
        _return = permissions.includes(value.trim());
    }
    return _return;
});

app.config.productionTip = false;

app.mount("#app");
