import { createStore } from "vuex";

export const store = createStore({
    state: {
        activityTypes: [],
        allthestatsusers: [],
        allthestatusuerslastmonth: [],
        allthestatsconnections: [],
        characters: [],
        charactersList: [],
        chartMax: 0,
        connections: [],
        connectionRegionList: [],
        connectionConstellationList: [],
        connectionNotes: [],
        constellationlist: [],
        currentKillCount: 0,
        currentSystemChars: [],
        currentSystemId: null,
        currentSystemNotes: [],
        currentSystemSigs: [],
        currentSystemSigsNotes: [],
        currentTempSystemID: null,
        drifterTypeList: [],
        feedback: [],
        hotArea: [],
        lastKillCount: 0,
        joveRegionList: [],
        joveSystems: [],
        lastSystemId: null,
        lastSystemSigs: [],
        lastSystemNotes: [],
        lastSystemChars: [],
        lastSystemNotes: [],
        lastTempSystemID: null,
        locationStatus: "green",
        overlayUpdateShow: false,
        overlayRefreshShow: false,
        overlayESIRemoved: false,
        reservedConnections: [],
        route: [],
        routeCurrentSystemID: null,
        routeID: 1,
        rolesList: [],
        routeNodes: [],
        routeLinks: [],
        regionlist: [],
        selectedChar: null,
        sigsAddedCount: [],
        sigConnectioncount: [],
        sigInfoUpdateCount: [],
        sigsUpdatedCount: [],
        systemListRoute: [],
        savedRoutes: [],
        showInfoPannelID: null,
        copySigBookmark: null,
        systemlist: [],
        showSystemInput: false,
        titanList: [],
        token: "",
        tracking: false,
        user_id: 0,
        users: [],
        userLogs: [],
        userLogsCharList: [],
        userList: [],
        usercount: [],
        user_name: "",
        universeList: [],
        wormholeTypeList: [],

        siteUrl: null,

        sigs: [],
        sigShow: 5,
        gasInfo: [],
        gasFilterDamage: null,
        gasFilterNPC: null,
        gasFilterNinja: null,
        gasFilterJedi: null,
        sigTableSearch: "",
        gasSigInfoShow: 0,

        nebulaHot: [],
        nebulaList: [],
    },
    mutations: {
        //  DELETE_STATION_NOTIFICATION(state, id) {
        //     let index = state.stations.findIndex(s => s.id == id)
        //     if(index >= 0){state.stations.splice(index, 1)}

        // },

        // SET_AMMO_REQUEST(state, ammorequest) {
        //     state.ammoRequest = ammorequest;
        // },

        //  ADD_AMMO_REQUEST(state, data) {
        //     state.ammoRequest.push(data)
        // },

        //  RANDOM UPDATE_AMMO_REQUEST(state, data) {
        //     const item = state.ammoRequest.find(item => item.id === data.id);
        //     const count = state.ammoRequest.filter(item => item.id === data.id).length
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.ammoRequest.push(data)
        //     }
        // },

        TEST_UPDATE_LOCATION(state, data) {
            state.currentKillCount = data.currentSystemKills;
            state.currentSystemChars = data.currentSystemUsers.characters;
            state.currentSystemId = data.currentSystemID;
            state.currentSystemNotes = data.currentSystemNotes;
            state.currentSystemSigs = data.currentSystemSigs;
            state.currentSystemSigsNotes = data.currentSystemSigNotes;
            state.wormholeTypeList = data.wormholeTypeList.wormholeTypeList;
            if (data.lastSystemID > 0 || data.lastSystemID != null) {
                state.lastKillCount = data.lastSystemKills;
                if (data.lastSystemUsers) {
                    state.lastSystemChars = data.lastSystemUsers.characters;
                } else {
                    state.lastSystemChars = [];
                }
                state.lastSystemId = data.lastSystemID;
                state.lastSystemNotes = data.lastSystemNotes;
                state.lastSystemSigs = data.lastSystemSigs;
            } else {
                state.lastKillCount = 0;
                state.lastSystemChars = [];
                state.lastSystemId = null;
                state.lastSystemNotes = [];
                state.lastSystemSigs = [];
            }
            state.route = data.route;
        },

        TEST_LOCATION_INFO(state, data) {
            state.currentKillCount = 0;
            state.currentSystemChars = [];
            state.currentSystemNotes = [];
            state.currentSystemSigs = [];
            state.currentSystemSigsNotes = [];
            state.lastKillCount = 0;
            state.lastSystemChars = [];
            state.lastSystemNotes = [];
            state.lastSystemSigs = [];

            state.currentKillCount = data.currentSystemKills;
            state.currentSystemChars = data.currentSystemUsers.characters;
            // state.currentSystemId = data.currentSystemID;
            state.currentSystemNotes = data.currentSystemNotes;
            state.currentSystemSigs = data.currentSystemSigs;
            state.currentSystemSigsNotes = data.currentSystemSigNotes;
            // state.wormholeTypeList = data.wormholeTypeList.wormholeTypeList;
            if (data.lastSystemID > 0) {
                state.lastKillCount = data.lastSystemKills;
                state.lastSystemChars = data.lastSystemUsers.characters;
                // state.lastSystemId = data.lastSystemID;
                state.lastSystemNotes = data.lastSystemNotes;
                state.lastSystemSigs = data.lastSystemSigs;
            } else {
                state.lastKillCount = 0;
                state.lastSystemChars = [];
                state.lastSystemId = null;
                state.lastSystemNotes = [];
                state.lastSystemSigs = [];
            }
            // state.route = data.route;
        },

        UPDATE_SYSTEM_LIST_ROUTE(state, data) {
            state.systemListRoute.push(data);
        },

        SET_SIGS(state, sigs) {
            state.sigs = sigs;
        },

        SET_NEBULA_HOT(state, hotNebula) {
            state.nebulaHot = hotNebula;
        },

        SET_NEBULA_LIST(state, nebulalist) {
            state.nebulaList = nebulalist;
        },

        SET_SIG_SHOW(state, num) {
            state.sigShow = num;
        },

        SET_SITE_URL(state, url) {
            state.siteUrl = url;
        },

        UPDATE_SIGS(state, data) {
            const item = state.sigs.find((s) => s.id == data.id);
            const count = state.sigs.filter((s) => s.id == data.id).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.sigs.push(data);
            }
        },

        DELETE_SIGS(state, id) {
            const item = state.sigs.filter((s) => s.id != id);
            state.sigs = item;
        },

        UPDATE_SHOW_INFO_PANNEL_ID(state, num) {
            state.showInfoPannelID = num;
        },

        SET_ACTIVITY_TYPES(state, types) {
            state.activityTypes = types;
        },

        UPDATE_COPY_SIG_BOOKMARK(state, num) {
            state.copySigBookmark = num;
        },

        UPDATE_ROUTE_CURRENT_SYSTEM_ID(state, id) {
            state.routeCurrentSystemID = id;
        },

        // UPDATE_SYSTEM_LIST_ROUTE(state, data) {
        //     if (state.systemListRoute) {

        //         const item = state.systemListRoute.find(system => system.value == data.value);
        //         const count = state.sysffftemListRoute.filter(system => system.value == data.value).length
        //         if (count > 0) {
        //             Object.assign(item, data);
        //         } else {
        //             state.systemListRoute.push(data)
        //         }
        //     } else {
        //         state.systemListRoute.push(data)
        //     }
        // },

        SET_GAS_FILTER_JEDI(state, newValue) {
            state.gasFilterJedi = newValue;
        },

        SET_GAS_FILTER_NINJA(state, newValue) {
            state.gasFilterNinja = newValue;
        },

        SET_GAS_FILTER_NPC(state, newValue) {
            state.gasFilterNPC = newValue;
        },

        SET_GAS_FILTER_DAMAGE(state, newValue) {
            state.gasFilterDamage = newValue;
        },

        SET_TEMP_SYSTEM_ID(state, data) {
            state.currentTempSystemID = data.currentTempSystemID;
            state.lastTempSystemID = data.lastTempSystemID;
        },

        SET_USER_LOGS(state, userLogs) {
            state.userLogs = userLogs;
        },

        SET_USER_LOGS_CHAR_LIST(state, userLogsCharList) {
            state.userLogsCharList = userLogsCharList;
        },

        SET_USER_LIST(state, userList) {
            state.userList = userList;
        },

        SET_CHART_DATA(state, data) {
            state.usercount = data.usercount;
            state.sigsAddedCount = data.sigAddcount;
            state.sigsUpdatedCount = data.sigUpdatecount;
            state.sigConnectioncount = data.sigConnectioncount;
            state.sigInfoUpdateCount = data.sigInfoUpdateCount;
            state.chartMax = data.max;
        },

        UPDATE_USER_COUNT(state, data) {},

        SET_OVERLAY_UPDATE_SHOW(state, data) {
            // state.overlayUpdateShow = data

            if (data == "true") {
                state.overlayUpdateShow = true;
            } else {
                state.overlayUpdateShow = false;
            }
        },

        SET_OVERLAY_REFRESH_SHOW(state, data) {
            state.overlayRefreshShow = data;
        },
        SET_OVERLAY_ESI_REMOVED_SHOW(state, data) {
            state.overlayESIRemoved = data;
        },

        SET_SHOW_SYSTEM_INPUT(state, data) {
            state.showSystemInput = data;
        },

        SET_JOVE_SYSTEMS(state, data) {
            state.joveSystems = data;
        },

        SET_JOVE_REGION_LIST(state, data) {
            state.joveRegionList = data;
        },

        SET_CONNECTION_LIST(state, data) {
            state.connectionRegionList = data.region;
            state.connectionConstellationList = data.constellation;
        },

        SET_CURRENT_SYSTEM_USERS(state, systemUsers) {
            state.currentSystemChars = systemUsers.characters;
        },

        SET_LAST_SYSTEM_USERS(state, systemUsers) {
            state.lastSystemChars = systemUsers.characters;
        },

        SET_SAVED_ROUTES(state, routes) {
            // const json = JSON.parse(routes.saved_routes);
            state.savedRoutes = routes.saved_routes;
        },

        SET_RESERVED_CONNECTIONS(state, reserved) {
            // const json = JSON.parse(routes.saved_routes);
            state.reservedConnections = reserved;
        },

        SET_LOCATION_STATUS(state, status) {
            // const json = JSON.parse(routes.saved_routes);
            state.locationStatus = status;
        },

        SET_ROUTE_MAPPING(state, data) {
            state.routeLinks = data.links;
            state.routeNodes = data.nodes;
        },

        UPDATE_HOT_AREA(state, data) {
            const item = state.hotArea.find((hot) => hot.id == data.id);
            const count = state.hotArea.filter(
                (hot) => hot.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.hotArea.push(data);
            }
        },

        UPDATE_JOVE_SYSTEM(state, data) {
            const item = state.joveSystems.find((jove) => jove.id == data.id);
            const count = state.joveSystems.filter(
                (jove) => jove.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.joveSystems.push(data);
            }
        },

        SET_HOT_AREA(state, data) {
            state.hotArea = data;
        },

        SET_ALL_THE_STATUS_USERS_LAS_MONTH(state, data) {
            state.allthestatusuerslastmonth = data;
        },

        UPDATE_ALL_THE_STATS_USER(state, data) {
            const item = state.allthestatsusers.find(
                (user) => user.id == data.id
            );
            const count = state.allthestatsusers.filter(
                (user) => user.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.allthestatsusers.push(data);
            }
        },

        SET_ALL_THE_STATS_USER(state, stats) {
            state.allthestatsusers = stats;
        },

        SET_ROUTE_ID(state, num) {
            state.routeID = num;
        },

        UPDATE_SIG_TABLE_SEARCH(state, newValue) {
            state.sigTableSearch = newValue;
        },

        UPDATE_GAS_INFO_SHOW(state, newValue) {
            state.gasSigInfoShow = newValue;
        },

        DELETE_CURRENT_SYSTEM_SIG(state, id) {
            let index = state.currentSystemSigs.findIndex((s) => s.id == id);
            if (index >= 0) {
                state.currentSystemSigs.splice(index, 1);
            }
        },

        SET_SYSTEM_NOTES(state, payload) {
            if (payload.type == 1) {
                state.currentSystemNotes = payload.systemNotes;
            }

            if (payload.type == 2) {
                state.lastSystemNotes = payload.systemNotes;
            }
        },

        SET_SIG_NOTES(state, payload) {
            if (payload.type == 1) {
                state.currentSigNotes = payload.sigNotes;
            }

            if (payload.type == 2) {
                state.lastSigNotes = payload.sigNotes;
            }
        },

        SET_CONNECTION_NOTES(state, notes) {
            state.connectionNotes = notes;
        },

        DELETE_CURRENT_SYSTEM_NOTE(state, id, type) {
            if (type == 1) {
                let index = state.currentSystemNotes.findIndex(
                    (s) => s.id == id
                );
                if (index >= 0) {
                    state.currentSystemNotes.splice(index, 1);
                }
            }

            if (type == 2) {
                let index = state.lastSystemNotes.findIndex((s) => s.id == id);
                if (index >= 0) {
                    state.lastSystemNotes.splice(index, 1);
                }
            }
        },

        DELETE_LAST_SYSTEM_SIG(state, id) {
            let index = state.lastSystemSigs.findIndex((s) => s.id == id);
            if (index >= 0) {
                state.lastSystemSigs.splice(index, 1);
            }
        },

        DELETE_FEEDBACK(state, id) {
            let index = state.feedback.findIndex((s) => s.id == id);
            if (index >= 0) {
                state.feedback.splice(index, 1);
            }
        },

        CLEAR_SIGS(state) {
            state.currentSystemSigs = [];
            state.lastSystemSigs = [];
        },

        CLEAR_ROUTE(state) {
            state.route = [];
            state.currentSystemId = null;
            state.currentSystemSigs = [];
            state.currentTempSystemID = null;
            state.lastSystemId = null;
            state.lastSystemSigs = [];
            state.lastTempSystemID = null;
            state.routeID = 1;
            state.tracking = false;
            state.rolesList = [];
            state.currentSystemChars = [];
            state.lastSystemChars = [];
            state.routeNodes = [];
            state.routeLinks = [];
        },

        CLEAR_ROUTE_KEEP_TRACKING(state) {
            state.route = [];
            state.currentSystemId = null;
            state.currentSystemSigs = [];
            state.currentTempSystemID = null;
            state.lastSystemId = null;
            state.lastSystemSigs = [];
            state.lastTempSystemID = null;
            state.routeID = 1;
            state.rolesList = [];
            state.currentSystemChars = [];
            state.lastSystemChars = [];
            state.routeNodes = [];
            state.routeLinks = [];
        },

        SET_CHARACTERS(state, chars) {
            state.characters = chars;
        },

        SET_FEEDBACK(state, feedback) {
            state.feedback = feedback;
        },

        SET_CONNECTIONS(state, connections) {
            state.connections = connections;
        },

        UPDATE_CHARACTERS(state, data) {
            const item = state.characters.find((char) => char.id == data.id);
            const count = state.characters.filter(
                (char) => char.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.characters.push(data);
            }
        },

        UPDATE_JOVE(state, data) {
            const item = state.route.find((route) => route.id == data.id);
            const count = state.route.filter(
                (route) => route.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        PIN_NODE(state) {
            const items = state.routeNodes;
            // items.forEach((element, index, array) => {
            //     console.log(element.id); // 100, 200, 300
            //     console.log(index); // 0, 1, 2
            //     console.log(array); // same myArray object 3 times
            // });

            //  items.forEach(function (node) {
            //     var x = node.x;
            //     var y = node.y
            //     console.log(x, y);
            // })

            items.forEach((node) =>
                Object.assign(node, {
                    pinned: true,
                    fx: node.x,
                    fy: node.y,
                })
            );
        },

        SET_TOKEN(state, token) {
            state.token = token;
        },

        SET_TRACKING(state, tracking) {
            state.tracking = tracking;
        },

        SET_CURRENT_SYSTEM_ID(state, id) {
            state.currentSystemId = id;
        },

        SET_LAST_SYSTEM_ID(state, id) {
            state.lastSystemId = id;
        },

        SET_USER_ID(state, user_id) {
            state.user_id = user_id;
        },
        SET_USER_NAME(state, user_name) {
            state.user_name = user_name;
        },

        SET_USERS(state, users) {
            state.users = users;
        },

        SET_SELECTED_CHAR(state, selectedChar) {
            state.selectedChar = selectedChar;
        },

        SET_LAST_SYSTEM_SIGS(state, sigs) {
            state.lastSystemSigs = sigs;
        },

        SET_LAST_KILL_COUNT(state, kills) {
            state.lastKillCount = kills;
        },

        UPDATE_LAST_KILL_COUNT(state, kills) {
            state.lastKillCount = kills;
        },

        SET_CURRENT_KILL_COUNT(state, kills) {
            state.currentKillCount = kills;
        },

        UPDATE_CURRENT_KILL_COUNT(state, kills) {
            state.currentKillCount = kills;
        },

        SET_CURRENT_SYSTEM_SIGS(state, sigs) {
            state.currentSystemSigs = sigs;
        },

        SET_SYSTEMLIST(state, systemlist) {
            state.systemlist = systemlist;
        },

        SET_TITAN_LIST(state, titanList) {
            state.titanList = titanList;
        },

        SET_REGIONLIST(state, regionlist) {
            state.regionlist = regionlist;
        },

        SET_CONSTELLATIONLIST(state, constellationlist) {
            state.constellationlist = constellationlist;
        },

        SET_GAS_INFO(state, gasinfo) {
            state.gasInfo = gasinfo;
        },

        SET_CHARACTERSLIST(state, charlist) {
            state.charactersList = charlist;
        },

        SET_ROLES(state, roles) {
            state.rolesList = roles;
        },

        SET_UNIVERSE_LIST(state, universeList) {
            state.universeList = universeList;
        },

        SET_WORMHOLE_TYPE_LIST(state, wormholeTypeList) {
            state.wormholeTypeList = wormholeTypeList;
        },

        SET_DRIFTER_TYPE_LIST(state, drifterTypeList) {
            state.drifterTypeList = drifterTypeList;
        },

        UPDATE_LAST_SYSTEM_SIGS(state, data) {
            const item = state.lastSystemSigs.find((sig) => sig.id == data.id);
            const count = state.lastSystemSigs.filter(
                (sig) => sig.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.lastSystemSigs.push(data);
            }
        },

        UPDATE_CURRENT_SYSTEM_SIGS(state, data) {
            const item = state.currentSystemSigs.find(
                (sig) => sig.id == data.id
            );
            const count = state.currentSystemSigs.filter(
                (sig) => sig.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.currentSystemSigs.push(data);
            }
        },

        ADD_ROUTE(state, data) {
            const item = state.route.find((r) => r.id == data.id);
            const count = state.route.filter((r) => r.id == data.id).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.route.push(data);
            }
        },
    },

    actions: {
        // async getTimerDataAll({ commit, state }) {
        //     let res = await axios({
        //         method: "get",
        // withCredentials: true,
        //         url: "/api/timers",
        //         headers: {
        //             Accepdddt: "application/json",
        //             "Content-Type": "application/json"
        //         }
        //     });
        //     commit("SET_TIMERS", res.data.timers);
        // },

        // async setSelectedCharLoadAPI({ commit, state }, user_id) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/selectedchar/" + user_id,
        //         headers: {

        //             Accept: "application/json",
        //             "Content-Type": "application/json"
        //         }
        //     });
        //     commit("SET_SELECTED_CHAR", res.data.selectedChar);
        // },

        async getJoveSystems({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/jovesystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_JOVE_SYSTEMS", res.data.systems);
        },

        async setAllTheStatsUser({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allthestats",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_ALL_THE_STATS_USER", res.data.stats);
        },

        async getLogsByUser({ commit }, user_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/userlogs/" + user_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_USER_LOGS", res.data.userlogs);
            commit("SET_USER_LOGS_CHAR_LIST", res.data.userlogsChars);
        },

        async getUserList({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/userlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_USER_LIST", res.data.userlist);
        },

        async getJoveRegions({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/joveregionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_JOVE_REGION_LIST", res.data.regions);
        },

        async getActivityTypes({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/activiytypes",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_ACTIVITY_TYPES", res.data.types);
        },

        async getSigs({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getsigs",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_SIGS", res.data.sigs);
        },

        async setSigShow({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/setSigShow",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_SIG_SHOW", res.data.num[0]);
        },

        async getSiteUrl({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/geturl",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_SITE_URL", res.data.url);
        },

        async getConnectionLists({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/connectionlists",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CONNECTION_LIST", res.data);
        },

        async setRouteMapping({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/routemapping/" + state.selectedChar,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_ROUTE_MAPPING", res.data);
        },

        async getSavedRoutes({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/savedroutes",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_SAVED_ROUTES", res.data.routes);
        },

        async getReservedConnection({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/reservedconnection",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_RESERVED_CONNECTIONS", res.data.reserved);
        },

        async getLocationStatus({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getlocationstatus",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LOCATION_STATUS", res.data.status);
        },

        async setHotArea({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/hotarea",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_HOT_AREA", res.data.hot);
        },

        async getAllByUserId({ commit, state }) {
            axios.defaults.withCredentials = true;
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/charsbyuser/" + state.user_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHARACTERS", res.data.chars);
        },

        async getRoles({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/roles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_ROLES", res.data.roles);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getUsers({ commit, state }) {
            if (state.token == "") {
                await sleep(500);
            }
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allusersroles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            // debugger
            commit("SET_USERS", res.data.usersroles);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getAllTheStatsLastMonth({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allthestatslastmonth",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            // debugger
            commit("SET_ALL_THE_STATUS_USERS_LAS_MONTH", res.data.stats);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getAllFeedBack({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/feedback",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_FEEDBACK", res.data.feedback);
        },

        async getAllConnections({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/connections",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CONNECTIONS", res.data.connections);
        },

        async getCurrentSystemChars({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/currentsystemchars/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CURRENT_SYSTEM_USERS", res.data.systemUsers);
        },

        async getChartDataMins({ commit }, mins) {
            let request = {
                mins: mins,
            };
            let res = await axios({
                method: "post",
                withCredentials: true,
                url: "/api/chartdatamins",
                data: request,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHART_DATA", res.data);
        },

        async getChartDataHour({ commit }, mins) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/chartdatahour",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHART_DATA", res.data);
        },

        async getLastSystemChars({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/lastsystemchars/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LAST_SYSTEM_USERS", res.data.systemUsers);
        },

        async getCurrentTempSystemChars({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/currentsystemchars/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CURRENT_SYSTEM_USERS", res.data.systemUsers);
        },

        async getLastTempSystemChars({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/lastsystemchars/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LAST_SYSTEM_USERS", res.data.systemUsers);
        },

        async setCurrentSigsBySystemID({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/sigs/" + state.currentSystemId,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CURRENT_SYSTEM_SIGS", res.data.sigs);
            commit("SET_CURRENT_KILL_COUNT", res.data.kills);
        },

        async setLastSigsBySystemID({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/sigs/" + state.lastSystemId,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LAST_SYSTEM_SIGS", res.data.sigs);
            commit("SET_LAST_KILL_COUNT", res.data.kills);
        },

        async setTempCurrentSigsBySystemID({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/sigs/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CURRENT_SYSTEM_SIGS", res.data.sigs);
            commit("SET_CURRENT_KILL_COUNT", res.data.kills);
        },

        async setTempLastSigsBySystemID({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/sigs/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LAST_SYSTEM_SIGS", res.data.sigs);
            commit("SET_LAST_KILL_COUNT", res.data.kills);
        },

        async getSystemList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/systemlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_SYSTEMLIST", res.data.systemlist);
        },

        async getTitanList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/titanlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_TITAN_LIST", res.data.titanList);
        },

        async getRegionList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/regionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_REGIONLIST", res.data.regionlist);
        },

        async getConstellationList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/constellationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CONSTELLATIONLIST", res.data.constellationlist);
        },

        async getGasInfo({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getgasinfo",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_GAS_INFO", res.data.gasinfo);
        },

        async getCharList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/charlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHARACTERSLIST", res.data.charlist);
        },

        async getUniverseList({ commit, state }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/unilist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_UNIVERSE_LIST", res.data.universeList);
        },

        async getWormholeTypeList({ commit, state }, systemID) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/wormholetypelist/" + systemID,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit(
                "SET_WORMHOLE_TYPE_LIST",
                res.data.wormholeTypeList.wormholeTypeList
            );
        },

        async getSystemNotes({ commit }, { type, id }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getsystemnotes/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            var payload = {
                systemNotes: res.data.systemNotes,
                type: type,
            };
            commit("SET_SYSTEM_NOTES", payload);
        },

        async getConnectionNotes({ commit }, id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getconnectionNotes/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CONNECTION_NOTES", res.data.notes);
        },

        async getNebulaHot({ commit }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebula",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_NEBULA_HOT", res.data.hotNebula);
        },

        async getNebulaList({ commit }, id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebulalist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_NEBULA_LIST", res.data.nebulalist);
        },

        async getSigNotes({ commit }, { type, id }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getsignotes/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            var payload = {
                sigNotes: res.data.sigNotes,
                type: type,
            };
            commit("SET_SIG_NOTES", payload);
        },

        async getDrifterTypeList({ commit }, systemID) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/driftertypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            commit("SET_DRIFTER_TYPE_LIST", res.data.drifterTypeList);
        },

        clearSigs({ commit }) {
            commit("CLEAR_SIGS");
        },

        clearRoute({ commit }) {
            commit("CLEAR_ROUTE");
        },

        updateOverlayUpdateShow({ commit }, data) {
            commit("SET_OVERLAY_UPDATE_SHOW", data);
        },

        updateOverlayRefreshShow({ commit }, data) {
            commit("SET_OVERLAY_REFRESH_SHOW", data);
        },

        updateOverlayESIRemoved({ commit }, data) {
            commit("SET_OVERLAY_ESI_REMOVED_SHOW", data);
        },

        setShowSystemInput({ commit }, data) {
            commit("SET_SHOW_SYSTEM_INPUT", data);
        },

        clearRouteKeeptracking({ commit }) {
            commit("CLEAR_ROUTE_KEEP_TRACKING");
        },

        setCurrentSystemId({ commit }, id) {
            commit("SET_CURRENT_SYSTEM_ID", id);
        },

        updateLocationStatus({ commit }, status) {
            commit("SET_LOCATION_STATUS", status);
        },
        setLastSystemId({ commit }, id) {
            commit("SET_LAST_SYSTEM_ID", id);
        },
        setToken({ commit }, token) {
            commit("SET_TOKEN", token);
        },

        setTracking({ commit }, tracking) {
            commit("SET_TRACKING", tracking);
        },

        setUser_id({ commit }, user_id) {
            commit("SET_USER_ID", user_id);
        },
        setUser_name({ commit }, user_name) {
            commit("SET_USER_NAME", user_name);
        },

        addRoute({ commit }, data) {
            commit("ADD_ROUTE", data);
        },

        updateHotArea({ commit }, data) {
            commit("UPDATE_HOT_AREA", data);
        },

        updateSigs({ commit }, data) {
            commit("UPDATE_SIGS", data);
        },

        deleteSigs({ commit }, id) {
            commit("DELETE_SIGS", id);
        },

        setSigShowSocket({ commit }, num) {
            commit("SET_SIG_SHOW", num);
        },

        updateJoveSystem({ commit }, data) {
            commit("UPDATE_JOVE_SYSTEM", data);
        },

        setTempSystemids({ commit }, data) {
            commit("SET_TEMP_SYSTEM_ID", data);
        },

        setRouteID({ commit }, num) {
            commit("SET_ROUTE_ID", num);
        },

        updateSigTableSearch({ commit }, newValue) {
            commit("UPDATE_SIG_TABLE_SEARCH", newValue);
        },

        updateGasInfoShow({ commit }, newValue) {
            commit("UPDATE_GAS_INFO_SHOW", newValue);
        },

        deleteCurrentSystemSig({ commit }, id) {
            commit("DELETE_CURRENT_SYSTEM_SIG", id);
        },

        deleteLastSystemSig({ commit }, id) {
            commit("DELETE_LAST_SYSTEM_SIG", id);
        },

        deleteFeedback({ commit }, id) {
            commit("DELETE_FEEDBACK", id);
        },

        updateAllTheStatsUser({ commit }, data) {
            commit("UPDATE_ALL_THE_STATS_USER", data);
        },

        updateCurrentKillCount({ commit }, kills) {
            commit("UPDATE_CURRENT_KILL_COUNT", kills);
        },

        updateLastKillCount({ commit }, kills) {
            commit("UPDATE_LAST_KILL_COUNT", kills);
        },

        setGasFilterDamage({ commit }, newValue) {
            commit("SET_GAS_FILTER_DAMAGE", newValue);
        },

        setGasFilterNPC({ commit }, newValue) {
            commit("SET_GAS_FILTER_NPC", newValue);
        },

        setGasFilterNinja({ commit }, newValue) {
            commit("SET_GAS_FILTER_NINJA", newValue);
        },

        setGasFilterJedi({ commit }, newValue) {
            commit("SET_GAS_FILTER_JEDI", newValue);
        },

        setSelectedChar({ commit }, selectedChar) {
            commit("SET_SELECTED_CHAR", selectedChar);
        },

        setLastSystemChars({ commit }, data) {
            commit("SET_LAST_SYSTEM_USERS", data);
        },

        setCurrentSystemChars({ commit }, data) {
            commit("SET_CURRENT_SYSTEM_USERS", data);
        },

        updateLastSystemSigs({ commit }, data) {
            commit("UPDATE_LAST_SYSTEM_SIGS", data);
        },

        updateCurrentSystemSigs({ commit }, data) {
            commit("UPDATE_CURRENT_SYSTEM_SIGS", data);
        },

        updateChars({ commit }, data) {
            commit("UPDATE_CHARACTERS", data);
        },

        pinNode({ commit }, data) {
            commit("PIN_NODE", data);
        },

        updateJove({ commit }, data) {
            commit("UPDATE_JOVE", data);
        },

        updateSystemListRoute({ commit }, data) {
            commit("UPDATE_SYSTEM_LIST_ROUTE", data);
        },

        updateShowInfoPannel({ commit }, num) {
            commit("UPDATE_SHOW_INFO_PANNEL_ID", num);
        },

        updateCopySigBookmark({ commit }, num) {
            commit("UPDATE_COPY_SIG_BOOKMARK", num);
        },

        updateRouteUpdateSystemID({ commit }, id) {
            commit("UPDATE_ROUTE_CURRENT_SYSTEM_ID", id);
        },

        test({ commit }, num) {
            commit("TEST", num);
        },

        testLocationUpdate({ commit }, data) {
            commit("TEST_UPDATE_LOCATION", data);
        },

        testLocationInfo({ commit }, data) {
            commit("TEST_LOCATION_INFO", data);
        },
    },

    getters: {
        getLocationCount: (state) => {
            return state.route.length;
        },

        getCurrentSystemSigsList: (state) => {
            return state.currentSystemSigs.filter(
                (sig) =>
                    sig.signature_id != null &&
                    sig.group.id == 1 &&
                    sig.delete == 0 &&
                    sig.leads_to == null
            );
        },

        getCurrentSystemSigsListCount: (state) => {
            let a = state.currentSystemSigs.filter(
                (sig) =>
                    sig.signature_id != null &&
                    sig.group.id == 1 &&
                    sig.delete == 0 &&
                    sig.leads_to == null
            );
            return a.length;
        },

        getCurrentRoute: (state) => (num) => {
            return state.route[num];
        },
        getUserTrackingStatus: (state) => (id) => {
            let user = state.allthestatsusers.find((user) => user.id == id);
            let count = user.esi_tokens.filter((t) => t.tracking == 1).length;
            if (count == 0) {
                return false;
            } else {
                return true;
            }
        },

        getDrifterCount: (state) => {
            let drifters = state.currentSystemSigs.filter(
                (sig) => sig.name_id == "DRIFT"
            );

            // let index = state.currentSystemSigs.findIndex(sigs => sigs.name_id === "DRIFT" );
            // let directives = state.currentSystemSigs;
            let count = drifters.length;
            if (count == 0) {
                return 1;
            } else {
                var pos = count - 1;
                var name = drifters[pos]["signature_id"];
                var explode = name.split("-");
                var numText = explode[1];
                var numNum = parseInt(numText);
                var num = numNum + 1;

                return num;
            }
        },

        getRouteLinkSystem: (state) => (id) => {
            let sys = state.systemlist.filter((sys) => sys.value == id);
            let cont = sys.length;
            if (cont > 0) {
                return sys;
            } else {
                return null;
            }
        },

        getCurrentSystemCharsCount: (state) => {
            let count = state.currentSystemChars.length;
            if (count > 0) {
                return count;
            } else {
                return 0;
            }
        },

        getLastSystemCharsCount: (state) => {
            let count = state.lastSystemChars.length;
            if (count > 0) {
                return count;
            } else {
                return 0;
            }
        },

        getCheckOpenInfoPannel: (state) => (num) => {
            if (state.showInfoPannelID == num) {
                return true;
            } else {
                return false;
            }
        },

        getUserLogs: (state) => {
            if (state.userLogs) {
                return state.userLogs;
            } else {
                return [];
            }
        },

        getUserURL: (state) => {
            if (state.userLogs) {
                let url =
                    "https://image.eveonline.com/Character/" +
                    state.userLogs.main_character_id +
                    "_128.jpg";
                return url;
            } else {
                return null;
            }
        },

        getSigNotesBySigID: (state) => (payload) => {
            if (payload.type == 1) {
                if (state.currentSigNotes) {
                    let notes = state.currentSigNotes.filter(
                        (sig) => sig.signature_id == payload.id
                    );
                    return notes;
                } else {
                    return [];
                }
            } else {
                if (state.lastSigNotes) {
                    let notes = state.lastSigNotes.filter(
                        (sig) => sig.signature_id == payload.id
                    );
                    return notes;
                } else {
                    return [];
                }
            }
        },

        // getSystemRfffffeadyToGoCount: state => payload => {

        //     return state.campaignusers.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id && u.status_id ==  3).length
        // },
    },
});
