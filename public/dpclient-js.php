<?php
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
 * @subpackage public
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id$
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpclient.php
 */

/**
 * Gets server settings
 */
require_once(realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/..')
    . '/config/dpserver-ini.php');

error_reporting(DPSERVER_ERROR_REPORTING);
?>
/* jquery (packed) */
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('7(1C 1w.6=="T"){1w.T=1w.T;B 6=u(a,c){7(1w==q)v 1p 6(a,c);a=a||17;7(6.1t(a))v 1p 6(17)[6.E.27?"27":"2O"](a);7(1C a=="23"){B m=/^[^<]*(<(.|\\s)+>)[^>]*$/.2Q(a);7(m)a=6.3k([m[1]]);J v 1p 6(c).2o(a)}v q.6r(a.1l==2y&&a||(a.3Y||a.I&&a!=1w&&!a.24&&a[0]!=T&&a[0].24)&&6.3M(a)||[a])};7(1C $!="T")6.2S$=$;B $=6;6.E=6.8p={3Y:"1.1.2",8q:u(){v q.I},I:0,2b:u(1T){v 1T==T?6.3M(q):q[1T]},2r:u(a){B L=6(a);L.6p=q;v L},6r:u(a){q.I=0;[].1g.14(q,a);v q},K:u(E,1E){v 6.K(q,E,1E)},2h:u(1c){B 4c=-1;q.K(u(i){7(q==1c)4c=i});v 4c},1I:u(1Y,O,C){B 1c=1Y;7(1Y.1l==3t)7(O==T)v q.I&&6[C||"1I"](q[0],1Y)||T;J{1c={};1c[1Y]=O}v q.K(u(2h){P(B H 1x 1c)6.1I(C?q.1q:q,H,6.H(q,1c[H],C,2h,H))})},1m:u(1Y,O){v q.1I(1Y,O,"30")},2L:u(e){7(1C e=="23")v q.3u().3r(17.8t(e));B t="";6.K(e||q,u(){6.K(q.2I,u(){7(q.24!=8)t+=q.24!=1?q.60:6.E.2L([q])})});v t},2K:u(){B a=6.3k(1A);v q.K(u(){B b=a[0].3l(U);q.11.2X(b,q);22(b.1b)b=b.1b;b.4C(q)})},3r:u(){v q.3j(1A,U,1,u(a){q.4C(a)})},5i:u(){v q.3j(1A,U,-1,u(a){q.2X(a,q.1b)})},5j:u(){v q.3j(1A,12,1,u(a){q.11.2X(a,q)})},5t:u(){v q.3j(1A,12,-1,u(a){q.11.2X(a,q.2e)})},4g:u(){v q.6p||6([])},2o:u(t){v q.2r(6.31(q,u(a){v 6.2o(t,a)}),t)},4Y:u(4N){v q.2r(6.31(q,u(a){B a=a.3l(4N!=T?4N:U);a.$1H=16;v a}))},1D:u(t){v q.2r(6.1t(t)&&6.2q(q,u(2z,2h){v t.14(2z,[2h])})||6.3z(t,q))},2g:u(t){v q.2r(t.1l==3t&&6.3z(t,q,U)||6.2q(q,u(a){v(t.1l==2y||t.3Y)?6.3y(a,t)<0:a!=t}))},1M:u(t){v q.2r(6.2k(q.2b(),t.1l==3t?6(t).2b():t.I!=T&&(!t.1f||t.1f=="8v")?t:[t]))},4l:u(1s){v 1s?6.1D(1s,q).r.I>0:12},1a:u(1a){v 1a==T?(q.I?q[0].O:16):q.1I("O",1a)},4U:u(1a){v 1a==T?(q.I?q[0].2t:16):q.3u().3r(1a)},3j:u(1E,1P,3Z,E){B 4Y=q.I>1;B a=6.3k(1E);7(3Z<0)a.8w();v q.K(u(){B 1c=q;7(1P&&6.1f(q,"1P")&&6.1f(a[0],"3m"))1c=q.5J("20")[0]||q.4C(17.6n("20"));6.K(a,u(){E.14(1c,[4Y?q.3l(U):q])})})}};6.1z=6.E.1z=u(){B 1O=1A[0],a=1;7(1A.I==1){1O=q;a=0}B H;22(H=1A[a++])P(B i 1x H)1O[i]=H[i];v 1O};6.1z({8x:u(){7(6.2S$)$=6.2S$;v 6},1t:u(E){v!!E&&1C E!="23"&&!E.1f&&1C E[0]=="T"&&/u/i.1n(E+"")},4B:u(D){v D.66&&D.5I&&!D.5I.64},1f:u(D,Y){v D.1f&&D.1f.3K()==Y.3K()},K:u(1c,E,1E){7(1c.I==T)P(B i 1x 1c)E.14(1c[i],1E||[i,1c[i]]);J P(B i=0,6q=1c.I;i<6q;i++)7(E.14(1c[i],1E||[i,1c[i]])===12)3O;v 1c},H:u(D,O,C,2h,H){7(6.1t(O))O=O.3n(D,[2h]);B 6s=/z-?2h|7P-?8A|1d|58|8B-?28/i;v O&&O.1l==3Q&&C=="30"&&!6s.1n(H)?O+"4S":O},19:{1M:u(D,c){6.K(c.3o(/\\s+/),u(i,Q){7(!6.19.2V(D.19,Q))D.19+=(D.19?" ":"")+Q})},2f:u(D,c){D.19=c?6.2q(D.19.3o(/\\s+/),u(Q){v!6.19.2V(c,Q)}).6t(" "):""},2V:u(t,c){t=t.19||t;c=c.1R(/([\\.\\\\\\+\\*\\?\\[\\^\\]\\$\\(\\)\\{\\}\\=\\!\\<\\>\\|\\:])/g,"\\\\$1");v t&&1p 4v("(^|\\\\s)"+c+"(\\\\s|$)").1n(t)}},4d:u(e,o,f){P(B i 1x o){e.1q["1N"+i]=e.1q[i];e.1q[i]=o[i]}f.14(e,[]);P(B i 1x o)e.1q[i]=e.1q["1N"+i]},1m:u(e,p){7(p=="28"||p=="3V"){B 1N={},46,3P,d=["7d","8C","8D","8E"];6.K(d,u(){1N["8F"+q]=0;1N["8G"+q+"8H"]=0});6.4d(e,1N,u(){7(6.1m(e,"1h")!="1Z"){46=e.8I;3P=e.8J}J{e=6(e.3l(U)).2o(":4j").5l("2Z").4g().1m({4n:"1G",45:"8K",1h:"2D",7I:"0",8M:"0"}).5z(e.11)[0];B 3d=6.1m(e.11,"45");7(3d==""||3d=="4b")e.11.1q.45="6x";46=e.6y;3P=e.6z;7(3d==""||3d=="4b")e.11.1q.45="4b";e.11.33(e)}});v p=="28"?46:3P}v 6.30(e,p)},30:u(D,H,53){B L;7(H=="1d"&&6.W.1j)v 6.1I(D.1q,"1d");7(H=="4h"||H=="2v")H=6.W.1j?"3T":"2v";7(!53&&D.1q[H])L=D.1q[H];J 7(17.44&&17.44.4W){7(H=="2v"||H=="3T")H="4h";H=H.1R(/([A-Z])/g,"-$1").4m();B Q=17.44.4W(D,16);7(Q)L=Q.55(H);J 7(H=="1h")L="1Z";J 6.4d(D,{1h:"2D"},u(){B c=17.44.4W(q,"");L=c&&c.55(H)||""})}J 7(D.51){B 56=H.1R(/\\-(\\w)/g,u(m,c){v c.3K()});L=D.51[H]||D.51[56]}v L},3k:u(a){B r=[];6.K(a,u(i,1r){7(!1r)v;7(1r.1l==3Q)1r=1r.6C();7(1C 1r=="23"){B s=6.35(1r),1V=17.6n("1V"),2i=[];B 2K=!s.18("<1u")&&[1,"<42>","</42>"]||(!s.18("<6D")||!s.18("<20")||!s.18("<6E"))&&[1,"<1P>","</1P>"]||!s.18("<3m")&&[2,"<1P><20>","</20></1P>"]||(!s.18("<6F")||!s.18("<6G"))&&[3,"<1P><20><3m>","</3m></20></1P>"]||[0,"",""];1V.2t=2K[1]+s+2K[2];22(2K[0]--)1V=1V.1b;7(6.W.1j){7(!s.18("<1P")&&s.18("<20")<0)2i=1V.1b&&1V.1b.2I;J 7(2K[1]=="<1P>"&&s.18("<20")<0)2i=1V.2I;P(B n=2i.I-1;n>=0;--n)7(6.1f(2i[n],"20")&&!2i[n].2I.I)2i[n].11.33(2i[n])}1r=[];P(B i=0,l=1V.2I.I;i<l;i++)1r.1g(1V.2I[i])}7(1r.I===0&&!6.1f(1r,"3w"))v;7(1r[0]==T||6.1f(1r,"3w"))r.1g(1r);J r=6.2k(r,1r)});v r},1I:u(D,Y,O){B 2j=6.4B(D)?{}:{"P":"6J","6L":"19","4h":6.W.1j?"3T":"2v",2v:6.W.1j?"3T":"2v",2t:"2t",19:"19",O:"O",2W:"2W",2Z:"2Z",89:"6N",2Y:"2Y"};7(Y=="1d"&&6.W.1j&&O!=T){D.58=1;v D.1D=D.1D.1R(/4i\\([^\\)]*\\)/6O,"")+(O==1?"":"4i(1d="+O*6g+")")}J 7(Y=="1d"&&6.W.1j)v D.1D?4T(D.1D.6P(/4i\\(1d=(.*)\\)/)[1])/6g:1;7(Y=="1d"&&6.W.3h&&O==1)O=0.6R;7(2j[Y]){7(O!=T)D[2j[Y]]=O;v D[2j[Y]]}J 7(O==T&&6.W.1j&&6.1f(D,"3w")&&(Y=="81"||Y=="80"))v D.6T(Y).60;J 7(D.66){7(O!=T)D.6V(Y,O);7(6.W.1j&&/5E|3e/.1n(Y)&&!6.4B(D))v D.36(Y,2);v D.36(Y)}J{Y=Y.1R(/-([a-z])/6W,u(z,b){v b.3K()});7(O!=T)D[Y]=O;v D[Y]}},35:u(t){v t.1R(/^\\s+|\\s+$/g,"")},3M:u(a){B r=[];7(a.1l!=2y)P(B i=0,2R=a.I;i<2R;i++)r.1g(a[i]);J r=a.3N(0);v r},3y:u(b,a){P(B i=0,2R=a.I;i<2R;i++)7(a[i]==b)v i;v-1},2k:u(2u,3H){B r=[].3N.3n(2u,0);P(B i=0,5b=3H.I;i<5b;i++)7(6.3y(3H[i],r)==-1)2u.1g(3H[i]);v 2u},2q:u(1U,E,4k){7(1C E=="23")E=1p 4w("a","i","v "+E);B 1i=[];P(B i=0,2z=1U.I;i<2z;i++)7(!4k&&E(1U[i],i)||4k&&!E(1U[i],i))1i.1g(1U[i]);v 1i},31:u(1U,E){7(1C E=="23")E=1p 4w("a","v "+E);B 1i=[],r=[];P(B i=0,2z=1U.I;i<2z;i++){B 1a=E(1U[i],i);7(1a!==16&&1a!=T){7(1a.1l!=2y)1a=[1a];1i=1i.6Z(1a)}}B r=1i.I?[1i[0]]:[];5f:P(B i=1,5e=1i.I;i<5e;i++){P(B j=0;j<i;j++)7(1i[i]==r[j])5F 5f;r.1g(1i[i])}v r}});1p u(){B b=7L.71.4m();6.W={2N:/5D/.1n(b),3f:/3f/.1n(b),1j:/1j/.1n(b)&&!/3f/.1n(b),3h:/3h/.1n(b)&&!/(72|5D)/.1n(b)};6.7H=!6.W.1j||17.74=="75"};6.K({5u:"a.11",4z:"6.4z(a)",76:"6.2a(a,2,\'2e\')",7D:"6.2a(a,2,\'5s\')",78:"6.2B(a.11.1b,a)",79:"6.2B(a.1b)"},u(i,n){6.E[i]=u(a){B L=6.31(q,n);7(a&&1C a=="23")L=6.3z(a,L);v q.2r(L)}});6.K({5z:"3r",7b:"5i",2X:"5j",7e:"5t"},u(i,n){6.E[i]=u(){B a=1A;v q.K(u(){P(B j=0,2R=a.I;j<2R;j++)6(a[j])[n](q)})}});6.K({5l:u(1Y){6.1I(q,1Y,"");q.7g(1Y)},7h:u(c){6.19.1M(q,c)},7i:u(c){6.19.2f(q,c)},7k:u(c){6.19[6.19.2V(q,c)?"2f":"1M"](q,c)},2f:u(a){7(!a||6.1D(a,[q]).r.I)q.11.33(q)},3u:u(){22(q.1b)q.33(q.1b)}},u(i,n){6.E[i]=u(){v q.K(n,1A)}});6.K(["5q","5n","5p","5v"],u(i,n){6.E[n]=u(1T,E){v q.1D(":"+n+"("+1T+")",E)}});6.K(["28","3V"],u(i,n){6.E[n]=u(h){v h==T?(q.I?6.1m(q[0],n):16):q.1m(n,h.1l==3t?h:h+"4S")}});6.1z({1s:{"":"m[2]==\'*\'||6.1f(a,m[2])","#":"a.36(\'2J\')==m[2]",":":{5n:"i<m[3]-0",5p:"i>m[3]-0",2a:"m[3]-0==i",5q:"m[3]-0==i",2u:"i==0",2T:"i==r.I-1",5R:"i%2==0",5S:"i%2","2a-3s":"6.2a(a.11.1b,m[3],\'2e\',a)==a","2u-3s":"6.2a(a.11.1b,1,\'2e\')==a","2T-3s":"6.2a(a.11.7n,1,\'5s\')==a","7p-3s":"6.2B(a.11.1b).I==1",5u:"a.1b",3u:"!a.1b",5v:"6.E.2L.14([a]).18(m[3])>=0",3i:\'a.C!="1G"&&6.1m(a,"1h")!="1Z"&&6.1m(a,"4n")!="1G"\',1G:\'a.C=="1G"||6.1m(a,"1h")=="1Z"||6.1m(a,"4n")=="1G"\',7v:"!a.2W",2W:"a.2W",2Z:"a.2Z",2Y:"a.2Y||6.1I(a,\'2Y\')",2L:"a.C==\'2L\'",4j:"a.C==\'4j\'",5x:"a.C==\'5x\'",4G:"a.C==\'4G\'",5y:"a.C==\'5y\'",4R:"a.C==\'4R\'",5A:"a.C==\'5A\'",5B:"a.C==\'5B\'",3x:\'a.C=="3x"||6.1f(a,"3x")\',5C:"/5C|42|7A|3x/i.1n(a.1f)"},".":"6.19.2V(a,m[2])","@":{"=":"z==m[4]","!=":"z!=m[4]","^=":"z&&!z.18(m[4])","$=":"z&&z.2U(z.I - m[4].I,m[4].I)==m[4]","*=":"z&&z.18(m[4])>=0","":"z",4u:u(m){v["",m[1],m[3],m[2],m[5]]},5P:"z=a[m[3]];7(!z||/5E|3e/.1n(m[3]))z=6.1I(a,m[3]);"},"[":"6.2o(m[2],a).I"},5M:[/^\\[ *(@)([a-2m-3C-]*) *([!*$^=]*) *(\'?"?)(.*?)\\4 *\\]/i,/^(\\[)\\s*(.*?(\\[.*?\\])?[^[]*?)\\s*\\]/,/^(:)([a-2m-3C-]*)\\("?\'?(.*?(\\(.*?\\))?[^(]*?)"?\'?\\)/i,/^([:.#]*)([a-2m-3C*-]*)/i],1Q:[/^(\\/?\\.\\.)/,"a.11",/^(>|\\/)/,"6.2B(a.1b)",/^(\\+)/,"6.2a(a,2,\'2e\')",/^(~)/,u(a){B s=6.2B(a.11.1b);v s.3N(6.3y(a,s)+1)}],3z:u(1s,1U,2g){B 1N,Q=[];22(1s&&1s!=1N){1N=1s;B f=6.1D(1s,1U,2g);1s=f.t.1R(/^\\s*,\\s*/,"");Q=2g?1U=f.r:6.2k(Q,f.r)}v Q},2o:u(t,1B){7(1C t!="23")v[t];7(1B&&!1B.24)1B=16;1B=1B||17;7(!t.18("//")){1B=1B.4H;t=t.2U(2,t.I)}J 7(!t.18("/")){1B=1B.4H;t=t.2U(1,t.I);7(t.18("/")>=1)t=t.2U(t.18("/"),t.I)}B L=[1B],2c=[],2T=16;22(t&&2T!=t){B r=[];2T=t;t=6.35(t).1R(/^\\/\\//i,"");B 3B=12;B 1J=/^[\\/>]\\s*([a-2m-9*-]+)/i;B m=1J.2Q(t);7(m){6.K(L,u(){P(B c=q.1b;c;c=c.2e)7(c.24==1&&(6.1f(c,m[1])||m[1]=="*"))r.1g(c)});L=r;t=t.1R(1J,"");7(t.18(" ")==0)5F;3B=U}J{P(B i=0;i<6.1Q.I;i+=2){B 1J=6.1Q[i];B m=1J.2Q(t);7(m){r=L=6.31(L,6.1t(6.1Q[i+1])?6.1Q[i+1]:u(a){v 40(6.1Q[i+1])});t=6.35(t.1R(1J,""));3B=U;3O}}}7(t&&!3B){7(!t.18(",")){7(L[0]==1B)L.4L();6.2k(2c,L);r=L=[1B];t=" "+t.2U(1,t.I)}J{B 34=/^([a-2m-3C-]+)(#)([a-2m-9\\\\*2S-]*)/i;B m=34.2Q(t);7(m){m=[0,m[2],m[3],m[1]]}J{34=/^([#.]?)([a-2m-9\\\\*2S-]*)/i;m=34.2Q(t)}7(m[1]=="#"&&L[L.I-1].4X){B 2l=L[L.I-1].4X(m[2]);7(6.W.1j&&2l&&2l.2J!=m[2])2l=6(\'[@2J="\'+m[2]+\'"]\',L[L.I-1])[0];L=r=2l&&(!m[3]||6.1f(2l,m[3]))?[2l]:[]}J{7(m[1]==".")B 4r=1p 4v("(^|\\\\s)"+m[2]+"(\\\\s|$)");6.K(L,u(){B 3E=m[1]!=""||m[0]==""?"*":m[2];7(6.1f(q,"7J")&&3E=="*")3E="3g";6.2k(r,m[1]!=""&&L.I!=1?6.4x(q,[],m[1],m[2],4r):q.5J(3E))});7(m[1]=="."&&L.I==1)r=6.2q(r,u(e){v 4r.1n(e.19)});7(m[1]=="#"&&L.I==1){B 5K=r;r=[];6.K(5K,u(){7(q.36("2J")==m[2]){r=[q];v 12}})}L=r}t=t.1R(34,"")}}7(t){B 1a=6.1D(t,r);L=r=1a.r;t=6.35(1a.t)}}7(L&&L[0]==1B)L.4L();6.2k(2c,L);v 2c},1D:u(t,r,2g){22(t&&/^[a-z[({<*:.#]/i.1n(t)){B p=6.5M,m;6.K(p,u(i,1J){m=1J.2Q(t);7(m){t=t.7M(m[0].I);7(6.1s[m[1]].4u)m=6.1s[m[1]].4u(m);v 12}});7(m[1]==":"&&m[2]=="2g")r=6.1D(m[3],r,U).r;J 7(m[1]=="."){B 1J=1p 4v("(^|\\\\s)"+m[2]+"(\\\\s|$)");r=6.2q(r,u(e){v 1J.1n(e.19||"")},2g)}J{B f=6.1s[m[1]];7(1C f!="23")f=6.1s[m[1]][m[2]];40("f = u(a,i){"+(6.1s[m[1]].5P||"")+"v "+f+"}");r=6.2q(r,f,2g)}}v{r:r,t:t}},4x:u(o,r,1Q,Y,1J){P(B s=o.1b;s;s=s.2e)7(s.24==1){B 1M=U;7(1Q==".")1M=s.19&&1J.1n(s.19);J 7(1Q=="#")1M=s.36("2J")==Y;7(1M)r.1g(s);7(1Q=="#"&&r.I)3O;7(s.1b)6.4x(s,r,1Q,Y,1J)}v r},4z:u(D){B 4A=[];B Q=D.11;22(Q&&Q!=17){4A.1g(Q);Q=Q.11}v 4A},2a:u(Q,1i,3Z,D){1i=1i||1;B 1T=0;P(;Q;Q=Q[3Z]){7(Q.24==1)1T++;7(1T==1i||1i=="5R"&&1T%2==0&&1T>1&&Q==D||1i=="5S"&&1T%2==1&&Q==D)v Q}},2B:u(n,D){B r=[];P(;n;n=n.2e){7(n.24==1&&(!D||n!=D))r.1g(n)}v r}});6.G={1M:u(S,C,1o,F){7(6.W.1j&&S.3L!=T)S=1w;7(F)1o.F=F;7(!1o.2A)1o.2A=q.2A++;7(!S.$1H)S.$1H={};B 38=S.$1H[C];7(!38){38=S.$1H[C]={};7(S["39"+C])38[0]=S["39"+C]}38[1o.2A]=1o;S["39"+C]=q.5Y;7(!q.1k[C])q.1k[C]=[];q.1k[C].1g(S)},2A:1,1k:{},2f:u(S,C,1o){7(S.$1H){B i,j,k;7(C&&C.C){1o=C.1o;C=C.C}7(C&&S.$1H[C])7(1o)5U S.$1H[C][1o.2A];J P(i 1x S.$1H[C])5U S.$1H[C][i];J P(j 1x S.$1H)q.2f(S,j);P(k 1x S.$1H[C])7(k){k=U;3O}7(!k)S["39"+C]=16}},1S:u(C,F,S){F=6.3M(F||[]);7(!S)6.K(q.1k[C]||[],u(){6.G.1S(C,F,q)});J{B 1o=S["39"+C],1a,E=6.1t(S[C]);7(1o){F.61(q.2j({C:C,1O:S}));7((1a=1o.14(S,F))!==12)q.4F=U}7(E&&1a!==12)S[C]();q.4F=12}},5Y:u(G){7(1C 6=="T"||6.G.4F)v;G=6.G.2j(G||1w.G||{});B 3R;B c=q.$1H[G.C];B 1E=[].3N.3n(1A,1);1E.61(G);P(B j 1x c){1E[0].1o=c[j];1E[0].F=c[j].F;7(c[j].14(q,1E)===12){G.2n();G.2H();3R=12}}7(6.W.1j)G.1O=G.2n=G.2H=G.1o=G.F=16;v 3R},2j:u(G){7(!G.1O&&G.63)G.1O=G.63;7(G.65==T&&G.67!=T){B e=17.4H,b=17.64;G.65=G.67+(e.68||b.68);G.7Y=G.7Z+(e.6c||b.6c)}7(6.W.2N&&G.1O.24==3){B 3a=G;G=6.1z({},3a);G.1O=3a.1O.11;G.2n=u(){v 3a.2n()};G.2H=u(){v 3a.2H()}}7(!G.2n)G.2n=u(){q.3R=12};7(!G.2H)G.2H=u(){q.82=U};v G}};6.E.1z({3U:u(C,F,E){v q.K(u(){6.G.1M(q,C,E||F,F)})},6u:u(C,F,E){v q.K(u(){6.G.1M(q,C,u(G){6(q).6f(G);v(E||F).14(q,1A)},F)})},6f:u(C,E){v q.K(u(){6.G.2f(q,C,E)})},1S:u(C,F){v q.K(u(){6.G.1S(C,F,q)})},3X:u(){B a=1A;v q.6j(u(e){q.4M=q.4M==0?1:0;e.2n();v a[q.4M].14(q,[e])||12})},83:u(f,g){u 4O(e){B p=(e.C=="41"?e.84:e.85)||e.86;22(p&&p!=q)2G{p=p.11}2w(e){p=q};7(p==q)v 12;v(e.C=="41"?f:g).14(q,[e])}v q.41(4O).6k(4O)},27:u(f){7(6.3W)f.14(17,[6]);J{6.3c.1g(u(){v f.14(q,[6])})}v q}});6.1z({3W:12,3c:[],27:u(){7(!6.3W){6.3W=U;7(6.3c){6.K(6.3c,u(){q.14(17)});6.3c=16}7(6.W.3h||6.W.3f)17.87("6o",6.27,12)}}});1p u(){6.K(("88,8a,2O,8b,8d,52,6j,8e,"+"8f,8g,8h,41,6k,8j,42,"+"4R,8k,8l,8m,2C").3o(","),u(i,o){6.E[o]=u(f){v f?q.3U(o,f):q.1S(o)}});7(6.W.3h||6.W.3f)17.8n("6o",6.27,12);J 7(6.W.1j){17.8o("<8r"+"8s 2J=62 8u=U "+"3e=//:><\\/2d>");B 2d=17.4X("62");7(2d)2d.37=u(){7(q.3D!="1X")v;q.11.33(q);6.27()};2d=16}J 7(6.W.2N)6.50=3L(u(){7(17.3D=="8y"||17.3D=="1X"){4p(6.50);6.50=16;6.27()}},10);6.G.1M(1w,"2O",6.27)};7(6.W.1j)6(1w).6u("52",u(){B 1k=6.G.1k;P(B C 1x 1k){B 4Z=1k[C],i=4Z.I;7(i&&C!=\'52\')6w 6.G.2f(4Z[i-1],C);22(--i)}});6.E.1z({6A:u(V,21,M){q.2O(V,21,M,1)},2O:u(V,21,M,1W){7(6.1t(V))v q.3U("2O",V);M=M||u(){};B C="5d";7(21)7(6.1t(21)){M=21;21=16}J{21=6.3g(21);C="5V"}B 4e=q;6.3v({V:V,C:C,F:21,1W:1W,1X:u(2P,15){7(15=="2M"||!1W&&15=="5L")4e.1I("2t",2P.3G).4V().K(M,[2P.3G,15,2P]);J M.14(4e,[2P.3G,15,2P])}});v q},6B:u(){v 6.3g(q)},4V:u(){v q.2o("2d").K(u(){7(q.3e)6.59(q.3e);J 6.4a(q.2L||q.6H||q.2t||"")}).4g()}});7(!1w.3p)3p=u(){v 1p 6I("6K.6M")};6.K("5m,5Q,5O,5W,5N,5H".3o(","),u(i,o){6.E[o]=u(f){v q.3U(o,f)}});6.1z({2b:u(V,F,M,C,1W){7(6.1t(F)){M=F;F=16}v 6.3v({V:V,F:F,2M:M,4t:C,1W:1W})},6Q:u(V,F,M,C){v 6.2b(V,F,M,C,1)},59:u(V,M){v 6.2b(V,16,M,"2d")},6S:u(V,F,M){v 6.2b(V,F,M,"6m")},6U:u(V,F,M,C){7(6.1t(F)){M=F;F={}}v 6.3v({C:"5V",V:V,F:F,2M:M,4t:C})},6X:u(29){6.3q.29=29},6Y:u(5c){6.1z(6.3q,5c)},3q:{1k:U,C:"5d",29:0,5r:"70/x-73-3w-77",5h:U,48:U,F:16},3S:{},3v:u(s){s=6.1z({},6.3q,s);7(s.F){7(s.5h&&1C s.F!="23")s.F=6.3g(s.F);7(s.C.4m()=="2b"){s.V+=((s.V.18("?")>-1)?"&":"?")+s.F;s.F=16}}7(s.1k&&!6.4E++)6.G.1S("5m");B 4y=12;B N=1p 3p();N.7j(s.C,s.V,s.48);7(s.F)N.3A("7l-7m",s.5r);7(s.1W)N.3A("7o-4K-7q",6.3S[s.V]||"7s, 7t 7w 7x 4o:4o:4o 7z");N.3A("X-7B-7C","3p");7(N.7E)N.3A("7F","7G");7(s.5G)s.5G(N);7(s.1k)6.G.1S("5H",[N,s]);B 37=u(4s){7(N&&(N.3D==4||4s=="29")){4y=U;7(3I){4p(3I);3I=16}B 15;2G{15=6.5Z(N)&&4s!="29"?s.1W&&6.69(N,s.V)?"5L":"2M":"2C";7(15!="2C"){B 3F;2G{3F=N.4P("6b-4K")}2w(e){}7(s.1W&&3F)6.3S[s.V]=3F;B F=6.6i(N,s.4t);7(s.2M)s.2M(F,15);7(s.1k)6.G.1S("5N",[N,s])}J 6.3J(s,N,15)}2w(e){15="2C";6.3J(s,N,15,e)}7(s.1k)6.G.1S("5O",[N,s]);7(s.1k&&!--6.4E)6.G.1S("5Q");7(s.1X)s.1X(N,15);7(s.48)N=16}};B 3I=3L(37,13);7(s.29>0)57(u(){7(N){N.7N();7(!4y)37("29")}},s.29);2G{N.7Q(s.F)}2w(e){6.3J(s,N,16,e)}7(!s.48)37();v N},3J:u(s,N,15,e){7(s.2C)s.2C(N,15,e);7(s.1k)6.G.1S("5W",[N,s,e])},4E:0,5Z:u(r){2G{v!r.15&&7V.7W=="4G:"||(r.15>=5X&&r.15<7X)||r.15==6d||6.W.2N&&r.15==T}2w(e){}v 12},69:u(N,V){2G{B 6e=N.4P("6b-4K");v N.15==6d||6e==6.3S[V]||6.W.2N&&N.15==T}2w(e){}v 12},6i:u(r,C){B 4Q=r.4P("8c-C");B F=!C&&4Q&&4Q.18("N")>=0;F=C=="N"||F?r.8i:r.3G;7(C=="2d")6.4a(F);7(C=="6m")40("F = "+F);7(C=="4U")6("<1V>").4U(F).4V();v F},3g:u(a){B s=[];7(a.1l==2y||a.3Y)6.K(a,u(){s.1g(2x(q.Y)+"="+2x(q.O))});J P(B j 1x a)7(a[j]&&a[j].1l==2y)6.K(a[j],u(){s.1g(2x(j)+"="+2x(q))});J s.1g(2x(j)+"="+2x(a[j]));v s.6t("&")},4a:u(F){7(1w.54)1w.54(F);J 7(6.W.2N)1w.57(F,0);J 40.3n(1w,F)}});6.E.1z({1L:u(R,M){B 1G=q.1D(":1G");R?1G.26({28:"1L",3V:"1L",1d:"1L"},R,M):1G.K(u(){q.1q.1h=q.2E?q.2E:"";7(6.1m(q,"1h")=="1Z")q.1q.1h="2D"});v q},1K:u(R,M){B 3i=q.1D(":3i");R?3i.26({28:"1K",3V:"1K",1d:"1K"},R,M):3i.K(u(){q.2E=q.2E||6.1m(q,"1h");7(q.2E=="1Z")q.2E="2D";q.1q.1h="1Z"});v q},5g:6.E.3X,3X:u(E,4I){B 1E=1A;v 6.1t(E)&&6.1t(4I)?q.5g(E,4I):q.K(u(){6(q)[6(q).4l(":1G")?"1L":"1K"].14(6(q),1E)})},7a:u(R,M){v q.26({28:"1L"},R,M)},7c:u(R,M){v q.26({28:"1K"},R,M)},7f:u(R,M){v q.K(u(){B 5k=6(q).4l(":1G")?"1L":"1K";6(q).26({28:5k},R,M)})},7r:u(R,M){v q.26({1d:"1L"},R,M)},7u:u(R,M){v q.26({1d:"1K"},R,M)},7y:u(R,43,M){v q.26({1d:43},R,M)},26:u(H,R,1v,M){v q.1F(u(){q.2F=6.1z({},H);B 1u=6.R(R,1v,M);P(B p 1x H){B e=1p 6.3b(q,1u,p);7(H[p].1l==3Q)e.2s(e.Q(),H[p]);J e[H[p]](H)}})},1F:u(C,E){7(!E){E=C;C="3b"}v q.K(u(){7(!q.1F)q.1F={};7(!q.1F[C])q.1F[C]=[];q.1F[C].1g(E);7(q.1F[C].I==1)E.14(q)})}});6.1z({R:u(R,1v,E){B 1u=R&&R.1l==7K?R:{1X:E||!E&&1v||6.1t(R)&&R,25:R,1v:E&&1v||1v&&1v.1l!=4w&&1v};1u.25=(1u.25&&1u.25.1l==3Q?1u.25:{7R:7S,7T:5X}[1u.25])||7U;1u.1N=1u.1X;1u.1X=u(){6.6a(q,"3b");7(6.1t(1u.1N))1u.1N.14(q)};v 1u},1v:{},1F:{},6a:u(D,C){C=C||"3b";7(D.1F&&D.1F[C]){D.1F[C].4L();B f=D.1F[C][0];7(f)f.14(D)}},3b:u(D,1e,H){B z=q;B y=D.1q;B 4D=6.1m(D,"1h");y.5T="1G";z.a=u(){7(1e.49)1e.49.14(D,[z.2p]);7(H=="1d")6.1I(y,"1d",z.2p);J 7(6l(z.2p))y[H]=6l(z.2p)+"4S";y.1h="2D"};z.6v=u(){v 4T(6.1m(D,H))};z.Q=u(){B r=4T(6.30(D,H));v r&&r>-8z?r:z.6v()};z.2s=u(4f,43){z.4J=(1p 5o()).5w();z.2p=4f;z.a();z.4q=3L(u(){z.49(4f,43)},13)};z.1L=u(){7(!D.1y)D.1y={};D.1y[H]=q.Q();1e.1L=U;z.2s(0,D.1y[H]);7(H!="1d")y[H]="5a"};z.1K=u(){7(!D.1y)D.1y={};D.1y[H]=q.Q();1e.1K=U;z.2s(D.1y[H],0)};z.3X=u(){7(!D.1y)D.1y={};D.1y[H]=q.Q();7(4D=="1Z"){1e.1L=U;7(H!="1d")y[H]="5a";z.2s(0,D.1y[H])}J{1e.1K=U;z.2s(D.1y[H],0)}};z.49=u(32,47){B t=(1p 5o()).5w();7(t>1e.25+z.4J){4p(z.4q);z.4q=16;z.2p=47;z.a();7(D.2F)D.2F[H]=U;B 2c=U;P(B i 1x D.2F)7(D.2F[i]!==U)2c=12;7(2c){y.5T="";y.1h=4D;7(6.1m(D,"1h")=="1Z")y.1h="2D";7(1e.1K)y.1h="1Z";7(1e.1K||1e.1L)P(B p 1x D.2F)7(p=="1d")6.1I(y,p,D.1y[p]);J y[p]=""}7(2c&&6.1t(1e.1X))1e.1X.14(D)}J{B n=t-q.4J;B p=n/1e.25;z.2p=1e.1v&&6.1v[1e.1v]?6.1v[1e.1v](p,n,32,(47-32),1e.25):((-6h.7O(p*6h.8L)/2)+0.5)*(47-32)+32;z.a()}}}})}',62,545,'||||||jQuery|if|||||||||||||||||||this||||function|return||||||var|type|elem|fn|data|event|prop|length|else|each|ret|callback|xml|value|for|cur|speed|element|undefined|true|url|browser||name|||parentNode|false||apply|status|null|document|indexOf|className|val|firstChild|obj|opacity|options|nodeName|push|display|result|msie|global|constructor|css|test|handler|new|style|arg|expr|isFunction|opt|easing|window|in|orig|extend|arguments|context|typeof|filter|args|queue|hidden|events|attr|re|hide|show|add|old|target|table|token|replace|trigger|num|elems|div|ifModified|complete|key|none|tbody|params|while|string|nodeType|duration|animate|ready|height|timeout|nth|get|done|script|nextSibling|remove|not|index|tb|fix|merge|oid|z0|preventDefault|find|now|grep|pushStack|custom|innerHTML|first|cssFloat|catch|encodeURIComponent|Array|el|guid|sibling|error|block|oldblock|curAnim|try|stopPropagation|childNodes|id|wrap|text|success|safari|load|res|exec|al|_|last|substr|has|disabled|insertBefore|selected|checked|curCSS|map|firstNum|removeChild|re2|trim|getAttribute|onreadystatechange|handlers|on|originalEvent|fx|readyList|parPos|src|opera|param|mozilla|visible|domManip|clean|cloneNode|tr|call|split|XMLHttpRequest|ajaxSettings|append|child|String|empty|ajax|form|button|inArray|multiFilter|setRequestHeader|foundToken|9_|readyState|tag|modRes|responseText|second|ival|handleError|toUpperCase|setInterval|makeArray|slice|break|oWidth|Number|returnValue|lastModified|styleFloat|bind|width|isReady|toggle|jquery|dir|eval|mouseover|select|to|defaultView|position|oHeight|lastNum|async|step|globalEval|static|pos|swap|self|from|end|float|alpha|radio|inv|is|toLowerCase|visibility|00|clearInterval|timer|rec|isTimeout|dataType|_resort|RegExp|Function|getAll|requestDone|parents|matched|isXMLDoc|appendChild|oldDisplay|active|triggered|file|documentElement|fn2|startTime|Modified|shift|lastToggle|deep|handleHover|getResponseHeader|ct|submit|px|parseFloat|html|evalScripts|getComputedStyle|getElementById|clone|els|safariTimer|currentStyle|unload|force|execScript|getPropertyValue|newProp|setTimeout|zoom|getScript|1px|sl|settings|GET|rl|check|_toggle|processData|prepend|before|state|removeAttr|ajaxStart|lt|Date|gt|eq|contentType|previousSibling|after|parent|contains|getTime|checkbox|password|appendTo|image|reset|input|webkit|href|continue|beforeSend|ajaxSend|ownerDocument|getElementsByTagName|tmp|notmodified|parse|ajaxSuccess|ajaxComplete|_prefix|ajaxStop|even|odd|overflow|delete|POST|ajaxError|200|handle|httpSuccess|nodeValue|unshift|__ie_init|srcElement|body|pageX|tagName|clientX|scrollLeft|httpNotModified|dequeue|Last|scrollTop|304|xmlRes|unbind|100|Math|httpData|click|mouseout|parseInt|json|createElement|DOMContentLoaded|prevObject|ol|setArray|exclude|join|one|max|do|relative|clientHeight|clientWidth|loadIfModified|serialize|toString|thead|tfoot|td|th|textContent|ActiveXObject|htmlFor|Microsoft|class|XMLHTTP|readOnly|gi|match|getIfModified|9999|getJSON|getAttributeNode|post|setAttribute|ig|ajaxTimeout|ajaxSetup|concat|application|userAgent|compatible|www|compatMode|CSS1Compat|next|urlencoded|siblings|children|slideDown|prependTo|slideUp|Top|insertAfter|slideToggle|removeAttribute|addClass|removeClass|open|toggleClass|Content|Type|lastChild|If|only|Since|fadeIn|Thu|01|fadeOut|enabled|Jan|1970|fadeTo|GMT|textarea|Requested|With|prev|overrideMimeType|Connection|close|boxModel|right|object|Object|navigator|substring|abort|cos|font|send|slow|600|fast|400|location|protocol|300|pageY|clientY|method|action|cancelBubble|hover|fromElement|toElement|relatedTarget|removeEventListener|blur|readonly|focus|resize|content|scroll|dblclick|mousedown|mouseup|mousemove|responseXML|change|keydown|keypress|keyup|addEventListener|write|prototype|size|scr|ipt|createTextNode|defer|FORM|reverse|noConflict|loaded|10000|weight|line|Bottom|Right|Left|padding|border|Width|offsetHeight|offsetWidth|absolute|PI|left'.split('|'),0,{}))

