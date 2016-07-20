/**
 * Client side Javascript for DutchPIPE
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpclient.js 4 2006-05-16 08:49:02Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpclient.php
 */

// This should match your domain. If you're testing locally, use
// 'http:://localhost'
var dphost_url = 'http://www.yourdomain.com';

// Don't change this yet, not supported elsewhere
var dpclient_url = '/dpclient.php';

var http_obj = null;
var cur_loc = null;
var action_obj = null;

var actionmenu_obj = null;
var actionmenu_window = null;
var actionmenu_id = null;
var actionmenu_justopened = 2;
var actionmenu_mouse_x = null;
var actionmenu_mouse_y = null;

var event_count;
var event_time;
var new_window_opened = false;
var dpwindow = null;
var has_started = false;
var scriptid;
var loc = '';
var standalone = false;
var warned_nocookies = false;

function send_alive2server(firstcall, calltime, getdivs)
{
    var ob = new Date();
    var curtime = ob.getTime();

    if (curtime - calltime > 2300) {
        firstcall = true;
    }
    if (http_obj = get_http_obj()) {
        getdivs = getdivs && getdivs != '' ? '&getdivs=' + escape(getdivs) : '';
        http_obj.onreadystatechange = rcv_alive2server;
        http_obj.open("GET", dpclient_url + '?location=' + loc + '&scriptid='
            + (firstcall ? 0 : scriptid) + getdivs + '&ajax='
            + Math.round(Math.random() * 999999), true);
        http_obj.send(null);
    } else {
        alert('Could not establish connection with the DutchPIPE server.');
    }
}

function send_action2server(action)
{
    action_obj = get_http_obj();
    if (action_obj) {
        action_obj.onreadystatechange = rcv_action2server;
        action_obj.open("GET", dpclient_url + '?location=' + loc + '&scriptid='
            + scriptid + '&ajax=' + Math.round(Math.random() * 999999) +
            '&action=' + escape(action), true);
        action_obj.send(null);
    } else {
        alert("Could not establish connection with server.");
    }
}

function rcv_alive2server()
{
    if (http_obj.readyState != 4 || warned_nocookies) {
        return;
    }
    if (http_obj.status == 200) {
        handle_response(http_obj);
    }
    var ob = new Date();
    var curtime = ob.getTime();
    http_obj = null;
    if (!warned_nocookies) {
        setTimeout('send_alive2server(false, '+curtime+', "")', 2000);
    }
}

function rcv_action2server()
{
    if (action_obj.readyState != 4 || warned_nocookies) {
        return;
    }
    if (action_obj.status == 200) {
        handle_response(action_obj);
    }
    action_obj = null;
}

// Needed for IE7 beta 2
function _gbody()
{
    return document.compatMode && document.compatMode!="BackCompat"
        ? document.documentElement : document.body;
}

function get_actions(id, event)
{
    old_actionmenu_id = actionmenu_id;
    close_actions();
    if (old_actionmenu_id != null && old_actionmenu_id == id) {
        actionmenu_id = null;
        return;
    }

    actionmenu_obj = get_http_obj();
    if (actionmenu_obj) {
        actionmenu_obj.onreadystatechange = rcv_getactions;
        actionmenu_id = id;
        actionmenu_mouse_x = event.pageX ? event.pageX : event.clientX
            + _gbody().scrollLeft - 2;
        if (actionmenu_mouse_x < 0) {
            actionmenu_mouse_x = 0;
        }
        actionmenu_mouse_y = event.pageY ? event.pageY : event.clientY
            + _gbody().scrollTop - 2;
        if (actionmenu_mouse_y < 0) {
            actionmenu_mouse_y = 0;
        }
        actionmenu_obj.open("GET", dpclient_url + '?call_object=' + escape(id)
            + '&method=getActionsMenu&ajax=' +
            Math.round(Math.random() * 999999), true);
        actionmenu_obj.send(null);
    } else {
        alert("Could not establish connection with server.");
    }
}

