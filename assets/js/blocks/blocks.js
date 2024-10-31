(()=>{"use strict";var e,t={441:()=>{const e=window.React,t=window.wc.wcBlocksRegistry,n=window.wp.htmlEntities,a=window.wc.wcSettings,r=(0,window.wp.i18n.__)("Netsmax","netsmax-gateway-for-woocommerce"),o=({divload:t})=>{if(t)return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",{style:{width:"100%",height:"32px",backgroundColor:"#f0f0f0",display:"flex",justifyContent:"center",alignItems:"center"}},(0,e.createElement)("div",{style:{border:"4px solid #f3f3f3",borderTop:"4px solid #3498db",borderRadius:"50%",width:"20px",height:"20px",animation:"spin 2s linear infinite"}})))},i=r,s=({title:e})=>(0,n.decodeEntities)(e)||i,l=({settings:t,props:a})=>{const[r,i]=(0,e.useState)(!1),[s,l]=(0,e.useState)(!1),{emitResponse:c,billing:m}=a,{ValidationInputError:d}=a.components,{onCheckoutSuccess:u,onPaymentSetup:p,onCheckoutFail:w,onCheckoutValidation:g}=a.eventRegistration,f=(0,e.useRef)(null),[y,v]=(0,e.useState)(""),E=(0,n.decodeEntities)(t.inline_url),[_,b]=(0,e.useState)(!1);(0,e.useEffect)((()=>{t.div_loading||b(!0),f.current.addEventListener("load",(()=>{b(!0)}))}),[]),(0,e.useEffect)((()=>{const e=p((async()=>{if(!s)return window.location.reload(),{type:c.responseTypes.FAIL,meta:{}};i("");const e=await(n={event:"onSubmit",data:{amount:m.cartTotal.value,currency:m.currency.code}},new Promise(((e,t)=>{const a=t=>{t.source===f.current.contentWindow&&(window.removeEventListener("message",a),e(t.data))};window.addEventListener("message",a),h(n)})));var n;const a=JSON.parse(e);return"onSubmit"===a.event&&200===a.status?(i(""),{type:c.responseTypes.SUCCESS,meta:{paymentMethodData:{"is-netsmax-card-block":!0,request_id:""+t.request_id,card_number:""+a.data?.card_number,prepayment_id:""+a.data?.prepayment_id,prepayment_info:""+a.data?.prepayment_info,timeout:""+a.data?.timeout,amount:""+m.cartTotal.value,currency:""+m.currency.code}}}):(i(a.info),{type:c.responseTypes.FAIL,meta:{}})}));return()=>{e()}}),[t,p,s,y,c.responseTypes.SUCCESS]);const h=e=>{f.current&&f.current.contentWindow.postMessage(JSON.stringify(e),E)},x=e=>{if(e.source===f.current.contentWindow){const t=JSON.parse(e.data);200===t?.status&&"init"===t?.event&&l(!0),v(t.info),window.removeEventListener("message",x)}return()=>{window.removeEventListener("message",x)}};return window.addEventListener("message",x),(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",{id:"netsmax-gateway-for-woocommerce-cards-inline",className:"netsmax-gateway-for-woocommerce-cards-inline"},!_&&(0,e.createElement)(o,{divload:t.div_loading}),(0,e.createElement)("iframe",{width:"100%",height:"32px",id:"netsmax-gateway-for-woocommerce-cards-inline-iframe",name:"netsmax-gateway-for-woocommerce-cards-inline-iframe",style:{display:_?"block":"none"},allowtransparency:"true",frameBorder:"0",scrolling:"no",src:E,ref:f})),(0,e.createElement)("div",null,(0,e.createElement)("input",{type:"hidden",name:"request_id",value:t.request_id})),(0,e.createElement)(d,{errorMessage:r}))},c=({logoUrls:t,label:n})=>t?(0,e.createElement)("div",{style:{display:"flex",flexDirection:"row",gap:"0.5rem",flexWrap:"wrap"}},t.map(((t,a)=>(0,e.createElement)("img",{key:a,src:t,alt:n})))):"",m=(0,a.getSetting)("netsmax-gateway-for-woocommerce_data",{}),d=s({title:m.title}),u=(({payment_page:e,button_name:t})=>"inline"!==e?"":t)({payment_page:m.payment_page,button_name:m.button_name}),p=t=>"inline"!==m.payment_page?(0,n.decodeEntities)(m.description||""):(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",null,(0,n.decodeEntities)(m.description||"")),(0,e.createElement)(l,{settings:m,props:t})),w={name:m.name,icons:m.icons,label:(0,e.createElement)((({logoUrls:t,title:n})=>(0,e.createElement)("div",{style:{display:"flex",flexDirection:"row",gap:"0.5rem"}},(0,e.createElement)("div",null,s({title:n})),(0,e.createElement)(c,{logoUrls:t,label:s({title:n})}))),{logoUrls:m.logo_urls,title:d}),content:(0,e.createElement)(p,null),edit:(0,e.createElement)(p,null),canMakePayment:()=>!0,ariaLabel:d,placeOrderButtonLabel:u};(0,t.registerPaymentMethod)(w)}},n={};function a(e){var r=n[e];if(void 0!==r)return r.exports;var o=n[e]={exports:{}};return t[e](o,o.exports,a),o.exports}a.m=t,e=[],a.O=(t,n,r,o)=>{if(!n){var i=1/0;for(m=0;m<e.length;m++){for(var[n,r,o]=e[m],s=!0,l=0;l<n.length;l++)(!1&o||i>=o)&&Object.keys(a.O).every((e=>a.O[e](n[l])))?n.splice(l--,1):(s=!1,o<i&&(i=o));if(s){e.splice(m--,1);var c=r();void 0!==c&&(t=c)}}return t}o=o||0;for(var m=e.length;m>0&&e[m-1][2]>o;m--)e[m]=e[m-1];e[m]=[n,r,o]},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={346:0,812:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var r,o,[i,s,l]=n,c=0;if(i.some((t=>0!==e[t]))){for(r in s)a.o(s,r)&&(a.m[r]=s[r]);if(l)var m=l(a)}for(t&&t(n);c<i.length;c++)o=i[c],a.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return a.O(m)},n=globalThis.webpackChunknetsmax_gateway_for_woocommerce=globalThis.webpackChunknetsmax_gateway_for_woocommerce||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var r=a.O(void 0,[812],(()=>a(441)));r=a.O(r)})();