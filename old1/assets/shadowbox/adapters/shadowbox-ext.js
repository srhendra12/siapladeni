if(typeof Ext=="undefined"){throw"Unable to load Shadowbox adapter, Ext not found"}if(typeof Shadowbox=="undefined"){throw"Unable to load Shadowbox adapter, Shadowbox not found"}(function(a){var b=Ext.lib.Event;a.lib={getStyle:function(d,c){return Ext.get(d).getStyle(c)},remove:function(c){Ext.get(c).remove()},getTarget:function(c){return b.getTarget(c)},getPageXY:function(c){return[b.getPageX(c),b.getPageY(c)]},preventDefault:function(c){b.preventDefault(c)},keyCode:function(c){return b.getCharCode(c)},addEvent:function(e,c,d){b.addListener(e,c,d)},removeEvent:function(e,c,d){b.removeListener(e,c,d)},append:function(d,c){Ext.DomHelper.append(d,c)}}})(Shadowbox);