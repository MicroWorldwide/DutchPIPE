<?php
/**
 * Client side Javascript for DutchPIPE
 *
 * This is the source of public/dpclient-js.php, which is packed JavaScript.
 * This is the unpacked version, with the exception of jQuery, of which a packed
 * version is included. See http://jquery.com/ for a full version. This file is
 * not used directly, but changes should go in here after which a new packed
 * version can be produced.
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * jQuery 1.1.3.1 is included in this source file and is not subject to the
 * DutchPIPE license. It has its own license and copyright statement. See
 * LICENSE-3RDPARTY.
 *
 * @package    DutchPIPE
 * @subpackage public
 * @author     Lennert Stock <ls@dutchpipe.org> (DutchPIPE part)
 * @author     John Resig <http://jquery.com/> (jQuery part)
 * @copyright  2006, 2007 Lennert Stock (DutchPIPE part)
 * @copyright  2006, 2007 John Resig (jQuery part)
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @license    http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt
 * @version    Subversion: $Id: dpclient-js-src.php 288 2007-08-21 19:29:16Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpclient-js.php dpclient.php
 */

/**
 * Gets server settings
 */
require_once(realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/..')
    . '/config/dpserver-ini.php');

error_reporting(DPSERVER_ERROR_REPORTING);
header("Cache-Control: max-age=86400, must-revalidate");
?>
var php = [
    '<?php echo dp_text('You must have (session) cookies enabled in order to view this site'); ?>',
    '<?php echo dp_text('Invalid event count'); ?>',
    '<?php echo dp_text('Invalid event time'); ?>',
    '<?php echo dp_text('Invalid DutchPIPE XML (2), tagname:'); ?>',
    '<?php echo DPSERVER_CLIENT_DIR; ?>',
    '<?php echo dp_text('Invalid DutchPIPE XML (1), tagname:'); ?>',
    '<?php echo dp_text('inventory'); ?>',
    '<?php echo dp_text('close'); ?>',
    '<?php echo DPSERVER_HOST_URL . DPSERVER_CLIENT_DIR; ?>',
    '<?php echo DPSERVER_CLIENT_FILENAME; ?>'
];
<?php
/*
 * jQuery 1.1.3.1 - New Wave Javascript
 *
 * Copyright (c) 2007 John Resig (jquery.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * $Date: 2007-07-05 00:43:24 -0400 (Thu, 05 Jul 2007) $
 * $Rev: 2243 $
 */
