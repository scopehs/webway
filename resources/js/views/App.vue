<template>
    <q-layout view="hHh lpR fFf" class="my-background">
        <q-ajax-bar position="top" size="4px" color="red" skip-hijack />
        <q-header elevated class="bg-dark text-white">
            <q-toolbar>
                <q-btn
                    dense
                    flat
                    round
                    icon="fa-solid fa-bars"
                    @click="toggleLeftDrawer"
                />
                <q-select
                    ref="dropChar"
                    v-model="selectedChar"
                    :options="dropDownOptionEnd"
                    option-value="value"
                    option-label="text"
                    option-disable="inactive"
                    input-debounce="0"
                    map-options
                    use-input
                    hide-selected
                    label="Active Char"
                    filled
                    @filter="filterFn"
                    @update:model-value="changedChar()"
                    fill-input
                    ><template v-slot:option="scope" rounded>
                        <q-item v-bind="scope.itemProps">
                            <q-item-section avatar>
                                <q-avatar>
                                    <img :src="scope.opt.url" />
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{
                                    scope.opt.text
                                }}</q-item-label>
                            </q-item-section>
                        </q-item> </template
                    ><template v-slot:before="scope">
                        <q-avatar v-if="!enableTracking">
                            <img :src="actuveCharURL" />
                        </q-avatar> </template
                ></q-select>
                <q-toggle
                    class="q-ml-lg"
                    v-model="store.tracking"
                    @update:model-value="changedTracking()"
                    :color="toggleColor"
                    :class="toggleClass"
                    keep-color
                    unchecked-icon="fa-solid fa-compass-drafting fa-rotate-180"
                    checked-icon="fa-solid fa-compass-drafting"
                >
                    <template v-slot:default>
                        <transition
                            mode="out-in"
                            enter-active-class="animate__animated animate__flash "
                        >
                            <span :class="toggleTextColor" :key="store.tracking"
                                >Tracking</span
                            >
                        </transition>
                    </template>
                </q-toggle>
                <q-toolbar-title class="“absolute-center”">
                    <div class="text-center">
                        <span class="text-webway"> WebWay </span>
                        <q-avatar>
                            <img
                                src="https://goonfleet.com/public/style_extra/team_icons/pf_bee.png"
                            />
                        </q-avatar>
                    </div>
                </q-toolbar-title>
                <div class="row align-baseline">
                    <ChatWindow v-if="loadChatButton" v-show="showChatButton" />
                    <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash animate__faster"
                        leave-active-class="animate__animated animate__flash animate__faster"
                    >
                        <span
                            v-if="showLocationStatus"
                            :key="`${store.showLocationStatus}-text`"
                            :class="locationColor"
                            class="flex flex-center"
                        >
                            ESI Status</span
                        >
                    </transition>
                    <RouteMapping v-if="showRouteMapping"></RouteMapping>
                    <NewFeedBackButton />
                    <q-btn
                        color="webway"
                        icon="fa-solid fa-right-from-bracket"
                        flat
                        @click="logout"
                    />
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above
            :mini="miniState"
            @mouseover="miniState = false"
            @mouseout="miniState = true"
            v-model="leftDrawerOpen"
            :width="200"
            :breakpoint="500"
            side="left"
            mini-to-overlay
            elevated
        >
            <q-list>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar>
                            <img
                                src="https://goonfleet.com/public/style_extra/team_icons/pf_bee.png"
                            />
                        </q-avatar>
                    </q-item-section>
                    <q-item-section> {{ store.user_name }}</q-item-section>
                </q-item>
            </q-list>

            <q-separator />
            <q-list>
                <q-item
                    clickable
                    v-ripple
                    v-for="item in items"
                    :key="item.title"
                    link
                    :to="item.link"
                >
                    <q-item-section avatar>
                        <q-icon
                            :color="iconColor(item.link)"
                            :name="item.icon"
                        />
                    </q-item-section>
                    <q-item-section :class="textColor(item.link)">{{
                        item.title
                    }}</q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container v-if="ready">
            <router-view v-slot="{ Component }">
                <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__fadeIn "
                    leave-active-class="animate__animated animate__fadeOut "
                >
                    <component :key="route.path" :is="Component" />
                </transition>
            </router-view>
        </q-page-container>
    </q-layout>
    <q-dialog
        v-model="store.overlayUpdateShow"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card style="width: 500px">
            <q-card-section class="bg-warning">
                <div class="text-h6">UPDATE HAPPENING</div>
            </q-card-section>

            <q-card-section class="q-pt-sx">
                <p>
                    Currently pushing an update to the app. Once the update is
                    done this message will go away.
                </p>
                <p>If things are not working after, try a refresh.</p>
                <p>If still not working give feedback</p>
            </q-card-section>
        </q-card>
    </q-dialog>

    <q-dialog
        v-model="ESIBrokenShow"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card style="width: 500px">
            <q-card-section class="bg-warning">
                <div class="text-h6">CCP DID IT AGAIN</div>
            </q-card-section>

            <q-card-section class="q-pt-sx">
                <p>
                    There is something wrong with the ESI atm, check again
                    later.
                </p>
                <p>Your tracking has been set too off</p>
            </q-card-section>
            <q-card-actions vertical align="center">
                <q-btn flat label="close" @click="ESIBrokenShow = false" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-dialog
        v-model="store.overlayRefreshShow"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card style="width: 500px">
            <q-card-section class="bg-warning">
                <div class="text-h6">REFRESH NEEDED</div>
            </q-card-section>

            <q-card-section class="q-pt-sx">
                An update has been applyed to the app. Which requires you to
                refresh the site.
            </q-card-section>
        </q-card>
    </q-dialog>

    <q-dialog
        v-model="store.overlayESIRemoved"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card style="width: 500px">
            <q-card-section class="bg-warning">
                <div class="text-h6">YOUR TOKEN HAS BEEN REMOVED</div>
            </q-card-section>

            <q-card-section class="q-pt-sx">
                Somthing has gone wrong with the ESI and your token has been
                remove. Add your Token again.
            </q-card-section>
            <q-card-actions>
                <q-btn color="positive" flat label="ADD char" href="/esi/add" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-dialog
        v-model="overlayAddBlocker"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card style="width: 500px">
            <q-card-section class="bg-warning">
                <div class="text-h6">AdBlocker messing up the site</div>
            </q-card-section>

            <q-card-section class="q-pt-sx">
                Your ad blocker is making it so that site will not work for you.
                Your options are to turn off the blocker, white list the site or
                dont use the site.
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
import { useRoute } from "vue-router";
import { setMapStoreSuffix } from "pinia";