var loc = '';
var cur_loc = null;
var event_count;
var event_time;
var has_started = false;
var scriptid;
var getstr = '';
var standalone = false;
var warned_nocookies = false;
var seq = 0;

var actionmenu_mouse_x;
var actionmenu_mouse_y;

var action_history;
var remote_history = 0;
var action_index = null;
var action_cur = null;
var action_count = 0;

function send_alive2server(firstcall, calltime, getdivs)
{
    jQuery.ajax({
        data: 'location=' + loc + getstr + '&scriptid='
            + (firstcall || (new Date().getTime() - calltime > 2300) ? 0 : scriptid)
            + (getdivs && getdivs != '' ? '&getdivs=' + escape(getdivs) : '')
            + (standalone ? '&standalone=true' : '') + '&seq=' + seq + _arnd(),
        success: function(msg) {

            if (handle_response(msg)) {
                seq++;
                setTimeout('send_alive2server(false, ' + (new Date().getTime()) +', "")', 2000);
            }
        }
    });
}

function send_action2server(dpaction, cmdline)
{
    jQuery.ajax({
        data: 'location=' + loc + getstr + '&scriptid='
            + scriptid + (standalone ? '&standalone=true' : '')
            + '&seq=' + seq + _arnd() +
            '&dpaction=' + escape(dpaction) + (!cmdline ? '&menuaction=1' : '&cmdline=1')
    });
}

