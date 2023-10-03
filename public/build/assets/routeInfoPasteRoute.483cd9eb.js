import{r as n,c as w,e as x,f as S,g as t,n as r,E as u,w as o,Q as _,j as m,i as k,z as d,aF as Q,h as V,aH as T}from"./app.947dfe37.js";import{Q as I}from"./QInput.0a5b359e.js";import{C as j}from"./ClosePopup.32ce8d79.js";const A={class:"text-h5"},P={__name:"routeInfoPasteRoute",props:{item:Object},emits:["infoClosed"],setup(f,{emit:h}){const i=f;let g=n(" <-> "),l=n(),v=n(`An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere.

This wormhole seems to lead into unknown parts of space.

This wormhole is reaching the end of its natural lifetime.
This wormhole has not yet had its stability significantly disrupted by ships passing through it.
Larger ships can pass through this wormhole.

Stage 1 = Stable/Fresh
Stage 2 = Destab/Shrink
Stage 3 = Crit/EOL`),s=n(!1),p=()=>{l.value=null,h("infoClosed"),s.value=!1},y=()=>{s.value=!0},C=async()=>{let c={text:l.value};await axios({method:"post",withCredentials:!0,url:"/api/parse_info/"+i.item.connection.source_sig.id,data:c,headers:{Accept:"application/json","Content-Type":"application/json"}}),p()},b=w(()=>l.value==null);return(c,e)=>(x(),S("div",null,[t(u,{icon:"fa-solid fa-question-circle",flat:"",label:"Update Connection Info",onClick:e[0]||(e[0]=a=>r(y)())}),t(T,{modelValue:s.value,"onUpdate:modelValue":e[4]||(e[4]=a=>s.value=a),persistent:""},{default:o(()=>[t(_,{class:"myRoundTop"},{default:o(()=>[t(m,{class:"row items-center bg-primary"},{default:o(()=>[k("span",A,"Paste In Info for Connection "+d(i.item.connection.source_sig.signature_id)+" "+d(g.value)+" "+d(i.item.connection.target_sig.signature_id)+".",1)]),_:1}),t(m,null,{default:o(()=>[t(I,{"input-style":"height: 500px",modelValue:l.value,"onUpdate:modelValue":e[1]||(e[1]=a=>l.value=a),placeholder:v.value,outlined:"",type:"textarea"},null,8,["modelValue","placeholder"])]),_:1}),t(Q,{align:"right"},{default:o(()=>[t(u,{color:"primary",label:"Submit",onClick:e[2]||(e[2]=a=>r(C)()),disabled:b.value},null,8,["disabled"]),V(t(u,{color:"secondary",label:"Close",onClick:e[3]||(e[3]=a=>r(p)())},null,512),[[j,2]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{P as default};
