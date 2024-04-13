(()=>{"use strict";var e={857:e=>{var t=function(e){var t;return!!e&&"object"==typeof e&&"[object RegExp]"!==(t=Object.prototype.toString.call(e))&&"[object Date]"!==t&&e.$$typeof!==r},r="function"==typeof Symbol&&Symbol.for?Symbol.for("react.element"):60103;function i(e,t){return!1!==t.clone&&t.isMergeableObject(e)?a(Array.isArray(e)?[]:{},e,t):e}function n(e,t,r){return e.concat(t).map(function(e){return i(e,r)})}function s(e){return Object.keys(e).concat(Object.getOwnPropertySymbols?Object.getOwnPropertySymbols(e).filter(function(t){return Object.propertyIsEnumerable.call(e,t)}):[])}function o(e,t){try{return t in e}catch(e){return!1}}function a(e,r,l){(l=l||{}).arrayMerge=l.arrayMerge||n,l.isMergeableObject=l.isMergeableObject||t,l.cloneUnlessOtherwiseSpecified=i;var u,c,d=Array.isArray(r);return d!==Array.isArray(e)?i(r,l):d?l.arrayMerge(e,r,l):(c={},(u=l).isMergeableObject(e)&&s(e).forEach(function(t){c[t]=i(e[t],u)}),s(r).forEach(function(t){(!o(e,t)||Object.hasOwnProperty.call(e,t)&&Object.propertyIsEnumerable.call(e,t))&&(o(e,t)&&u.isMergeableObject(r[t])?c[t]=(function(e,t){if(!t.customMerge)return a;var r=t.customMerge(e);return"function"==typeof r?r:a})(t,u)(e[t],r[t],u):c[t]=i(r[t],u))}),c)}a.all=function(e,t){if(!Array.isArray(e))throw Error("first argument should be an array");return e.reduce(function(e,r){return a(e,r,t)},{})},e.exports=a}},t={};function r(i){var n=t[i];if(void 0!==n)return n.exports;var s=t[i]={exports:{}};return e[i](s,s.exports,r),s.exports}(()=>{r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t}})(),(()=>{r.d=(e,t)=>{for(var i in t)r.o(t,i)&&!r.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})}})(),(()=>{r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t)})(),(()=>{var e=r(857),t=r.n(e);class i{static ucFirst(e){return e.charAt(0).toUpperCase()+e.slice(1)}static lcFirst(e){return e.charAt(0).toLowerCase()+e.slice(1)}static toDashCase(e){return e.replace(/([A-Z])/g,"-$1").replace(/^-/,"").toLowerCase()}static toLowerCamelCase(e,t){let r=i.toUpperCamelCase(e,t);return i.lcFirst(r)}static toUpperCamelCase(e,t){return t?e.split(t).map(e=>i.ucFirst(e.toLowerCase())).join(""):i.ucFirst(e.toLowerCase())}static parsePrimitive(e){try{return/^\d+(.|,)\d+$/.test(e)&&(e=e.replace(",",".")),JSON.parse(e)}catch(t){return e.toString()}}}class n{static isNode(e){return"object"==typeof e&&null!==e&&(e===document||e===window||e instanceof Node)}static hasAttribute(e,t){if(!n.isNode(e))throw Error("The element must be a valid HTML Node!");return"function"==typeof e.hasAttribute&&e.hasAttribute(t)}static getAttribute(e,t){let r=!(arguments.length>2)||void 0===arguments[2]||arguments[2];if(r&&!1===n.hasAttribute(e,t))throw Error('The required property "'.concat(t,'" does not exist!'));if("function"!=typeof e.getAttribute){if(r)throw Error("This node doesn't support the getAttribute function!");return}return e.getAttribute(t)}static getDataAttribute(e,t){let r=!(arguments.length>2)||void 0===arguments[2]||arguments[2],s=t.replace(/^data(|-)/,""),o=i.toLowerCamelCase(s,"-");if(!n.isNode(e)){if(r)throw Error("The passed node is not a valid HTML Node!");return}if(void 0===e.dataset){if(r)throw Error("This node doesn't support the dataset attribute!");return}let a=e.dataset[o];if(void 0===a){if(r)throw Error('The required data attribute "'.concat(t,'" does not exist on ').concat(e,"!"));return a}return i.parsePrimitive(a)}static querySelector(e,t){let r=!(arguments.length>2)||void 0===arguments[2]||arguments[2];if(r&&!n.isNode(e))throw Error("The parent node is not a valid HTML Node!");let i=e.querySelector(t)||!1;if(r&&!1===i)throw Error('The required element "'.concat(t,'" does not exist in parent node!'));return i}static querySelectorAll(e,t){let r=!(arguments.length>2)||void 0===arguments[2]||arguments[2];if(r&&!n.isNode(e))throw Error("The parent node is not a valid HTML Node!");let i=e.querySelectorAll(t);if(0===i.length&&(i=!1),r&&!1===i)throw Error('At least one item of "'.concat(t,'" must exist in parent node!'));return i}}class s{publish(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=arguments.length>2&&void 0!==arguments[2]&&arguments[2],i=new CustomEvent(e,{detail:t,cancelable:r});return this.el.dispatchEvent(i),i}subscribe(e,t){let r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},i=this,n=e.split("."),s=r.scope?t.bind(r.scope):t;if(r.once&&!0===r.once){let t=s;s=function(r){i.unsubscribe(e),t(r)}}return this.el.addEventListener(n[0],s),this.listeners.push({splitEventName:n,opts:r,cb:s}),!0}unsubscribe(e){let t=e.split(".");return this.listeners=this.listeners.reduce((e,r)=>([...r.splitEventName].sort().toString()===t.sort().toString()?this.el.removeEventListener(r.splitEventName[0],r.cb):e.push(r),e),[]),!0}reset(){return this.listeners.forEach(e=>{this.el.removeEventListener(e.splitEventName[0],e.cb)}),this.listeners=[],!0}get el(){return this._el}set el(e){this._el=e}get listeners(){return this._listeners}set listeners(e){this._listeners=e}constructor(e=document){this._el=e,e.$emitter=this,this._listeners=[]}}class o{init(){throw Error('The "init" method for the plugin "'.concat(this._pluginName,'" is not defined.'))}update(){}_init(){this._initialized||(this.init(),this._initialized=!0)}_update(){this._initialized&&this.update()}_mergeOptions(e){let r=i.toDashCase(this._pluginName),s=n.getDataAttribute(this.el,"data-".concat(r,"-config"),!1),o=n.getAttribute(this.el,"data-".concat(r,"-options"),!1),a=[this.constructor.options,this.options,e];s&&a.push(window.PluginConfigManager.get(this._pluginName,s));try{o&&a.push(JSON.parse(o))}catch(e){throw console.error(this.el),Error('The data attribute "data-'.concat(r,'-options" could not be parsed to json: ').concat(e.message))}return t().all(a.filter(e=>e instanceof Object&&!(e instanceof Array)).map(e=>e||{}))}_registerInstance(){window.PluginManager.getPluginInstancesFromElement(this.el).set(this._pluginName,this),window.PluginManager.getPlugin(this._pluginName,!1).get("instances").push(this)}_getPluginName(e){return e||(e=this.constructor.name),e}constructor(e,t={},r=!1){if(!n.isNode(e))throw Error("There is no valid element given.");this.el=e,this.$emitter=new s(this.el),this._pluginName=this._getPluginName(r),this.options=this._mergeOptions(t),this._initialized=!1,this._registerInstance(),this._init()}}class a{get(e,t){let r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"application/json",i=this._createPreparedRequest("GET",e,r);return this._sendRequest(i,null,t)}post(e,t,r){let i=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"application/json";i=this._getContentType(t,i);let n=this._createPreparedRequest("POST",e,i);return this._sendRequest(n,t,r)}delete(e,t,r){let i=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"application/json";i=this._getContentType(t,i);let n=this._createPreparedRequest("DELETE",e,i);return this._sendRequest(n,t,r)}patch(e,t,r){let i=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"application/json";i=this._getContentType(t,i);let n=this._createPreparedRequest("PATCH",e,i);return this._sendRequest(n,t,r)}abort(){if(this._request)return this._request.abort()}_registerOnLoaded(e,t){t&&e.addEventListener("loadend",()=>{t(e.responseText,e)})}_sendRequest(e,t,r){return this._registerOnLoaded(e,r),e.send(t),e}_getContentType(e,t){return e instanceof FormData&&(t=!1),t}_createPreparedRequest(e,t,r){return this._request=new XMLHttpRequest,this._request.open(e,t),this._request.setRequestHeader("X-Requested-With","XMLHttpRequest"),r&&this._request.setRequestHeader("Content-type",r),this._request}constructor(){this._request=null}}window.PluginManager.register("FastOrderPlugin",class extends o{init(){this._client=new a;let e=document.getElementById("fast-order-form");null!=e&&e.addEventListener("keydown",function(e){"Enter"===e.key&&e.preventDefault()}),this.completionEvent(),this.articleEvents(),this.quantityEvents()}hideInactive(e,t,r){for(let i=0;i<t.length;i++)i==e?(t[i].classList.add("active"),r[i].style.display="inline"):(t[i].classList.remove("active"),r[i].style.display="none")}completionEvent(){let e=document.querySelectorAll(".fast-order-completion");document.body.addEventListener("click",t=>{for(let t=0;t<e.length;t++)e[t].innerHTML="",e[t].style.display="none"})}articleEvents(){let e=["input","focusin"],t=document.querySelectorAll(".fast-order-input-article");for(let r=0;r<t.length;r++)for(let i=0;i<e.length;i++)t[r].addEventListener(e[i],e=>{t[r].setAttribute("data-price",""),this.calculateTotal();let i=e.target.value.trim();""==i?(t[r].nextElementSibling.innerHTML="",t[r].nextElementSibling.style.display="none"):this._client.get("/fast-order/articles/"+encodeURIComponent(i),this.handleData.bind(this))})}quantityEvents(){let e=document.querySelectorAll(".fast-order-input-quantity");for(let t=0;t<e.length;t++)e[t].addEventListener("input",e=>{this.calculateTotal()})}handleData(e){let t=document.activeElement;t.nextElementSibling.innerHTML=e,t.nextElementSibling.style.display="block";let r=document.querySelectorAll(".fast-order-link");for(let e=0;e<r.length;e++)r[e].addEventListener("click",i=>{let n=r[e].getAttribute("data-id"),s=r[e].getAttribute("data-price");t.value=n,t.setAttribute("data-price",s),this.calculateTotal()})}calculateTotal(){let e=0,t=document.querySelectorAll(".fast-order-input-article"),r=document.querySelectorAll(".fast-order-input-quantity");for(let i=0;i<t.length;i++)if(t[i].getAttribute("data-price")&&r[i].value.trim()){let n=parseInt(r[i].value);e+=parseFloat(t[i].getAttribute("data-price"))*n}document.getElementById("totalPrice").innerHTML=e.toFixed(2)}},"[data-fast-order-plugin]")})()})();