const RouteMapping = defineAsyncComponent(() =>
    import("../components/step/routeMapping.vue")
);
const NewFeedBackButton = defineAsyncComponent(() =>
    import("../components/feedback/newFeedbackButton.vue")
);

const ChatWindow = defineAsyncComponent(() =>
    import("../components/chat/chatWindow.vue")
);
let store = useMainStore();
let can = inject("can");
let route = useRoute();

onMounted(async () => {
    await store.getLoginInfo();
    await store.getCharList();
    await store.getSiteUrl();
    await store.setSigShow();
    await store.getSupportRoom(store.user_id);
    setMenu();
    await window.Echo.private("user." + store.user_id)
        .listen("UserUpdate", (e) => {
            if (e.flag.flag == 1) {
                store.getAllByUserId();
                store.getCharList();
            }

            if (e.flag.flag == 2) {
                store.updateOverlayESIRemoved(true);
            }
            if (e.flag.flag == 3) {
                socket = true;
            }

            if (e.flag.flag == 4) {
                if (store.tracking == true) {
                    store.tracking = false;
                    ESIBrokenShow = true;
                }
            }

            if (e.flag.flag == 5) {
                store.getSupportRoom(store.user_id);
            }

            if (e.flag.flag == 6) {
                store.supportRoom = null;
            }
        })
        .listen("TrackingAdd", (e) => {
            if (e.flag.message != null) {
                store.addRoute(e.flag.message);
            }
        });
    await window.Echo.private("online").listen("OverlayUpdate", (e) => {
        if (e.flag.flag == 1) {
            store.updateOverlayUpdateShow(e.flag.message);
        }

        if (e.flag.flag == 2) {
            store.updateOverlayRefreshShow(true);
        }

        if (e.flag.flag == 3) {
            store.updateLocationStatus(e.flag.message);
        }

        if (e.flag.flag == 4) {
            var num = parseInt(e.flag.message);
            store.setSigShowSocket(num);
            setMenu();
            checkSigKick();
        }
    });
    //   setInfo;
    await setChar();
    runAdCheck();
    await store.clearRoute();
    await store.getLocationStatus().then((ready = true));
});