function rcv_getactions()
{
    if (actionmenu_obj.readyState != 4) {
        return;
    }
    if (actionmenu_obj.status == 200) {
        handle_response(actionmenu_obj);

    }
    actionmenu_obj = null;
}

function close_actions()
{
    if (actionmenu_window != null
            || (actionmenu_window = _gel('actionmenu_window'))) {
        actionmenu_window.parentNode.removeChild(actionmenu_window);
        actionmenu_window = null;
        if (actionmenu_id != null && _gel(actionmenu_id) != null) {
            _gel(actionmenu_id).style.zIndex = 1;
            actionmenu_id = null;
        }
        _gel('action').focus();
    }
}

function load_xml(xml)
{
    if (window.DOMParser) {
        return (new DOMParser()).parseFromString(xml,
            'text/xml').documentElement;
    } else if (window.ActiveXObject) {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async = false;

        var loaded = xmlDoc.loadXML(xml);
        if (loaded) {
            return xmlDoc.documentElement;
        }
        alert("Failure loading XML");
    } else {
        alert('Your browser cannot handle this script');
    }
}

function handle_response(response_obj)
{
    /*if (response_obj.responseText != '1')
        alert(response_obj.responseText);*/
    if (typeof(response_obj) == 'string') {
        response = load_xml(response_obj);
    } else {
        if (response_obj.responseText == '1') {
            return;
        }
        if (response_obj.responseText == '2') {
            if (!warned_nocookies) {
                warned_nocookies = true;
                document.body.innerHTML = "<h1 align=\"center\" "
                    + "style=\"margin-top: 100px\">You must have (session) "
                    + "cookies enabled in order to view this site</h1>\n";
            }
            return;
        }
        if (response_obj.responseXML == null) {
            return;
        }
        response = response_obj.responseXML.documentElement;

        if (response == undefined) {
            return;
        }
        if (response_obj.responseXML.parseError != null
                && response_obj.responseXML.parseError.errorCode != null
                && response_obj.responseXML.parseError.errorCode != 0) {
            return;
        }
    }

    for (var i = 0; i < response.childNodes.length ; i++) {
        handle_dp_element(response.childNodes[i]);
    }
}

function handle_dp_element(childnode)
{
    switch(childnode.tagName) {
    case 'event':
        var e_count = parseInt(childnode.getAttribute('count'));
        var e_time = parseInt(childnode.getAttribute('time'));

        if (event_count != null && event_count != -1
                && event_count >= e_count) {
            //alert('Invalid event count ' + event_count + ': '
            //    + inner_text(childnode));
            window.location.reload();
            return;
        } else {
            event_count = e_count;
        }
        if (event_time != null && event_time != -1  && event_time > e_time) {
            //alert('Invalid event time ' + event_time + ': '
            //    + inner_text(childnode));
            window.location.reload();
            return;
        } else {
            event_time = e_time;
        }

        for (var j = 0; j < childnode.childNodes.length ; j++) {
            handle_dp_event(childnode.childNodes[j]);
        }
        break;
    case 'location':
        var newloc = inner_text(childnode);
        newloc = newloc == '/' ? '/' : dpclient_url + '?location=' + newloc;
        this.location = newloc;
        return;
    default:
        if (inner_text(childnode) != '1') {
            alert('Invalid DutchPIPE XML (2), tagname:' + childnode.tagName);
        }
    }
}