function _get_actions_init(id, event)
{
    if (event.pageX) {
        actionmenu_mouse_x = event.pageX;
        actionmenu_mouse_y = event.pageY;
    } else {
        // Needed for IE7
        var gbody = document.compatMode && document.compatMode != "BackCompat"
            ? document.documentElement : document.body;

        actionmenu_mouse_x = event.clientX + gbody.scrollLeft - 2;
        actionmenu_mouse_y = event.clientY + gbody.scrollTop - 2;
    }
    if (actionmenu_mouse_x < 0)
        actionmenu_mouse_x = 0;
    if (actionmenu_mouse_y < 0)
        actionmenu_mouse_y = 0;
}

function get_actions(id, event)
{
    _get_actions_init(id, event);

    jQuery.ajax({
        data: 'call_object=' + escape(id) + '&method=getActionsMenu' + _arnd()
    });
}

function get_map_area_actions(map_name, map_area_id, id, event)
{
    _get_actions_init(id, event);

    jQuery.ajax({
        data: 'call_object=' + escape(id)
            + '&method=getMapAreaActionsMenu&map_name=' + escape(map_name)
            + '&map_area_id=' + escape(map_area_id) +_arnd()
    });
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
                + "style=\"margin-top: 100px\"><?php echo
dptext('You must have (session) cookies enabled in order to view this site'); ?></h1>\n";
        }

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
        if (jQuery(response2.childNodes[i]).text() != '')
            handle_dp_element(response2.childNodes[i]);
    }
    if (window.init_drag)
        init_drag();
    return true;
}

