import{a as p,u as l,e as d,f as m,g as u,n as c,E as f}from"./app.947dfe37.js";const v={__name:"routeDeleteButtonHasRolesNotOwner",props:{item:Object,type:Number},setup(n){const t=n,o=p();let a=l(),r=async()=>{var s=t.item.name_id;await axios({method:"POST",withCredentials:!0,url:"api/sigdone/"+t.item.id,headers:{Accept:"application/json","Content-Type":"application/json"}});var e={id:t.item.id,delete:1};t.type==1?a.updateCurrentSystemSigs(e):a.updateLastSystemSigs(e);var i="Sig "+s+" has been removed";o.notify({type:"positive",message:i})};return(s,e)=>(d(),m("div",null,[u(f,{"text-color":"negative",icon:"fa-solid fa-circle-minus",flat:"",size:"sm",padding:"none",onClick:e[0]||(e[0]=i=>c(r)())})]))}};export{v as default};