function handle_dp_event(childnode)
{
    switch(childnode.tagName) {
    case 'location':
        var newloc = inner_text(childnode);
        newloc = newloc == '/' ? '/' : dpclient_url + '?location=' + newloc;
        this.location = newloc;
        return;
    case 'div':
        var newdiv = document.createElement("DIV");
        newdiv.setAttribute('id', childnode.getAttribute('id'));
        var tmp = inner_text(childnode);
        var pos = tmp.indexOf('<div id="dppage">');
        if (pos != -1 && pos == 0) {
            tmp = tmp.substring(pos + 17);
            tmp = tmp.substring(0,tmp.length - 6);
        }
        newdiv.innerHTML = tmp;
        var olddiv = _gel(childnode.getAttribute('id'));

        if (olddiv == undefined) {
            document.body.appendChild(newdiv);
            newdiv.style.zIndex = 5;
            if (childnode.getAttribute('id') == 'dpinventory'
                    || childnode.getAttribute('id') == 'dpmessagearea') {
                if (document.addEventListener) {
                    newdiv.style.position = 'relative';
                } else if (childnode.getAttribute('id') == 'dpinventory') {
                    newdiv.style.position = 'absolute';
                }
            }
            if (childnode.getAttribute('id') == 'dpmessagearea'
                    && !document.addEventListener) {
                newdiv.style.position = 'absolute';
                if (!document.addEventListener) {
                    newdiv.style.marginTop = '110px';
                }
            }
            newdiv.style.zIndex = 5;
        } else {
            olddiv.style.display = 'none';
            olddiv.parentNode.insertBefore(newdiv, olddiv);
            olddiv.parentNode.removeChild(olddiv);
        }
        break;
    case 'message':
        var message;
        var messagediv;
        message = inner_text(childnode);
        if (message == 'close_window') {
            new_window_opened = true;
            this.location = '/dpmultiplewindows.txt';
            return;
        }
        messagediv = _gel('messages');
        messagediv.style.marginTop = '-4px';
        messagediv.style.marginBottom = '10px';
        while (messagediv.childNodes.length >= 17) {
            messagediv.removeChild(messagediv.childNodes[1]);
        }
        messagediv.innerHTML += '<div id="message">' + message + '</div>';
        break;
    case 'actions':
        if (!actionmenu_window) {
            actionmenu_window = document.createElement("DIV");
            actionmenu_window.className = 'actionwindow';
            actionmenu_window.innerHTML = inner_text(childnode);
            document.body.appendChild(actionmenu_window);
            actionmenu_window.style.left = (actionmenu_mouse_x-11)+'px';
            actionmenu_window.style.top = (actionmenu_mouse_y-13)+'px';
            actionmenu_window.style.zIndex = 5;
            actionmenu_justopened = true;
            actionmenu_window.style.display = 'none';
            actionmenu_window.style.display = '';
        }
        break;
    case 'script':
        add_dp_script(childnode);
        break;
    case 'removeDpElement':
        remove_dp_element(childnode);
        break;
    case 'addDpElement':
        add_dp_element(childnode);
        break;
    case 'changeDpElement':
        change_dp_element(childnode);
        break;
    case 'moveDpElement':
        move_dp_element(childnode);
        break;
    case 'newbody':
        alert('x');
        var newdpbody = document.createElement("DIV");
        newdpbody.setAttribute('id', 'dppage');
        newdpbody.innerHTML = inner_text(childnode);
        var olddpbody = _gel('dppage');
        /*if (olddpbody == null) {
            olddpbody = document.body.firstChild;
        }*/

        if (olddpbody == null || olddpbody == undefined) {
            oldpage = _gel('dpobinv');
        }
        if (olddpbody == null || olddpbody == undefined) {
            document.body.appendChild(newdpbody);
        } else {
            document.body.insertBefore(newdpbody, olddpbody);
            document.body.removeChild(olddpbody);
        }

        break;
    case 'window':
        var autoload = childnode.getAttribute('autoclose');
        var classname = childnode.getAttribute('styleclass');
        close_dpwindow();
        make_dpwindow(inner_text(childnode),autoload,classname);
        break;
    default:
        break;
        if (inner_text(childnode) != '1') {
            alert('Invalid DutchPIPE XML (1), tagname:'+childnode.tagName);
        }
    }
}

function add_dp_script(src)
{
    var tag = document.createElement("script");
    tag.setAttribute('type','text/javascript');
    //tag.type="text/javascript";
    //tag.src = '';
    if (src.textContent) {
        content = src.textContent;
        tag.textContent = content;
    } else {
        content = src.text;
        tag.text = content;
    }
    document.getElementsByTagName('head')[0].appendChild(tag);
    //window.status = '';
}

