var busystaticit = false;

// Needed for IE7 beta 2
function _gbody2()
{
    return document.compatMode && document.compatMode!="BackCompat"
        ? document.documentElement : document.body;
}

function inserttext()
{
    if (busystaticit == false || document.all) {
        busystaticit = true
        crossgototop = document.getElementById("gototop")
        w=window.innerWidth != undefined? window.innerWidth-crossgototop.offsetWidth-37 : _gbody2().clientWidth-crossgototop.offsetWidth-27
        h=window.innerHeight != undefined? window.innerHeight-crossgototop.offsetHeight-165 : _gbody2().clientHeight-crossgototop.offsetHeight-160
        crossgototop.style.left=w+"px"
        crossgototop.style.top=h+"px"
    }
    var w2 = !document.all ? pageXOffset+w : _gbody2().scrollLeft+w
    var h2 = !document.all ? pageYOffset+h : _gbody2().scrollTop+h
    crossgototop.style.left = w2+"px"
    crossgototop.style.top = h2+"px"
    setTimeout("inserttext()", 200)
}

if (busystaticit == false) {
    if (window.addEventListener)
        window.addEventListener("DOMContentLoaded", inserttext, false)
    else if (window.attachEvent)
        window.attachEvent("onload", inserttext)
    else
        window.onload = inserttext
    window.onresize=new Function("busystaticit = false")
}