function handle_dp_element(childnode)
{
    switch(childnode.tagName) {
    case 'event':
        var e_count = parseInt(jQuery(childnode).attr('count'));
        var e_time = parseInt(jQuery(childnode).attr('time'));

        if (event_count != null && event_count != -1
                && event_count >= e_count) {
            /*
            alert('<?php echo dptext('Invalid event count'); ?> ' + event_count + ': '
                + jQuery(childnode).text());
            */
            window.location.reload();
            return;
        } else
            event_count = e_count;

        if (event_time != null && event_time != -1  && event_time > e_time) {
            /*
            alert('<?php echo dptext('Invalid event time'); ?> ' + event_time + ': '
                + jQuery(childnode).text());
            */
            window.location.reload();
            return;
        } else
            event_time = e_time;

        for (var j = 0; j < childnode.childNodes.length; j++)
            handle_dp_event(childnode.childNodes[j]);
        break;
    case 'location':
        this.location = jQuery(childnode).text();
        return;
    default:
        /*
        if (jQuery(childnode).text() != '1')
            alert('<?php echo dptext('Invalid DutchPIPE XML (2), tagname:'); ?>'
                + childnode.tagName);
        */
        break;
    }
}

function handle_dp_event(childnode)
{
    switch(childnode.tagName) {
    case 'location':
        this.location = jQuery(childnode).text();
        return;
    case 'div':
        var tmp = jQuery(childnode).text();
        var pos = tmp.indexOf('<div id="dppage">');
        if (pos != -1 && pos == 0) {
            tmp = tmp.substring(pos + 17);
            tmp = tmp.substring(0,tmp.length - 6);
        }

        var newdiv = jQuery(document.createElement("DIV")).attr('id', jQuery(childnode).attr('id')).
            html(tmp).css('zIndex', 5);
        var olddiv = jQuery('#' + jQuery(childnode).attr('id'));

        if (!olddiv.length) {
            jQuery(document.body).append(newdiv);
            if (jQuery(childnode).attr('id') == 'dpinventory'
                    || jQuery(childnode).attr('id') == 'dpmessagearea') {
                if (document.addEventListener || typeof document.body.style.maxHeight != "undefined")
                    newdiv.css('position', 'relative');
                else
                    newdiv.css({ position: 'absolute', marginTop: '110px' });
            }
        } else
            olddiv.css('display', 'none').before(newdiv).remove();
        break;
    case 'message':
        var message;
        var messagediv;
        message = jQuery(childnode).text();
        if (message == 'close_window') {
            this.location = '<?php echo DPSERVER_CLIENT_DIR; ?>dpmultiplewindows.txt';
            return;
        }
        messagediv = _gel('messages');
        if (jQuery(messagediv).css('marginTop') == '0px') {
            jQuery(messagediv).css({ marginTop: '11px', marginBottom: '10px' });
            messagediv.removeChild(messagediv.childNodes[0]);
        }

        if (messagediv.childNodes.length == 16) {
            jQuery(messagediv).css({ width: '100%', height: jQuery(messagediv).height() + 'px',
                overflow: 'auto' });
        }
        while (messagediv.childNodes.length >= 500)
            messagediv.removeChild(messagediv.childNodes[0]);

        jQuery(messagediv).append('<div id="message">' + message + '</div>');
        messagediv.scrollTop = messagediv.scrollHeight;
        break;
    case 'actions':
        if (!jQuery('div.actionwindow').length) {
            var amenu = jQuery(document.createElement("div")).addClass('actionwindow').
                html(jQuery(childnode).text()).css({ left: (actionmenu_mouse_x-11)+'px',
                top: (actionmenu_mouse_y-13)+'px', zIndex: 5 }).appendTo(document.body);
            if (jQuery.browser.msie && amenu.css('-moz-opacity'))
                amenu.css('filter', 'alpha(opacity=' + (amenu.css('-moz-opacity')*100) + ')');
        }
        break;
    case 'script':
        if (jQuery(childnode).attr('src')) {
            jQuery.getScript(jQuery(childnode).attr('src'));
            return;
        }

        var tag = document.createElement("script");
        tag.setAttribute('type','text/javascript');

        if (childnode.textContent)
            tag.textContent = childnode.textContent;
        else if (childnode.text)
            tag.text = childnode.text;

        document.getElementsByTagName('head')[0].appendChild(tag);
        break;
    case 'removeDpElement':
        jQuery('#' + jQuery(childnode).attr('id')).remove();
        break;
    case 'addDpElement':
        if (jQuery('#' + jQuery(childnode).attr('where')).length)
            jQuery(document.createElement("div")).attr('id', jQuery(childnode).attr('id')).
                addClass(jQuery(childnode).attr('class')).html(jQuery(childnode).text()).
                appendTo(jQuery('#' + jQuery(childnode).attr('where')));
        break;
    case 'changeDpElement':
        jQuery('#' + jQuery(childnode).attr('id')).html(jQuery(childnode).text());
        break;
    case 'moveDpElement':
        jQuery('#' + jQuery(childnode).attr('id')).removeClass().remove().clone().
            attr('id', jQuery(childnode).attr('id')).addClass(jQuery(childnode).attr('class')).
            appendTo(jQuery('#' + jQuery(childnode).attr('where')));
        break;
    case 'window':
        make_dpwindow(jQuery(childnode).text(), jQuery(childnode).attr('autoclose'),
            jQuery(childnode).attr('styleclass'));
        break;
    case 'reportmove':
        jQuery('#' + jQuery(childnode).attr('id')).animate({'left': parseInt(jQuery(childnode).
            attr('left'))}).animate({'top': parseInt(jQuery(childnode).attr('top'))});
        break;
    case 'history':
        insert_history(childnode);
        break;
    default:
        break;
        if (jQuery(childnode).text() != '1') {
            alert('<?php echo dptext('Invalid DutchPIPE XML (1), tagname:'); ?>'+childnode.tagName);
        }
    }
}