function check_dp_inventory()
{
    if (!_gel('dpinventory')) {
        var adddiv;

        adddiv = document.createElement('DIV');
        adddiv.setAttribute('id', 'dpinventory');
        document.getElementsByTagName('body')[0].appendChild(adddiv);
    }
}

function check_dp_messagearea()
{
    if (!_gel('dpmessagearea')) {
        var adddiv;

        adddiv = document.createElement('DIV');
        adddiv.setAttribute('id', 'dpmessagearea');
        document.getElementsByTagName('body')[0].appendChild(adddiv);
    }
}

function add_dp_element(dp_element)
{
    var adddiv;
    var where;

    adddiv = document.createElement("DIV");
    adddiv.setAttribute('id', dp_element.getAttribute('id'));
    adddiv.className = dp_element.getAttribute('class');
    adddiv.innerHTML = inner_text(dp_element);
    where = _gel(dp_element.getAttribute('where'));
    if (where == null) {
        return;
    }
    where.appendChild(adddiv);
    where.style.display = "none";
    where.style.display = "";
}

function remove_dp_element(dp_element)
{
    var remparent;
    var remdiv;

    remdiv = _gel(dp_element.getAttribute('id'));
    if (remdiv != null) {
        remparent = remdiv.parentNode;
        remparent.removeChild(remdiv);
        //remparent.style.display = "none";
        //remparent.style.display = "";
    }
}

function change_dp_element(dp_element)
{
    var changediv;

    changediv = _gel(dp_element.getAttribute('id'));
    if (changediv != null) {
        changediv.innerHTML = inner_text(dp_element);
    }
}

function move_dp_element(dp_element)
{
    var moveparent;
    var movediv;
    var moveinnerhtml;
    var adddiv;

    movediv = _gel(dp_element.getAttribute('id'));
    moveinnerhtml = movediv.innerHTML;
    if (movediv != null) {
        moveparent = movediv.parentNode;
        moveparent.removeChild(movediv);
    }

    adddiv = document.createElement("DIV");
    adddiv.setAttribute('id', dp_element.getAttribute('id'));
    adddiv.className = dp_element.getAttribute('class');
    adddiv.innerHTML = moveinnerhtml;
    where = _gel(dp_element.getAttribute('where'));
    where.appendChild(adddiv);
    where.style.display = "none";
    where.style.display = "";
}

function get_http_obj()
{
    try {
        tmp_http_obj = new ActiveXObject('Msxml2.XMLHTTP')
    }
    catch(b) {
        try {
            tmp_http_obj = new ActiveXObject('Microsoft.XMLHTTP')
        }
        catch(c) {
            tmp_http_obj = null
        }
    }
    if (!tmp_http_obj && typeof XMLHttpRequest != 'undefined') {
        tmp_http_obj = new XMLHttpRequest()
    }
    return tmp_http_obj
}

function make_dpwindow(text,autoclose,styleclass)
{
    if (!dpwindow) {
        dpwindow = document.createElement("DIV");
        dpwindow.className = styleclass == null ? 'dpwindow_default'
            : styleclass;
        var topspace = 50;
        if (dpwindow.className == 'dpwindow_todo') {
            topspace = 35;
        } else if (dpwindow.className == 'dpwindow_src') {
            topspace = 10;
        }
        topspace += window.pageYOffset != null ? window.pageYOffset
            : _gbody().scrollTop;
        dpwindow.style.top = topspace + 'px';
        if (autoclose == null) {
            dpwindow.innerHTML = text + '<p align="right">'
             + '<a href="javascript:close_dpwindow()">close</a></p>';
        } else {
            dpwindow.innerHTML = text;
            setTimeout('close_dpwindow()', autoclose);
        }
        body = document.getElementsByTagName('body')[0];
        body.insertBefore(dpwindow, body.firstChild);

        // Command line loses focus in IE. This solves most cases for now, but
        // this should not go just to 'action', it could e.g. by a form field.
        _gel('action').focus();
    }
}