?>
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7(1g 18.6=="I"){18.I=18.I;u 6=q(a,c){7(18==9||!9.3X)v 14 6(a,c);v 9.3X(a,c)};7(1g $!="I")6.1I$=$;u $=6;6.11=6.8r={3X:q(a,c){a=a||P;7(6.16(a))v 14 6(P)[6.11.1G?"1G":"1W"](a);7(1g a=="1s"){u m=/^[^<]*(<(.|\\s)+>)[^>]*$/.1V(a);7(m)a=6.31([m[1]]);B v 14 6(c).1L(a)}v 9.4E(a.15==2b&&a||(a.3C||a.C&&a!=18&&!a.1q&&a[0]!=I&&a[0].1q)&&6.2L(a)||[a])},3C:"1.1.3.1",7W:q(){v 9.C},C:0,1M:q(a){v a==I?6.2L(9):9[a]},1Z:q(a){u b=6(a);b.5q=9;v b},4E:q(a){9.C=0;[].R.O(9,a);v 9},F:q(a,b){v 6.F(9,a,b)},2p:q(a){u b=-1;9.F(q(i){7(9==a)b=i});v b},1b:q(f,d,e){u c=f;7(f.15==33)7(d==I)v 9.C&&6[e||"1b"](9[0],f)||I;B{c={};c[f]=d}v 9.F(q(a){E(u b V c)6.1b(e?9.T:9,b,6.4H(9,c[b],e,a,b))})},1f:q(b,a){v 9.1b(b,a,"2z")},2A:q(e){7(1g e=="1s")v 9.2Y().3e(P.66(e));u t="";6.F(e||9,q(){6.F(9.2S,q(){7(9.1q!=8)t+=9.1q!=1?9.5R:6.11.2A([9])})});v t},8b:q(){u a,1S=19;v 9.F(q(){7(!a)a=6.31(1S,9.2O);u b=a[0].3s(K);9.L.2K(b,9);1v(b.1d)b=b.1d;b.4g(9)})},3e:q(){v 9.2F(19,K,1,q(a){9.4g(a)})},5w:q(){v 9.2F(19,K,-1,q(a){9.2K(a,9.1d)})},5t:q(){v 9.2F(19,N,1,q(a){9.L.2K(a,9)})},5s:q(){v 9.2F(19,N,-1,q(a){9.L.2K(a,9.1X)})},2U:q(){v 9.5q||6([])},1L:q(t){u b=6.3k(9,q(a){v 6.1L(t,a)});v 9.1Z(/[^+>] [^+>]/.17(t)||t.J("..")>-1?6.5g(b):b)},7x:q(e){u d=9.1A(9.1L("*"));d.F(q(){9.1I$1a={};E(u a V 9.$1a)9.1I$1a[a]=6.1c({},9.$1a[a])}).3U();u r=9.1Z(6.3k(9,q(a){v a.3s(e!=I?e:K)}));d.F(q(){u b=9.1I$1a;E(u a V b)E(u c V b[a])6.S.1A(9,a,b[a][c],b[a][c].W);9.1I$1a=H});v r},1i:q(t){v 9.1Z(6.16(t)&&6.2s(9,q(b,a){v t.O(b,[a])})||6.2x(t,9))},4Y:q(t){v 9.1Z(t.15==33&&6.2x(t,9,K)||6.2s(9,q(a){v(t.15==2b||t.3C)?6.2w(a,t)<0:a!=t}))},1A:q(t){v 9.1Z(6.1T(9.1M(),t.15==33?6(t).1M():t.C!=I&&(!t.Q||t.Q=="6Z")?t:[t]))},37:q(a){v a?6.2x(a,9).C>0:N},6R:q(a){v a==I?(9.C?9[0].2v:H):9.1b("2v",a)},3F:q(a){v a==I?(9.C?9[0].27:H):9.2Y().3e(a)},2F:q(f,d,g,e){u c=9.C>1,a;v 9.F(q(){7(!a){a=6.31(f,9.2O);7(g<0)a.6E()}u b=9;7(d&&6.Q(9,"1r")&&6.Q(a[0],"2V"))b=9.3R("1z")[0]||9.4g(P.5h("1z"));6.F(a,q(){e.O(b,[c?9.3s(K):9])})})}};6.1c=6.11.1c=q(){u c=19[0],a=1;7(19.C==1){c=9;a=0}u b;1v((b=19[a++])!=H)E(u i V b)c[i]=b[i];v c};6.1c({6n:q(){7(6.1I$)$=6.1I$;v 6},16:q(a){v!!a&&1g a!="1s"&&!a.Q&&a.15!=2b&&/q/i.17(a+"")},40:q(a){v a.4z&&a.2O&&!a.2O.4y},Q:q(b,a){v b.Q&&b.Q.1D()==a.1D()},F:q(a,b,c){7(a.C==I)E(u i V a)b.O(a[i],c||[i,a[i]]);B E(u i=0,4x=a.C;i<4x;i++)7(b.O(a[i],c||[i,a[i]])===N)1F;v a},4H:q(c,b,d,e,a){7(6.16(b))b=b.3D(c,[e]);u f=/z-?2p|5Y-?8p|1e|5U|8i-?1u/i;v b&&b.15==3y&&d=="2z"&&!f.17(a)?b+"4o":b},12:{1A:q(b,c){6.F(c.2R(/\\s+/),q(i,a){7(!6.12.3w(b.12,a))b.12+=(b.12?" ":"")+a})},1E:q(b,c){b.12=c!=I?6.2s(b.12.2R(/\\s+/),q(a){v!6.12.3w(c,a)}).5M(" "):""},3w:q(t,c){v 6.2w(c,(t.12||t).3v().2R(/\\s+/))>-1}},4m:q(e,o,f){E(u i V o){e.T["2N"+i]=e.T[i];e.T[i]=o[i]}f.O(e,[]);E(u i V o)e.T[i]=e.T["2N"+i]},1f:q(e,p){7(p=="1u"||p=="29"){u b={},3r,3p,d=["83","81","80","7Y"];6.F(d,q(){b["7V"+9]=0;b["7T"+9+"7S"]=0});6.4m(e,b,q(){7(6(e).37(\':4f\')){3r=e.7Q;3p=e.7O}B{e=6(e.3s(K)).1L(":4b").5v("2B").2U().1f({48:"1y",3i:"7L",U:"2h",7K:"0",7I:"0"}).5o(e.L)[0];u a=6.1f(e.L,"3i")||"3n";7(a=="3n")e.L.T.3i="7G";3r=e.7E;3p=e.7D;7(a=="3n")e.L.T.3i="3n";e.L.3q(e)}});v p=="1u"?3r:3p}v 6.2z(e,p)},2z:q(e,a,d){u g;7(a=="1e"&&6.M.1h){g=6.1b(e.T,"1e");v g==""?"1":g}7(a.3t(/3x/i))a=6.1U;7(!d&&e.T[a])g=e.T[a];B 7(P.3f&&P.3f.3Y){7(a.3t(/3x/i))a="3x";a=a.1o(/([A-Z])/g,"-$1").2H();u b=P.3f.3Y(e,H);7(b)g=b.57(a);B 7(a=="U")g="1P";B 6.4m(e,{U:"2h"},q(){u c=P.3f.3Y(9,"");g=c&&c.57(a)||""})}B 7(e.3S){u f=a.1o(/\\-(\\w)/g,q(m,c){v c.1D()});g=e.3S[a]||e.3S[f]}v g},31:q(a,c){u r=[];c=c||P;6.F(a,q(i,b){7(!b)v;7(b.15==3y)b=b.3v();7(1g b=="1s"){u s=6.2C(b).2H(),1x=c.5h("1x"),1N=[];u a=!s.J("<1H")&&[1,"<2y>","</2y>"]||!s.J("<7g")&&[1,"<52>","</52>"]||(!s.J("<7c")||!s.J("<1z")||!s.J("<7a")||!s.J("<78"))&&[1,"<1r>","</1r>"]||!s.J("<2V")&&[2,"<1r><1z>","</1z></1r>"]||(!s.J("<75")||!s.J("<74"))&&[3,"<1r><1z><2V>","</2V></1z></1r>"]||!s.J("<73")&&[2,"<1r><4W>","</4W></1r>"]||[0,"",""];1x.27=a[1]+b+a[2];1v(a[0]--)1x=1x.1d;7(6.M.1h){7(!s.J("<1r")&&s.J("<1z")<0)1N=1x.1d&&1x.1d.2S;B 7(a[1]=="<1r>"&&s.J("<1z")<0)1N=1x.2S;E(u n=1N.C-1;n>=0;--n)7(6.Q(1N[n],"1z")&&!1N[n].2S.C)1N[n].L.3q(1N[n])}b=6.2L(1x.2S)}7(0===b.C&&(!6.Q(b,"34")&&!6.Q(b,"2y")))v;7(b[0]==I||6.Q(b,"34")||b.71)r.R(b);B r=6.1T(r,b)});v r},1b:q(c,d,a){u e=6.40(c)?{}:6.3H;7(e[d]){7(a!=I)c[e[d]]=a;v c[e[d]]}B 7(a==I&&6.M.1h&&6.Q(c,"34")&&(d=="70"||d=="6Y"))v c.6W(d).5R;B 7(c.4z){7(a!=I)c.6U(d,a);7(6.M.1h&&/4M|2u/.17(d)&&!6.40(c))v c.35(d,2);v c.35(d)}B{7(d=="1e"&&6.M.1h){7(a!=I){c.5U=1;c.1i=(c.1i||"").1o(/4L\\([^)]*\\)/,"")+(39(a).3v()=="6M"?"":"4L(1e="+a*4X+")")}v c.1i?(39(c.1i.3t(/1e=([^)]*)/)[1])/4X).3v():""}d=d.1o(/-([a-z])/6K,q(z,b){v b.1D()});7(a!=I)c[d]=a;v c[d]}},2C:q(t){v t.1o(/^\\s+|\\s+$/g,"")},2L:q(a){u r=[];7(1g a!="6I")E(u i=0,26=a.C;i<26;i++)r.R(a[i]);B r=a.51(0);v r},2w:q(b,a){E(u i=0,26=a.C;i<26;i++)7(a[i]==b)v i;v-1},1T:q(a,b){E(u i=0;b[i];i++)a.R(b[i]);v a},5g:q(a){u r=[],3P=6.1k++;E(u i=0,4G=a.C;i<4G;i++)7(3P!=a[i].1k){a[i].1k=3P;r.R(a[i])}v r},1k:0,2s:q(c,b,d){7(1g b=="1s")b=14 45("a","i","v "+b);u a=[];E(u i=0,30=c.C;i<30;i++)7(!d&&b(c[i],i)||d&&!b(c[i],i))a.R(c[i]);v a},3k:q(c,b){7(1g b=="1s")b=14 45("a","v "+b);u d=[];E(u i=0,30=c.C;i<30;i++){u a=b(c[i],i);7(a!==H&&a!=I){7(a.15!=2b)a=[a];d=d.6v(a)}}v d}});14 q(){u b=6u.6t.2H();6.M={4D:(b.3t(/.+(?:6s|6q|6o|6m)[\\/: ]([\\d.]+)/)||[])[1],20:/5l/.17(b),2a:/2a/.17(b),1h:/1h/.17(b)&&!/2a/.17(b),3j:/3j/.17(b)&&!/(6h|5l)/.17(b)};6.6g=!6.M.1h||P.6f=="6c";6.1U=6.M.1h?"1U":"5x",6.3H={"E":"68","67":"12","3x":6.1U,5x:6.1U,1U:6.1U,27:"27",12:"12",2v:"2v",2r:"2r",2B:"2B",65:"63",2T:"2T",62:"5Z"}};6.F({4v:"a.L",4p:"6.4p(a)",8o:"6.22(a,2,\'1X\')",8n:"6.22(a,2,\'4t\')",8k:"6.4q(a.L.1d,a)",8h:"6.4q(a.1d)"},q(i,n){6.11[i]=q(a){u b=6.3k(9,n);7(a&&1g a=="1s")b=6.2x(a,b);v 9.1Z(b)}});6.F({5o:"3e",8g:"5w",2K:"5t",8f:"5s"},q(i,n){6.11[i]=q(){u a=19;v 9.F(q(){E(u j=0,26=a.C;j<26;j++)6(a[j])[n](9)})}});6.F({5v:q(a){6.1b(9,a,"");9.8d(a)},8c:q(c){6.12.1A(9,c)},88:q(c){6.12.1E(9,c)},87:q(c){6.12[6.12.3w(9,c)?"1E":"1A"](9,c)},1E:q(a){7(!a||6.1i(a,[9]).r.C)9.L.3q(9)},2Y:q(){1v(9.1d)9.3q(9.1d)}},q(i,n){6.11[i]=q(){v 9.F(n,19)}});6.F(["5Q","5P","5O","5N"],q(i,n){6.11[n]=q(a,b){v 9.1i(":"+n+"("+a+")",b)}});6.F(["1u","29"],q(i,n){6.11[n]=q(h){v h==I?(9.C?6.1f(9[0],n):H):9.1f(n,h.15==33?h:h+"4o")}});6.1c({4n:{"":"m[2]==\'*\'||6.Q(a,m[2])","#":"a.35(\'2m\')==m[2]",":":{5P:"i<m[3]-0",5O:"i>m[3]-0",22:"m[3]-0==i",5Q:"m[3]-0==i",2Q:"i==0",2P:"i==r.C-1",5L:"i%2==0",5K:"i%2","2Q-3u":"a.L.3R(\'*\')[0]==a","2P-3u":"6.22(a.L.5J,1,\'4t\')==a","86-3u":"!6.22(a.L.5J,2,\'4t\')",4v:"a.1d",2Y:"!a.1d",5N:"(a.5H||a.85||\'\').J(m[3])>=0",4f:\'"1y"!=a.G&&6.1f(a,"U")!="1P"&&6.1f(a,"48")!="1y"\',1y:\'"1y"==a.G||6.1f(a,"U")=="1P"||6.1f(a,"48")=="1y"\',84:"!a.2r",2r:"a.2r",2B:"a.2B",2T:"a.2T||6.1b(a,\'2T\')",2A:"\'2A\'==a.G",4b:"\'4b\'==a.G",5F:"\'5F\'==a.G",4l:"\'4l\'==a.G",5E:"\'5E\'==a.G",4k:"\'4k\'==a.G",5D:"\'5D\'==a.G",5C:"\'5C\'==a.G",1J:\'"1J"==a.G||6.Q(a,"1J")\',5B:"/5B|2y|82|1J/i.17(a.Q)"},"[":"6.1L(m[2],a).C"},5A:[/^\\[ *(@)([\\w-]+) *([!*$^~=]*) *(\'?"?)(.*?)\\4 *\\]/,/^(\\[)\\s*(.*?(\\[.*?\\])?[^[]*?)\\s*\\]/,/^(:)([\\w-]+)\\("?\'?(.*?(\\(.*?\\))?[^(]*?)"?\'?\\)/,14 3o("^([:.#]*)("+(6.2J=6.M.20&&6.M.4D<"3.0.0"?"\\\\w":"(?:[\\\\w\\7Z-\\7X*1I-]|\\\\\\\\.)")+"+)")],2x:q(a,c,b){u d,1K=[];1v(a&&a!=d){d=a;u f=6.1i(a,c,b);a=f.t.1o(/^\\s*,\\s*/,"");1K=b?c=f.r:6.1T(1K,f.r)}v 1K},1L:q(t,l){7(1g t!="1s")v[t];7(l&&!l.1q)l=H;l=l||P;7(!t.J("//")){l=l.4h;t=t.2G(2,t.C)}B 7(!t.J("/")&&!l.2O){l=l.4h;t=t.2G(1,t.C);7(t.J("/")>=1)t=t.2G(t.J("/"),t.C)}u b=[l],2j=[],2P;1v(t&&2P!=t){u r=[];2P=t;t=6.2C(t).1o(/^\\/\\//,"");u k=N;u g=14 3o("^[/>]\\\\s*("+6.2J+"+)");u m=g.1V(t);7(m){u o=m[1].1D();E(u i=0;b[i];i++)E(u c=b[i].1d;c;c=c.1X)7(c.1q==1&&(o=="*"||c.Q.1D()==o.1D()))r.R(c);b=r;t=t.1o(g,"");7(t.J(" ")==0)7R;k=K}B{g=/^((\\/?\\.\\.)|([>\\/+~]))\\s*([a-z]*)/i;7((m=g.1V(t))!=H){r=[];u o=m[4],1k=6.1k++;m=m[1];E(u j=0,2e=b.C;j<2e;j++)7(m.J("..")<0){u n=m=="~"||m=="+"?b[j].1X:b[j].1d;E(;n;n=n.1X)7(n.1q==1){7(m=="~"&&n.1k==1k)1F;7(!o||n.Q.1D()==o.1D()){7(m=="~")n.1k=1k;r.R(n)}7(m=="+")1F}}B r.R(b[j].L);b=r;t=6.2C(t.1o(g,""));k=K}}7(t&&!k){7(!t.J(",")){7(l==b[0])b.4e();2j=6.1T(2j,b);r=b=[l];t=" "+t.2G(1,t.C)}B{u h=14 3o("^("+6.2J+"+)(#)("+6.2J+"+)");u m=h.1V(t);7(m){m=[0,m[2],m[3],m[1]]}B{h=14 3o("^([#.]?)("+6.2J+"*)");m=h.1V(t)}m[2]=m[2].1o(/\\\\/g,"");u f=b[b.C-1];7(m[1]=="#"&&f&&f.4d){u p=f.4d(m[2]);7((6.M.1h||6.M.2a)&&p&&1g p.2m=="1s"&&p.2m!=m[2])p=6(\'[@2m="\'+m[2]+\'"]\',f)[0];b=r=p&&(!m[3]||6.Q(p,m[3]))?[p]:[]}B{E(u i=0;b[i];i++){u a=m[1]!=""||m[0]==""?"*":m[2];7(a=="*"&&b[i].Q.2H()=="7P")a="2E";r=6.1T(r,b[i].3R(a))}7(m[1]==".")r=6.4c(r,m[2]);7(m[1]=="#"){u e=[];E(u i=0;r[i];i++)7(r[i].35("2m")==m[2]){e=[r[i]];1F}r=e}b=r}t=t.1o(h,"")}}7(t){u d=6.1i(t,r);b=r=d.r;t=6.2C(d.t)}}7(t)b=[];7(b&&l==b[0])b.4e();2j=6.1T(2j,b);v 2j},4c:q(r,m,a){m=" "+m+" ";u b=[];E(u i=0;r[i];i++){u c=(" "+r[i].12+" ").J(m)>=0;7(!a&&c||a&&!c)b.R(r[i])}v b},1i:q(t,r,h){u d;1v(t&&t!=d){d=t;u p=6.5A,m;E(u i=0;p[i];i++){m=p[i].1V(t);7(m){t=t.7N(m[0].C);m[2]=m[2].1o(/\\\\/g,"");1F}}7(!m)1F;7(m[1]==":"&&m[2]=="4Y")r=6.1i(m[3],r,K).r;B 7(m[1]==".")r=6.4c(r,m[2],h);B 7(m[1]=="@"){u g=[],G=m[3];E(u i=0,2e=r.C;i<2e;i++){u a=r[i],z=a[6.3H[m[2]]||m[2]];7(z==H||/4M|2u/.17(m[2]))z=6.1b(a,m[2])||\'\';7((G==""&&!!z||G=="="&&z==m[5]||G=="!="&&z!=m[5]||G=="^="&&z&&!z.J(m[5])||G=="$="&&z.2G(z.C-m[5].C)==m[5]||(G=="*="||G=="~=")&&z.J(m[5])>=0)^h)g.R(a)}r=g}B 7(m[1]==":"&&m[2]=="22-3u"){u e=6.1k++,g=[],17=/(\\d*)n\\+?(\\d*)/.1V(m[3]=="5L"&&"2n"||m[3]=="5K"&&"2n+1"||!/\\D/.17(m[3])&&"n+"+m[3]||m[3]),2Q=(17[1]||1)-0,d=17[2]-0;E(u i=0,2e=r.C;i<2e;i++){u j=r[i],L=j.L;7(e!=L.1k){u c=1;E(u n=L.1d;n;n=n.1X)7(n.1q==1)n.4a=c++;L.1k=e}u b=N;7(2Q==1){7(d==0||j.4a==d)b=K}B 7((j.4a+d)%2Q==0)b=K;7(b^h)g.R(j)}r=g}B{u f=6.4n[m[1]];7(1g f!="1s")f=6.4n[m[1]][m[2]];49("f = q(a,i){v "+f+"}");r=6.2s(r,f,h)}}v{r:r,t:t}},4p:q(c){u b=[];u a=c.L;1v(a&&a!=P){b.R(a);a=a.L}v b},22:q(a,e,c,b){e=e||1;u d=0;E(;a;a=a[c])7(a.1q==1&&++d==e)1F;v a},4q:q(n,a){u r=[];E(;n;n=n.1X){7(n.1q==1&&(!a||n!=a))r.R(n)}v r}});6.S={1A:q(d,e,c,b){7(6.M.1h&&d.3m!=I)d=18;7(!c.1Q)c.1Q=9.1Q++;7(b!=I){u f=c;c=q(){v f.O(9,19)};c.W=b;c.1Q=f.1Q}7(!d.$1a)d.$1a={};7(!d.$1p)d.$1p=q(){u a;7(1g 6=="I"||6.S.47)v a;a=6.S.1p.O(d,19);v a};u g=d.$1a[e];7(!g){g=d.$1a[e]={};7(d.46)d.46(e,d.$1p,N);B d.7M("5r"+e,d.$1p)}g[c.1Q]=c;7(!9.Y[e])9.Y[e]=[];7(6.2w(d,9.Y[e])==-1)9.Y[e].R(d)},1Q:1,Y:{},1E:q(b,c,a){u d=b.$1a,1Y,2p;7(d){7(c&&c.G){a=c.44;c=c.G}7(!c){E(c V d)9.1E(b,c)}B 7(d[c]){7(a)3l d[c][a.1Q];B E(a V b.$1a[c])3l d[c][a];E(1Y V d[c])1F;7(!1Y){7(b.43)b.43(c,b.$1p,N);B b.7J("5r"+c,b.$1p);1Y=H;3l d[c];1v(9.Y[c]&&((2p=6.2w(b,9.Y[c]))>=0))3l 9.Y[c][2p]}}E(1Y V d)1F;7(!1Y)b.$1p=b.$1a=H}},1t:q(c,b,d){b=6.2L(b||[]);7(!d)6.F(9.Y[c]||[],q(){6.S.1t(c,b,9)});B{u a,1Y,11=6.16(d[c]||H);b.5p(9.42({G:c,1O:d}));7(6.16(d.$1p)&&(a=d.$1p.O(d,b))!==N)9.47=K;7(11&&a!==N&&!6.Q(d,\'a\'))d[c]();9.47=N}},1p:q(b){u a;b=6.S.42(b||18.S||{});u c=9.$1a&&9.$1a[b.G],1S=[].51.3D(19,1);1S.5p(b);E(u j V c){1S[0].44=c[j];1S[0].W=c[j].W;7(c[j].O(9,1S)===N){b.2d();b.2D();a=N}}7(6.M.1h)b.1O=b.2d=b.2D=b.44=b.W=H;v a},42:q(c){u a=c;c=6.1c({},a);c.2d=q(){7(a.2d)v a.2d();a.7H=N};c.2D=q(){7(a.2D)v a.2D();a.7F=K};7(!c.1O&&c.5n)c.1O=c.5n;7(6.M.20&&c.1O.1q==3)c.1O=a.1O.L;7(!c.41&&c.4j)c.41=c.4j==c.1O?c.7C:c.4j;7(c.5k==H&&c.5j!=H){u e=P.4h,b=P.4y;c.5k=c.5j+(e&&e.5i||b.5i);c.7z=c.7y+(e&&e.5f||b.5f)}7(!c.3h&&(c.5e||c.5d))c.3h=c.5e||c.5d;7(!c.5c&&c.5b)c.5c=c.5b;7(!c.3h&&c.1J)c.3h=(c.1J&1?1:(c.1J&2?3:(c.1J&4?2:0)));v c}};6.11.1c({3g:q(c,a,b){v c=="3z"?9.3Z(c,a,b):9.F(q(){6.S.1A(9,c,b||a,b&&a)})},3Z:q(d,b,c){v 9.F(q(){6.S.1A(9,d,q(a){6(9).3U(a);v(c||b).O(9,19)},c&&b)})},3U:q(a,b){v 9.F(q(){6.S.1E(9,a,b)})},1t:q(a,b){v 9.F(q(){6.S.1t(a,b,9)})},1R:q(){u a=19;v 9.5a(q(e){9.4u=0==9.4u?1:0;e.2d();v a[9.4u].O(9,[e])||N})},7w:q(f,g){q 3W(e){u p=e.41;1v(p&&p!=9)2g{p=p.L}25(e){p=9};7(p==9)v N;v(e.G=="3V"?f:g).O(9,[e])}v 9.3V(3W).59(3W)},1G:q(f){7(6.3d)f.O(P,[6]);B 6.2q.R(q(){v f.O(9,[6])});v 9}});6.1c({3d:N,2q:[],1G:q(){7(!6.3d){6.3d=K;7(6.2q){6.F(6.2q,q(){9.O(P)});6.2q=H}7(6.M.3j||6.M.2a)P.43("58",6.1G,N);7(!18.7v.C)6(18).1W(q(){6("#3T").1E()})}}});14 q(){6.F(("7u,7t,1W,7s,7r,3z,5a,7q,"+"7p,7o,7n,3V,59,7m,2y,"+"4k,7l,7k,7j,2c").2R(","),q(i,o){6.11[o]=q(f){v f?9.3g(o,f):9.1t(o)}});7(6.M.3j||6.M.2a)P.46("58",6.1G,N);B 7(6.M.1h){P.7i("<7h"+"7f 2m=3T 7e=K "+"2u=//:><\\/3b>");u a=P.4d("3T");7(a)a.7d=q(){7(9.3a!="1n")v;6.1G()};a=H}B 7(6.M.20)6.3N=3m(q(){7(P.3a=="79"||P.3a=="1n"){3M(6.3N);6.3N=H;6.1G()}},10);6.S.1A(18,"1W",6.1G)};7(6.M.1h)6(18).3Z("3z",q(){u a=6.S.Y;E(u b V a){u c=a[b],i=c.C;7(i&&b!=\'3z\')77 c[i-1]&&6.S.1E(c[i-1],b);1v(--i)}});6.11.1c({76:q(c,b,a){9.1W(c,b,a,1)},1W:q(g,d,c,e){7(6.16(g))v 9.3g("1W",g);c=c||q(){};u f="3K";7(d)7(6.16(d)){c=d;d=H}B{d=6.2E(d);f="50"}u h=9;6.2Z({1C:g,G:f,W:d,2t:e,1n:q(a,b){7(b=="28"||!e&&b=="4V")h.1b("27",a.3c).3J().F(c,[a.3c,b,a]);B c.O(h,[a.3c,b,a])}});v 9},72:q(){v 6.2E(9)},3J:q(){v 9.1L("3b").F(q(){7(9.2u)6.4U(9.2u);B 6.3I(9.2A||9.5H||9.27||"")}).2U()}});6.F("4T,4I,4S,4R,4Q,4P".2R(","),q(i,o){6.11[o]=q(f){v 9.3g(o,f)}});6.1c({1M:q(e,c,a,d,b){7(6.16(c)){a=c;c=H}v 6.2Z({G:"3K",1C:e,W:c,28:a,3G:d,2t:b})},6X:q(d,b,a,c){v 6.1M(d,b,a,c,1)},4U:q(b,a){v 6.1M(b,H,a,"3b")},6V:q(c,b,a){v 6.1M(c,b,a,"4N")},6T:q(d,b,a,c){7(6.16(b)){a=b;b={}}v 6.2Z({G:"50",1C:d,W:b,28:a,3G:c})},6S:q(a){6.36.21=a},6Q:q(a){6.1c(6.36,a)},36:{Y:K,G:"3K",21:0,4O:"6P/x-6O-34-6N",4K:K,38:K,W:H},32:{},2Z:q(s){s=6.1c({},6.36,s);7(s.W){7(s.4K&&1g s.W!="1s")s.W=6.2E(s.W);7(s.G.2H()=="1M"){s.1C+=((s.1C.J("?")>-1)?"&":"?")+s.W;s.W=H}}7(s.Y&&!6.3L++)6.S.1t("4T");u f=N;u h=18.4Z?14 4Z("6L.6J"):14 4J();h.7b(s.G,s.1C,s.38);7(s.W)h.3Q("6H-6G",s.4O);7(s.2t)h.3Q("6F-3O-6D",6.32[s.1C]||"6C, 6B 6A 6z 4r:4r:4r 6y");h.3Q("X-6x-6w","4J");7(s.56)s.56(h);7(s.Y)6.S.1t("4P",[h,s]);u g=q(d){7(h&&(h.3a==4||d=="21")){f=K;7(i){3M(i);i=H}u c;2g{c=6.54(h)&&d!="21"?s.2t&&6.4F(h,s.1C)?"4V":"28":"2c";7(c!="2c"){u b;2g{b=h.3E("53-3O")}25(e){}7(s.2t&&b)6.32[s.1C]=b;u a=6.55(h,s.3G);7(s.28)s.28(a,c);7(s.Y)6.S.1t("4Q",[h,s])}B 6.2X(s,h,c)}25(e){c="2c";6.2X(s,h,c,e)}7(s.Y)6.S.1t("4S",[h,s]);7(s.Y&&!--6.3L)6.S.1t("4I");7(s.1n)s.1n(h,c);7(s.38)h=H}};u i=3m(g,13);7(s.21>0)4C(q(){7(h){h.6r();7(!f)g("21")}},s.21);2g{h.6p(s.W)}25(e){6.2X(s,h,H,e)}7(!s.38)g();v h},2X:q(s,a,b,e){7(s.2c)s.2c(a,b,e);7(s.Y)6.S.1t("4R",[a,s,e])},3L:0,54:q(r){2g{v!r.23&&7A.7B=="4l:"||(r.23>=5u&&r.23<6l)||r.23==5m||6.M.20&&r.23==I}25(e){}v N},4F:q(a,c){2g{u b=a.3E("53-3O");v a.23==5m||b==6.32[c]||6.M.20&&a.23==I}25(e){}v N},55:q(r,b){u c=r.3E("6k-G");u a=!b&&c&&c.J("4B")>=0;a=b=="4B"||a?r.6j:r.3c;7(b=="3b")6.3I(a);7(b=="4N")a=49("("+a+")");7(b=="3F")6("<1x>").3F(a).3J();v a},2E:q(a){u s=[];7(a.15==2b||a.3C)6.F(a,q(){s.R(2l(9.6i)+"="+2l(9.2v))});B E(u j V a)7(a[j]&&a[j].15==2b)6.F(a[j],q(){s.R(2l(j)+"="+2l(9))});B s.R(2l(j)+"="+2l(a[j]));v s.5M("&")},3I:q(a){7(18.4A)18.4A(a);B 7(6.M.20)18.4C(a,0);B 49.3D(18,a)}});6.11.1c({1m:q(b,a){v b?9.1w({1u:"1m",29:"1m",1e:"1m"},b,a):9.1i(":1y").F(q(){9.T.U=9.2i?9.2i:"";7(6.1f(9,"U")=="1P")9.T.U="2h"}).2U()},1j:q(b,a){v b?9.1w({1u:"1j",29:"1j",1e:"1j"},b,a):9.1i(":4f").F(q(){9.2i=9.2i||6.1f(9,"U");7(9.2i=="1P")9.2i="2h";9.T.U="1P"}).2U()},5G:6.11.1R,1R:q(a,b){v 6.16(a)&&6.16(b)?9.5G(a,b):a?9.1w({1u:"1R",29:"1R",1e:"1R"},a,b):9.F(q(){6(9)[6(9).37(":1y")?"1m":"1j"]()})},6e:q(b,a){v 9.1w({1u:"1m"},b,a)},6d:q(b,a){v 9.1w({1u:"1j"},b,a)},6b:q(b,a){v 9.1w({1u:"1R"},b,a)},6a:q(b,a){v 9.1w({1e:"1m"},b,a)},69:q(b,a){v 9.1w({1e:"1j"},b,a)},7U:q(c,a,b){v 9.1w({1e:a},c,b)},1w:q(d,h,f,g){v 9.1l(q(){u c=6(9).37(":1y"),1H=6.5z(h,f,g),5y=9;E(u p V d){7(d[p]=="1j"&&c||d[p]=="1m"&&!c)v 6.16(1H.1n)&&1H.1n.O(9);7(p=="1u"||p=="29"){1H.U=6.1f(9,"U");1H.2f=9.T.2f}}7(1H.2f!=H)9.T.2f="1y";9.2k=6.1c({},d);6.F(d,q(a,b){u e=14 6.2M(5y,1H,a);7(b.15==3y)e.2W(e.1K(),b);B e[b=="1R"?c?"1m":"1j":b](d)})})},1l:q(a,b){7(!b){b=a;a="2M"}v 9.F(q(){7(!9.1l)9.1l={};7(!9.1l[a])9.1l[a]=[];9.1l[a].R(b);7(9.1l[a].C==1)b.O(9)})}});6.1c({5z:q(b,a,c){u d=b&&b.15==64?b:{1n:c||!c&&a||6.16(b)&&b,1B:b,2I:c&&a||a&&a.15!=45&&a||(6.2I.4i?"4i":"4w")};d.1B=(d.1B&&d.1B.15==3y?d.1B:{61:60,89:5u}[d.1B])||8a;d.2N=d.1n;d.1n=q(){6.5I(9,"2M");7(6.16(d.2N))d.2N.O(9)};v d},2I:{4w:q(p,n,b,a){v b+a*p},4i:q(p,n,b,a){v((-5W.5X(p*5W.8e)/2)+0.5)*a+b}},1l:{},5I:q(b,a){a=a||"2M";7(b.1l&&b.1l[a]){b.1l[a].4e();u f=b.1l[a][0];7(f)f.O(b)}},3B:[],2M:q(f,e,g){u z=9;u y=f.T;z.a=q(){7(e.3A)e.3A.O(f,[z.2o]);7(g=="1e")6.1b(y,"1e",z.2o);B{y[g]=8m(z.2o)+"4o";y.U="2h"}};z.5V=q(){v 39(6.1f(f,g))};z.1K=q(){u r=39(6.2z(f,g));v r&&r>-8l?r:z.5V()};z.2W=q(c,b){z.4s=(14 5T()).5S();z.2o=c;z.a();6.3B.R(q(){v z.3A(c,b)});7(6.3B.C==1){u d=3m(q(){u a=6.3B;E(u i=0;i<a.C;i++)7(!a[i]())a.8j(i--,1);7(!a.C)3M(d)},13)}};z.1m=q(){7(!f.24)f.24={};f.24[g]=6.1b(f.T,g);e.1m=K;z.2W(0,9.1K());7(g!="1e")y[g]="8q";6(f).1m()};z.1j=q(){7(!f.24)f.24={};f.24[g]=6.1b(f.T,g);e.1j=K;z.2W(9.1K(),0)};z.3A=q(a,c){u t=(14 5T()).5S();7(t>e.1B+z.4s){z.2o=c;z.a();7(f.2k)f.2k[g]=K;u b=K;E(u i V f.2k)7(f.2k[i]!==K)b=N;7(b){7(e.U!=H){y.2f=e.2f;y.U=e.U;7(6.1f(f,"U")=="1P")y.U="2h"}7(e.1j)y.U="1P";7(e.1j||e.1m)E(u p V f.2k)6.1b(y,p,f.24[p])}7(b&&6.16(e.1n))e.1n.O(f);v N}B{u n=t-9.4s;u p=n/e.1B;z.2o=6.2I[e.2I](p,n,a,(c-a),e.1B);z.a()}v K}}})}',62,524,'||||||jQuery|if||this|||||||||||||||||function||||var|return||||||else|length||for|each|type|null|undefined|indexOf|true|parentNode|browser|false|apply|document|nodeName|push|event|style|display|in|data||global|||fn|className||new|constructor|isFunction|test|window|arguments|events|attr|extend|firstChild|opacity|css|typeof|msie|filter|hide|mergeNum|queue|show|complete|replace|handle|nodeType|table|string|trigger|height|while|animate|div|hidden|tbody|add|duration|url|toUpperCase|remove|break|ready|opt|_|button|cur|find|get|tb|target|none|guid|toggle|args|merge|styleFloat|exec|load|nextSibling|ret|pushStack|safari|timeout|nth|status|orig|catch|al|innerHTML|success|width|opera|Array|error|preventDefault|rl|overflow|try|block|oldblock|done|curAnim|encodeURIComponent|id||now|index|readyList|disabled|grep|ifModified|src|value|inArray|multiFilter|select|curCSS|text|checked|trim|stopPropagation|param|domManip|substr|toLowerCase|easing|chars|insertBefore|makeArray|fx|old|ownerDocument|last|first|split|childNodes|selected|end|tr|custom|handleError|empty|ajax|el|clean|lastModified|String|form|getAttribute|ajaxSettings|is|async|parseFloat|readyState|script|responseText|isReady|append|defaultView|bind|which|position|mozilla|map|delete|setInterval|static|RegExp|oWidth|removeChild|oHeight|cloneNode|match|child|toString|has|float|Number|unload|step|timers|jquery|call|getResponseHeader|html|dataType|props|globalEval|evalScripts|GET|active|clearInterval|safariTimer|Modified|num|setRequestHeader|getElementsByTagName|currentStyle|__ie_init|unbind|mouseover|handleHover|init|getComputedStyle|one|isXMLDoc|relatedTarget|fix|removeEventListener|handler|Function|addEventListener|triggered|visibility|eval|nodeIndex|radio|classFilter|getElementById|shift|visible|appendChild|documentElement|swing|fromElement|submit|file|swap|expr|px|parents|sibling|00|startTime|previousSibling|lastToggle|parent|linear|ol|body|tagName|execScript|xml|setTimeout|version|setArray|httpNotModified|fl|prop|ajaxStop|XMLHttpRequest|processData|alpha|href|json|contentType|ajaxSend|ajaxSuccess|ajaxError|ajaxComplete|ajaxStart|getScript|notmodified|colgroup|100|not|ActiveXObject|POST|slice|fieldset|Last|httpSuccess|httpData|beforeSend|getPropertyValue|DOMContentLoaded|mouseout|click|ctrlKey|metaKey|keyCode|charCode|scrollTop|unique|createElement|scrollLeft|clientX|pageX|webkit|304|srcElement|appendTo|unshift|prevObject|on|after|before|200|removeAttr|prepend|cssFloat|self|speed|parse|input|reset|image|password|checkbox|_toggle|textContent|dequeue|lastChild|odd|even|join|contains|gt|lt|eq|nodeValue|getTime|Date|zoom|max|Math|cos|font|maxLength|600|slow|maxlength|readOnly|Object|readonly|createTextNode|class|htmlFor|fadeOut|fadeIn|slideToggle|CSS1Compat|slideUp|slideDown|compatMode|boxModel|compatible|name|responseXML|content|300|ie|noConflict|ra|send|it|abort|rv|userAgent|navigator|concat|With|Requested|GMT|1970|Jan|01|Thu|Since|reverse|If|Type|Content|array|XMLHTTP|ig|Microsoft|NaN|urlencoded|www|application|ajaxSetup|val|ajaxTimeout|post|setAttribute|getJSON|getAttributeNode|getIfModified|method|FORM|action|options|serialize|col|th|td|loadIfModified|do|colg|loaded|tfoot|open|thead|onreadystatechange|defer|ipt|leg|scr|write|keyup|keypress|keydown|change|mousemove|mouseup|mousedown|dblclick|scroll|resize|focus|blur|frames|hover|clone|clientY|pageY|location|protocol|toElement|clientWidth|clientHeight|cancelBubble|relative|returnValue|left|detachEvent|right|absolute|attachEvent|substring|offsetWidth|object|offsetHeight|continue|Width|border|fadeTo|padding|size|uFFFF|Left|u0128|Right|Bottom|textarea|Top|enabled|innerText|only|toggleClass|removeClass|fast|400|wrap|addClass|removeAttribute|PI|insertAfter|prependTo|children|line|splice|siblings|10000|parseInt|prev|next|weight|1px|prototype'.split('|'),0,{}))
<?php
/*
 * END of jquery
 */
?>

var loc = '',
cur_loc = null,
event_count,
event_time,
has_started = false,
scriptid,
getstr = '',
standalone = false,
warned_nocookies = false,
seq = 0,
am_mouse_x,
am_mouse_y,
am_level,
am_levels,
am_checksum = 0,
am_start = 0,
am_no_close = false,
am_target_over = null,
am_target_out = null,
am_y_mod = 0,
action_history,
remote_history = 0,
action_index = null,
action_cur = null,
action_count = 0,
cur_menu_item = null,
has_formfocus = 0,
options_open,
sb_width = 0,
alive_err = 0;

function send_alive2server(firstcall, calltime, getdivs)
{
    jQuery.ajax({
        data: 'location=' + encodeURIComponent(loc + getstr) + '&scriptid='
            + (firstcall || (new Date().getTime() - calltime > 2300) ? 0
            : scriptid)
            + (getdivs && getdivs != '' ? '&getdivs='
            + encodeURIComponent(getdivs) : '')
            + (standalone ? '&standalone=true' + (firstcall ? '&title='
            + encodeURIComponent(jQuery('title').html()) : '')  : '') + '&seq='
            + seq + _arnd(),
        success: function(msg) {
            alive_err = 0;
            if (handle_response(msg)) {
                seq++;
                setTimeout('send_alive2server(false, ' + (new Date().getTime())
                + ', "")', 2000);
            }
        },
        error: function(requestOb,msg) {
            //dpdebug(alive_err + ';' + seq + ';' + msg);
            // no error handling for first call yet
            if (seq && alive_err < 3) {
                seq++;
                alive_err++;
                send_alive2server(false, (new Date().getTime()), '');
            }
        }
    });
}

function send_action2server(dpaction, cmdline)
{
    jQuery.ajax({
        data: 'location=' + loc + getstr + '&scriptid='
            + scriptid + (standalone ? '&standalone=true' : '')
            + '&seq=' + seq + _arnd()
            + '&dpaction=' + encodeURIComponent(dpaction)
            + (!cmdline ? '&menuaction=1' : '&cmdline=1')
    });
}

function _get_actions_init(event, args)
{
    if (event.pageX) {
        am_mouse_x = event.pageX;
        am_mouse_y = event.pageY;
    } else {
        am_mouse_x = event.clientX + gbody().scrollLeft - 2;
        am_mouse_y = event.clientY + gbody().scrollTop - 2;
    }
    if (am_mouse_x < 0)
        am_mouse_x = 0;
    if (am_mouse_y < 0)
        am_mouse_y = 0;

    am_checksum++;
    am_start = new Date();
}

function get_actions(id, event)
{
    var lvls = '';
    var i;

    am_level = arguments.length - 2;
    am_levels = new Array(am_level);

    for (i = 1; i <= am_level; i++) {
        lvls += '&l' + i + '=' + arguments[i + 1];
        am_levels[i - 1] = arguments[i + 1];
    }

    _get_actions_init(event);
    jQuery.ajax({
        data: 'call_object=' + encodeURIComponent(id) + '&method=getActionsMenu'
            + _arnd() + lvls + '&checksum=' + am_checksum
    });
}

function get_map_area_actions(map_name, map_area_id, id, event)
{
    var lvls = '';
    var i;

    am_level = arguments.length - 4;
    am_levels = new Array(am_level);

    for (i = 1; i <= am_level; i++) {
        lvls += '&l' + i + '=' + encodeURIComponent(arguments[i + 3]);
        am_levels[i - 1] = encodeURIComponent(arguments[i + 3]);
    }

    _get_actions_init(event);
    jQuery.ajax({
        data: 'call_object=' + encodeURIComponent(id)
            + '&method=getActionsMenu&map_name=' + encodeURIComponent(map_name)
            + '&map_area_id=' + encodeURIComponent(map_area_id) +_arnd()
            + lvls + '&checksum=' + am_checksum
    });
}


function action_over(el)
{
    var target;

    target = jQuery.trim(jQuery(el).text());
    if (target == am_target_over)
        return false;

    if (am_target_out) {
        jQuery(am_target_out).removeClass('am_selected');
        if (jQuery('span.am_icon', am_target_out).attr('id'))
            jQuery('span.am_icon > img', am_target_out).attr('src',
                jQuery('span.am_icon', am_target_out).attr('id'));
        am_target_out = cur_menu_item = null;
    }

    am_target_over = target;

    if (cur_menu_item == el)
        return false;

    cur_menu_item = el;
    if (am_level != undefined && am_levels != undefined) {
        var lvl = jQuery(el).attr('id');
        lvl = parseInt(lvl.substring(11));
        if (lvl != am_level) {
            var regExp = /<\/?[^>]+>/gi;
            if (jQuery(el).text() != am_levels[lvl].replace(regExp,"")) {
                jQuery('div[@id=action_menu' + lvl + '].am_deep_selected').
                    removeClass('am_deep_selected am_selected am_deep_selected_ghosted');
                while (jQuery('div#am_div' + (lvl + 1)).length) {
                    jQuery('div#am_div' + (lvl + 1)).remove();
                    lvl++;
                }
            }
        }
    }
    jQuery(el).addClass('am_selected');
    am_over = true;
    return true;
}

function handle_response(response)
{
    if (warned_nocookies || response == null || response == undefined) {
        return false;
    }

    if (typeof(response) == 'string') {
        if (response == '1')
            return true;
        if (response == '2') {
            warned_nocookies = true;
            document.body.innerHTML = "<h1 align=\"center\" "
                + 'style="margin-top: 100px">' + php[0] + '</h1>' + script_id
                + '/' + seq + "\n";

        }
        //alert(response);
        return false;
    }

    if (response.responseXML != null)
        response = response.responseXML;

    if (response.parseError != null
            && response.parseError.errorCode != null
            && response.parseError.errorCode != 0) {
        return false;
    }

    var response2 = response.documentElement;

    if (response2 == undefined) {
        return false;
    }

    for (var i = 0; i < response2.childNodes.length ; i++) {
        //if (jQuery(response2.childNodes[i]).text() != '')
            handle_dp_element(response2.childNodes[i]);
    }
    if (window.init_drag)
        init_drag();
    return true;
}

function handle_dp_element(el)
{
    switch(el.tagName) {
    case 'event':
        var e_count = parseInt(jQuery(el).attr('count'));
        var e_time = parseInt(jQuery(el).attr('time'));

        if (event_count != null && event_count != -1
                && event_count >= e_count) {
            /*
            alert(php[1] + ' ' + event_count
                 + ' (received ' + e_count + '): ' + jQuery(el).text());
            window.location.reload();
            */
            return;
        } else
            event_count = e_count;

        if (event_time != null && event_time != -1  && event_time > e_time) {
            /*
            alert(php[2] + ' ' + event_time
                + ' (received ' + e_count + '): ' + jQuery(el).text());
            window.location.reload();
            */
            return;
        } else
            event_time = e_time;
        for (var j = 0; j < el.childNodes.length; j++)
            handle_dp_event(el.childNodes[j]);
        break;
    case 'location':
        this.location = jQuery(el).text();
        return;
    default:
        /*
        if (jQuery(el).text() != '1')
            alert(php[3] + el.tagName);
        */
        break;
    }
}

function handle_dp_event(el)
{
    var id = jQuery(el).attr('id');

    switch(el.tagName) {
    case 'location':
        this.location = jQuery(el).text();
        return;
    case 'div':
        var tmp = jQuery(el).text();

        var pos = tmp.indexOf('<div id="dppage">');
        if (pos != -1 && pos == 0) {
            tmp = tmp.substring(pos + 17);
            tmp = tmp.substring(0,tmp.length - 6);
        }
        var newdiv = jQuery(document.createElement("DIV")).attr('id', id).
            html(tmp).css('zIndex', 5);
        var olddiv = jQuery('#' + id);

        if (!olddiv.length) {
            jQuery(document.body).append(newdiv);
            if (id == 'dpinventory' || id == 'dpmessagearea')
                newdiv.css('position', 'relative');
        } else {
            zindex = olddiv.css('z-index');
            olddiv.css('display', 'none').before(newdiv).remove();
            if (!isNaN(zindex))
                newdiv.css('z-index', zindex);
        }
        if ('dpinput_wrap' == id && jQuery('#dpinput[input]').length)
            gototop(null,true);
        break;
    case 'message':
        var message;
        var messagediv;
        message = jQuery(el).text();
        if (message == 'close_window') {
            this.location = php[4] + 'dpmultiplewindows.txt';
            return;
        }
        messagediv = _gel('messages');
        if (!jQuery('.dpmsgtop').length)
            jQuery(messagediv).before('<div class="dpmsgtop">&nbsp;</div>');
        jQuery(messagediv).css('display', 'block');

        if (messagediv.childNodes.length == 16)
            jQuery(messagediv).css({ height: jQuery(messagediv).height() + 'px',
                overflow: 'auto' });

        while (messagediv.childNodes.length >= 500)
            messagediv.removeChild(messagediv.childNodes[0]);

        jQuery(messagediv).append('<div id="message">' + message + '</div>');
        messagediv.scrollTop = messagediv.scrollHeight;
        break;
    case 'actions':
        make_amenu(el);
        break;
    case 'script':
        if (jQuery(el).attr('src')) {
            jQuery.getScript(jQuery(el).attr('src'));
            break;
        }

        var tag = document.createElement("script");
        tag.setAttribute('type','text/javascript');

        if (el.textContent)
            tag.textContent = el.textContent;
        else if (el.text)
            tag.text = el.text;

        document.getElementsByTagName('head')[0].appendChild(tag);
        break;
    case 'stylesheet':
        jQuery(document.createElement('link')).attr({ type: 'text/css', rel:
            'stylesheet', href: jQuery(el).attr('href'), media: 'screen' }).
            appendTo(jQuery('head'));
        break;
    case 'removeDpElement':
        jQuery('#' + id).remove();
        update_dpinv();
        break;
    case 'addDpElement':
        if (jQuery('#' + jQuery(el).attr('where')).length) {
            jQuery('#' + id).remove();
            jQuery(document.createElement("div")).attr('id', jQuery(el).
                attr('id')).addClass(jQuery(el).attr('class')).html(jQuery(el).
                text()).appendTo(jQuery('#' + jQuery(el).attr('where')));
            update_dpinv();
        }
        break;
    case 'changeDpElement':
        var t = 'div[@id=' + id + ']';
        jQuery('dppage_body' == id ? t : t + '[@class^=title_img],' + t
            + '[@class^=dpobject]').html(jQuery(el).text());
        break;
    case 'moveDpElement':
        jQuery('#' + id).removeClass().remove().clone().attr('id', id).
            addClass(jQuery(el).attr('class')).appendTo(jQuery('#' + jQuery(el).
            attr('where')));
        break;
    case 'window':
        var delay = jQuery(el).attr('delay');
        if (!delay)
            make_dpwindow(jQuery(el).text(), jQuery(el).attr('autoclose'),
                jQuery(el).attr('styleclass'), jQuery(el).attr('name'));
        else
            setTimeout('make_dpwindow(' + check_val(jQuery(el).text()) + ', '
                + check_val(jQuery(el).attr('autoclose')) + ', '
                + check_val(jQuery(el).attr('styleclass')) + ', '
                + check_val(jQuery(el).attr('name')) + ')', parseInt(delay));
        break;
    case 'refreshDpWindow':
        if (jQuery('div[@name=' + jQuery(el).attr('name') + ']').length)
            send_action2server(jQuery(el).attr('action'));
        break;
    case 'reportmove':
        var ob = jQuery('#' + id).get(0);
        jQuery(ob).
            css({
                left: ob.offsetLeft + 'px',
                top: ob.offsetTop + 'px'
            }).
            animate({
                left: parseInt(jQuery(el).attr('left')),
                top: parseInt(jQuery(el).attr('top'))
            });
        break;
    case 'history':
        insert_history(el);
        break;
    default:
        break;
        /*
        if (jQuery(el).text() != '1')
            alert(php[5] + el.tagName);
        */
    }
}

function check_val(val)
{
    return undefined == val || null == val ? val
        : "'" + val.replace(/\'/g,'\\\'') + "'";
}

function update_dpinv()
{
    if (jQuery('div[@id=dpobinv]/div[@id=dpobinv_inner1]').length)
        send_action2server(php[6]);
}

function wheight()
{
    return (jQuery.browser.mozilla || jQuery.browser.opera)
        && dwidth() > self.innerWidth ? self.innerHeight - sbwidth()
        : self.innerHeight || jQuery.boxModel
        && document.documentElement.clientHeight || document.body.clientHeight;
}

function dheight()
{
    Math.max(document.body.scrollHeight, document.body.offsetHeight);
}

function wwidth()
{
    return (jQuery.browser.mozilla || jQuery.browser.opera)
        && dheight() > self.innerHeight ? self.innerWidth - sbwidth()
        : self.innerWidth || jQuery.boxModel
        && document.documentElement.clientWidth || document.body.clientWidth;
}

function dwidth()
{
    if (!jQuery.browser.mozilla)
        return Math.max(document.body.scrollWidth, document.body.offsetWidth);
    var l = self.pageXOffset;
    self.scrollTo(9999999, self.pageYOffset);
    var w = self.pageXOffset;
    self.scrollTo(l, self.pageYOffset);
    return document.body.offsetWidth + w;
}

function sbwidth()
{
    if (sb_width)
        return sb_width;
    var el = jQuery('<div>').css({ width: 100, height: 100, overflow: 'auto',
        position: 'absolute', top: -999, left: -999 }).appendTo('body');
    sb_width = 100 - el.append('<div>').find('div').css({ width: '100%',
        height: 200 }).width();
    el.remove();
    return sb_width;
};

function scrtop()
{
    return jQuery.boxModel && document.documentElement.scrollTop ||
        document.body.scrollTop;
}

function make_amenu(el)
{
    var level = parseInt(jQuery(el).attr('level'));
    if (am_checksum !== parseInt(jQuery(el).attr('checksum'))
            || jQuery('div#action_menu' + level).length)
        return;

    var div_id = 'div[@id=action_menu' + (level - 1) + ']';
    var indx = jQuery(div_id + '.am').index(jQuery(div_id
        + '.am_deep_selected')[0]);
    if (level && indx == -1)
        return;

    var e = jQuery('div#am_div' + (level - 1));
    var lft = !level ? (am_mouse_x-11) : parseInt(e.css('left')) + e.width()
        - 5;
    var tp = !level ? (am_mouse_y-13) : parseInt(e.css('top')) + (16 * indx);
    var amenu = jQuery(document.createElement("div")).attr('id', 'am_div'
        + level). addClass('actionwindow').html(jQuery(el).text()).
        css({ left: '0px', top: '0px' });
    amenu.appendTo(document.body);
    if (!(jQuery.browser.msie && 6 == parseInt(jQuery.browser.version)))
        jQuery('div.am').css('height', (parseInt(jQuery('div.am').
            css('line-height'))) + 'px');
    var zindex = parseInt(amenu.css('z-index'));
    amenu.css({ zIndex: -1, visiblity: 'hidden', display: 'block'});
    var w = amenu.get(0).clientWidth;

    var highlight = 'first';
    if (tp + (6 + 16 * jQuery('.am', amenu).length) - scrtop() > wheight()) {
        tp = tp - (6 + 16 * jQuery('.am', amenu).length) + 22 + am_y_mod;
        am_y_mod = 0;
        highlight = 'last';
    }
    var w2 = !level ? '-' : e.get(0).clientWidth;
    if (w < 96)
        w = 96;
    else if (w > 200)
        w = 200;
    if (lft + w + 15 > wwidth()) {
        lft = lft - w - (!level ? -21 : w2 - 5);
    }
    amenu.css({
        display: 'none',
        visiblity: 'visible',
        zIndex: (isNaN(zindex) ? 7 : zindex),
        width: w + 'px',
        left: lft + 'px',
        top: tp + 'px'
    });
    var stop = new Date();
    var menu_delay = 350 - (stop.getTime() - am_start.getTime());

    if (jQuery.browser.msie) {
        msie_opacfix(amenu);
        if (6 == parseInt(jQuery.browser.version))
            jQuery('span.am_submenu').remove();
    }

    if (!level) {
        e = jQuery('div[@class=actionwindow_inner]/div:' + highlight,
            amenu);
        var e2 = jQuery('div[@class=actionwindow_inner]/div[@class=am]:'
            + highlight, amenu);
        if (e2.length && e.get(0) == e2.get(0)) {
            jQuery(e).addClass('am_selected');
            am_target_out = e;
        } else
           jQuery(e).addClass('am_deep_selected');
        if (jQuery('span.am_icon', e).length
                && jQuery('span.am_icon', e).attr('id'))
            jQuery('span.am_icon > img', e).attr('src',
                jQuery('span.am_icon', e).attr('id'));
    }

    if (!level || menu_delay <= 0) {
        amenu.css('display', 'block');
    } else
        setTimeout("jQuery('div#am_div" + level + "').css('display', 'block')",
            menu_delay);
}

function open_options(ob,e)
{
    if (options_open)
        return;
    close_amenu();
    options_open = true;
    _get_actions_init(e);
    am_mouse_x = getleft(ob) + 3 + (jQuery.browser.msie ? 1 : 0);
    am_mouse_y = gettop(ob) + 26
        + (jQuery.browser.msie || jQuery.browser.safari ? 1 : 0);
    if (am_mouse_y + 44 - scrtop() > wheight())
        am_y_mod = -35;
    jQuery.ajax({ data: 'call_object=' + encodeURIComponent(
        jQuery('div[@id=dpinventory] div[@class=dpinventory2]').
        attr('id')) + '&method=getInputAreaOptions' + _arnd() + '&checksum='
        + am_checksum });
}

function make_dpwindow(text, autoclose, styleclass, name)
{
    close_dpwindow(styleclass == null ? null : styleclass);
    var win = document.createElement("DIV");
    var zindex = false;
    if (jQuery('#dpwindow:last').length)
        zindex = parseInt(jQuery('#dpwindow:last').css('z-index')) + 1;
    jQuery(win).attr({ id: 'dpwindow', name: (!name ? '' : name) }).
        addClass(null == styleclass ? 'dpwindow_default' : styleclass);
    if (autoclose == null)
        jQuery(win).html(text + '<p align="right" style="clear: both">'
            + '<a href="" onclick="close_dpwindow(' + (styleclass == null ? ''
            : "'" + styleclass + "'") + '); gbody().focus(); return false" '
            + 'style="cursor: pointer">'
            + php[7] + '</a></p>');
    else {
        jQuery(win).html(text);
        setTimeout('close_dpwindow(' + (styleclass == null ? ''
            : "'" + styleclass + "'") + ')', autoclose);
    }
    jQuery(jQuery('body')[0].firstChild).before(win);
    jQuery(win).css({ top: (parseInt(jQuery(win).css('top'))
        + (window.pageYOffset != null ? window.pageYOffset : gbody().scrollTop))
        + 'px' });

    if (zindex != false)
        jQuery(win).css('z-index', zindex);

    if (jQuery.browser.msie)
        msie_opacfix(jQuery(win));
    // Command line loses focus in IE. This solves most cases for now, but
    // this should not go just to 'dpaction', it could e.g. be a form field.
    focus_input();
}

function close_dpwindow(styleclass)
{
    jQuery(!styleclass ? '#dpwindow' : 'div.' + styleclass).remove();
    focus_input();
}

function msie_opacfix(el)
{
    if (el.css('-moz-opacity'))
        el.css('filter', 'alpha(opacity='
            + (el.css('-moz-opacity') * 100) + ')');
    else if (el.css('opacity') == '1') // IE6
        el.css('filter', 'alpha(opacity=95)');
}

function _gel(a)
{
    return document.getElementById(a)
}

function _rnd()
{
    return Math.round(Math.random() * 999999);
}

function _arnd()
{
    return '&ajax=' + _rnd();
}

/*
function init_drag(id, event)
{
    return;
}
*/
function stopdrag(x)
{
    jQuery.ajax({
        data: 'call_object=' + encodeURIComponent(jQuery(x).attr('id'))
            + '&method=reportMove&x=' + x.style.left + '&y=' + x.style.top
            + _arnd()
    });
}

function gototop(jump,no_focus_delay)
{
    var top = 0;
    if (jump)
        top = gettop(jQuery('a[@name=' + jump + ']').get()[0], true);
    focus_input(no_focus_delay,'self.scrollTo(0, ' + top + '); '
        + 'jQuery("#dpaction").unbind("focus")');
}

function dpdebug(str)
{
    jQuery('#dpdebug').html(jQuery('#dpdebug').html() + str + '<br />');
}


function gettop(ob, scrollfix)
{
    for (var top = 0; ob; ob = ob.offsetParent)
        top += ob.offsetTop + (scrollfix && jQuery.browser.opera ? ob.scrollTop
            : 0);
    return top;
}

function getleft(ob)
{
    for (var left = 0; ob; ob = ob.offsetParent)
        left += ob.offsetLeft + (jQuery.browser.opera ? ob.scrollLeft : 0);
    return left;
}

function action_dutchpipe()
{
    var dpaction = jQuery('#dpaction').val();

    if (dpaction == '')
        close_dpwindow();
    else {
        if (action_history == undefined)
            action_history = new Array();

        var len = action_history.length;
        if (!len || action_history[len - 1] != dpaction) {
            if (20 == len)
                action_history.shift();
            action_history.push(dpaction);
            action_count++;
        }
    }

    send_action2server(dpaction, true);
    jQuery('#dpaction').val('');
    action_index = action_cur = null;
    focus_input();
    if ('once' == jQuery('#dpinputpersistent').attr('value'))
        setTimeout("close_input()", 100);
    return false;
}

function insert_history(el)
{
    if (action_history == null) {
        if (jQuery(el).text())
            action_history = jQuery(el).text().split('@SEP@');
    } else if (action_history.length >= 20)
        return;
    else {
        var ins_history = jQuery(el).text().split('@SEP@');
        var ins_length = ins_history.length - action_count;
        if (ins_length <= 0)
            return;
        while (action_history.length < 20 && ins_length) {
            action_history.unshift(ins_history[ins_length - 1]);
            ins_length--;
        }

    }

    action_index = action_history.length - 1;
    if (action_cur == null)
        action_cur = jQuery('#dpaction').val();
    jQuery('#dpaction').val(action_history[action_index]);
}

function bindKeyDown(e)
{
    var keynum = e.which ? e.which : e.keyCode;

    if (38 != keynum && 40 != keynum)
        return true;
    if (remote_history == 0) {
        remote_history = 1;
        jQuery.ajax({
            data: 'location=' + loc + getstr + '&scriptid=' + scriptid
            + (standalone ? '&standalone=true' : '') + '&seq=' + seq + _arnd()
            + '&gethistory=1'
        });
     }

     if (action_history == null || action_history.length == 0)
        return false;

     if (action_cur == null)
        action_cur = jQuery('#dpaction').val();

     if (38 == keynum) {
        if (action_index == null || action_index > 0) {
            if (action_index == null) {
                action_index = action_history.length - 1;
                action_cur = jQuery('#dpaction').val();
            }
            else
                action_index--;
            jQuery('#dpaction').val(action_history[action_index]);
        }
    }
    else {
        if (action_index != null) {
            if (action_index == action_history.length - 1) {
                jQuery('#dpaction').val(action_cur);
                action_index = action_cur = null;
            } else
                jQuery('#dpaction').val(action_history[++action_index]);
        }
    }

    return false;
}

function gbody()
{
    return document.compatMode && document.compatMode != 'BackCompat'
        ? document.documentElement : document.body;
}

function focus_input(no_delay,gototop)
{
    if (!jQuery('input:not(#dpaction)').length)
        has_formfocus = 0;
    if (!has_formfocus && jQuery('#dpinput[input]').length) {
        if (gototop)
            jQuery('#dpaction').bind('focus', {src: gototop},
                function(event){setTimeout(event.data.src,
                jQuery.browser.msie ? 175 : 0 )});
        var src = "if (!has_formfocus && jQuery('#dpinput[input]').length) { "
            + "gbody().focus();\ jQuery('#dpaction')[0].focus() }";
        if (no_delay) {
            if (jQuery.browser.msie)
                setTimeout(src, 10);
            else
                eval(src);
        }
        else
            setTimeout(src, 100);
    }
}

function show_input(act)
{
    if (jQuery('#dpinput input').length) {
        if ('string' == typeof act)
            _gel('dpaction').value = act;
        return true;
    }
    if ('object' == typeof act && 9 != (act.which ? act.which : act.keyCode))
        return true;

    var tmp = jQuery('#dpinput').html();
    jQuery('#dpinput').html(jQuery('#dpinput_say').html());
    jQuery('#dpinput_say').html(tmp);
    jQuery('#dpaction').bind('keydown', bindKeyDown);
    focus_input();
    jQuery(gbody()).unbind('keydown', show_input);
    if ('string' == typeof act)
        _gel('dpaction').value = act;
    jQuery.ajax({
        data: 'call_object='
            + encodeURIComponent(jQuery(
            'div[@id=dpinventory] div[@class=dpinventory2]').attr('id'))
            + '&method=openInputArea' + _arnd()
    });
    return false;
}

function close_input()
{
    jQuery.ajax({
        data: 'call_object='
            + encodeURIComponent(jQuery(
            'div[@id=dpinventory] div[@class=dpinventory2]').attr('id'))
            + '&method=closeInputArea' + _arnd()
    });
    var tmp = jQuery('#dpinput').html();
    jQuery('#dpinput').html(jQuery('#dpinput_say').html());
    jQuery('#dpinput_say').html(tmp);
    bind_input();
}

function bind_input()
{
    if (!jQuery('#dpinput[input]').length)
        jQuery(gbody()).bind('keydown', show_input);

    jQuery('input:not(#dpaction),a').focus(function() {
        if (!jQuery('#dpinput[input]').length)
            jQuery(gbody()).unbind('keydown', show_input);
        has_formfocus++;
    });
    var t = "if (!has_formfocus && !jQuery('#dpinput[input]').length) "
        + "jQuery(gbody()).bind('keydown', show_input)";
    jQuery('input:not(#dpaction),a').bind('blur', function() {
        has_formfocus--;
        setTimeout(t, 100);
    });
    jQuery('form:not(#actionform)').submit(function() {
        has_formfocus = 0;
        setTimeout("gbody().focus(); " + t, 100);
    });
}

function close_amenu()
{
    if (jQuery('div.actionwindow').remove().length) {
        am_target_over = am_target_out = options_open = null;
        focus_input();
    }
}

jQuery(function() {
    jQuery(gbody()).click(function() {
        if (am_no_close) {
            am_no_close = false;
            return;
        }
        close_amenu();
    });

    var _loc = location.href;
    var pos = _loc.indexOf('#');
    var jump;

    if (pos != -1) {
        if (_loc.length > pos + 1)
            jump = _loc.substring(pos + 1);
        _loc = _loc.substring(0, pos);
    }
    var dphost_url = php[8];
    var dpclient_fn = php[9];

    if (_loc != dphost_url && _loc != dphost_url + dpclient_fn) {
        pos = _loc.indexOf(dphost_url);
        if (pos == 0)
            _loc = _loc.substring(dphost_url.length);
        else
            return;

        loc = location.href;
        pos = _loc.indexOf(dpclient_fn);
        if (pos != -1) {
            _loc = _loc.substring(pos + dpclient_fn.length);
            pos = _loc.indexOf('?location=');
            if (pos != -1) {
                _loc = _loc.substring(pos + 10);
                pos = _loc.indexOf('&');
                if (pos != -1) {
                    getstr = _loc.substring(pos);
                    _loc = _loc.substring(0, pos);
                }
                loc = _loc;
            }
        }
        if (loc == location.href)
            standalone = true;
    }

    jQuery.ajaxSetup({
        url: php[4] + dpclient_fn,
        success: handle_response
    });

    var ob = new Date();
    var curtime = ob.getTime();
    scriptid = _rnd();
    if (window.dp_load_elements)
        dp_load_elements();

    var getdivs = '';
    if (standalone) {
        if (!jQuery('#dpinventory').length || jQuery('#dpinventory').html()
            == '')
            getdivs += 'dpinventory#';
        if (!jQuery('#dpmessagearea').length || jQuery('#dpmessagearea').html()
            == '')
            getdivs += 'dpmessagearea#';
        if (!jQuery('#dpinput_wrap').length
                || jQuery('#dpinput_wrap').html() == '')
            getdivs += 'dpinput_wrap#';
        if (!jQuery('#dploginout').length || jQuery('#dploginout').html() == '')
            getdivs += 'dploginout#';
    }
    if (getdivs != '')
        send_alive2server(true, curtime, getdivs);
    else {
        if (standalone)
            send_alive2server(true, curtime, '');
        else {
            setTimeout('send_alive2server(true, '+curtime+', "")',
            (!has_started ? 10 : 2000));
        }
        if (jQuery('#dpinput[input]').length)
            gototop(jump,true);
        has_started = true;
    }
    if (typeof history.navigationMode != 'undefined')
        history.navigationMode = 'compatible';
    jQuery('#dpaction').bind('keydown', bindKeyDown);
    bind_input();
});