function make_dpwindow(text, autoclose, styleclass)
{
    close_dpwindow();

    var dpwindow = document.createElement("DIV");
    var gbody = document.compatMode && document.compatMode != "BackCompat"
        ? document.documentElement : document.body;

    jQuery(dpwindow).attr('id', 'dpwindow');
    jQuery(dpwindow).addClass(styleclass == null ? 'dpwindow_default' : styleclass);

    if (autoclose == null)
        jQuery(dpwindow).html(text + '<p align="right">'
            + '<a href="javascript:close_dpwindow()"><?php echo dptext('close'); ?></a></p>');
    else {
        jQuery(dpwindow).html(text);
        setTimeout('close_dpwindow()', autoclose);
    }
    jQuery(jQuery('body')[0].firstChild).before(dpwindow);
    var gbody = document.compatMode && document.compatMode != "BackCompat"
        ? document.documentElement : document.body;
    jQuery(dpwindow).css('top', (parseInt(jQuery(dpwindow).css('top'))
        + (window.pageYOffset != null ? window.pageYOffset : gbody.scrollTop)) + 'px');

    if (jQuery.browser.msie && jQuery(dpwindow).css('-moz-opacity'))
        jQuery(dpwindow).css('filter',
            'alpha(opacity=' + (jQuery(dpwindow).css('-moz-opacity')*100) + ')');

    // Command line loses focus in IE. This solves most cases for now, but
    // this should not go just to 'dpaction', it could e.g. be a form field.
    jQuery('#dpaction').focus();
}

