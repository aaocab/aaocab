// DD Scroll Box v1.0: http://www.dynamicdrive.com

var scrollBox = (function(){

	var defaults = {dir: 'down', pctboundary: 0, baseclass: 'animated', fxclass: 'rubberBand', delaydismiss: true}
	var winheight, docheight, trackLength, throttlescroll, callbackDB = {}, pctScrolled = 0, curscrolltop, prevscrolltop, curdir = 'static'


	/* Util functions */

 // http://stackoverflow.com/questions/4320587/merge-two-object-literals-in-javascript
	function mergeliterals() {
	  var o = {}
	  for (var i = arguments.length - 1; i >= 0; i --) {
	    var s = arguments[i]
	    for (var k in s) o[k] = s[k]
	  }
	  return o
	}

	// CSS classList polyfill: https://github.com/eligrey/classList.js/

	if("document"in self){if(!("classList"in document.createElement("_"))||document.createElementNS&&!("classList"in document.createElementNS("http://www.w3.org/2000/svg","g"))){(function(t){"use strict";if(!("Element"in t))return;var e="classList",i="prototype",n=t.Element[i],s=Object,r=String[i].trim||function(){return this.replace(/^\s+|\s+$/g,"")},a=Array[i].indexOf||function(t){var e=0,i=this.length;for(;e<i;e++){if(e in this&&this[e]===t){return e}}return-1},o=function(t,e){this.name=t;this.code=DOMException[t];this.message=e},l=function(t,e){if(e===""){throw new o("SYNTAX_ERR","An invalid or illegal string was specified")}if(/\s/.test(e)){throw new o("INVALID_CHARACTER_ERR","String contains an invalid character")}return a.call(t,e)},c=function(t){var e=r.call(t.getAttribute("class")||""),i=e?e.split(/\s+/):[],n=0,s=i.length;for(;n<s;n++){this.push(i[n])}this._updateClassName=function(){t.setAttribute("class",this.toString())}},u=c[i]=[],f=function(){return new c(this)};o[i]=Error[i];u.item=function(t){return this[t]||null};u.contains=function(t){t+="";return l(this,t)!==-1};u.add=function(){var t=arguments,e=0,i=t.length,n,s=false;do{n=t[e]+"";if(l(this,n)===-1){this.push(n);s=true}}while(++e<i);if(s){this._updateClassName()}};u.remove=function(){var t=arguments,e=0,i=t.length,n,s=false,r;do{n=t[e]+"";r=l(this,n);while(r!==-1){this.splice(r,1);s=true;r=l(this,n)}}while(++e<i);if(s){this._updateClassName()}};u.toggle=function(t,e){t+="";var i=this.contains(t),n=i?e!==true&&"remove":e!==false&&"add";if(n){this[n](t)}if(e===true||e===false){return e}else{return!i}};u.toString=function(){return this.join(" ")};if(s.defineProperty){var h={get:f,enumerable:true,configurable:true};try{s.defineProperty(n,e,h)}catch(d){if(d.number===-2146823252){h.enumerable=false;s.defineProperty(n,e,h)}}}else if(s[i].__defineGetter__){n.__defineGetter__(e,f)}})(self)}else{(function(){"use strict";var t=document.createElement("_");t.classList.add("c1","c2");if(!t.classList.contains("c2")){var e=function(t){var e=DOMTokenList.prototype[t];DOMTokenList.prototype[t]=function(t){var i,n=arguments.length;for(i=0;i<n;i++){t=arguments[i];e.call(this,t)}}};e("add");e("remove")}t.classList.toggle("c3",false);if(t.classList.contains("c3")){var i=DOMTokenList.prototype.toggle;DOMTokenList.prototype.toggle=function(t,e){if(1 in arguments&&!this.contains(t)===!e){return e}else{return i.call(this,t)}}}t=null})()}}
	

	// domready function: https://github.com/ded/domready

	!function(e,t){typeof module!="undefined"?module.exports=t():typeof define=="function"&&typeof define.amd=="object"?define(t):this[e]=t()}("domready",function(){var e=[],t,n=document,r=n.documentElement.doScroll,i="DOMContentLoaded",s=(r?/^loaded|^c/:/^loaded|^i|^c/).test(n.readyState);return s||n.addEventListener(i,t=function(){n.removeEventListener(i,t),s=1;while(t=e.shift())t()}),function(t){s?setTimeout(t,0):e.push(t)}})

	// other util functions

	function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    )
	}
	
	function getmeasurements(){
		winheight= window.innerHeight || (document.documentElement || document.body).clientHeight
		docheight = getDocHeight()
		trackLength = docheight - winheight
	}

	function getwinYOffset(){
		return window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
	}
	
	function scrollaction(){ // function called inside window.onscroll
		var scrollTop = getwinYOffset()
		pctScrolled = Math.floor(scrollTop/trackLength * 100) // gets percentage scrolled (ie: 80 or NaN if tracklength == 0)
		for (var i in callbackDB){ // call each scrollBox instance's showhidebox() method
		   if (callbackDB.hasOwnProperty(i)){
					var scrollbox = callbackDB[i]
					if (typeof scrollbox == 'object')
		      	scrollbox.showhidebox()
		   }
		}
		prevscrolltop = scrollTop // update most previous scrolltop pos
	}

	function getCookie(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	}

	function setCookie(name, value, duration){
		var expirestr='', expiredate=new Date()
		if (typeof duration!="undefined"){ //if set persistent cookie
			var offsetmin=parseInt(duration) * (/hr/i.test(duration)? 60 : /day/i.test(duration)? 60*24 : 1)
			expiredate.setMinutes(expiredate.getMinutes() + offsetmin)
			expirestr="; expires=" + expiredate.toUTCString()
		}
		document.cookie = name+"="+value+"; path=/"+expirestr
	}

	/* Main Scrollbox func */

	function scrollBox(setting){
		var s = mergeliterals(setting, defaults)
		var hidescrollbox = getCookie(s.elementid + '_cookie')
		var target = document.getElementById(s.elementid)
		target.classList.add(s.baseclass)
		this.s = s
		this.target = target
		if (hidescrollbox){
			return
		}
		getmeasurements()
		prevscrolltop = getwinYOffset() // update most previous scrolltop pos
		this.registerScroll()
	}

	scrollBox.prototype = {

		showhidebox: function(){
			var s = this.s
			var target = this.target
			var dir = s.dir
			var fxclass = s.fxclass
			var pctboundary = s.pctboundary
			var delaydismiss = s.delaydismiss
			var showcond_down = (curdir == 'down') // check user is scrolling in same direction as "dir"
			var showcond_up = (curdir == 'up')
			var hidecond_down = (curdir == 'up' && !delaydismiss) // check user is scrolling in opposite direction as "dir" and delaydismiss == false
			var hidecond_up = (curdir == 'down' && !delaydismiss)

			if ( ((showcond_down && dir == 'down' && pctScrolled >= pctboundary) || (showcond_up && dir == 'up' && pctScrolled <= pctboundary)) 
			&& !target.classList.contains(fxclass) ){
				target.classList.add('animatedvisible')
				target.classList.add(fxclass)
			}
			else if ( (((dir == 'down' && (pctScrolled < pctboundary || hidecond_down)) ) || (dir == 'up' && (pctScrolled > pctboundary || hidecond_up)) )
			&& target.classList.contains(fxclass) ){
				target.classList.remove('animatedvisible')
				target.classList.remove(fxclass)
			}
		},

		hide: function(session){
			var target = this.target
			target.classList.remove(this.s.fxclass)	
			target.classList.remove('animatedvisible')
			this.deregisterScroll()
			if (typeof session != 'undefined'){ //session cookie hide
				setCookie(this.s.elementid + '_cookie', 'hidden')
			}
		},

		registerScroll: function(){
		var s = this.s
			callbackDB[s.elementid] = this
		},

		deregisterScroll: function(){
		var s = this.s
			callbackDB[s.elementid] = undefined
		}

	}

	
	window.addEventListener("resize", function(){
		getmeasurements()
	}, false)
	
	window.addEventListener("scroll", function(){
		clearTimeout(throttlescroll)
		throttlescroll = setTimeout(function(){ // throttle code inside scroll to once every 50 milliseconds
			curscrolltop = getwinYOffset()
			curdir = (curscrolltop > prevscrolltop)? 'down' : (curscrolltop < prevscrolltop)? 'up' : 'static'
			scrollaction()
		}, 50)
	}, false)

	return scrollBox
})()