(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-60c102ae"],{1079:function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("b-card",e._g(e._b({attrs:{"no-body":""}},"b-card",e.cardAttrs,!1),e.$listeners),[a("div",{staticClass:"card-header"},[a("div",[a("b-card-title",[e._v(e._s(e.$attrs.title))]),e.$attrs["sub-title"]?a("b-card-sub-title",[e._v(" "+e._s(e.$attrs["sub-title"])+" ")]):e._e()],1),a("i",{staticClass:"code-toggler feather icon-code cursor-pointer",attrs:{"aria-expanded":e.code_visible?"false":"true","aria-controls":e.parentID},on:{click:function(t){e.code_visible=!e.code_visible}}})]),void 0!==e.$attrs["no-body"]?[e._t("default"),a("b-collapse",{staticClass:"card-code",attrs:{id:e.parentID,visible:e.code_visible},model:{value:e.code_visible,callback:function(t){e.code_visible=t},expression:"code_visible"}},[a("b-card-body",[a("prism",{attrs:{language:e.codeLanguage}},[e._t("code")],2)],1)],1)]:a("b-card-body",[e._t("default"),a("b-collapse",{staticClass:"card-code",attrs:{id:e.parentID,visible:e.code_visible},model:{value:e.code_visible,callback:function(t){e.code_visible=t},expression:"code_visible"}},[a("div",{staticClass:"p-1"}),a("prism",{attrs:{language:e.codeLanguage}},[e._t("code")],2)],1)],2)],2)},r=[],i=a("205f"),s=a("4968"),l=a("ba06"),o=a("6197"),c=a("5843"),u=(a("c197"),a("84bf"),a("8d51")),d=a.n(u),g={components:{BCard:i["a"],BCardTitle:s["a"],BCardSubTitle:l["a"],BCardBody:o["a"],BCollapse:c["a"],Prism:d.a},inheritAttrs:!1,props:{codeLanguage:{default:"markup",type:String}},data:function(){return{parentID:"",code_visible:!1}},computed:{cardAttrs:function(){var e=JSON.parse(JSON.stringify(this.$attrs));return delete e.title,delete e["sub-title"],e}},created:function(){this.parentID=String(Math.floor(10*Math.random())+1)}},p=g,b=(a("8d54"),a("2877")),f=Object(b["a"])(p,n,r,!1,null,"aa799a9e",null),h=f.exports;t["a"]=h},3010:function(e,t,a){"use strict";a.d(t,"a",(function(){return c}));var n=a("2b0e"),r=a("b42e"),i=a("c637"),s=a("a723"),l=a("cf75"),o=Object(l["d"])({ariaLive:Object(l["c"])(s["u"]),forceShow:Object(l["c"])(s["g"],!1),id:Object(l["c"])(s["u"]),role:Object(l["c"])(s["u"]),state:Object(l["c"])(s["g"],null),tag:Object(l["c"])(s["u"],"div"),tooltip:Object(l["c"])(s["g"],!1)},i["J"]),c=n["default"].extend({name:i["J"],functional:!0,props:o,render:function(e,t){var a=t.props,n=t.data,i=t.children,s=a.tooltip,l=a.ariaLive,o=!0===a.forceShow||!1===a.state;return e(a.tag,Object(r["a"])(n,{class:{"d-block":o,"invalid-feedback":!s,"invalid-tooltip":s},attrs:{id:a.id||null,role:a.role||null,"aria-live":l||null,"aria-atomic":l?"true":null}}),i)}})},5395:function(e,t,a){},8226:function(e,t,a){"use strict";a.d(t,"a",(function(){return M}));var n=a("c637"),r=a("e863"),i=a("a723"),s=a("992e"),l=a("9b76"),o=a("2326"),c=a("228e"),u=a("fa73"),d=function(e){return"\\"+e},g=function(e){e=Object(u["g"])(e);var t=e.length,a=e.charCodeAt(0);return e.split("").reduce((function(n,r,i){var s=e.charCodeAt(i);return 0===s?n+"�":127===s||s>=1&&s<=31||0===i&&s>=48&&s<=57||1===i&&s>=48&&s<=57&&45===a?n+d("".concat(s.toString(16)," ")):0===i&&45===s&&1===t?n+d(r):s>=128||45===s||95===s||s>=48&&s<=57||s>=65&&s<=90||s>=97&&s<=122?n+r:n+d(r)}),"")},p=a("906c"),b=a("6c06"),f=a("7b1e"),h=a("3a58"),m=a("d82f"),v=a("cf75"),y=a("d520"),O=a("90ef"),j=a("8c18"),k=a("b28b"),F=a("2b0e"),w=a("b42e"),x=Object(v["d"])({tag:Object(v["c"])(i["u"],"div")},n["M"]),A=F["default"].extend({name:n["M"],functional:!0,props:x,render:function(e,t){var a=t.props,n=t.data,r=t.children;return e(a.tag,Object(w["a"])(n,{staticClass:"form-row"}),r)}});function _(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var C=Object(v["d"])({id:Object(v["c"])(i["u"]),inline:Object(v["c"])(i["g"],!1),tag:Object(v["c"])(i["u"],"small"),textVariant:Object(v["c"])(i["u"],"muted")},n["O"]),S=F["default"].extend({name:n["O"],functional:!0,props:C,render:function(e,t){var a=t.props,n=t.data,r=t.children;return e(a.tag,Object(w["a"])(n,{class:_({"form-text":!a.inline},"text-".concat(a.textVariant),a.textVariant),attrs:{id:a.id}}),r)}}),$=a("3010"),P=Object(v["d"])({ariaLive:Object(v["c"])(i["u"]),forceShow:Object(v["c"])(i["g"],!1),id:Object(v["c"])(i["u"]),role:Object(v["c"])(i["u"]),state:Object(v["c"])(i["g"],null),tag:Object(v["c"])(i["u"],"div"),tooltip:Object(v["c"])(i["g"],!1)},n["Q"]),D=F["default"].extend({name:n["Q"],functional:!0,props:P,render:function(e,t){var a=t.props,n=t.data,r=t.children,i=a.tooltip,s=a.ariaLive,l=!0===a.forceShow||!0===a.state;return e(a.tag,Object(w["a"])(n,{class:{"d-block":l,"valid-feedback":!i,"valid-tooltip":i},attrs:{id:a.id||null,role:a.role||null,"aria-live":s||null,"aria-atomic":s?"true":null}}),r)}});function L(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function E(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?L(Object(a),!0).forEach((function(t){B(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):L(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function B(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var N=["input","select","textarea"],z=N.map((function(e){return"".concat(e,":not([disabled])")})).join(),I=[].concat(N,["a","button","label"]),T=function(){return Object(v["d"])(Object(m["m"])(E(E(E(E({},O["b"]),y["b"]),Object(c["b"])().reduce((function(e,t){return e[Object(v["g"])(t,"contentCols")]=Object(v["c"])(i["i"]),e[Object(v["g"])(t,"labelAlign")]=Object(v["c"])(i["u"]),e[Object(v["g"])(t,"labelCols")]=Object(v["c"])(i["i"]),e}),Object(m["c"])(null))),{},{description:Object(v["c"])(i["u"]),disabled:Object(v["c"])(i["g"],!1),feedbackAriaLive:Object(v["c"])(i["u"],"assertive"),invalidFeedback:Object(v["c"])(i["u"]),label:Object(v["c"])(i["u"]),labelClass:Object(v["c"])(i["e"]),labelFor:Object(v["c"])(i["u"]),labelSize:Object(v["c"])(i["u"]),labelSrOnly:Object(v["c"])(i["g"],!1),tooltip:Object(v["c"])(i["g"],!1),validFeedback:Object(v["c"])(i["u"]),validated:Object(v["c"])(i["g"],!1)})),n["H"])},M={name:n["H"],mixins:[O["a"],y["a"],j["a"]],get props(){return delete this.props,this.props=T()},data:function(){return{ariaDescribedby:null}},computed:{contentColProps:function(){return this.getColProps(this.$props,"content")},labelAlignClasses:function(){return this.getAlignClasses(this.$props,"label")},labelColProps:function(){return this.getColProps(this.$props,"label")},isHorizontal:function(){return Object(m["h"])(this.contentColProps).length>0||Object(m["h"])(this.labelColProps).length>0}},watch:{ariaDescribedby:function(e,t){e!==t&&this.updateAriaDescribedby(e,t)}},mounted:function(){var e=this;this.$nextTick((function(){e.updateAriaDescribedby(e.ariaDescribedby)}))},methods:{getAlignClasses:function(e,t){return Object(c["b"])().reduce((function(a,n){var r=e[Object(v["g"])(n,"".concat(t,"Align"))]||null;return r&&a.push(["text",n,r].filter(b["a"]).join("-")),a}),[])},getColProps:function(e,t){return Object(c["b"])().reduce((function(a,n){var r=e[Object(v["g"])(n,"".concat(t,"Cols"))];return r=""===r||(r||!1),Object(f["b"])(r)||"auto"===r||(r=Object(h["b"])(r,0),r=r>0&&r),r&&(a[n||(Object(f["b"])(r)?"col":"cols")]=r),a}),{})},updateAriaDescribedby:function(e,t){var a=this.labelFor;if(r["h"]&&a){var n=Object(p["C"])("#".concat(g(a)),this.$refs.content);if(n){var i="aria-describedby",l=(e||"").split(s["u"]),c=(t||"").split(s["u"]),u=(Object(p["h"])(n,i)||"").split(s["u"]).filter((function(e){return!Object(o["a"])(c,e)})).concat(l).filter((function(e,t,a){return a.indexOf(e)===t})).filter(b["a"]).join(" ").trim();u?Object(p["E"])(n,i,u):Object(p["x"])(n,i)}}},onLegendClick:function(e){if(!this.labelFor){var t=e.target,a=t?t.tagName:"";if(-1===I.indexOf(a)){var n=Object(p["D"])(z,this.$refs.content).filter(p["u"]);1===n.length&&Object(p["d"])(n[0])}}}},render:function(e){var t=this.computedState,a=this.feedbackAriaLive,n=this.isHorizontal,r=this.labelFor,i=this.normalizeSlot,s=this.safeId,o=this.tooltip,c=s(),u=!r,d=e(),g=i(l["x"])||this.label,p=g?s("_BV_label_"):null;if(g||n){var f=this.labelSize,h=this.labelColProps,m=u?"legend":"label";this.labelSrOnly?(g&&(d=e(m,{class:"sr-only",attrs:{id:p,for:r||null}},[g])),d=e(n?k["a"]:"div",{props:n?h:{}},[d])):d=e(n?k["a"]:m,{on:u?{click:this.onLegendClick}:{},props:n?E(E({},h),{},{tag:m}):{},attrs:{id:p,for:r||null,tabindex:u?"-1":null},class:[u?"bv-no-focus-ring":"",n||u?"col-form-label":"",!n&&u?"pt-0":"",n||u?"":"d-block",f?"col-form-label-".concat(f):"",this.labelAlignClasses,this.labelClass]},[g])}var v=e(),y=i(l["w"])||this.invalidFeedback,O=y?s("_BV_feedback_invalid_"):null;y&&(v=e($["a"],{props:{ariaLive:a,id:O,role:a?"alert":null,state:t,tooltip:o},attrs:{tabindex:y?"-1":null}},[y]));var j=e(),F=i(l["eb"])||this.validFeedback,w=F?s("_BV_feedback_valid_"):null;F&&(j=e(D,{props:{ariaLive:a,id:w,role:a?"alert":null,state:t,tooltip:o},attrs:{tabindex:F?"-1":null}},[F]));var x=e(),_=i(l["i"])||this.description,C=_?s("_BV_description_"):null;_&&(x=e(S,{attrs:{id:C,tabindex:"-1"}},[_]));var P=this.ariaDescribedby=[C,!1===t?O:null,!0===t?w:null].filter(b["a"]).join(" ")||null,L=e(n?k["a"]:"div",{props:n?this.contentColProps:{},ref:"content"},[i(l["h"],{ariaDescribedby:P,descriptionId:C,id:c,labelId:p})||e(),v,j,x]);return e(u?"fieldset":n?A:"div",{staticClass:"form-group",class:[{"was-validated":this.validated},this.stateClass],attrs:{id:c,disabled:u?this.disabled:null,role:u?null:"group","aria-invalid":this.computedAriaInvalid,"aria-labelledby":u&&n?p:null}},n&&u?[e(A,[d,L])]:[d,L])}}},"84bf":function(e,t,a){},"8d51":function(e,t,a){"use strict";function n(e){for(var t=arguments,a=1;a<arguments.length;a++)for(var n in arguments[a])e[n]=t[a][n];return e}var r={functional:!0,props:{code:{type:String},inline:{type:Boolean,default:!1},language:{type:String,default:"markup"}},render:function(e,t){var a=t.props.code||(t.children&&t.children.length>0?t.children[0].text:""),r=t.props.inline,i=t.props.language,s=Prism.languages[i],l="language-"+i;return r?e("code",n({},t.data,{class:[t.data.class,l],domProps:n({},t.data.domProps,{innerHTML:Prism.highlight(a,s)})})):e("pre",n({},t.data,{class:[t.data.class,l]}),[e("code",{class:l,domProps:{innerHTML:Prism.highlight(a,s)}})])}};e.exports=r},"8d54":function(e,t,a){"use strict";a("5395")},c197:function(e,t,a){(function(t){var a="undefined"!==typeof window?window:"undefined"!==typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?self:{},n=function(e){var t=/\blang(?:uage)?-([\w-]+)\b/i,a=0,n={manual:e.Prism&&e.Prism.manual,disableWorkerMessageHandler:e.Prism&&e.Prism.disableWorkerMessageHandler,util:{encode:function(e){return e instanceof r?new r(e.type,n.util.encode(e.content),e.alias):Array.isArray(e)?e.map(n.util.encode):e.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/\u00a0/g," ")},type:function(e){return Object.prototype.toString.call(e).slice(8,-1)},objId:function(e){return e["__id"]||Object.defineProperty(e,"__id",{value:++a}),e["__id"]},clone:function e(t,a){var r,i,s=n.util.type(t);switch(a=a||{},s){case"Object":if(i=n.util.objId(t),a[i])return a[i];for(var l in r={},a[i]=r,t)t.hasOwnProperty(l)&&(r[l]=e(t[l],a));return r;case"Array":return i=n.util.objId(t),a[i]?a[i]:(r=[],a[i]=r,t.forEach((function(t,n){r[n]=e(t,a)})),r);default:return t}},getLanguage:function(e){while(e&&!t.test(e.className))e=e.parentElement;return e?(e.className.match(t)||[,"none"])[1].toLowerCase():"none"},currentScript:function(){if("undefined"===typeof document)return null;if("currentScript"in document)return document.currentScript;try{throw new Error}catch(n){var e=(/at [^(\r\n]*\((.*):.+:.+\)$/i.exec(n.stack)||[])[1];if(e){var t=document.getElementsByTagName("script");for(var a in t)if(t[a].src==e)return t[a]}return null}}},languages:{extend:function(e,t){var a=n.util.clone(n.languages[e]);for(var r in t)a[r]=t[r];return a},insertBefore:function(e,t,a,r){r=r||n.languages;var i=r[e],s={};for(var l in i)if(i.hasOwnProperty(l)){if(l==t)for(var o in a)a.hasOwnProperty(o)&&(s[o]=a[o]);a.hasOwnProperty(l)||(s[l]=i[l])}var c=r[e];return r[e]=s,n.languages.DFS(n.languages,(function(t,a){a===c&&t!=e&&(this[t]=s)})),s},DFS:function e(t,a,r,i){i=i||{};var s=n.util.objId;for(var l in t)if(t.hasOwnProperty(l)){a.call(t,l,t[l],r||l);var o=t[l],c=n.util.type(o);"Object"!==c||i[s(o)]?"Array"!==c||i[s(o)]||(i[s(o)]=!0,e(o,a,l,i)):(i[s(o)]=!0,e(o,a,null,i))}}},plugins:{},highlightAll:function(e,t){n.highlightAllUnder(document,e,t)},highlightAllUnder:function(e,t,a){var r={callback:a,container:e,selector:'code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'};n.hooks.run("before-highlightall",r),r.elements=Array.prototype.slice.apply(r.container.querySelectorAll(r.selector)),n.hooks.run("before-all-elements-highlight",r);for(var i,s=0;i=r.elements[s++];)n.highlightElement(i,!0===t,r.callback)},highlightElement:function(a,r,i){var s=n.util.getLanguage(a),l=n.languages[s];a.className=a.className.replace(t,"").replace(/\s+/g," ")+" language-"+s;var o=a.parentNode;o&&"pre"===o.nodeName.toLowerCase()&&(o.className=o.className.replace(t,"").replace(/\s+/g," ")+" language-"+s);var c=a.textContent,u={element:a,language:s,grammar:l,code:c};function d(e){u.highlightedCode=e,n.hooks.run("before-insert",u),u.element.innerHTML=u.highlightedCode,n.hooks.run("after-highlight",u),n.hooks.run("complete",u),i&&i.call(u.element)}if(n.hooks.run("before-sanity-check",u),!u.code)return n.hooks.run("complete",u),void(i&&i.call(u.element));if(n.hooks.run("before-highlight",u),u.grammar)if(r&&e.Worker){var g=new Worker(n.filename);g.onmessage=function(e){d(e.data)},g.postMessage(JSON.stringify({language:u.language,code:u.code,immediateClose:!0}))}else d(n.highlight(u.code,u.grammar,u.language));else d(n.util.encode(u.code))},highlight:function(e,t,a){var i={code:e,grammar:t,language:a};return n.hooks.run("before-tokenize",i),i.tokens=n.tokenize(i.code,i.grammar),n.hooks.run("after-tokenize",i),r.stringify(n.util.encode(i.tokens),i.language)},matchGrammar:function(e,t,a,i,s,l,o){for(var c in a)if(a.hasOwnProperty(c)&&a[c]){var u=a[c];u=Array.isArray(u)?u:[u];for(var d=0;d<u.length;++d){if(o&&o==c+","+d)return;var g=u[d],p=g.inside,b=!!g.lookbehind,f=!!g.greedy,h=0,m=g.alias;if(f&&!g.pattern.global){var v=g.pattern.toString().match(/[imsuy]*$/)[0];g.pattern=RegExp(g.pattern.source,v+"g")}g=g.pattern||g;for(var y=i,O=s;y<t.length;O+=t[y].length,++y){var j=t[y];if(t.length>e.length)return;if(!(j instanceof r)){if(f&&y!=t.length-1){g.lastIndex=O;var k=g.exec(e);if(!k)break;for(var F=k.index+(b&&k[1]?k[1].length:0),w=k.index+k[0].length,x=y,A=O,_=t.length;x<_&&(A<w||!t[x].type&&!t[x-1].greedy);++x)A+=t[x].length,F>=A&&(++y,O=A);if(t[y]instanceof r)continue;C=x-y,j=e.slice(O,A),k.index-=O}else{g.lastIndex=0;k=g.exec(j);var C=1}if(k){b&&(h=k[1]?k[1].length:0);F=k.index+h,k=k[0].slice(h),w=F+k.length;var S=j.slice(0,F),$=j.slice(w),P=[y,C];S&&(++y,O+=S.length,P.push(S));var D=new r(c,p?n.tokenize(k,p):k,m,k,f);if(P.push(D),$&&P.push($),Array.prototype.splice.apply(t,P),1!=C&&n.matchGrammar(e,t,a,y,O,!0,c+","+d),l)break}else if(l)break}}}}},tokenize:function(e,t){var a=[e],r=t.rest;if(r){for(var i in r)t[i]=r[i];delete t.rest}return n.matchGrammar(e,a,t,0,0,!1),a},hooks:{all:{},add:function(e,t){var a=n.hooks.all;a[e]=a[e]||[],a[e].push(t)},run:function(e,t){var a=n.hooks.all[e];if(a&&a.length)for(var r,i=0;r=a[i++];)r(t)}},Token:r};function r(e,t,a,n,r){this.type=e,this.content=t,this.alias=a,this.length=0|(n||"").length,this.greedy=!!r}if(e.Prism=n,r.stringify=function(e,t){if("string"==typeof e)return e;if(Array.isArray(e))return e.map((function(e){return r.stringify(e,t)})).join("");var a={type:e.type,content:r.stringify(e.content,t),tag:"span",classes:["token",e.type],attributes:{},language:t};if(e.alias){var i=Array.isArray(e.alias)?e.alias:[e.alias];Array.prototype.push.apply(a.classes,i)}n.hooks.run("wrap",a);var s=Object.keys(a.attributes).map((function(e){return e+'="'+(a.attributes[e]||"").replace(/"/g,"&quot;")+'"'})).join(" ");return"<"+a.tag+' class="'+a.classes.join(" ")+'"'+(s?" "+s:"")+">"+a.content+"</"+a.tag+">"},!e.document)return e.addEventListener?(n.disableWorkerMessageHandler||e.addEventListener("message",(function(t){var a=JSON.parse(t.data),r=a.language,i=a.code,s=a.immediateClose;e.postMessage(n.highlight(i,n.languages[r],r)),s&&e.close()}),!1),n):n;var i=n.util.currentScript();if(i&&(n.filename=i.src,i.hasAttribute("data-manual")&&(n.manual=!0)),!n.manual){function s(){n.manual||n.highlightAll()}var l=document.readyState;"loading"===l||"interactive"===l&&i&&i.defer?document.addEventListener("DOMContentLoaded",s):window.requestAnimationFrame?window.requestAnimationFrame(s):window.setTimeout(s,16)}return n}(a);e.exports&&(e.exports=n),"undefined"!==typeof t&&(t.Prism=n),n.languages.markup={comment:/<!--[\s\S]*?-->/,prolog:/<\?[\s\S]+?\?>/,doctype:{pattern:/<!DOCTYPE(?:[^>"'[\]]|"[^"]*"|'[^']*')+(?:\[(?:(?!<!--)[^"'\]]|"[^"]*"|'[^']*'|<!--[\s\S]*?-->)*\]\s*)?>/i,greedy:!0},cdata:/<!\[CDATA\[[\s\S]*?]]>/i,tag:{pattern:/<\/?(?!\d)[^\s>\/=$<%]+(?:\s(?:\s*[^\s>\/=]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s'">=]+(?=[\s>]))|(?=[\s/>])))+)?\s*\/?>/i,greedy:!0,inside:{tag:{pattern:/^<\/?[^\s>\/]+/i,inside:{punctuation:/^<\/?/,namespace:/^[^\s>\/:]+:/}},"attr-value":{pattern:/=\s*(?:"[^"]*"|'[^']*'|[^\s'">=]+)/i,inside:{punctuation:[/^=/,{pattern:/^(\s*)["']|["']$/,lookbehind:!0}]}},punctuation:/\/?>/,"attr-name":{pattern:/[^\s>\/]+/,inside:{namespace:/^[^\s>\/:]+:/}}}},entity:/&#?[\da-z]{1,8};/i},n.languages.markup["tag"].inside["attr-value"].inside["entity"]=n.languages.markup["entity"],n.hooks.add("wrap",(function(e){"entity"===e.type&&(e.attributes["title"]=e.content.replace(/&amp;/,"&"))})),Object.defineProperty(n.languages.markup.tag,"addInlined",{value:function(e,t){var a={};a["language-"+t]={pattern:/(^<!\[CDATA\[)[\s\S]+?(?=\]\]>$)/i,lookbehind:!0,inside:n.languages[t]},a["cdata"]=/^<!\[CDATA\[|\]\]>$/i;var r={"included-cdata":{pattern:/<!\[CDATA\[[\s\S]*?\]\]>/i,inside:a}};r["language-"+t]={pattern:/[\s\S]+/,inside:n.languages[t]};var i={};i[e]={pattern:RegExp(/(<__[\s\S]*?>)(?:<!\[CDATA\[[\s\S]*?\]\]>\s*|[\s\S])*?(?=<\/__>)/.source.replace(/__/g,e),"i"),lookbehind:!0,greedy:!0,inside:r},n.languages.insertBefore("markup","cdata",i)}}),n.languages.xml=n.languages.extend("markup",{}),n.languages.html=n.languages.markup,n.languages.mathml=n.languages.markup,n.languages.svg=n.languages.markup,function(e){var t=/("|')(?:\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/;e.languages.css={comment:/\/\*[\s\S]*?\*\//,atrule:{pattern:/@[\w-]+[\s\S]*?(?:;|(?=\s*\{))/,inside:{rule:/@[\w-]+/}},url:{pattern:RegExp("url\\((?:"+t.source+"|[^\n\r()]*)\\)","i"),inside:{function:/^url/i,punctuation:/^\(|\)$/}},selector:RegExp("[^{}\\s](?:[^{};\"']|"+t.source+")*?(?=\\s*\\{)"),string:{pattern:t,greedy:!0},property:/[-_a-z\xA0-\uFFFF][-\w\xA0-\uFFFF]*(?=\s*:)/i,important:/!important\b/i,function:/[-a-z0-9]+(?=\()/i,punctuation:/[(){};:,]/},e.languages.css["atrule"].inside.rest=e.languages.css;var a=e.languages.markup;a&&(a.tag.addInlined("style","css"),e.languages.insertBefore("inside","attr-value",{"style-attr":{pattern:/\s*style=("|')(?:\\[\s\S]|(?!\1)[^\\])*\1/i,inside:{"attr-name":{pattern:/^\s*style/i,inside:a.tag.inside},punctuation:/^\s*=\s*['"]|['"]\s*$/,"attr-value":{pattern:/.+/i,inside:e.languages.css}},alias:"language-css"}},a.tag))}(n),n.languages.clike={comment:[{pattern:/(^|[^\\])\/\*[\s\S]*?(?:\*\/|$)/,lookbehind:!0},{pattern:/(^|[^\\:])\/\/.*/,lookbehind:!0,greedy:!0}],string:{pattern:/(["'])(?:\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/,greedy:!0},"class-name":{pattern:/(\b(?:class|interface|extends|implements|trait|instanceof|new)\s+|\bcatch\s+\()[\w.\\]+/i,lookbehind:!0,inside:{punctuation:/[.\\]/}},keyword:/\b(?:if|else|while|do|for|return|in|instanceof|function|new|try|throw|catch|finally|null|break|continue)\b/,boolean:/\b(?:true|false)\b/,function:/\w+(?=\()/,number:/\b0x[\da-f]+\b|(?:\b\d+\.?\d*|\B\.\d+)(?:e[+-]?\d+)?/i,operator:/[<>]=?|[!=]=?=?|--?|\+\+?|&&?|\|\|?|[?*/~^%]/,punctuation:/[{}[\];(),.:]/},n.languages.javascript=n.languages.extend("clike",{"class-name":[n.languages.clike["class-name"],{pattern:/(^|[^$\w\xA0-\uFFFF])[_$A-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\.(?:prototype|constructor))/,lookbehind:!0}],keyword:[{pattern:/((?:^|})\s*)(?:catch|finally)\b/,lookbehind:!0},{pattern:/(^|[^.]|\.\.\.\s*)\b(?:as|async(?=\s*(?:function\b|\(|[$\w\xA0-\uFFFF]|$))|await|break|case|class|const|continue|debugger|default|delete|do|else|enum|export|extends|for|from|function|get|if|implements|import|in|instanceof|interface|let|new|null|of|package|private|protected|public|return|set|static|super|switch|this|throw|try|typeof|undefined|var|void|while|with|yield)\b/,lookbehind:!0}],number:/\b(?:(?:0[xX](?:[\dA-Fa-f](?:_[\dA-Fa-f])?)+|0[bB](?:[01](?:_[01])?)+|0[oO](?:[0-7](?:_[0-7])?)+)n?|(?:\d(?:_\d)?)+n|NaN|Infinity)\b|(?:\b(?:\d(?:_\d)?)+\.?(?:\d(?:_\d)?)*|\B\.(?:\d(?:_\d)?)+)(?:[Ee][+-]?(?:\d(?:_\d)?)+)?/,function:/#?[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*(?:\.\s*(?:apply|bind|call)\s*)?\()/,operator:/--|\+\+|\*\*=?|=>|&&|\|\||[!=]==|<<=?|>>>?=?|[-+*/%&|^!=<>]=?|\.{3}|\?[.?]?|[~:]/}),n.languages.javascript["class-name"][0].pattern=/(\b(?:class|interface|extends|implements|instanceof|new)\s+)[\w.\\]+/,n.languages.insertBefore("javascript","keyword",{regex:{pattern:/((?:^|[^$\w\xA0-\uFFFF."'\])\s])\s*)\/(?:\[(?:[^\]\\\r\n]|\\.)*]|\\.|[^/\\\[\r\n])+\/[gimyus]{0,6}(?=(?:\s|\/\*[\s\S]*?\*\/)*(?:$|[\r\n,.;:})\]]|\/\/))/,lookbehind:!0,greedy:!0},"function-variable":{pattern:/#?[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*[=:]\s*(?:async\s*)?(?:\bfunction\b|(?:\((?:[^()]|\([^()]*\))*\)|[_$a-zA-Z\xA0-\uFFFF][$\w\xA0-\uFFFF]*)\s*=>))/,alias:"function"},parameter:[{pattern:/(function(?:\s+[_$A-Za-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*)?\s*\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\))/,lookbehind:!0,inside:n.languages.javascript},{pattern:/[_$a-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*(?=\s*=>)/i,inside:n.languages.javascript},{pattern:/(\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\)\s*=>)/,lookbehind:!0,inside:n.languages.javascript},{pattern:/((?:\b|\s|^)(?!(?:as|async|await|break|case|catch|class|const|continue|debugger|default|delete|do|else|enum|export|extends|finally|for|from|function|get|if|implements|import|in|instanceof|interface|let|new|null|of|package|private|protected|public|return|set|static|super|switch|this|throw|try|typeof|undefined|var|void|while|with|yield)(?![$\w\xA0-\uFFFF]))(?:[_$A-Za-z\xA0-\uFFFF][$\w\xA0-\uFFFF]*\s*)\(\s*)(?!\s)(?:[^()]|\([^()]*\))+?(?=\s*\)\s*\{)/,lookbehind:!0,inside:n.languages.javascript}],constant:/\b[A-Z](?:[A-Z_]|\dx?)*\b/}),n.languages.insertBefore("javascript","string",{"template-string":{pattern:/`(?:\\[\s\S]|\${(?:[^{}]|{(?:[^{}]|{[^}]*})*})+}|(?!\${)[^\\`])*`/,greedy:!0,inside:{"template-punctuation":{pattern:/^`|`$/,alias:"string"},interpolation:{pattern:/((?:^|[^\\])(?:\\{2})*)\${(?:[^{}]|{(?:[^{}]|{[^}]*})*})+}/,lookbehind:!0,inside:{"interpolation-punctuation":{pattern:/^\${|}$/,alias:"punctuation"},rest:n.languages.javascript}},string:/[\s\S]+/}}}),n.languages.markup&&n.languages.markup.tag.addInlined("script","javascript"),n.languages.js=n.languages.javascript,function(){"undefined"!==typeof self&&self.Prism&&self.document&&document.querySelector&&(self.Prism.fileHighlight=function(e){e=e||document;var t={js:"javascript",py:"python",rb:"ruby",ps1:"powershell",psm1:"powershell",sh:"bash",bat:"batch",h:"c",tex:"latex"};Array.prototype.slice.call(e.querySelectorAll("pre[data-src]")).forEach((function(e){if(!e.hasAttribute("data-src-loaded")){var a,r=e.getAttribute("data-src"),i=e,s=/\blang(?:uage)?-([\w-]+)\b/i;while(i&&!s.test(i.className))i=i.parentNode;if(i&&(a=(e.className.match(s)||[,""])[1]),!a){var l=(r.match(/\.(\w+)$/)||[,""])[1];a=t[l]||l}var o=document.createElement("code");o.className="language-"+a,e.textContent="",o.textContent="Loading…",e.appendChild(o);var c=new XMLHttpRequest;c.open("GET",r,!0),c.onreadystatechange=function(){4==c.readyState&&(c.status<400&&c.responseText?(o.textContent=c.responseText,n.highlightElement(o),e.setAttribute("data-src-loaded","")):c.status>=400?o.textContent="✖ Error "+c.status+" while fetching file: "+c.statusText:o.textContent="✖ Error: File does not exist or is empty")},c.send(null)}}))},document.addEventListener("DOMContentLoaded",(function(){self.Prism.fileHighlight()})))}()}).call(this,a("c8ba"))}}]);
//# sourceMappingURL=chunk-60c102ae.92b40fed.js.map