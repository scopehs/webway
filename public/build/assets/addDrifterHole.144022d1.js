import{I as T,a as b,u as k,c as o,e as m,q as g,w as S,h as _,v as f,f as v,k as O,g as n,n as d,E as l,i as u,t as N,O as B,P as $}from"./app.947dfe37.js";import{d as y}from"./date.e8c4c223.js";const q=r=>(B("data-v-68a47d62"),r=r(),$(),r),A=q(()=>u("div",{class:"row"},[u("div",{class:"col"},"Jove Observatrory")],-1)),H={class:"row"},J={class:"col"},P={__name:"addDrifterHole",props:{system:Object,routeID:Number},setup(r){const e=r,h=b();let a=k(),D=()=>{let s=y.buildDate(!0);s=y.formatDate(s,"YYYY-MM-DD HH:mm:ss");var t="DRIFT-"+C.value,i={signature_id:t,name_id:"DRIFT",system_id:e.system.system_id,signature_group_id:1,name:"Unstable Wormhole",life_time:s,created_by_name:a.user_name,modified_by_id:a.user_id};axios({method:"POST",withCredentials:!0,url:"api/adddrifter/"+e.system.system_id,data:i,headers:{Accept:"application/json","Content-Type":"application/json"}})},j=()=>{var s={drifter:1,system_id:e.system.system_id},t={drifter:1,id:e.routeID};a.updateJove(t),axios({method:"POST",withCredentials:!0,url:"api/addjove/"+e.routeID,data:s,headers:{Accept:"application/json","Content-Type":"application/json"}})},x=()=>{var s={drifter:0,system_id:e.system.system_id},t={drifter:0,id:e.routeID};a.updateJove(t),axios({method:"POST",withCredentials:!0,url:"api/addjove/"+e.routeID,data:s,headers:{Accept:"application/json","Content-Type":"application/json"}})},w=()=>{axios({method:"POST",withCredentials:!0,url:"api/mainjovesystemno/"+e.system.system_id,headers:{Accept:"application/json","Content-Type":"application/json"}}),h.notify({type:"positive",message:"Thanks for the info",position:"top",icon:"fa-regular fa-thumbs-up"})},p=o(()=>e.system.system_id==a.currentSystemId&&e.system.jove?e.system.jove.drifter==1:!1),C=o(()=>a.getDrifterCount),I=o(()=>{var s=e.system.system_type[0].id;return s==7||s==8||s==9}),c=o(()=>e.system.system_id==a.currentSystemId?e.system.jove?!1:!!I.value:!1);return(s,t)=>(m(),g(N,{"enter-active-class":"animate__animated animate__flash animate__faster","leave-active-class":"animate__animated animate__flash animate__faster"},{default:S(()=>[_((m(),v("div",{key:`${p.value}-Drift`},[O(" Drifter "),n(l,{color:"primary",class:"myOutLineButton",label:"Add",onClick:t[0]||(t[0]=i=>d(D)()),rounded:"",size:"xs"}),n(l,{color:"negative",class:"myOutLineButton",label:"No",onClick:t[1]||(t[1]=i=>d(w)()),rounded:"",size:"xs"})])),[[f,p.value]]),_((m(),v("div",{key:`${c.value}-Jove`},[A,u("div",H,[u("div",J,[n(l,{color:"primary",label:"Yes",onClick:t[2]||(t[2]=i=>d(j)()),rounded:"",size:"xs"}),n(l,{color:"negative",label:"No",onClick:t[3]||(t[3]=i=>d(x)()),rounded:"",size:"xs"})])])])),[[f,c.value]])]),_:1}))}},M=T(P,[["__scopeId","data-v-68a47d62"]]);export{M as default};
