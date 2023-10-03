import{a as L,u as O,d as b,_ as j,K as W,r as T,e as r,f as G,g as o,w as s,aT as N,a2 as _,n as i,h as f,q as c,E as p,ao as u,s as d,ar as k}from"./app.947dfe37.js";import{C as y}from"./ClosePopup.32ce8d79.js";const H={__name:"routeButton",props:{item:Object},emits:["avoidConnection"],setup(t,{emit:g}){const n=t,v=L();let q=O();const A=b(()=>j(()=>import("./routeInfoPasteRoute.483cd9eb.js"),["assets/routeInfoPasteRoute.483cd9eb.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QInput.0a5b359e.js","assets/ClosePopup.32ce8d79.js"])),E=b(()=>j(()=>import("./connectionRate.b4bc7133.js"),["assets/connectionRate.b4bc7133.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTd.5e156fed.js","assets/QTable.8d314c3a.js","assets/QInput.0a5b359e.js","assets/date.e8c4c223.js","assets/connectionRate.4ea10b24.css"]));let C=W("can"),m=T(!1);T(0);let P=a=>{if(n.item.solar_system)switch(n.item.solar_system.system_type[0].name){case"NS":return!0;case"LS":return!0;case"HS":return!0;case"Poch":return!0}return!1},w=a=>{g("avoidConnection",a.connection.id)},x=()=>{m.value=!1},R=async a=>{m.value=!1,await axios({method:"POST",withCredentials:!0,url:"api/reserveconnection/"+n.item.connection.id,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(e=>{this.$emit("routeReserved",n.item.jump)})},$=async a=>{await axios({method:"DELETE",withCredentials:!0,url:"api/deleteConnection/"+n.item.connection.id,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(e=>{v.notify({type:"info",message:"Connection removed and route recalculated."})}),w(a)},S=async a=>{await axios({method:"GET",withCredentials:!0,url:"api/reportConnection/"+n.item.connection.id,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(e=>{v.notify({type:"info",message:"Connection reported, added to your avoid list and route recalculated."})}),w(a)},h=a=>{if(C("make_reserved_connection")&&n.item.connection)switch(a.connection.type.id){case 2:return!0;case 4:return!0;case 5:return!0}return!1},V=a=>{if(C("delete_connections")&&n.item.connection)switch(n.item.connection.type.id){case 2:return!0;case 4:return!0;case 5:return!0}return!1},I=a=>{if(!C("delete_connections")&&n.item.connection)switch(n.item.connection.type.id){case 2:return!0;case 4:return!0;case 5:return!0}return!1},Q=a=>{if(n.item.connection)switch(n.item.connection.type.id){case 2:return!0;case 3:return!0;case 4:return!0;case 5:return!0}return!1},B=async a=>{m.value=!1;var e={system_id:n.item.solar_system.system_id,character_id:q.selectedChar,add_to_beginning:!1,clear_other_waypoints:!0};await axios({method:"POST",withCredentials:!0,url:"api/setwaypoint",data:e,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(l=>{var D="Waypoint set to "+n.item.solar_system.name;v.notify({type:"info",message:D})})};return(a,e)=>(r(),G("div",null,[o(p,{color:"webway",round:"",flat:"",padding:"none",icon:"fa-solid fa-ellipsis-vertical"},{default:s(()=>[o(N,{modelValue:m.value,"onUpdate:modelValue":e[7]||(e[7]=l=>m.value=l)},{default:s(()=>[o(_,{style:{"min-width":"100px"}},{default:s(()=>[i(P)(t.item)?f((r(),c(u,{key:0,class:"q-pa-none"},{default:s(()=>[f(o(p,{flat:"",icon:"fa-solid fa-map-pin",label:"Set Waypoint",onClick:e[0]||(e[0]=l=>i(B)(t.item))},null,512),[[y]])]),_:1})),[[y]]):d("",!0),i(h)(t.item)?(r(),c(u,{key:1,class:"q-pa-none"},{default:s(()=>[f(o(p,{icon:"fa-solid fa-code-branch",flat:"",label:"Reserve Connection",onClick:e[1]||(e[1]=l=>i(R)(t.item))},null,512),[[y]])]),_:1})):d("",!0),i(Q)(t.item)?(r(),c(u,{key:2,class:"q-pa-none"},{default:s(()=>[f(o(p,{icon:"fa-solid fa-database",flat:"",label:"Avoid Connection",onClick:e[2]||(e[2]=l=>i(w)(t.item))},null,512),[[y]])]),_:1})):d("",!0),i(h)(t.item)?(r(),c(u,{key:3,class:"q-pa-none"},{default:s(()=>[o(k,null,{default:s(()=>[o(i(E),{onFeedbackclosed:e[3]||(e[3]=l=>m.value=!1),item:t.item},null,8,["item"])]),_:1})]),_:1})):d("",!0),i(h)(t.item)?(r(),c(u,{key:4,class:"q-pa-none"},{default:s(()=>[o(k,null,{default:s(()=>[o(i(A),{onInfoClosed:e[4]||(e[4]=l=>i(x)()),item:t.item},null,8,["item"])]),_:1})]),_:1})):d("",!0),i(V)(t.item)?(r(),c(u,{key:5,class:"q-pa-none"},{default:s(()=>[f(o(p,{icon:"fa-solid fa-minus-circle",color:"warning",flat:"",label:"Connection Gone",onClick:e[5]||(e[5]=l=>i($)(t.item))},null,512),[[y]]),o(k)]),_:1})):d("",!0),i(I)(t.item)?(r(),c(u,{key:6,class:"q-pa-none"},{default:s(()=>[f(o(p,{icon:"fa-solid fa-minus-circle",color:"warning",flat:"",label:"Connection Gone",onClick:e[6]||(e[6]=l=>i(S)(t.item))},null,512),[[y]])]),_:1})):d("",!0)]),_:1})]),_:1},8,["modelValue"])]),_:1})]))}};export{H as default};