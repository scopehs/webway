import{u as Q,r as T,c as l,e as E,f as O,g as o,n as V,E as j,w as m,i as t,z as s,aT as z}from"./app.947dfe37.js";import{b as G}from"./QTable.8d314c3a.js";const R=t("thead",null,[t("tr",null,[t("th",{class:"text-left"},"Type"),t("th",{class:"text-right"},"Complete"),t("th",{class:"text-right"},"Not Complete")])],-1),U=t("td",{class:"text-left"},"Wormhole",-1),W={class:"text-right"},$={class:"text-right"},q=t("td",{class:"text-left"},"Relic Site",-1),A={class:"text-right"},F={class:"text-right"},H=t("td",{class:"text-left"},"Data Site",-1),I={class:"text-right"},J={class:"text-right"},K=t("td",{class:"text-left"},"Gas Site",-1),L={class:"text-right"},P={class:"text-right"},X=t("td",{class:"text-left"},"Combat Site",-1),Y={class:"text-right"},Z={class:"text-right"},tt=t("td",{class:"text-left"},"Ore Site",-1),et={class:"text-right"},lt={class:"text-right"},st=t("td",{class:"text-left"},"Unknowen",-1),rt=t("td",{class:"text-right"},"-",-1),ot={class:"text-right"},ut={__name:"nextSystemSigs",props:{nextSigs:Object},setup(S){const r=S;Q();let b=T(!1),D=()=>{b.value=!0},n=l(()=>r.nextSigs.filter(e=>e.signature_group_id===1)),a=l(()=>n.value.filter(e=>e.completed_by_id>0).length),y=l(()=>n.value.length-a.value),i=l(()=>r.nextSigs.filter(e=>e.signature_group_id===2)),u=l(()=>i.value.filter(e=>e.completed_by_id>0).length),N=l(()=>i.value.length-u.value),d=l(()=>r.nextSigs.filter(e=>e.signature_group_id===3)),_=l(()=>d.value.filter(e=>e.completed_by_id>0).length),w=l(()=>d.value.length-_.value),c=l(()=>r.nextSigs.filter(e=>e.signature_group_id===4)),g=l(()=>c.value.filter(e=>e.completed_by_id>0).length),k=l(()=>c.value.length-g.value),h=l(()=>r.nextSigs.filter(e=>e.signature_group_id===5)),f=l(()=>h.value.filter(e=>e.completed_by_id>0).length),C=l(()=>h.value.length-f.value),x=l(()=>r.nextSigs.filter(e=>e.signature_group_id===6)),v=l(()=>x.value.filter(e=>e.completed_by_id>0).length),B=l(()=>x.value.length-v.value),M=l(()=>r.nextSigs.filter(e=>e.signature_group_id===7).length);return(e,p)=>(E(),O("div",null,[o(j,{"text-color":"primary",padding:"none",icon:"fa-solid fa-magnifying-glass-arrow-right",flat:"",onClick:p[0]||(p[0]=nt=>V(D)())}),o(z,{anchor:"bottom left",self:"top left"},{default:m(()=>[o(G,{dark:"",class:"bg-indigo-8"},{default:m(()=>[R,t("tbody",null,[t("tr",null,[U,t("td",W,s(a.value),1),t("td",$,s(y.value),1)]),t("tr",null,[q,t("td",A,s(u.value),1),t("td",F,s(N.value),1)]),t("tr",null,[H,t("td",I,s(_.value),1),t("td",J,s(w.value),1)]),t("tr",null,[K,t("td",L,s(g.value),1),t("td",P,s(k.value),1)]),t("tr",null,[X,t("td",Y,s(f.value),1),t("td",Z,s(C.value),1)]),t("tr",null,[tt,t("td",et,s(v.value),1),t("td",lt,s(B.value),1)]),t("tr",null,[st,rt,t("td",ot,s(M.value),1)])])]),_:1})]),_:1})]))}};export{ut as default};
