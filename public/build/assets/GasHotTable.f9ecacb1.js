import{J as N,r,a as j,u as L,c as d,e as B,f as F,g as a,w as l,E as u,n as b,i as o,aT as q,Q as S,j as V,ai as A,aF as H,h as v}from"./app.947dfe37.js";import{Q as P}from"./QTd.5e156fed.js";import{a as z,Q as G}from"./QTable.8d314c3a.js";import{C as w}from"./ClosePopup.32ce8d79.js";const R={class:""},$={class:"q-pa-md"},E=o("div",{class:"row full-width flex-center q-pt-xs myRoundTop bg-primary"},[o("div",{class:"col-12 flex flex-center"},[o("span",{class:"text-h6"},"Jabber Gas")])],-1),O={class:"row full-width justify-end items-end bg-webBack"},J={class:"col-3 flex justify-end"},W={__name:"GasHotTable",setup(M){N(e=>({b8475a60:k.value}));let g=r({sortBy:"name",descending:!1,page:1,rowsPerPage:0});const _=j();let s=L(),h=r(0),i=r(null),x=async e=>{var t={jabber:0};await axios({method:"POST",withCredentials:!0,url:"api/nebula/"+e.id,data:t,headers:{Accept:"application/json","Content-Type":"application/json"}}),await s.getNebulaList(),await s.getNebulaHot()},y=async()=>{var e={jabber:1};await axios({method:"POST",withCredentials:!0,url:"api/nebula/"+i.value.value,data:e,headers:{Accept:"application/json","Content-Type":"application/json"}}),await s.getNebulaList(),await s.getNebulaHot(),_.notify({type:"positive",message:"Gas Added"}),i.value=null,h.value=0},C=d(()=>s.nebulaHot),f=d(()=>s.nebulaList),m=d(()=>c.value?f.value.filter(e=>e.text.toLowerCase().indexOf(c.value)>-1):s.nebulaList),c=r(null),T=(e,t,n)=>{t(()=>{c.value=e.toLowerCase(),m.value.length>0&&e&&(i.value=f.value[0])})},Q=r([{name:"name",label:"Name",align:"left",field:e=>e.name,format:e=>`${e}`,sortable:!0},{name:"actions",align:"right"}]),k=d(()=>{let e=530;return s.size.height-e+"px"});return(e,t)=>(B(),F("div",R,[a(G,{class:"gasTable myRoundTop bg-webBack",dense:"",rows:C.value,columns:Q.value,flat:"","table-class":" text-webway","table-header-class":" text-weight-bolder bg-amber","row-key":"id",ref:"tableRef",dark:"",rounded:"",color:"amber","hide-bottom":"",pagination:g.value},{"body-cell-actions":l(n=>[a(P,{props:n},{default:l(()=>[a(u,{push:"",padding:"none",size:"sm","text-color":"negative",round:"",icon:"fas fa-minus-circle",onClick:p=>b(x)(n.row)},null,8,["onClick"])]),_:2},1032,["props"])]),"header-cell-actions":l(n=>[a(z,{props:n},{default:l(()=>[o("div",$,[a(u,{color:"warning",label:"Add Nebual",rounded:"",size:"sm"},{default:l(()=>[a(q,{class:"myRound"},{default:l(()=>[a(S,{class:"my-card"},{default:l(()=>[a(V,null,{default:l(()=>[a(A,{ref:"dropNeb",autofocus:"",modelValue:i.value,"onUpdate:modelValue":t[0]||(t[0]=p=>i.value=p),options:m.value,"input-debounce":"0","option-value":"value","option-label":"text","map-options":"","use-input":"","hide-selected":"",label:"Nebual List",filled:"",onFilter:b(T),"fill-input":""},null,8,["modelValue","options","onFilter"])]),_:1}),a(H,{horizontal:"",align:"evenly"},{default:l(()=>[v(a(u,{flat:"",color:"red",label:"Cancel"},null,512),[[w]]),v(a(u,{flat:"",color:"green",label:"Save",onClick:t[1]||(t[1]=p=>b(y)())},null,512),[[w]])]),_:1})]),_:1})]),_:1})]),_:1})])]),_:2},1032,["props"])]),top:l(n=>[E,o("div",O,[o("div",J,[a(u,{flat:"",round:"",dense:"",icon:n.inFullscreen?"fullscreen_exit":"fullscreen",onClick:n.toggleFullscreen,class:"q-ml-md"},null,8,["icon","onClick"])])])]),_:1},8,["rows","columns","pagination"])]))}};export{W as default};
