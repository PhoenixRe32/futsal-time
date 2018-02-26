/*
CSS Browser Selector v0.4.0 (Nov 02, 2010)
Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Contributors: http://rafael.adm.br/css_browser_selector#contributors
*/
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;};css_browser_selector(navigator.userAgent);

/*
USAGE
1. Copy and paste the line below, inside <head> and </head> tag

<script src="css_browser_selector.js" type="text/javascript"></script>
2. Set CSS attributes with the code of each browser/os you want to hack

Examples:
html.gecko div#header { margin: 1em; }
.opera #header { margin: 1.2em; }
.ie .mylink { font-weight: bold; }
.mac.ie .mylink { font-weight: bold; }
.[os].[browser] .mylink { font-weight: bold; } -> without space between .[os] and .[browser]
Available OS Codes [os]:
win - Microsoft Windows (all versions)
vista - Microsoft Windows Vista new
linux - Linux (x11 and linux)
mac - Mac OS
freebsd - FreeBSD
ipod - iPod Touch
iphone - iPhone
ipad - iPad new
webtv - WebTV
j2me - J2ME Devices (ex: Opera mini) changed from mobile to j2me
blackberry - BlackBerry new
android - Google Android new
mobile - All mobile devices new
Available Browser Codes [browser]:
ie - Internet Explorer (All versions)
ie8 - Internet Explorer 8.x
ie7 - Internet Explorer 7.x
ie6 - Internet Explorer 6.x
ie5 - Internet Explorer 5.x
gecko - Mozilla, Firefox (all versions), Camino
ff2 - Firefox 2
ff3 - Firefox 3
ff3_5 - Firefox 3.5
ff3_6 - Firefox 3.6 new
opera - Opera (All versions)
opera8 - Opera 8.x
opera9 - Opera 9.x
opera10 - Opera 10.x
konqueror - Konqueror
webkit or safari - Safari, NetNewsWire, OmniWeb, Shiira, Google Chrome
safari3 - Safari 3.x
chrome - Google Chrome
iron - SRWare Iron

*/