import{A as R,c as u,C as r,G as N,aS as E,K as D,u as F,d as P,_ as G,o as O,r as j,e as _,f as y,g as m,w as c,q as B,k as M,z as w,s as H,E as T,Q as K,j as V,t as J,F as X,p as Y,i as k,aF as Z,n as f,aH as ee}from"./app.947dfe37.js";import{Q as te}from"./QInput.0a5b359e.js";const ae=["top","middle","bottom"],ne=R({name:"QBadge",props:{color:String,textColor:String,floating:Boolean,transparent:Boolean,multiLine:Boolean,outline:Boolean,rounded:Boolean,label:[Number,String],align:{type:String,validator:e=>ae.includes(e)}},setup(e,{slots:s}){const l=u(()=>e.align!==void 0?{verticalAlign:e.align}:null),i=u(()=>{const x=e.outline===!0&&e.color||e.textColor;return`q-badge flex inline items-center no-wrap q-badge--${e.multiLine===!0?"multi":"single"}-line`+(e.outline===!0?" q-badge--outline":e.color!==void 0?` bg-${e.color}`:"")+(x!==void 0?` text-${x}`:"")+(e.floating===!0?" q-badge--floating":"")+(e.rounded===!0?" q-badge--rounded":"")+(e.transparent===!0?" q-badge--transparent":"")});return()=>r("div",{class:i.value,style:l.value,role:"status","aria-label":e.label},N(s.default,e.label!==void 0?[e.label]:[]))}}),se=R({name:"QChatMessage",props:{sent:Boolean,label:String,bgColor:String,textColor:String,name:String,avatar:String,text:Array,stamp:String,size:String,labelHtml:Boolean,nameHtml:Boolean,textHtml:Boolean,stampHtml:Boolean},setup(e,{slots:s}){const l=u(()=>e.sent===!0?"sent":"received"),i=u(()=>`q-message-text-content q-message-text-content--${l.value}`+(e.textColor!==void 0?` text-${e.textColor}`:"")),x=u(()=>`q-message-text q-message-text--${l.value}`+(e.bgColor!==void 0?` text-${e.bgColor}`:"")),g=u(()=>"q-message-container row items-end no-wrap"+(e.sent===!0?" reverse":"")),b=u(()=>e.size!==void 0?`col-${e.size}`:""),C=u(()=>({msg:e.textHtml===!0?"innerHTML":"textContent",stamp:e.stampHtml===!0?"innerHTML":"textContent",name:e.nameHtml===!0?"innerHTML":"textContent",label:e.labelHtml===!0?"innerHTML":"textContent"}));function q(o){return s.stamp!==void 0?[o,r("div",{class:"q-message-stamp"},s.stamp())]:e.stamp?[o,r("div",{class:"q-message-stamp",[C.value.stamp]:e.stamp})]:[o]}function h(o,d){const p=d===!0?o.length>1?v=>v:v=>r("div",[v]):v=>r("div",{[C.value.msg]:v});return o.map((v,S)=>r("div",{key:S,class:x.value},[r("div",{class:i.value},q(p(v)))]))}return()=>{const o=[];s.avatar!==void 0?o.push(s.avatar()):e.avatar!==void 0&&o.push(r("img",{class:`q-message-avatar q-message-avatar--${l.value}`,src:e.avatar,"aria-hidden":"true"}));const d=[];s.name!==void 0?d.push(r("div",{class:`q-message-name q-message-name--${l.value}`},s.name())):e.name!==void 0&&d.push(r("div",{class:`q-message-name q-message-name--${l.value}`,[C.value.name]:e.name})),s.default!==void 0?d.push(h(E(s.default()),!0)):e.text!==void 0&&d.push(h(e.text)),o.push(r("div",{class:b.value},d));const p=[];return s.label!==void 0?p.push(r("div",{class:"q-message-label"},s.label())):e.label!==void 0&&p.push(r("div",{class:"q-message-label",[C.value.label]:e.label})),p.push(r("div",{class:g.value},o)),r("div",{class:`q-message q-message-${l.value}`},p)}}});const le=k("div",{class:"text-h6"},"Support Chat",-1),ie={key:0},re={key:1},me={__name:"chatWindow",props:{item:{type:Object,required:!1}},setup(e){const s=e;let l=D("can"),i=F();const x=P(()=>G(()=>import("./index.ff6a3aa5.js"),["assets/index.ff6a3aa5.js","assets/app.947dfe37.js","assets/app.51e76855.css"]));O(async()=>{l("super_admin")||await window.Echo.private("room."+i.supportRoom.id).listen("RoomUpdate",t=>{t.flag.flag==1&&i.updateUserMessage(t.flag.message),t.flag.flag==2,t.flag.flag==3,t.flag.flag==4})});let g=j(),b=j(!1);const C=async()=>{const t=l("super_admin")?s.item.id:i.supportRoom.id,a={message:g.value};try{await axios.post(`/api/support/message/${t}`,a,{withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}}),g.value=null}catch{}};let q=async()=>{l("super_admin")?await i.clearWebWayMessageCount(s.item.id):await i.clearUserMessageCount();const t=l("super_admin")?s.item.id:i.supportRoom.id;try{await axios.post(`/api/support/messageclear/${t}`,{withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}}),g.value=null}catch{}},h=async()=>{const t=l("super_admin")?s.item.id:i.supportRoom.id;try{await axios.post(`/api/support/closeroom/${t}`,{withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}}),g.value=null,b.value=!1}catch{}},o=t=>new Date(t).getTime(),d=u(()=>l("super_admin")?i.getWebWayMessageCount(s.item.id):i.getUserMessageCount),p=u(()=>l("super_admin")?i.getWebWayMessages(s.item.id):i.getUserMessages),v=u(()=>d.value?"fa-solid fa-message":"fa-regular fa-message"),S=t=>i.user_id==t,A=(t,a)=>i.user_id==a?"me":l("super_admin")?t:"WebWay",U=t=>i.user_id==t?"amber-7":"positive",z=u(()=>!g.value),I=t=>{var n,$;let a=0;if(l("super_admin"))a=(n=t.user.main_character_id)!=null?n:t.user.character_id;else{if(t.user.id==25107)return"https://goonfleet.com/public/style_extra/team_icons/pf_bee.png";a=($=t.user.main_character_id)!=null?$:t.user.character_id}return"https://image.eveonline.com/Character/"+a+"_128.jpg"};return(t,a)=>(_(),y("div",null,[m(T,{"text-color":"positive",icon:v.value,round:"",flat:"",onClick:a[0]||(a[0]=n=>b.value=!0)},{default:c(()=>[d.value?(_(),B(ne,{key:0,color:"red",floating:""},{default:c(()=>[M(w(d.value),1)]),_:1})):H("",!0)]),_:1},8,["icon"]),m(ee,{modelValue:b.value,"onUpdate:modelValue":a[4]||(a[4]=n=>b.value=n),onBeforeHide:a[5]||(a[5]=n=>f(h)()),onBeforeShow:a[6]||(a[6]=n=>f(q)())},{default:c(()=>[m(K,{class:"my-card myRoundTop",style:{width:"1000px","max-height":"900px",height:"900px"}},{default:c(()=>[m(V,{class:"bg-primary myCardHeader text-center"},{default:c(()=>[le]),_:1}),m(V,{id:"messages",class:"overflow-auto",style:{height:"600px"}},{default:c(()=>[m(J,{"enter-active-class":"animate__animated animate__zoomIn"},{default:c(()=>[(_(!0),y(X,null,Y(p.value,(n,$)=>(_(),B(se,{key:`${n.id}-message`,name:f(A)(n.user.name,n.user_id),avatar:f(I)(n),text:[n.message],sent:f(S)(n.user_id),"bg-color":f(U)(n.user_id)},{stamp:c(()=>[M("Sent: "),(_(),B(f(x),{key:`${n.id}-time`,interval:6e4,time:f(o)(n.created_at)},{default:c(({days:Q,hours:W,minutes:L,seconds:oe})=>[Q!="00"?(_(),y("span",ie,w(Q)+"days, ",1)):H("",!0),W!="00"?(_(),y("span",re,w(W)+"hours,",1)):H("",!0),k("span",null,w(L)+"minutes",1),M(" ago ")]),_:2},1032,["time"]))]),_:2},1032,["name","avatar","text","sent","bg-color"]))),128))]),_:1})]),_:1}),m(V,null,{default:c(()=>[k("div",null,[m(te,{"input-style":"height: 150px",modelValue:g.value,"onUpdate:modelValue":a[1]||(a[1]=n=>g.value=n),clearable:"",outlined:"",rounded:"",dense:"",type:"textarea",label:"Message"},null,8,["modelValue"])])]),_:1}),m(Z,{align:"between"},{default:c(()=>[m(T,{rounded:"",label:"Submit",color:"primary",disable:z.value,onClick:a[2]||(a[2]=n=>C())},null,8,["disable"]),m(T,{rounded:"",label:"Close",color:"negative",onClick:a[3]||(a[3]=n=>f(h)())})]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{me as default};