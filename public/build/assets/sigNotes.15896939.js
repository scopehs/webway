import{I as B,J as S,d as A,_ as E,u as H,r,c as u,e as p,f as I,g as l,n as d,E as m,w as s,Q as M,j as c,k as P,z as $,q as g,s as _,aF as j,h as v,aH as R}from"./app.947dfe37.js";import{Q as Y}from"./QTd.5e156fed.js";import{Q as q}from"./QTable.8d314c3a.js";import{Q as F}from"./QInput.0a5b359e.js";import{C as y}from"./ClosePopup.32ce8d79.js";import{d as U}from"./date.e8c4c223.js";const z={__name:"sigNotes",props:{sig:Object,type:Number},setup(f){const o=f;S(e=>({e9c4a9c0:D.value}));const b=A(()=>E(()=>import("./deleteSigNotes.4edb6536.js"),["assets/deleteSigNotes.4edb6536.js","assets/app.947dfe37.js","assets/app.51e76855.css"]));let x=H(),w=r({sortBy:"time",descending:!0,page:1,rowsPerPage:50}),i=r(!1),n=r(null),C=()=>{i.value=!0},h=()=>{n.value=null,i.value=!1},k=async()=>{let e={signature_id:o.sig.id,connection_id:o.sig.connection_id,text:n.value,system_id:o.sig.system_id};await axios({method:"post",withCredentials:!0,url:"/api/addsignote",data:e,headers:{Accept:"application/json","Content-Type":"application/json"}}),n.value=null},Q=u(()=>o.sig.notes),V=u(()=>{var e=o.sig.notes.length;return e==0?"fa-regular fa-message":"fa-solid fa-message"}),N=u(()=>n.value==null),T=r([{name:"text",label:"Text",align:"left",field:e=>e.text,format:e=>`${e}`},{name:"user",label:"User",align:"left",field:e=>e.user.name,format:e=>`${e}`,sortable:!0},{name:"time",label:"Time",align:"left",field:e=>e.created_at,format:e=>U.formatDate(e,"YYYY-MM-DD - HH:mm"),sortable:!0},{name:"actions",label:"",align:"right"}]),D=u(()=>{let e=50;return x.size.height-e+"px"});return(e,t)=>(p(),I("div",null,[l(m,{"text-color":"primary",icon:V.value,flat:"",padding:"none",round:"",onClick:t[0]||(t[0]=a=>d(C)())},null,8,["icon"]),l(R,{modelValue:i.value,"onUpdate:modelValue":t[3]||(t[3]=a=>i.value=a),persistent:"",onBeforeHide:t[4]||(t[4]=a=>d(h)())},{default:s(()=>[l(M,{style:{"max-width":"1200px","max-height":"1200px"},class:"myRoundTop"},{default:s(()=>[l(c,{class:"myCardHeader bg-primary text-h5 text-center"},{default:s(()=>[P(" Notes for "+$(f.sig.signature_id),1)]),_:1}),l(c,null,{default:s(()=>[l(q,{class:"myRound bg-webBack mySigMessageTable",rows:Q.value,columns:T.value,style:{width:"1000px"},"table-class":"text-webway","table-header-class":"bg-amber","row-key":"id",dense:"",dark:"",ref:"tableRef",rounded:"",color:"amber",pagination:w.value},{"top-right":s(a=>[l(m,{flat:"",padding:"none",round:"",dense:"",icon:a.inFullscreen?"fullscreen_exit":"fullscreen",onClick:a.toggleFullscreen,class:"q-ml-md"},null,8,["icon","onClick"])]),"body-cell-actions":s(a=>[l(Y,{props:a},{default:s(()=>[l(d(b),{item:a.row},null,8,["item"])]),_:2},1032,["props"])]),_:1},8,["rows","columns","pagination"])]),_:1}),o.type==1?(p(),g(c,{key:0},{default:s(()=>[l(F,{"input-style":"height: 100px min-width:700px",modelValue:n.value,"onUpdate:modelValue":t[1]||(t[1]=a=>n.value=a),autofocus:"",placeholder:"Enter notes here",outlined:"",type:"textarea"},null,8,["modelValue"])]),_:1})):_("",!0),l(j,{align:"right"},{default:s(()=>[o.type==1?v((p(),g(m,{key:0,color:"positive",label:"Submit",onClick:t[2]||(t[2]=a=>d(k)()),disabled:N.value,rounded:""},null,8,["disabled"])),[[y]]):_("",!0),v(l(m,{color:"secondary",rounded:"",label:"Close"},null,512),[[y]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}},X=B(z,[["__scopeId","data-v-e583539b"]]);export{X as default};