onBeforeUnmount(async () => {
    await window.Echo.leave("user." + store.user_id);
    await window.Echo.leave("online");
});

let onResize = (size) => {
    store.size = size;
    // {
    //   width: 20 // width of container (in px)
    //   height: 50 // height of container (in px)
    // }
};
let $q = useQuasar();
$q.dark.set(true); // or false or "auto"
// let store = useMainStore();
// set status

let ready = $ref(false);

let miniState = $ref(true);
let leftDrawerOpen = $ref(false);
function toggleLeftDrawer() {
    leftDrawerOpen = !leftDrawerOpen;
}

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

let selectedChar = $ref(null);
let ESIBrokenShow = $ref(false);

let showRouteMapping = $computed(() => {
    //   if (this.$route.name == "Mapping" || this.$route.name == "default") {
    //     return true;
    //   } else {
    //     return false;
    //   }

    return true;
});

let showLocationStatus = $computed(() => {
    //   if (
    //     route.name == "Mapping" ||
    //     route.name == "default" ||
    //     route.name == "Routing" ||
    //     route.name == "Sigs"
    //   ) {
    //     return true;
    //   } else {
    //     return false;
    //   }

    return true;
});

let locationColor = $computed(() => {
    if (store.locationStatus == "red") {
        return "text-red pt-2";
    } else if (store.locationStatus == "green") {
        return "text-green pt-2";
    } else {
        return "text-yellow pt-2";
    }
});

let overlayAddBlocker = $computed(() => {
    if (showSocket == true) {
        return true;
    } else {
        return false;
    }
});

let checkSigKick = () => {
    if (route.name == "Sigs") {
        if (store.sigShow == 0 && can("view_sigs")) {
            router.push("/mapping");
        }
    }
};

let setMenu = () => {
    if (store.sigShow == 1) {
        var text = {
            title: "Sigs",
            icon: "fa-solid fa-joint",
            link: "/sigs",
            role: "base",
        };
    } else {
        var text = {
            title: "Sigs",
            icon: "fa-solid fa-joint",
            link: "/sigs",
            role: "view_sigs",
        };
    }

    let item = menu.find((m) => m.title == "Sigs");
    Object.assign(item, text);
};

let toggleColor = $computed(() => {
    return store.tracking ? "primary" : "warning";
});

let toggleTextColor = $computed(() => {
    return store.tracking ? "text-primary" : "text-warning text-strike";
});

let toggleClass = $computed(() => {
    return store.tracking ? "" : "my-class";
});

let setChar = () => {
    selectedChar = store.selectedChar;
};
let userFilterText = $ref(null);
let dropChar = $ref();

let activeChar = $computed(() => {
    if (store.charactersList) {
        let test = store.charactersList.find(
            (c) => c.value == store.selectedChar
        );
        return test;
    }
    return false;
});

let changedChar = async () => {
    store.selectedChar = selectedChar.value;
    store.clearRouteKeeptracking();
    let request = {
        character_id: store.selectedChar,
    };
    await axios({
        method: "post", //you can set what request you want to be
        withCredentials: true,
        url: "/api/usercharupdate/" + store.user_id,
        data: request,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    });
};

let actuveCharURL = $computed(() => {
    if (activeChar) {
        return activeChar.url;
    }
});

let filterFn = (val, update, abort) => {
    update(() => {
        userFilterText = val.toLowerCase();
        if (dropDownOptionEnd.length > 0 && val) {
            selectedChar = dropDownOptionEnd[0];
        }
    });
};

let dropDownOptionStart = $computed(() => {
    return store.charactersList;
});

let dropDownOptionEnd = $computed(() => {
    let data = [];
    if (userFilterText) {
        return dropDownOptionStart.filter(
            (v) => v.text.toLowerCase().indexOf(userFilterText) > -1
        );
    }

    return store.charactersList;
});

