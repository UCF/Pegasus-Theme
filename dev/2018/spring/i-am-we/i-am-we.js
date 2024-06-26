/*----------------------------------------
 * objectFitPolyfill 2.0.3
 *
 * Made by Constance Chen
 * Released under the MIT license
 *
 * https://github.com/constancecchen/object-fit-polyfill
 *--------------------------------------*/

!function(){"use strict";if("undefined"!=typeof window){var t=-1!==window.navigator.userAgent.indexOf("Edge/16.");if("objectFit"in document.documentElement.style!=!1&&!t)return void(window.objectFitPolyfill=function(){return!1});var e=function(t){var e=window.getComputedStyle(t,null),i=e.getPropertyValue("position"),n=e.getPropertyValue("overflow"),o=e.getPropertyValue("display");i&&"static"!==i||(t.style.position="relative"),"hidden"!==n&&(t.style.overflow="hidden"),o&&"inline"!==o||(t.style.display="block"),0===t.clientHeight&&(t.style.height="100%"),-1===t.className.indexOf("object-fit-polyfill")&&(t.className=t.className+" object-fit-polyfill")},i=function(t){var e=window.getComputedStyle(t,null),i={"max-width":"none","max-height":"none","min-width":"0px","min-height":"0px",top:"auto",right:"auto",bottom:"auto",left:"auto","margin-top":"0px","margin-right":"0px","margin-bottom":"0px","margin-left":"0px"};for(var n in i){e.getPropertyValue(n)!==i[n]&&(t.style[n]=i[n])}},n=function(t,e,i){var n,o,l,a,d;if(i=i.split(" "),i.length<2&&(i[1]=i[0]),"x"===t)n=i[0],o=i[1],l="left",a="right",d=e.clientWidth;else{if("y"!==t)return;n=i[1],o=i[0],l="top",a="bottom",d=e.clientHeight}return n===l||o===l?void(e.style[l]="0"):n===a||o===a?void(e.style[a]="0"):"center"===n||"50%"===n?(e.style[l]="50%",void(e.style["margin-"+l]=d/-2+"px")):n.indexOf("%")>=0?(n=parseInt(n),void(n<50?(e.style[l]=n+"%",e.style["margin-"+l]=d*(n/-100)+"px"):(n=100-n,e.style[a]=n+"%",e.style["margin-"+a]=d*(n/-100)+"px"))):void(e.style[l]=n)},o=function(t){var o=t.dataset?t.dataset.objectFit:t.getAttribute("data-object-fit"),l=t.dataset?t.dataset.objectPosition:t.getAttribute("data-object-position");o=o||"cover",l=l||"50% 50%";var a=t.parentNode;e(a),i(t),t.style.position="absolute",t.style.height="100%",t.style.width="auto","scale-down"===o&&(t.style.height="auto",t.clientWidth<a.clientWidth&&t.clientHeight<a.clientHeight?(n("x",t,l),n("y",t,l)):(o="contain",t.style.height="100%")),"none"===o?(t.style.width="auto",t.style.height="auto",n("x",t,l),n("y",t,l)):"cover"===o&&t.clientWidth>a.clientWidth||"contain"===o&&t.clientWidth<a.clientWidth?(t.style.top="0",t.style.marginTop="0",n("x",t,l)):"scale-down"!==o&&(t.style.width="100%",t.style.height="auto",t.style.left="0",t.style.marginLeft="0",n("y",t,l))},l=function(e){if(void 0===e)e=document.querySelectorAll("[data-object-fit]");else if(e&&e.nodeName)e=[e];else{if("object"!=typeof e||!e.length||!e[0].nodeName)return!1;e=e}for(var i=0;i<e.length;i++)if(e[i].nodeName){var n=e[i].nodeName.toLowerCase();"img"!==n||t?"video"===n&&(e[i].readyState>0?o(e[i]):e[i].addEventListener("loadedmetadata",function(){o(this)})):e[i].complete?o(e[i]):e[i].addEventListener("load",function(){o(this)})}return!0};document.addEventListener("DOMContentLoaded",function(){l()}),window.addEventListener("resize",function(){l()}),window.objectFitPolyfill=l}}();


/**
 * Story js
 */
function init() {
  objectFitPolyfill($('#animation-desktop'));
}

$(init);
