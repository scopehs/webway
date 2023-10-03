import { defineStore } from "pinia";
//

export const useMainStore = defineStore("main", {
    state: () => ({
        test: 2,
        size: [],
        activityTypes: [],
        allthestatsusers: [],
        allthestatusuerslastmonth: [],
        allthestatsconnections: [],
        brokenChain: [],
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
        roles: [],
        routeNodes: {},
        routeLinks: {},
        regionlist: [],
        selectedChar: null,
        sigsAddedCount: [],
        sigConnectioncount: [],
        sigInfoUpdateCount: [],
        sigsUpdatedCount: [],
        staticMissing: [],
        systemListRoute: [],
        savedRoutes: [],
        supportRooms: [],
        supportRoom: null,
        supportMessages: [],
        showInfoPannelID: null,
        copySigBookmark: null,
        systemlist: [],
        showSystemInput: false,
        shortestRoute: [],
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
        usersroles: [],
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

        hotAreaSystemShow: false,
        hotAreaConstellationShow: false,
        hotAreaRegionShow: false,
        routeTableShowMenu: false,
    }),

    getters: {
        getNodeCount: (state) => {
            return state.routeNodes.length;
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

        getHotAreaSystemList: (state) => {
            let hot = state.hotArea;
            var sys = state.systemlist;
            hot.forEach((h) => {
                if (h.system_id != null) {
                    sys = sys.filter((s) => s.value != h.system_id);
                }
            });
            return sys;
        },

        getHotConstellationList: (state) => {
            let hot = state.hotArea;
            var con = state.constellationlist;
            hot.forEach((h) => {
                if (h.constellation_id != null) {
                    con = con.filter((c) => c.value != h.constellation_id);
                }
            });
            return con;
        },

        getHotRegionList: (state) => {
            let hot = state.hotArea;
            var reg = state.regionlist;
            hot.forEach((h) => {
                if (h.region_id != null) {
                    reg = reg.filter((c) => c.value != h.region_id);
                }
            });
            return reg;
        },

        getWhalersSystemList: (state) => {
            let hot = state.hotArea;
            var reg = state.regionlist;
            hot.forEach((h) => {
                if (h.region_id != null) {
                    reg = reg.filter((c) => c.value != h.region_id);
                }
            });
            return reg;
        },

        getLocationCount: (state) => {
            return state.route.length;
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

        getDrifterCount: (state) => {
            let drifters = state.currentSystemSigs.filter(
                (sig) => sig.name_id == "DRIFT"
            );
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

        getDrifterCount: (state) => {
            let drifters = state.currentSystemSigs.filter(
                (sig) => sig.name_id == "DRIFT"
            );
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

        getCheckOpenInfoPannel: (state) => (num) => {
            if (state.showInfoPannelID == num) {
                return true;
            } else {
                return false;
            }
        },

        getWebWayMessageCount: (state) => (id) => {
            let room = state.supportRooms.find((r) => r.id == id);
            let messages = room.messages;
            let count = messages.filter((m) => m.read_by_webway == 0).length;
            return count;
        },

        getWebWayMessages: (state) => (id) => {
            let room = state.supportRooms.find((r) => r.id == id);
            let messages = room.messages;
            return messages;
        },

        getUserMessageCount: (state) => {
            if (state.supportRoom) {
                if (state.supportRoom.messages) {
                    let messages = state.supportRoom.messages;
                    let count = messages.filter(
                        (m) => m.read_by_user == 0
                    ).length;
                    return count;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        },

        getUserMessages: (state) => {
            if (state.supportRoom) {
                let messages = state.supportRoom.messages;
                return messages;
            } else {
                return [];
            }
        },

        showChatButton: (state) => {
            if (state.supportRoom) {
                return true;
            } else {
                return false;
            }
        },
    },
    actions: {
        async setRouteMapping() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/routemapping/" + this.selectedChar,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            let route = res.data.links;
            let nodes = res.data.nodes;
            let data = {};
            nodes.forEach((r) => {
                Object.assign(data, r);
            });

            this.routeNodes = data;

            let date1 = {};
            route.forEach((r) => {
                Object.assign(date1, r);
            });

            this.routeLinks = date1;
        },

        async getRoles() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/roles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.roles = res.data.roles.map((role) => ({
                ...role,
                selected: false,
            }));
        },

        async getUsers() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allusersroles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.usersroles = res.data.usersroles;
        },

        async getLoginInfo() {
            let res = await axios({
                method: "get",
                url: "/api/user/info",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.user_name = res.data.data.username;
            this.user_id = res.data.data.user_id;
            this.selectedChar = res.data.data.char;
        },
        async getEvents() {
            let res = await axios({
                method: "get",
                url: "/api/events",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.events = res.data.events;
        },

        clearRouteKeeptracking() {
            this.route = [];
            this.currentSystemId = null;
            this.currentSystemSigs = [];
            this.currentTempSystemID = null;
            this.lastSystemId = null;
            this.lastSystemSigs = [];
            this.lastTempSystemID = null;
            this.routeID = 1;
            this.rolesList = [];
            this.currentSystemChars = [];
            this.lastSystemChars = [];
            this.routeNodes = [];
            this.routeLinks = [];
        },

        async getSiteUrl() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/geturl",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.siteUrl = res.data.url;
        },

        async setSigShow() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/setSigShow",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.sigShow = res.data.num[0];
        },

        async getCharList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/charlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.charactersList = res.data.charlist;
        },

        async getAllByUserId() {
            axios.defaults.withCredentials = true;
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/charsbyuser/" + this.user_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.characters = res.data.chars;
        },

        updateChars(data) {
            const item = this.characters.find((char) => char.id == data.id);
            const count = this.characters.filter(
                (char) => char.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.characters.push(data);
            }
        },

        updateOverlayESIRemoved(data) {
            this.overlayESIRemoved = data;
        },

        addRoute(data) {
            const item = this.route.find((r) => r.id == data.id);
            const count = this.route.filter((r) => r.id == data.id).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.route.push(data);
            }
        },

        updateOverlayUpdateShow(data) {
            if (data == "true") {
                this.overlayUpdateShow = true;
            } else {
                this.overlayUpdateShow = false;
            }
        },

        updateOverlayRefreshShow(data) {
            this.overlayRefreshShow = data;
        },

        updateLocationStatus(status) {
            this.locationStatus = status;
        },

        setSigShowSocket(num) {
            this.sigShow = num;
        },

        clearRoute() {
            this.route = [];
            this.currentSystemId = null;
            this.currentSystemSigs = [];
            this.currentTempSystemID = null;
            this.lastSystemId = null;
            this.lastSystemSigs = [];
            this.lastTempSystemID = null;
            this.routeID = 1;
            this.tracking = false;
            this.rolesList = [];
            this.currentSystemChars = [];
            this.lastSystemChars = [];
            this.routeNodes = [];
            this.routeLinks = [];
        },

        async getLocationStatus() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getlocationstatus",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.locationStatus = res.data.status;
        },

        async getAllConnections() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/connections",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.connections = res.data.connections;
        },

        async getConnectionLists() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/connectionlists",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.connectionRegionList = res.data.region;
            this.connectionConstellationList = res.data.constellation;
        },

        updateAllTheStatsUser(data) {
            const item = this.allthestatsusers.find(
                (user) => user.id == data.id
            );
            const count = this.allthestatsusers.filter(
                (user) => user.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.allthestatsusers.push(data);
            }
        },

        async setAllTheStatsUser() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allthestats",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.allthestatsusers = res.data.stats;
        },

        async getAllTheStatsLastMonth() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allthestatslastmonth",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.allthestatusuerslastmonth = res.data.stats;
        },

        updateHotArea(data) {
            const item = this.hotArea.find((hot) => hot.id == data.id);
            const count = this.hotArea.filter(
                (hot) => hot.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.hotArea.push(data);
            }
        },

        async getNebulaHot() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebula",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.nebulaHot = res.data.hotNebula;
        },

        async getNebulaList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebulalist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.nebulaList = res.data.nebulalist;
        },

        async setHotArea() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/hotarea",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.hotArea = res.data.hot;
        },

        async getRegionList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/regionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.regionlist = res.data.regionlist;
        },

        async getSystemList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/systemlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.systemlist = res.data.systemlist;
        },

        async getShortestRoute() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/shortest",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.shortestRoute = res.data.short;
        },

        updateShortestRoute(data) {
            const item = this.shortestRoute.find((s) => s.id == data.id);
            const count = this.shortestRoute.filter(
                (s) => s.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.shortestRoute.push(data);
            }
        },

        deleteShortestRoute(id) {
            const data = this.shortestRoute.filter((s) => s.id != id);
            this.shortestRoute = data;
        },

        async getConstellationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/constellationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.constellationlist = res.data.constellationlist;
        },

        async getNebulaList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebulalist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.nebulaList = res.data.nebulalist;
        },

        async getNebulaHot() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nebula",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.nebulaHot = res.data.hotNebula;
        },

        setHotAreaSystemShow() {
            this.hotAreaSystemShow = !this.hotAreaSystemShow;
        },

        setHotAreaConstellationShow() {
            this.hotAreaConstellationShow = !this.hotAreaConstellationShow;
        },

        setHotAreaRegionShow() {
            this.hotAreaRegionShow = !this.hotAreaRegionShow;
        },

        updateJoveSystem(data) {
            const item = this.joveSystems.find((jove) => jove.id == data.id);
            const count = this.joveSystems.filter(
                (jove) => jove.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.joveSystems.push(data);
            }
        },

        async getJoveRegions() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/joveregionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.joveRegionList = res.data.regions;
        },

        async getJoveSystems() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/jovesystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.joveSystems = res.data.systems;
        },

        async getSavedRoutes() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/savedroutes",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.savedRoutes = res.data.routes;
        },

        updateRouteUpdateSystemID(id) {
            this.routeCurrentSystemID = id;
        },

        async getReservedConnection() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/reservedconnection",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.reservedConnections = res.data.reserved;
        },

        async getTitanList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/titanlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.titanList = res.data.titanList;
        },

        async getConnectionNotes(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getconnectionNotes/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.connectionNotes = res.data.notes;
        },

        updateSigs(data) {
            const item = this.sigs.find((s) => s.id == data.id);
            const count = this.sigs.filter((s) => s.id == data.id).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.sigs.push(data);
            }
        },

        deleteSigs(id) {
            const item = this.sigs.filter((s) => s.id != id);
            this.sigs = item;
        },

        async getSigs() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getsigs",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.sigs = res.data.sigs;
        },

        async getGasInfo() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getgasinfo",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.gasInfo = res.data.gasinfo;
        },

        updateSigTableSearch(newValue) {
            this.sigTableSearch = newValue;
        },

        updateGasInfoShow(newValue) {
            this.gasSigInfoShow = newValue;
        },

        setGasFilterDamage(newValue) {
            this.gasFilterDamage = newValue;
        },

        setGasFilterNPC(newValue) {
            this.gasFilterNPC = newValue;
        },

        setGasFilterNinja(newValue) {
            this.gasFilterNinja = newValue;
        },

        setGasFilterJedi(newValue) {
            this.gasFilterJedi = newValue;
        },

        updateSigTableSearch(newValue) {
            this.sigTableSearch = newValue;
        },

        async getDrifterTypeList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/driftertypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.drifterTypeList = res.data.drifterTypeList;
        },

        testLocationUpdate(data) {
            this.currentKillCount = data.currentSystemKills;
            if (data.currentSystemUsers) {
                this.currentSystemChars = data.currentSystemUsers.characters;
            } else {
                this.currentSystemChars = null;
            }
            this.currentSystemId = data.currentSystemID;
            this.currentSystemNotes = data.currentSystemNotes;
            this.currentSystemSigs = data.currentSystemSigs;
            this.currentSystemSigsNotes = data.currentSystemSigNotes;
            if (data.wormholeTypeList) {
                this.wormholeTypeList = data.wormholeTypeList.wormholeTypeList;
            } else {
                this.wormholeTypeList = null;
            }
            if (data.lastSystemID > 0 || data.lastSystemID != null) {
                this.lastKillCount = data.lastSystemKills;
                if (data.lastSystemUsers) {
                    this.lastSystemChars = data.lastSystemUsers.characters;
                } else {
                    this.lastSystemChars = [];
                }
                this.lastSystemId = data.lastSystemID;
                this.lastSystemNotes = data.lastSystemNotes;
                this.lastSystemSigs = data.lastSystemSigs;
            } else {
                this.lastKillCount = 0;
                this.lastSystemChars = [];
                this.lastSystemId = null;
                this.lastSystemNotes = [];
                this.lastSystemSigs = [];
            }
            this.route = data.route;
        },

        testLocationInfo(data) {
            // this.currentKillCount = 0;
            // this.currentSystemChars = [];
            // this.currentSystemNotes = [];
            // this.currentSystemSigs = [];
            // this.currentSystemSigsNotes = [];
            // this.lastKillCount = 0;
            // this.lastSystemChars = [];
            // this.lastSystemNotes = [];
            // this.lastSystemSigs = [];

            this.currentKillCount = data.currentSystemKills;
            this.currentSystemChars = data.currentSystemUsers.characters;
            this.currentSystemNotes = data.currentSystemNotes;
            this.currentSystemSigs = data.currentSystemSigs;
            this.currentSystemSigsNotes = data.currentSystemSigNotes;
            if (data.lastSystemID > 0) {
                this.lastKillCount = data.lastSystemKills;
                this.lastSystemId = data.lastSystemID;
                this.lastSystemChars = data.lastSystemUsers.characters;
                this.lastSystemNotes = data.lastSystemNotes;
                this.lastSystemSigs = data.lastSystemSigs;
            } else {
                this.lastKillCount = 0;
                this.lastSystemChars = [];
                this.lastSystemId = null;
                this.lastSystemNotes = [];
                this.lastSystemSigs = [];
            }
        },

        async getBrokenChain() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/broken",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.brokenChain = res.data.broken;
        },

        updateCurrentSystemSigs(data) {
            // console.log(data);
            const item = this.currentSystemSigs.find(
                (sig) => sig.id == data.id
            );
            const count = this.currentSystemSigs.filter(
                (sig) => sig.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.currentSystemSigs.push(data);
            }
        },

        deleteCurrentSystemSig(id) {
            let index = this.currentSystemSigs.findIndex((s) => s.id == id);
            if (index >= 0) {
                this.currentSystemSigs.splice(index, 1);
            }
        },

        deleteLastSystemSig(id) {
            let index = this.lastSystemSigs.findIndex((s) => s.id == id);
            if (index >= 0) {
                this.lastSystemSigs.splice(index, 1);
            }
        },

        updateJove(data) {
            const item = this.route.find((route) => route.id == data.id);
            const count = this.route.filter(
                (route) => route.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        setCurrentSystemChars(data) {
            this.currentSystemChars = data.characters;
        },

        updateCurrentKillCount(kills) {
            this.currentKillCount = kills;
        },

        async getSystemNotes({ type, id }) {
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
            if (payload.type == 1) {
                this.currentSystemNotes = payload.systemNotes;
            }

            if (payload.type == 2) {
                this.lastSystemNotes = payload.systemNotes;
            }
        },

        async getSigNotes({ type, id }) {
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
            if (payload.type == 1) {
                this.currentSigNotes = payload.sigNotes;
            }

            if (payload.type == 2) {
                this.lastSigNotes = payload.sigNotes;
            }
        },

        async getMissingStatic() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/static/static",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.staticMissing = res.data.static;
        },

        updateLastSystemSigs(data) {
            const item = this.lastSystemSigs.find((sig) => sig.id == data.id);
            const count = this.lastSystemSigs.filter(
                (sig) => sig.id == data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.lastSystemSigs.push(data);
            }
        },

        setLastSystemChars(data) {
            this.lastSystemChars = data.characters;
        },

        updateLastKillCount(kills) {
            this.lastKillCount = kills;
        },

        updateCopySigBookmark(num) {
            this.copySigBookmark = num;
        },

        updateShowInfoPannel(num) {
            this.showInfoPannelID = num;
        },

        removeStatic(id) {
            this.staticMissing = this.staticMissing.filter((s) => s.id != id);
        },

        removeBroken(id) {
            this.brokenChain = this.brokenChain.filter((b) => b.id != id);
        },

        updateStatic(data) {
            const item = this.static.find((stat) => stat.id === data.id);
            if (item) {
                Object.assign(item, data);
            } else {
                this.static.push(data);
            }
        },

        updateBroken(data) {
            const item = this.brokenChain.find((stat) => stat.id === data.id);
            if (item) {
                Object.assign(item, data);
            } else {
                this.brokenChain.push(data);
            }
        },

        updateRoom(data) {
            const item = this.supportRooms.find(
                (rooms) => rooms.id === data.id
            );
            if (item) {
                Object.assign(item, data);
            } else {
                this.supportRooms.push(data);
            }
        },

        async getSupportRooms() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/support/rooms",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.supportRooms = res.data.rooms;
        },

        async getUserList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/userlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.userList = res.data.userlist;
        },

        async getFullUserList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/fulluserlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.userList = res.data.userlist;
        },

        async getSupportRoom(userID) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/support/room/" + userID,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.supportRoom = res.data.room;
        },

        clearWebWayMessageCount(id) {
            let room = this.supportRooms.find((r) => r.id == id);
            if (room) {
                if (room.messages.length > 0) {
                    room.messages.forEach((m) => {
                        m.read_by_webway = 1;
                    });
                }
            }
        },

        clearUserMessageCount() {
            if (this.supportRoom.messages.length > 0) {
                this.supportRoom.messages.forEach((m) => {
                    m.read_by_user = 1;
                });
            }
        },

        updateUserMessage(data) {
            this.supportRoom.messages.unshift(data);
        },

        updateWebWayMessage(data, roomID) {
            let room = this.supportRooms.find((r) => r.id == roomID);
            room.messages.unshift(data);
        },
    },
});
