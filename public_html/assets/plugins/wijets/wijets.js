/*! 
 * wijets v0.1.2
 * http://ndevrstudios.com
 * 
 * by Shifat Adnan
 * adnan.pri@gmail.com
 *
 * Copyright (c) 2014, Ndevr Studios Ltd.
 * All rights reserved 
 * 
 */ 

$.wijets=function(){var e=this;this.actionDefinitions={};this.registerAction=function(t){e.actionDefinitions[t.handle]=t};$.fn.pushWidgetControls=function(){var e=$(this).closest("[data-widget]").find("[data-widget-controls]");console.log(e.data("currentControls"),$(this).data("actionDefinition").handle);if(e.data("currentControls")==$(this).data("actionDefinition").handle)return null;var t=$($(this).data("actionDefinition").controls);e.data("currentControls",$(this).data("actionDefinition").handle);if(e.children().length){var t=$($(this).data("actionDefinition").controls);t.hide();e.append(t);t.show();e.parent().toggleClass("editbox-open");t.siblings().hide()}else{t.show();e.append(t);e.slideDown(100,"linear",function(){e.parent().toggleClass("editbox-open")})}return t};$.fn.hideWidgetControls=function(e){var t=$(this).closest("[data-widget]").find("[data-widget-controls]");if(e){t.slideUp(100,"linear",function(){t.empty();t.data("currentControls","");t.parent().toggleClass("editbox-open")})}else{t.empty();t.hide();t.data("currentControls","");t.parent().toggleClass("editbox-open")}};$.fn.getWidgetState=function(e){var t=undefined;var n=$($(this).data("parentWidget")).data("widgetParameters");if(n){if(n.id){t=localStorage.getItem($($(this).data("parentWidget")).data("widgetId")+"."+e)}else{t=n[e]}}return t};$.fn.setWidgetState=function(e,t){var n=$($(this).data("parentWidget")).data("widgetParameters");if(n){if(n.id){localStorage.setItem($($(this).data("parentWidget")).data("widgetId")+"."+e,t);return true}else{return false}}return false};this.registerAction({handle:"collapse",html:'<span class="button-icon has-bg"><i class="fa fa-minus"></i></span>',onClick:function(){$(this).find("i").toggleClass("fa-minus").toggleClass("fa-plus");var e=$(this).data("actionParameters");if(!e.target)e.target=".panel-body";var t=$(this).closest("[data-widget]").find(e.target).is(":visible");$(this).closest("[data-widget]").find(e.target).slideToggle(100,"linear",function(){$(this).closest("[data-widget]").toggleClass("panel-collapsed")});$(this).setWidgetState("collapsed",t)},onInit:function(){var e=$(this).data("actionParameters");if(e.containerClass){$(this).addClass(e.containerClass)}if($(this).getWidgetState("collapsed")=="true"){if(!e.target)e.target=".panel-body";$(this).find("i").toggleClass("fa-minus").toggleClass("fa-plus");$(this).closest("[data-widget]").find(e.target).hide();$(this).closest("[data-widget]").addClass("panel-collapsed")}}});this.registerAction({handle:"edit",html:'<span class="button-icon"><i class="fa fa-pencil"></i></span>',controls:'<input type="text" class="form-control">',onClick:function(){var e=$(this);var t=e.pushWidgetControls();if(t){$(t).val(e.closest("[data-widget]").find("h2").html());t.bind("keyup",function(t){e.closest("[data-widget]").find("h2").html($(this).val());e.setWidgetState("headerTitle",$(this).val());if(t.keyCode==13){e.hideWidgetControls()}})}else{e.hideWidgetControls(true)}},onInit:function(){var e=$(this).getWidgetState("headerTitle");if(e){$(this).closest("[data-widget]").find("h2").html(e)}}});this.registerAction({handle:"colorpicker",html:'<span class="button-icon"><i class="fa fa-tint"></i></span>',controls:'<ul class="panel-color-list">'+'<li><span data-style="panel-default"></span></li>'+'<li><span data-style="panel-inverse"></span></li>'+'<li><span data-style="panel-success"></span></li>'+'<li><span data-style="panel-green"></span></li>'+'<li><span data-style="panel-info"></span></li>'+'<li><span data-style="panel-sky"></span></li>'+'<li><span data-style="panel-primary"></span></li>'+'<li><span data-style="panel-midnightblue"></span></li>'+'<li><span data-style="panel-warning"></span></li>'+'<li><span data-style="panel-orange"></span></li>'+'<li><span data-style="panel-danger"></span></li>'+'<li><span data-style="panel-brown"></span></li>'+'<li><span data-style="panel-magenta"></span></li>'+'<li><span data-style="panel-purple"></span></li>'+'<li><span data-style="panel-indigo"></span></li>'+'<li><span data-style="panel-grape"></span></li>'+"</ul>",onClick:function(){var e=$(this);var t=e.pushWidgetControls();if(t){t.find("li span").bind("click",function(t){var n=e.closest("[data-widget]");n.removeClass("panel-default panel-inverse panel-success panel-green panel-info panel-sky panel-primary panel-midnightblue panel-warning panel-orange panel-danger panel-brown panel-magenta panel-purple panel-indigo panel-grape").addClass($(this).attr("data-style"));$(e).setWidgetState("headerStyle",$(this).attr("data-style"))})}else{e.hideWidgetControls(true)}},onInit:function(){var e=$(this).getWidgetState("headerStyle");if(e){var t=$(this).closest("[data-widget]");t.removeClass("panel-default panel-inverse panel-success panel-green panel-info panel-sky panel-primary panel-midnightblue panel-warning panel-orange panel-danger panel-brown panel-magenta panel-purple panel-indigo panel-grape").addClass(e)}}});this.registerAction({handle:"expand",html:'<span class="button-icon has-bg"><i class="fa fa-expand"></i></span>',onClick:function(){bootbox.alert("Coming Soon at Avalon! Expand your panel to make it go fullscreen!")}});this.registerAction({handle:"refresh",html:'<span class="button-icon"><i class="fa fa-refresh"></i></span>',onClick:function(){bootbox.alert("Coming Soon at Avalon! Load and reload panel contents with the help of AJAX!")}});this.registerAction({handle:"separator",html:'<i class="separator">'});this.registerAction({handle:"close",html:'<span class="button-icon"><i class="fa fa-times"></i></span>',onClick:function(){bootbox.alert("Coming Soon at Avalon!")}});var t=function(e){var t={},n=[];for(var r=0,i=e.length;r<i;++r){if(t.hasOwnProperty(e[r])){continue}n.push(e[r]);t[e[r]]=1}return n};this.make=function(n){n=n?n:{};var r=$("[data-widget-group]").map(function(){return $(this).data("widget-group")}).get();var i=t(r);$.each($("[data-widget]"),function(){var e=$(this).closest("[data-widget-group]").attr("data-widget-group");try{var t=$(this).attr("data-widget");var n=undefined;if(t.length>0){t=$.parseJSON(t);$(this).data("widgetParameters",t);if(t&&t.id){n=e+"."+t.id;$(this).data("widgetId",n)}if(t.draggable=="false"){$(this).attr("data-widget-static","")}else{$(this).removeAttr("data-widget-static")}if(t.id){for(var r in t){if(r=="id")continue;if(t.hasOwnProperty(r)){if(localStorage.getItem(n+"."+r)==undefined){localStorage.setItem(n+"."+r,t[r]);console.log(n+"."+r,t[r])}}}}}}catch(i){console.log(i)}});for(var s=i.length-1;s>=0;s--){$('[data-widget-group="'+i[s]+'"]').sortable({connectWith:'[data-widget-group="'+i[s]+'"]',items:"[data-widget]:not([data-widget-static])",placeholder:"panel-placeholder",handle:n.handle?n.handle:".panel-heading",start:function(e,t){t.placeholder.height(t.helper.outerHeight()-4)}})}$("[data-actions-container]").each(function(){var t=$(this);var n=[];$.each(this.attributes,function(){n.push(this)});if(!$.browser.chrome)n.reverse();$.each(n,function(){if(this.name.substr(0,12)=="data-action-"){var n=this.name.substr(12);if(e.actionDefinitions[n]!==undefined){var r=$(e.actionDefinitions[n].html);t.append(r);try{var i=t.attr("data-action-"+n);if(i.length==0){i="{}"}r.data("actionParameters",$.parseJSON(i))}catch(s){console.log(s)}r.data("actionDefinition",e.actionDefinitions[n]);r.data("parentWidget",r.closest("[data-widget]"));if(e.actionDefinitions[n].onClick){r.click(function(){e.actionDefinitions[n].onClick.call(this)})}if(e.actionDefinitions[n].onInit){e.actionDefinitions[n].onInit.call(r)}}}})})};return this}