let socket = $ref(false);
let showSocket = $ref(false);
let runAdCheck = async () => {
    await sleep(2000);
    await axios({
        method: "post", //you can set what request you want to be
        withCredentials: true,
        url: "/api/checkadblock",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    });

    await sleep(2000);
    if (socket == false) {
        showSocket = true;
    }
};

let logout = () => {
    window.location.href = "/logout";
};

let showChatButton = $computed(() => {
    if (can("super_admin")) {
        return false;
    } else {
        return true;
    }
});

let loadChatButton = $computed(() => {
    if (can("super_admin")) {
        return false;
    }

    return store.showChatButton;
});

let menu = $ref([
    {
        title: "Mapping",
        icon: "fa-solid fa-road",
        link: "/mapping",
        role: "base",
    },
    {
        title: "Broken Chains",
        icon: "fa-solid fa-link-slash",
        link: "/brokenchain",
        role: "view_broken_connections",
    },
    {
        title: "Whaling",
        icon: "fa-solid fa-fish",
        link: "/whaling",
        role: "view_whalers",
    },
    {
        title: "My Characters",
        icon: "fa-solid fa-user",
        link: "/characters",
        role: "base",
    },
    {
        title: "Scopeh",
        icon: "fa-solid fa-screwdriver-wrench",
        link: "/scopehpage",
        role: "super_admin",
    },

    //   {
    //     title: "Universe 1",
    //     icon: "fa-brands fa-galactic-senate",
    //     link: "/universe",
    //     role: "super_admin",
    //   },
    //   {
    //     title: "Feedback",
    //     icon: "fa-solid fa-bug",
    //     link: "/feedback",
    //     role: "old",
    //   },
    {
        title: "Roles",
        icon: "fa-solid fa-user-tag",
        link: "/admin",
        role: "super_admin",
    },
    {
        title: "Stats",
        icon: "fa-solid fa-user-tag",
        link: "/allthestats",
        role: "base",
    },
    {
        title: "Routing",
        icon: "fa-solid fa-route",
        link: "/routing",
        role: "base",
    },
    {
        title: "Hot Area",
        icon: "fa-solid fa-temperature-full",
        link: "/hothothot",
        role: "view_hot_area",
    },
    //   {
    //     title: "Logs",
    //     icon: "fa-solid fa-file-medical-alt",
    //     link: "/userlogs",
    //     role: "view_hot_area",
    //   },
    {
        title: "Connections",
        icon: "fa-solid fas fa-wifi",
        link: "/alltheconnections",
        role: "view_all_connections",
    },

    {
        title: "Shortest",
        icon: "fa-solid fa-truck-fast",
        link: "/shortest",
        role: "view_shortest",
    },

    {
        title: "Support",
        icon: "fa-solid fa-headset",
        link: "/chat",
        role: "super_admin",
    },
    //   {
    //     title: "Charts",
    //     icon: "fa-solid fa-fire",
    //     link: "/charts",
    //     role: "super_admin",
    //   },
    {
        title: "Sigs",
        icon: "fa-solid fa-joint",
        link: "/sigs",
        role: "base",
    },
]);

let items = $computed(() => {
    return menu.filter((list) => can(list.role));
});

let iconColor = (link) => {
    if (route.path == link) {
        return "primary";
    } else {
        return "webway";
    }
};

let textColor = (link) => {
    if (route.path == link) {
        return "text-primary";
    } else {
        return "text-webway";
    }
};

let enableTracking = $computed(() => {
    if (store.selectedChar > 0) {
        return false;
    } else {
        return true;
    }
});

let changedTracking = async () => {
    var track = 0;
    if (store.tracking == true) {
        track = 1;
    } else {
        store.clearRoute("clearRoute");
    }

    let request = {
        tracking: track,
    };
    await axios({
        method: "post", //you can set what request you want to be
        withCredentials: true,
        url: "/api/updatetrackingchar/" + store.selectedChar,
        data: request,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    });
};
</script>

<style lang="scss" scoped>
.my-class,
.q-toggle__thumb {
    border-color: currentColor;
}
</style>

<style>
.my-background {
    background: url("https://i.imgur.com/t1EdOsB.jpeg") !important;
    background-size: cover !important;
    background-repeat: no-repeat !important;
}
</style>