function close_dpwindow()
{
    if (dpwindow != null || (dpwindow = _gel('dpwindow'))) {
        dpwindow.parentNode.removeChild(dpwindow);
        dpwindow = null;
        _gel('action').focus();
    }
}

function _gel(a)
{
    return document.getElementById(a)
}

// Opera stuff:
//window.selectSingleNode = function (d,v,c){v+="[1]";var nl=selectNodes(d,v,c);
//if(nl.length>0)return nl[0];else return null;}
//Document.prototype.selectSingleNode = function(v){return
//selectSingleNode(this,v,null);}
//Element.prototype.selectSingleNode = function(v){var scope=
//this.ownerDocument;if(scope.selectSingleNode)return
//selectSingleNode(scope,v,this);else return null;}

function inner_text(ob)
{
    if (ob.textContent != undefined) {
        return ob.textContent;
    }
    if (ob.text != undefined) {
        return ob.text;
    }
    var bSaf = (navigator.userAgent.indexOf('Safari') != -1);

    if (navigator.userAgent.indexOf('Safari') != -1
            && ob.innerHTML != undefined) {
        return ob.innerHTML;
    }
    return ob.childNodes[0].nodeValue;
    return 'Unsupported browser (yet)';
    var oDiv = document.createElement('div'), oStr = ob.toString();
    oDiv.innerHTML = '&gt;';
    if (oDiv.innerText != '>') {
	    //broken innerText, fix it
        oDiv.innerHTML = oStr;
        oStr = '';
        for( var i = 0; oDiv.childNodes[i]; i++ ) {
            oStr += oDiv.childNodes[i].nodeValue;
        }
    }
    delete oDiv;
    return oStr;
}

function inner_xml(ob)
{
    if (ob.xml == undefined) {
        var XS = new XMLSerializer();
        return XS.serializeToString(ob);
    }
    return ob.xml;
}

function start_dutchpipe(page)
{
    // quit if this function has already been called
    if (arguments.callee.done) {
        return;
    }
    arguments.callee.done = true;

    var tmp = location.href;
    var pos = tmp.indexOf(dphost_url);
    if (pos != -1)
        tmp = tmp.substring(pos + dphost_url.length);

    if (tmp == location.href)
        return;

    if (tmp != '/') {
        var tmp2 = tmp;
        pos = tmp.indexOf(dpclient_url);
        if (pos != -1) {
            tmp = tmp.substring(pos + dpclient_url.length);
            pos = tmp.indexOf('?location=');
            if (pos != -1) {
                tmp = tmp.substring(pos + 10);
                pos = tmp.indexOf('.php');
                if (pos != -1)
                    tmp = tmp.substring(0, pos + 4);
                loc = tmp;
            }
        } else {
            loc = location.href;
            standalone = true;
        }
    }
    var ob = new Date();
    var curtime = ob.getTime();
    scriptid = Math.round(Math.random()*999999);
    if (window.load_elements) {
        load_elements();
    }
    var getdivs = '';
    if (standalone) {
        if (_gel('dpinventory') == null) {
            getdivs += 'dpinventory#';
        }
        if (_gel('dpmessagearea') == null) {
            getdivs += 'dpmessagearea#';
        }
    }
    if (getdivs != '')
        send_alive2server(true, curtime, getdivs);
    else {
        setTimeout('send_alive2server(true, '+curtime+', "")',
            (!has_started ? 10 : 2000));
        _gel('action').focus();
        // IE needs a small timeout:
        setTimeout('gototop()', 10);
        has_started = true;
    }
}

function gototop()
{
    scroll(0,0);
}

function action_dutchpipe()
{
    if (_gel('action').value == '')
        close_dpwindow();
    send_action2server(_gel('action').value);
    _gel('action').value = '';
    return false;
}

document.onclick = close_actions;

/* for Mozilla */
if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", start_dutchpipe, null);
}
/* for other browsers */
window.onload = start_dutchpipe;


