import{u as n,c as u,e as l,q as p,n as c,E as d,s as m}from"./app.947dfe37.js";const g={__name:"SigReserveButton",props:{item:[Array,Object]},setup(r){const s=r;let a=n(),i=async()=>{let t={user_id:a.user_id,sig_id:s.item.id};await axios({method:"post",withCredentials:!0,url:"/api/siguser",data:t,headers:{Accept:"application/json","Content-Type":"application/json"}})},o=u(()=>{var t=s.item.reserve.length,e=1;return s.item.signature_group_id==4&&(e=25),t!=e});return(t,e)=>o.value?(l(),p(d,{key:0,rounded:"",push:"",outline:"",color:"white",class:"text-weight-bold myOutLineButton",label:"Reserve",onClick:e[0]||(e[0]=_=>c(i)()),size:"sm"})):m("",!0)}};export{g as default};