function close_dpwindow()
{
    jQuery('#dpwindow').remove();
    jQuery('#dpaction').focus();
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
        data: 'call_object=' + escape(jQuery(x).attr('id'))
            + '&method=reportMove&x=' + x.style.left + '&y=' + x.style.top
            + _arnd()
    });
}

function gototop()
{
    scroll(0,0);
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
    return false;
}

function insert_history(element)
{
    if (action_history == null && jQuery(element).text()) {
        action_history = jQuery(element).text().split('@SEP@');
    } else if (action_history.length >= 20)
        return;
    else {
        var insert_history = jQuery(element).text().split('@SEP@');
        var insert_length = insert_history.length - action_count;
        if (insert_length <= 0)
            return;
        while (action_history.length < 20 && insert_length) {
            action_history.unshift(insert_history[insert_length - 1]);
            insert_length--;
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

    if (keynum != 38 && keynum != 40)
        return true;
    if (remote_history == 0) {
        remote_history = 1;
        jQuery.ajax({
            data: 'location=' + loc + getstr + '&scriptid=' + scriptid
            + (standalone ? '&standalone=true' : '') + '&seq=' + seq + _arnd() + '&gethistory=1'
        });
     }

     if (action_history == null || action_history.length == 0)
        return false;

     if (action_cur == null)
        action_cur = jQuery('#dpaction').val();

     if (keynum == 38) {
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
            } else {
                jQuery('#dpaction').val(action_history[++action_index]);
            }
        }
    }

    return false;
};

