import{K as l,a as c,c as d,e as s,f as u,q as p,n as m,E as f,s as y}from"./app.947dfe37.js";const B={__name:"deleteSystemNotes",props:{item:Object},setup(a){const e=a;let n=l("can");const o=c();let i=async()=>{await axios({method:"delete",withCredentials:!0,url:"/api/deletesystemnotes/"+e.item.id,headers:{Accept:"application/json","Content-Type":"application/json"}}),o.notify({type:"positive",message:"Note deleted."})},r=d(()=>!!(n("delete_system_logs")||state.user_id==e.item.user_id));return(_,t)=>(s(),u("div",null,[r.value?(s(),p(f,{key:0,"text-color":"negative",icon:"fa-solid fa-trash-can",round:"",size:"sm",padding:"none",onClick:t[0]||(t[0]=k=>m(i)())})):y("",!0)]))}};export{B as default};
