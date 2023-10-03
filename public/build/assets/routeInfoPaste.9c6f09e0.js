import{u as S,r as d,c as p,e as I,f as Q,g as t,n as s,E as f,w as n,Q as T,j as v,k as V,z as P,bk as $,aF as j,h as w,aH as A}from"./app.947dfe37.js";import{Q as B}from"./QInput.0a5b359e.js";import{C as y}from"./ClosePopup.32ce8d79.js";const O={__name:"routeInfoPaste",props:{sig:Object},setup(m){const a=m;let i=S(),o=d(null),x=d(`An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere.

This wormhole seems to lead into unknown parts of space.

This wormhole is reaching the end of its natural lifetime.
This wormhole has not yet had its stability significantly disrupted by ships passing through it.
Larger ships can pass through this wormhole.

Stage 1 = Stable/Fresh
Stage 2 = Destab/Shrink
Stage 3 = Crit/EOL`),r=d(!1),u=()=>{o.value=null,i.updateShowInfoPannel(null),r.value=!1},b=()=>{r.value=!0,i.updateShowInfoPannel(a.sig.id)},h=async()=>{let g={text:o.value};await axios({method:"post",withCredentials:!0,url:"/api/parse_info/"+a.sig.id,data:g,headers:{Accept:"application/json","Content-Type":"application/json"}}),u()},C=p(()=>a.sig.wormhole_info_mass_id==null?"fa-regular fa-circle-question":"fa-solid fa-circle-question"),c=p(()=>r.value?!0:a.sig.wormhole_info_mass?!1:i.getCheckOpenInfoPannel(a.sig.id)),k=p(()=>o.value==null);return(g,e)=>(I(),Q("div",null,[t(f,{"text-color":"primary",padding:"none",icon:C.value,flat:"",onClick:e[0]||(e[0]=l=>s(b)())},null,8,["icon"]),t(A,{modelValue:c.value,"onUpdate:modelValue":e[5]||(e[5]=l=>c.value=l),persistent:"",onBeforeHide:e[6]||(e[6]=l=>s(u)())},{default:n(()=>[t(T,{class:"myRoundTop",style:{width:"500px"}},{default:n(()=>[t(v,{class:"bg-primary text-h5 text-center"},{default:n(()=>[V(" Paste Info for "+P(m.sig.signature_id)+". ",1)]),_:1}),t(v,null,{default:n(()=>[t(B,{"input-style":"height: 500px",modelValue:o.value,"onUpdate:modelValue":e[1]||(e[1]=l=>o.value=l),placeholder:x.value,autofocus:"",outlined:"",type:"textarea",onKeyup:e[2]||(e[2]=$(l=>s(h)(),["enter"]))},null,8,["modelValue","placeholder"])]),_:1}),t(j,{align:"right"},{default:n(()=>[w(t(f,{color:"positive",icon:"check",label:"Submit",onClick:e[3]||(e[3]=l=>s(h)()),disabled:k.value},null,8,["disabled"]),[[y]]),w(t(f,{label:"Close",color:"secondary",onClick:e[4]||(e[4]=l=>s(u)())},null,512),[[y]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{O as default};