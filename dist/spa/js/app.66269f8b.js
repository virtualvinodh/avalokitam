(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["app"],{0:function(e,n,t){e.exports=t("7d3e")},"78ab":function(e,n,t){},"7d3e":function(e,n,t){"use strict";t.r(n);var r=t("52b5"),a=t.n(r),o=(t("67c8"),t("c1c3"),t("549c")),u=t.n(o),l=(t("2233"),t("2f72"),t("838b"),t("78ab"),t("e832")),c=t("f846");l["default"].use(c["a"],{config:{}});var i=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{attrs:{id:"q-app"}},[t("router-view")],1)},p=[],f={name:"App"},s=f,b=t("a6c2"),d=Object(b["a"])(s,i,p,!1,null,null,null),h=d.exports,m=t("4af9"),w=[{path:"/",redirect:"/analyzer"},{path:"/search",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("6892362a"),t.e("494fcee3")]).then(t.bind(null,"c0a2"))}}]},{path:"/reference",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("797c8b41"),t.e("2d0d0bd6")]).then(t.bind(null,"68ec"))}}]},{path:"/types",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("6892362a"),t.e("27d8e4bb")]).then(t.bind(null,"2f28"))}}]},{path:"/download",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("11515b92")]).then(t.bind(null,"6180"))}}]},{path:"/help",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("65d1af48")]).then(t.bind(null,"b213"))}}]},{path:"/learn",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("6892362a"),t.e("0ece7ad0")]).then(t.bind(null,"4b7b"))}}]},{path:"/analyzer",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("6892362a"),t.e("797c8b41"),t.e("de75bc3a")]).then(t.bind(null,"2ccb"))}}]},{path:"/about",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("6892362a"),t.e("250bf050")]).then(t.bind(null,"eff6"))}}]},{path:"/prosody",component:function(){return Promise.all([t.e("7156231a"),t.e("c529b4b8")]).then(t.bind(null,"8f3e"))},children:[{path:"",component:function(){return Promise.all([t.e("7156231a"),t.e("271572cb")]).then(t.bind(null,"fed3"))}}]}];w.push({path:"*",redirect:"/analyzer"});var v=w;l["default"].use(m["a"]);var P=function(){var e=new m["a"]({scrollBehavior:function(){return{x:0,y:0}},routes:v,mode:"history",base:"/"});return e},x=function(){var e="function"===typeof P?P({Vue:l["default"]}):P,n={el:"#q-app",router:e,render:function(e){return e(h)}};return{app:n,router:e}},y=t("2726"),k={failed:"Action failed",success:"Action was successful"},g={"en-us":k};l["default"].use(y["a"]);var A=new y["a"]({locale:"en-us",fallbackLocale:"en-us",messages:g}),z=function(e){var n=e.app;n.i18n=A},V=t("7338"),q=t.n(V);l["default"].prototype.$axios=q.a;var J=t("7ee0"),$=t.n(J),_=function(e){var n=e.Vue;n.use($.a,{id:"UA-44456622-1",router:P})},j=x(),B=j.app,C=j.router;function E(){return L.apply(this,arguments)}function L(){return L=u()(a.a.mark((function e(){var n,t,r,o,u;return a.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=!0,t=function(e){n=!1,window.location.href=e},r=window.location.href.replace(window.location.origin,""),o=[z,void 0,_],u=0;case 5:if(!(!0===n&&u<o.length)){e.next=23;break}if("function"===typeof o[u]){e.next=8;break}return e.abrupt("continue",20);case 8:return e.prev=8,e.next=11,o[u]({app:B,router:C,Vue:l["default"],ssrContext:null,redirect:t,urlPath:r});case 11:e.next=20;break;case 13:if(e.prev=13,e.t0=e["catch"](8),!e.t0||!e.t0.url){e.next=18;break}return window.location.href=e.t0.url,e.abrupt("return");case 18:return console.error("[Quasar] boot error:",e.t0),e.abrupt("return");case 20:u++,e.next=5;break;case 23:if(!1!==n){e.next=25;break}return e.abrupt("return");case 25:new l["default"](B);case 26:case"end":return e.stop()}}),e,null,[[8,13]])}))),L.apply(this,arguments)}E()}},[[0,"runtime","vendor"]]]);