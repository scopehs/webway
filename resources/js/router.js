import { createWebHistory, createRouter } from "vue-router";
import pinia from "@/store.js";
import Main from "./views/Step.vue";
// import Stats from "./views/AllTheStats.vue";
// import AllTheConnections from "./views/AllConnections.vue";
import { useMainStore } from "@/store/useMain";
// import Whalers from "./views/Whalers.vue";
// import Characters from "./views/Chars.vue";
// import HotArea from "./views/HotAreas.vue";
// import Scopeh from "./views/Scopeh.vue";
// import Routing from "./views/Route.vue";

const routes = [
    {
        path: "/",
        name: "default",
        component: () => import("./views/Step.vue"),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next("/mapping");
            } else {
                next("/login");
            }
        },
    },

    {
        path: "",
        name: "defaultNone",
        component: () => import("./views/Step.vue"),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next("/mapping");
            } else {
                next("/login");
            }
        },
    },

    {
        path: "/home",
        name: "defaultHome",
        component: () => import("./views/Step.vue"),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next("/mapping");
            } else {
                next("/login");
            }
        },
    },

    // {
    //     path: "/chart",
    //     name: "chart",
    //     component: Chart
    // },

    // {
    //     path: "/",
    //     name: "Main",
    //     component: Main,
    // },

    {
        path: "/allthestats",
        name: "Stats",
        component: () => import("./views/AllTheStats.vue"),

        beforeEnter(to, from, next) {
            if (can("base")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/alltheconnections",
        name: "AllTheConnections",
        component: () => import("./views/AllConnections.vue"),
        beforeEnter(to, from, next) {
            if (can("view_all_connections")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/whaling",
        name: "Whalers",
        component: () => import("./views/Whalers.vue"),
        beforeEnter(to, from, next) {
            if (can("view_whalers")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/brokenchain",
        name: "BrokenChain",
        component: () => import("./views/BrokenChain.vue"),
        beforeEnter(to, from, next) {
            if (can("view_broken_connections")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/characters",
        name: "Characters",
        component: () => import("./views/Chars.vue"),
    },

    {
        path: "/hothothot",
        name: "HotArea",
        component: () => import("./views/HotAreas.vue"),
        beforeEnter(to, from, next) {
            if (can("view_hot_area")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/scopehpage",
        name: "Scopeh",
        component: () => import("./views/Scopeh.vue"),
        beforeEnter(to, from, next) {
            if (can("super_admin")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/shortest",
        name: "Shortest",
        component: () => import("./views/shortestPath.vue"),
        beforeEnter(to, from, next) {
            if (can("view_shortest")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/chat",
        name: "Chat",
        component: () => import("./views/Chat.vue"),
        beforeEnter(to, from, next) {
            if (can("super_admin")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/routing",
        name: "Routing",
        component: () => import("./views/Route.vue"),
        props: (route) => ({
            linkcon: route.query.linkcon,
            linkname: route.query.linkname,
            share: route.query.share,
        }),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next();
            } else {
                next("/");
            }
        },
    },

    // {
    //     path: "/chart2",
    //     name: "chart2",
    //     component: Chart2
    // },

    {
        path: "/mapping",
        name: "Mapping",
        component: () => import("./views/Step.vue"),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/sigs",
        name: "Sigs",
        component: () => import("./views/Sigs.vue"),
        props: (route) => ({
            sigID: route.query.sigID,
        }),
        beforeEnter(to, from, next) {
            if (can("base")) {
                next();
            } else {
                next("/");
            }
        },
    },

    {
        path: "/admin",
        name: "Admin",
        component: () => import("./views/AdminPanel.vue"),
        beforeEnter(to, from, next) {
            if (can("super_admin")) {
                next();
            } else {
                next("/");
            }
        },
    },

    // {
    //     path: "/charts",
    //     name: "Charts",
    //     component: Charts,
    //     beforeEnter(to, from, next) {
    //         if (Permissions.indexOf("super_admin") !== -1) {
    //             next();
    //         } else {
    //             next("/");
    //         }
    //     },
    // },

    // {
    //     path: "/test",
    //     name: "Test",
    //     component: Test,
    //     beforeEnter(to, from, next) {
    //         if (Permissions.indexOf("super_admin") !== -1) {
    //             next();
    //         } else {
    //             next("/");
    //         }
    //     },
    // },

    // {
    //     path: "/mapping2",
    //     name: "Mapping2",
    //     component: Mapping2,
    //     beforeEnter(to, from, next) {
    //         if(Permissions.indexOf('testing' )!== -1){
    //             next()
    //         }else{
    //            next("/")
    //         }

    //       }
    // },

    // {
    //     path: "/universe",
    //     name: "universe",
    //     component: Universe,
    //     beforeEnter(to, from, next) {
    //         if (Permissions.indexOf("super_admin") !== -1) {
    //             next();
    //         } else {
    //             next("/");
    //         }
    //     },
    // },

    // {
    //     path: "/userlogs",
    //     name: "UserLogs",
    //     component: UserLogs,
    //     beforeEnter(to, from, next) {
    //         if (Permissions.indexOf("view_user_logs") !== -1) {
    //             next();
    //         } else {
    //             next("/");
    //         }
    //     },
    // },

    // {
    //     path: "/feedback",
    //     name: "Feedback",
    //     component: Feedback,
    //     beforeEnter(to, from, next) {
    //         if (Permissions.indexOf("old") !== -1) {
    //             next();
    //         } else {
    //             next("/");
    //         }
    //     },
    // },

    // {
    //     path: "/feedback",
    //     name: "feedback",
    //     component: FeedBack,
    //       beforeEnter(to, from, next) {
    //         if(Permissions.indexOf('testing' )!== -1){
    //             next()
    //         }else{
    //            next("/")
    //         }

    //       }
    // },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const store = useMainStore(pinia);
    await store.count;
});

function can(value) {
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
}

export default router;

// scrollBehavior(to, from, savedPosition) {
//   return { x: 0, y: 0 }
// },
