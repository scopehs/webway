import{I as p,e as r,f as l,F as _,p as m,q as d,M as x,n as s,at as g}from"./app.947dfe37.js";const h={__name:"SigGasNebulasDamageChip",props:{items:Array},setup(t){const o=t;let n=e=>e=="em"?e.toUpperCase():e.charAt(0).toUpperCase()+e.slice(1),i=e=>n(e.type)+" - "+e.amount,c=e=>{if(e=="thermal")return"fire";if(e=="em")return"ice";if(e=="explosive")return"boom"},u=e=>{if(e=="thermal")return"fa-solid fa-fire";if(e=="em")return"fa-solid fa-poo-storm";if(e=="explosive")return"fa-solid fa-explosion"};return(e,b)=>(r(),l("div",null,[(r(!0),l(_,null,m(o.items,(a,f)=>(r(),d(g,{class:x(s(c)(a.type)),icon:s(u)(a.type),key:f,size:"md",label:s(i)(a)},null,8,["class","icon","label"]))),128))]))}},v=p(h,[["__scopeId","data-v-c042796c"]]);export{v as default};
