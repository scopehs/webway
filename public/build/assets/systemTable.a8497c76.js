import{I as ve,J as pe,u as we,K as he,d as m,_ as f,o as Se,b as be,r as L,c as u,e as o,f as w,g as s,w as l,i,a2 as P,ar as S,as as x,k as g,z as c,q as v,s as _,M as T,F as N,p as A,E as O,aT as xe,n as r,T as ke,l as Ie,ao as Ce,aE as De}from"./app.947dfe37.js";import{Q as p}from"./QTd.5e156fed.js";import{Q as Ee}from"./QTr.d1fe520f.js";import{Q as Te}from"./QTable.8d314c3a.js";const Re={class:"q-pa-md"},Le={class:"col-auto"},Pe={key:0},Ne={class:"col-auto"},Ae={class:"row full-width"},Oe={class:"col text-h4"},Ve=["src"],Be={class:"row full-width"},Qe={class:"col-auto text-h5"},Me={key:0},ze={class:"row"},Ke={class:"col-auto flex"},je={class:"row full-width justify-end"},qe={class:"col-auto"},He={class:"row full-width justify-end"},$e={class:"col-auto"},Fe={class:"row full-width justify-end q-gutter-xs"},Ue={class:"col-auto"},Ge={class:"col-auto"},Je={key:0,class:"col-auto"},We={key:1,class:"col-auto"},Xe={__name:"systemTable",props:{system:Object,type:Number,lastRouteSystemID:Number,routeID:Number,lo:Number,currentSystemPropID:Number,lastSystemPropID:Number},setup(d){const a=d;pe(e=>({"8a423aa8":ge.value}));let n=we(),V=he("can");const B=m(()=>f(()=>import("./nextSystemSigs.510c93ef.js"),["assets/nextSystemSigs.510c93ef.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTable.8d314c3a.js"])),Q=m(()=>f(()=>import("./addDrifterHole.144022d1.js"),["assets/addDrifterHole.144022d1.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/date.e8c4c223.js","assets/addDrifterHole.f5407348.css"])),M=m(()=>f(()=>import("./systemNotes.e358ff2a.js"),["assets/systemNotes.e358ff2a.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTd.5e156fed.js","assets/QTable.8d314c3a.js","assets/QInput.0a5b359e.js","assets/ClosePopup.32ce8d79.js","assets/date.e8c4c223.js","assets/systemNotes.aa1865e6.css"])),z=m(()=>f(()=>import("./cellSigId.676bbc46.js"),["assets/cellSigId.676bbc46.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTooltip.46988d05.js","assets/copy-to-clipboard.6d423000.js"])),K=m(()=>f(()=>import("./cellGroup.77b31751.js"),["assets/cellGroup.77b31751.js","assets/QTooltip.46988d05.js","assets/app.947dfe37.js","assets/app.51e76855.css"])),j=m(()=>f(()=>import("./cellAge.14a62bb6.js"),["assets/cellAge.14a62bb6.js","assets/app.947dfe37.js","assets/app.51e76855.css"])),q=m(()=>f(()=>import("./cellWormholeTypeButton.b50dde2b.js"),["assets/cellWormholeTypeButton.b50dde2b.js","assets/app.947dfe37.js","assets/app.51e76855.css"])),H=m(()=>f(()=>import("./cellLinkToButton.d7e2c0a6.js"),["assets/cellLinkToButton.d7e2c0a6.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTooltip.46988d05.js"])),k=m(()=>f(()=>import("./cell.bea202f6.js"),["assets/cell.bea202f6.js","assets/app.947dfe37.js","assets/app.51e76855.css"])),$=m(()=>f(()=>import("./routeInfoPaste.9c6f09e0.js"),["assets/routeInfoPaste.9c6f09e0.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QInput.0a5b359e.js","assets/ClosePopup.32ce8d79.js"])),F=m(()=>f(()=>import("./sigNotes.15896939.js"),["assets/sigNotes.15896939.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/QTd.5e156fed.js","assets/QTable.8d314c3a.js","assets/QInput.0a5b359e.js","assets/ClosePopup.32ce8d79.js","assets/date.e8c4c223.js","assets/sigNotes.9f4609f0.css"])),U=m(()=>f(()=>import("./routeDeleteButtonNoRolesIsOwner.e0b6bd12.js"),["assets/routeDeleteButtonNoRolesIsOwner.e0b6bd12.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/ClosePopup.32ce8d79.js"])),G=m(()=>f(()=>import("./routeDeleteButtonHasRolesIsOwner.17742ef6.js"),["assets/routeDeleteButtonHasRolesIsOwner.17742ef6.js","assets/app.947dfe37.js","assets/app.51e76855.css","assets/ClosePopup.32ce8d79.js"])),J=m(()=>f(()=>import("./routeDeleteButtonHasRolesNotOwner.16e8d2c9.js"),["assets/routeDeleteButtonHasRolesNotOwner.16e8d2c9.js","assets/app.947dfe37.js","assets/app.51e76855.css"])),W=m(()=>f(()=>import("./routeDeleteButtonNoRolesNotOwner.860088d5.js"),["assets/routeDeleteButtonNoRolesNotOwner.860088d5.js","assets/app.947dfe37.js","assets/app.51e76855.css"]));Se(async()=>{a.type==1&&(window.Echo.private("mapping."+a.system.system_id).listen("MappingUpdate",e=>{e.flag.flag==1&&n.updateCurrentSystemSigs(e.flag.message),e.flag.flag==2&&(n.deleteCurrentSystemSig(e.flag.id),n.deleteLastSystemSig(e.flag.id)),e.flag.flag==3&&n.updateJove(e.flag.message),e.flag.flag==4&&n.setCurrentSystemChars(e.flag.message),e.flag.flag==5&&n.updateCurrentKillCount(e.flag.kills),e.flag.flag==6&&n.getSystemNotes({type:1,id:a.system.system_id}),e.flag.flag==7&&n.getSigNotes({type:1,id:a.system.system_id})}),window.Echo.private("mapping."+a.lastRouteSystemID).listen("MappingUpdate",e=>{e.flag.flag==1&&n.updateLastSystemSigs(e.flag.message),e.flag.flag==2&&(n.deleteCurrentSystemSig(e.flag.id),n.deleteLastSystemSig(e.flag.id)),e.flag.flag==4&&n.setLastSystemChars(e.flag.message),e.flag.flag==5&&n.updateLastKillCount(e.flag.kills),e.flag.flag==6&&n.getSystemNotes({type:2,id:a.lastSystemPropID}),e.flag.flag==7&&n.getSigNotes({type:2,id:a.lastSystemPropID})}))}),be(async()=>{window.Echo.leave("mapping."+a.lastRouteSystemID),window.Echo.leave("mapping."+a.system.system_id)});let X=L({sortBy:"id",descending:!1,page:1,rowsPerPage:0}),Y=e=>{switch(e){case 1:return"text-negative";case 2:return"text-negative";case 3:return"text-negative";case 4:return"text-negative";case 5:return"text-negative";case 6:return"text-negative";case 7:return"text-primary";case 8:return"text-warning";case 9:return"text-negative";case 12:return"text-negative";case 13:return"text-negative";case 14:return"text-negative";case 15:return"text-negative";case 16:return"text-negative";case 17:return"text-negative";case 18:return"text-negative";case 25:return"text-negative"}},Z=e=>!!(e.type&&a.type==1),ee=()=>{if(a.type==1)return!0},b=e=>{var y=!1,t=!1;if(V("delete_sigs")&&(y=!0),e.created_by_id==n.user_id&&(t=!0),y==!1&&t==!0)return 1;if(y==!0&&t==!0)return 2;if(y==!0&&t==!1)return 3;if(y==!1&&t==!1)return 4},te=e=>e.signature_group_id==1,se=e=>"https://image.eveonline.com/Character/"+e+"_128.jpg",I=e=>e?e.table_text:"",ae=u(()=>R.value==0),le=u(()=>a.type==1?n.currentSystemChars:n.lastSystemChars),re=u(()=>a.type==1?n.currentKillCount>0?n.currentKillCount:0:n.lastKillCount>0?n.lastKillCount:0),R=u(()=>a.type==1?n.getCurrentSystemCharsCount:n.getLastSystemCharsCount),ne=u(()=>{switch(a.system.system_type[0].id){case 7:return!0;case 8:return!0;case 9:return!0;default:return!1}}),ie=u(()=>a.type==1?n.currentSystemSigs.filter(e=>e.delete!=1):n.lastSystemSigs.filter(e=>e.delete!=1)),C=u(()=>{var e=a.lo+1;return e==n.getLocationCount}),ue=u(()=>{switch(a.type){case 1:return"Current System";default:return"Last System"}}),oe=u(()=>a.system.system_type[0].name_full),de=u(()=>!!a.system.shattered);u(()=>{if(a.system.system_type[0].id<7||a.system.system_type[0].id>9)return"blue-grey darken-2";if(a.system.system_type[0].id==7)return"primay";if(a.system.system_type[0].id==8)return"amber darken-4";if(a.system.system_type[0].id==9)return"deep-orange darken-2"});let D=u(()=>{var e=a.system.security,y=Math.round(e*10)/10;return y==1?"1.0":y==-1?"-1.0":y}),me=u(()=>D.value<=0?"text-negative":D.value>=.45?"text-primay":"text-warning"),fe=u(()=>{if(a.system.effect[0])return a.system.effect[0].name}),ye=u(()=>"bg-blue-grey-10"),_e=u(()=>a.system.statics.length>0),ce=L([{name:"id",label:"ID",align:"left",field:e=>e.signature_id,format:e=>`${e}`,sortable:!0},{name:"group",label:"Group",align:"left",field:e=>e.group.name,format:e=>`${e}`,sortable:!0},{name:"type",label:"Type",align:"left"},{name:"age",label:"Age(HH:MM)",field:e=>e.life_left,format:e=>`${e}`,align:"center"},{name:"linkTo",label:"Link to",align:"left",field:e=>e.leads_to,format:e=>`${e}`},{name:"mass",label:"Mass",align:"left"},{name:"size",label:"Size",align:"left"},{name:"life",label:"Life",align:"left"},{name:"actions",align:"right"}]),ge=u(()=>{let e=100;return n.size.height-e+"px"});return(e,y)=>(o(),w("div",Re,[s(Te,{class:"myRound bg-webBack stepTable overflow-hidden",rows:ie.value,columns:ce.value,"table-class":"text-webway","table-header-class":"bg-amber","row-key":"id",dense:"",dark:"",ref:"tableRef",rounded:"","hide-bottom":"",color:"amber",pagination:X.value},{top:l(t=>[i("div",{class:T(["row full-width justify-between q-py-xs text-webway myRoundTop",ye.value])},[i("div",Le,[s(P,{dense:"",class:"q-pt-xs text-webway"},{default:l(()=>[s(S,null,{default:l(()=>[s(x,{class:"q-pl-sm",dense:""},{default:l(()=>[g(" Class: "+c(oe.value)+" ",1),de.value?(o(),v(Ie,{key:0,name:"fa-solid fa-skull"})):_("",!0)]),_:1})]),_:1}),s(S,null,{default:l(()=>[s(x,{dense:""},{default:l(()=>[g(" Security: "),i("span",{class:T(me.value)},c(D.value),3)]),_:1})]),_:1}),s(S,null,{default:l(()=>[s(x,{dense:""},{default:l(()=>[g(" Effects: "+c(fe.value),1)]),_:1})]),_:1}),s(S,null,{default:l(()=>[s(x,{dense:""},{default:l(()=>[g(" Statics: "),_e.value?(o(),w("span",Pe,[(o(!0),w(N,null,A(d.system.statics,(h,E)=>(o(),w("span",{key:E},[g(c(h.wormhole_type)+" ",1),i("span",{class:T(r(Y)(h.type.id))}," ("+c(h.type.name)+") ",3),g(" \xA0\xA0 ")]))),128))])):_("",!0)]),_:1})]),_:1})]),_:1})]),i("div",Ne,[i("div",Ae,[i("div",Oe,[g(c(ue.value)+" - ",1),s(O,{color:"green-9",size:"xs",disabled:ae.value,round:"",label:R.value},{default:l(()=>[s(xe,{"transition-show":"rotate","transition-hide":"rotate"},{default:l(()=>[s(P,{style:{"min-width":"100px"}},{default:l(()=>[(o(!0),w(N,null,A(le.value,(h,E)=>(o(),v(Ce,{key:E},{default:l(()=>[s(S,{avatar:""},{default:l(()=>[s(De,null,{default:l(()=>[i("img",{src:r(se)(h.id)},null,8,Ve)]),_:2},1024)]),_:2},1024),s(S,null,{default:l(()=>[g(c(h.name),1)]),_:2},1024)]),_:2},1024))),128))]),_:1})]),_:1})]),_:1},8,["disabled","label"])])]),i("div",Be,[i("div",Qe,[i("span",null,c(d.system.name),1),ne.value?(o(),w("span",Me," - "+c(d.system.region.name),1)):_("",!0)])]),i("div",ze,[s(r(Q),{system:d.system,routeID:d.routeID},null,8,["system","routeID"])])]),i("div",Ke,[i("div",je,[i("div",qe,[g(" Kills 24H - "+c(re.value)+" ",1),s(O,{flat:"",padding:"none",round:"",dense:"",icon:t.inFullscreen?"fullscreen_exit":"fullscreen",onClick:t.toggleFullscreen,class:"q-ml-md"},null,8,["icon","onClick"])])]),i("div",He,[i("div",$e,[s(r(M),{system:d.system,type:d.type},null,8,["system","type"])])])])],2)]),body:l(t=>[s(ke,{mode:"out-in","leave-active-class":"animate__animated animate__zoomOut"},{default:l(()=>[(o(),v(Ee,{props:t,key:`${t.row.signature_id} - row`},{default:l(()=>[s(p,{key:"id",props:t},{default:l(()=>[s(r(z),{value:t.row.signature_id,sig:t.row,type:d.type,edit:C.value,lastSystem:d.lastSystemPropID,currentSystem:d.currentSystemPropID},null,8,["value","sig","type","edit","lastSystem","currentSystem"])]),_:2},1032,["props"]),s(p,{key:"group",props:t},{default:l(()=>[s(r(K),{value:t.row.group.name,typeName:t.row.name,typeID:t.row.group.id},null,8,["value","typeName","typeID"])]),_:2},1032,["props"]),s(p,{key:"type",props:t},{default:l(()=>[r(te)(t.row)?(o(),v(r(q),{key:0,sig:t.row,type:d.type,edit:C.value,system:d.system},null,8,["sig","type","edit","system"])):_("",!0)]),_:2},1032,["props"]),s(p,{key:"age",props:t},{default:l(()=>[s(r(j),{age:t.row.life_time},null,8,["age"])]),_:2},1032,["props"]),s(p,{key:"linkTo",props:t},{default:l(()=>[s(r(H),{sig:t.row,type:d.type,edit:C.value},null,8,["sig","type","edit"])]),_:2},1032,["props"]),s(p,{key:"mass",props:t},{default:l(()=>[s(r(k),{value:r(I)(t.row.wormhole_info_mass),type:2},null,8,["value"])]),_:2},1032,["props"]),s(p,{key:"size",props:t},{default:l(()=>[s(r(k),{value:r(I)(t.row.wormhole_info_ship_size),type:1},null,8,["value"])]),_:2},1032,["props"]),s(p,{key:"life",props:t},{default:l(()=>[s(r(k),{value:r(I)(t.row.wormhole_info_time_till_death),type:2},null,8,["value"])]),_:2},1032,["props"]),s(p,{key:"actions",props:t},{default:l(()=>[i("div",Fe,[i("div",Ue,[r(Z)(t.row)?(o(),v(r($),{key:0,id:`${t.row.id}-infopannel`,sig:t.row},null,8,["id","sig"])):_("",!0)]),i("div",Ge,[s(r(F),{sig:t.row,type:d.type},null,8,["sig","type"])]),t.row.leads_to>0?(o(),w("div",Je,[s(r(B),{nextSigs:t.row.next_system_sigs},null,8,["nextSigs"])])):_("",!0),r(ee)()?(o(),w("div",We,[r(b)(t.row)==1?(o(),v(r(U),{key:0,item:t.row,type:t.type},null,8,["item","type"])):_("",!0),r(b)(t.row)==2?(o(),v(r(G),{key:1,item:t.row},null,8,["item"])):_("",!0),r(b)(t.row)==3?(o(),v(r(J),{key:2,item:t.row,type:t.type},null,8,["item","type"])):_("",!0),r(b)(t.row)==4?(o(),v(r(W),{key:3,item:t.row,type:t.type},null,8,["item","type"])):_("",!0)])):_("",!0)])]),_:2},1032,["props"])]),_:2},1032,["props"]))]),_:2},1024)]),_:1},8,["rows","columns","pagination"])]))}},st=ve(Xe,[["__scopeId","data-v-a0f74a92"]]);export{st as default};