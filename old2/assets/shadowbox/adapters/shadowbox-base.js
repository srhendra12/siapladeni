if(typeof Shadowbox=="undefined"){throw"Unable to load Shadowbox adapter, Shadowbox not found"}(function(d){var a=document.defaultView,c;if(d.client.isIE6){c=[];function b(){var h;for(var g=0,f=c.length;g<f;++g){h=c[g];h[0].detachEvent("on"+h[1],h[2])}window.detachEvent("onunload",b)}window.attachEvent("onunload",b)}d.lib={getStyle:a&&a.getComputedStyle?function(h,g){var e,f;if(g=="float"){g="cssFloat"}if(e=h.style[g]){return e}if(f=a.getComputedStyle(h,"")){return f[g]}return null}:function(i,h){var f,g;if(h=="opacity"){if(typeof i.style.filter=="string"){var e=i.style.filter.match(/alpha\(opacity=(.+)\)/i);if(e){var j=parseFloat(e[1]);if(!isNaN(j)){return(j?j/100:0)}}}return 1}if(h=="float"){h="styleFloat"}if(f=i.style[h]){return f}if(g=i.currentStyle){return g[h]}return null},remove:function(e){e.parentNode.removeChild(e)},getTarget:function(g){var f=g.target?g.target:g.srcElement;return f.nodeType==3?f.parentNode:f},getPageXY:function(g){var f=g.pageX||(g.clientX+(document.documentElement.scrollLeft||document.body.scrollLeft));var h=g.pageY||(g.clientY+(document.documentElement.scrollTop||document.body.scrollTop));return[f,h]},preventDefault:function(f){if(f.preventDefault){f.preventDefault()}else{f.returnValue=false}},keyCode:function(f){return f.which?f.which:f.keyCode},addEvent:function(g,e,f){if(c){c[c.length]=arguments}if(g.addEventListener){g.addEventListener(e,f,false)}else{if(g.attachEvent){g.attachEvent("on"+e,f)}}},removeEvent:function(g,e,f){if(g.removeEventListener){g.removeEventListener(e,f,false)}else{if(g.detachEvent){g.detachEvent("on"+e,f)}}},append:function(g,f){if(g.insertAdjacentHTML){g.insertAdjacentHTML("BeforeEnd",f)}else{if(g.lastChild){var e=g.ownerDocument.createRange();e.setStartAfter(g.lastChild);var h=e.createContextualFragment(f);g.appendChild(h)}else{g.innerHTML=f}}}}})(Shadowbox);