jQuery(function(){
    jQuery(document).click(function() {
        if (jQuery('div.actionwindow, #actionmenu_window').remove().length) {
            jQuery('#dpaction').focus();
        }
    });

    var _loc = location.href;
    var dphost_url = '<?php echo DPSERVER_HOST_URL . DPSERVER_CLIENT_DIR; ?>';
    var dpclient_fn = '<?php echo DPSERVER_CLIENT_FILENAME; ?>';

    if (_loc != dphost_url && _loc != dphost_url + dpclient_fn) {
        var pos = _loc.indexOf(dphost_url);
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
        url: '<?php echo DPSERVER_CLIENT_DIR; ?>' + dpclient_fn,
        success: handle_response
    });

    var ob = new Date();
    var curtime = ob.getTime();
    scriptid = _rnd();
    if (window.load_elements)
        load_elements();

    var getdivs = '';
    if (standalone) {
        if (!jQuery('#dpinventory').length || jQuery('#dpinventory').html() == '')
            getdivs += 'dpinventory#';
        if (!jQuery('#dpmessagearea').length || jQuery('#dpmessagearea').html() == '')
            getdivs += 'dpmessagearea#';
        if (!jQuery('#dploginout').length || jQuery('#dploginout').html() == '')
            getdivs += 'dploginout#';
    }
    if (getdivs != '')
        send_alive2server(true, curtime, getdivs);
    else {
        if (standalone)
            send_alive2server(true, curtime, '');
        else
            setTimeout('send_alive2server(true, '+curtime+', "")', (!has_started ? 10 : 2000));
        jQuery('#dpaction').focus();
        // IE needs a small timeout:
        setTimeout('gototop()', 10);
        has_started = true;
    }

    jQuery('#dpaction').bind("keydown", bindKeyDown);
});

