import{u as p,c as s,e as a,f as o,g as n,w as r,h as d,v as m,E as v,n as k,k as u,z as f,t as x}from"./app.947dfe37.js";import{Q as T}from"./QTooltip.46988d05.js";const b=["id"],B=["id"],C={__name:"cellLinkToButton",props:{sig:Object,type:Number,edit:Boolean},setup(t){const e=t;let i=p(),c=async()=>{{var l={modified_by_id:i.user_id,modified_by_name:i.user_name,current_system_id:i.currentSystemId,wormhole_info_leads_to_id:e.sig.wormhole_info_leads_to_id,wormhole_info_mass_id:e.sig.wormhole_info_mass_id,wormhole_info_ship_size_id:e.sig.wormhole_info_ship_size_id,wormhole_info_time_till_death_id:e.sig.wormhole_info_time_till_death_id,sig_id:e.sig.id,last_system_id:i.lastSystemId,life_time:e.sig.life_time,drift_number:i.getDrifterCount};await axios({method:"POST",withCredentials:!0,url:"api/leadsto",data:l,headers:{Accept:"application/json","Content-Type":"application/json"}})}},g=s(()=>!(e.sig.leads_to||e.type==1||!e.sig.type||!e.edit)),y=s(()=>e.sig.leads_to>0),h=s(()=>e.sig.linked_solar_system?e.sig.linked_solar_system.name:null),w=s(()=>e.sig.linked_solar_system?e.sig.linked_solar_system.system_type[0].name+": "+e.sig.linked_solar_system.region.name+" -> "+e.sig.linked_solar_system.constellation.name:null);return(l,_)=>(a(),o("div",null,[n(x,{mode:"out-in","enter-active-class":"animate__animated animate__zoomIn animate__faster","leave-active-class":"animate__animated animate__zoomOut animate__faster"},{default:r(()=>[d((a(),o("div",{key:`${t.sig.leads_to}-${t.sig.signature_id}-button`,id:`${t.sig.id}-button`},[n(v,{color:"warning",size:"xs",class:"myOutLineButton",label:"Link",onClick:_[0]||(_[0]=S=>k(c)()),rounded:""})],8,b)),[[m,g.value]]),d((a(),o("div",{key:`${t.sig.leads_to}-${t.sig.signature_id}-text`,class:"text-left",id:`${t.sig.leads_to}-text`},[u(f(h.value)+" ",1),n(T,{delay:800},{default:r(()=>[u(f(w.value),1)]),_:1})],8,B)),[[m,y.value]])]),_:1})]))}};export{C as default};