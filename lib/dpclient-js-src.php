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
 * @version    Subversion: $Id: dpclient-js-src.php 308 2007-09-02 19:18:58Z ls $
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
    '<?php echo DPSERVER_CLIENT_FILENAME; ?>'<?php
    if (!function_exists('gd_info')) {
        echo ",\n    " . DPSERVER_OBJECT_IMAGE_MAX_HEIGHT;
    }
    ?>
];
<?php
if (function_exists('gd_info')) {
    echo "function fix_dpimages() { }\n";
} else { ?>
var fix_dpimage_delay;
function fix_dpimages()
{
    jQuery('img[@class^=dpimage]').each(function(i) {
        if (!jQuery(this).attr('height')) {
            var h=jQuery(this).height();
            if (0==h) {
                if (!fix_dpimage_delay) {
                    setTimeout(fix_dpimages,10);
                    fix_dpimage_delay=true;
                }
            } else {
                jQuery(this).attr('height',h);
                jQuery(this).attr('width',jQuery(this).width());
                if (h<php[10]) jQuery(this).css('margin-top', (php[10]-h)+'px');
            }
        }});
}
<?php }
/*
 * jQuery 1.1.4 - New Wave Javascript
 *
 * Copyright (c) 2007 John Resig (jquery.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * $Date: 2007-08-23 21:49:27 -0400 (Thu, 23 Aug 2007) $
 * $Rev: 2862 $
 */
?>
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(9(){6(1f C!="Q")E v=C;E C=19.16=9(a,c){6(19==7||!7.4a)F 1s C(a,c);F 7.4a(a,c)};6(1f $!="Q")E B=$;19.$=C;E q=/^[^<]*(<(.|\\s)+>)[^>]*$|^#(\\w+)$/;C.15=C.3v={4a:9(a,c){a=a||R;6(1f a=="1E"){E m=q.2d(a);6(m&&(m[1]||!c)){6(m[1])a=C.3c([m[1]]);G{E b=R.37(m[3]);6(b)6(b.2j!=m[3])F C().1F(a);G{7[0]=b;7.H=1;F 7}G a=[]}}G F 1s C(c).1F(a)}G 6(C.1g(a))F 1s C(R)[C.15.1L?"1L":"2f"](a);F 7.5J(a.1b==1K&&a||(a.3w||a.H&&a!=19&&!a.1t&&a[0]!=Q&&a[0].1t)&&C.2V(a)||[a])},3w:"1.1.4",7K:9(){F 7.H},H:0,21:9(a){F a==Q?C.2V(7):7[a]},1O:9(a){E b=C(a);b.5c=7;F b},5J:9(a){7.H=0;1K.3v.Y.T(7,a);F 7},J:9(a,b){F C.J(7,a,b)},45:9(a){E b=-1;7.J(9(i){6(7==a)b=i});F b},1j:9(f,d,e){E c=f;6(f.1b==3n)6(d==Q)F 7.H&&C[e||"1j"](7[0],f)||Q;G{c={};c[f]=d}F 7.J(9(a){I(E b 17 c)C.1j(e?7.S:7,b,C.4Q(7,c[b],e,a,b))})},1h:9(b,a){F 7.1j(b,a,"34")},2Q:9(e){6(1f e!="4P"&&e!=K)F 7.3K().3H(R.60(e));E t="";C.J(e||7,9(){C.J(7.2Z,9(){6(7.1t!=8)t+=7.1t!=1?7.5S:C.15.2Q([7])})});F t},82:9(){E a,2e=1a;F 7.J(9(){6(!a)a=C.3c(2e,7.2I);E b=a[0].3B(O);7.P.2p(b,7);20(b.1k)b=b.1k;b.4p(7)})},3H:9(){F 7.2J(1a,O,1,9(a){7.4p(a)})},5v:9(){F 7.2J(1a,O,-1,9(a){7.2p(a,7.1k)})},5u:9(){F 7.2J(1a,M,1,9(a){7.P.2p(a,7)})},5t:9(){F 7.2J(1a,M,-1,9(a){7.P.2p(a,7.2a)})},3L:9(){F 7.5c||C([])},1F:9(t){E b=C.3M(7,9(a){F C.1F(t,a)});F 7.1O(/[^+>] [^+>]/.1d(t)||t.U("..")>-1?C.4d(b):b)},7o:9(e){e=e!=Q?e:O;E d=7.1r(7.1F("*"));6(C.N.12){d.J(9(){7.2l$1i={};I(E a 17 7.$1i)7.2l$1i[a]=C.14({},7.$1i[a])}).49()}E r=7.1O(C.3M(7,9(a){F a.3B(e)}));6(C.N.12){d.J(9(){E c=7.2l$1i;I(E a 17 c)I(E b 17 c[a])C.1c.1r(7,a,c[a][b],c[a][b].V);7.2l$1i=K})}6(e){E f=r.1r(r.1F(\'*\')).1l(\'2b,39[@L=3i]\');d.1l(\'2b,39[@L=3i]\').J(9(i){6(7.3j)f[i].3j=7.3j;6(7.27)f[i].27=O})}F r},1l:9(t){F 7.1O(C.1g(t)&&C.2B(7,9(b,a){F t.T(b,[a])})||C.2R(t,7))},5l:9(t){F 7.1O(t.1b==3n&&C.2R(t,7,O)||C.2B(7,9(a){F(t.1b==1K||t.3w)?C.4K(a,t)<0:a!=t}))},1r:9(t){F 7.1O(C.29(7.21(),t.1b==3n?C(t).21():t.H!=Q&&(!t.W||t.W=="6s")?t:[t]))},3y:9(a){F a?C.2R(a,7).H>0:M},2G:9(a){F a==Q?(7.H?7[0].2A:K):7.1j("2A",a)},5W:9(a){F a==Q?(7.H?7[0].2W:K):7.3K().3H(a)},3S:9(){F 7.1O(1K.3v.3S.T(7,1a))},2J:9(f,d,g,e){E c=7.H>1,a;F 7.J(9(){6(!a){a=C.3c(f,7.2I);6(g<0)a.8E()}E b=7;6(d&&C.W(7,"1A")&&C.W(a[0],"3O"))b=7.4L("1w")[0]||7.4p(R.6a("1w"));C.J(a,9(){6(C.W(7,"33")){6(7.32)C.31({1G:7.32,2w:M,3G:"33"});G C.4E(7.2Q||7.5Z||7.2W||"")}G e.T(b,[c?7.3B(O):7])})})}};C.14=C.15.14=9(){E c=1a[0]||{},a=1,1M=1a.H,4D=M;6(c.1b==8d){4D=c;c=1a[1]||{}}6(1M==1){c=7;a=0}E b;I(;a<1M;a++)6((b=1a[a])!=K)I(E i 17 b){6(c==b[i])5X;6(4D&&1f b[i]==\'4P\'&&c[i])C.14(c[i],b[i]);G 6(b[i]!=Q)c[i]=b[i]}F c};C.14({8a:9(a){19.$=B;6(a)19.16=v;F C},1g:9(a){F!!a&&1f a!="1E"&&!a.W&&a.1b!=1K&&/9/i.1d(a+"")},3E:9(a){F a.3D&&!a.4z||a.4y&&a.2I&&!a.2I.4z},4E:9(a){a=C.2s(a);6(a){6(19.5N)19.5N(a);G 6(C.N.1H)19.4x(a,0);G 2T.2S(19,a)}},W:9(b,a){F b.W&&b.W.1I()==a.1I()},J:9(a,b,c){6(c){6(a.H==Q)I(E i 17 a)b.T(a[i],c);G I(E i=0,3A=a.H;i<3A;i++)6(b.T(a[i],c)===M)1J}G{6(a.H==Q)I(E i 17 a)b.2S(a[i],i,a[i]);G I(E i=0,3A=a.H,2G=a[0];i<3A&&b.2S(2G,i,2G)!==M;2G=a[++i]){}}F a},4Q:9(c,b,d,e,a){6(C.1g(b))b=b.2S(c,[e]);E f=/z-?45|7S-?7Q|1e|5y|7O-?1u/i;F b&&b.1b==3x&&d=="34"&&!f.1d(a)?b+"4t":b},18:{1r:9(b,c){C.J((c||"").2M(/\\s+/),9(i,a){6(!C.18.2N(b.18,a))b.18+=(b.18?" ":"")+a})},23:9(b,c){b.18=c!=Q?C.2B(b.18.2M(/\\s+/),9(a){F!C.18.2N(c,a)}).5w(" "):""},2N:9(t,c){F C.4K(c,(t.18||t).3s().2M(/\\s+/))>-1}},1V:9(e,o,f){I(E i 17 o){e.S["2U"+i]=e.S[i];e.S[i]=o[i]}f.T(e,[]);I(E i 17 o)e.S[i]=e.S["2U"+i]},1h:9(e,p){6(p=="1u"||p=="24"){E b={},3p,3o,d=["7J","7G","7F","7B"];C.J(d,9(){b["7A"+7]=0;b["7x"+7+"7u"]=0});C.1V(e,b,9(){6(C(e).3y(\':4N\')){3p=e.7t;3o=e.7q}G{e=C(e.3B(O)).1F(":4e").5d("27").3L().1h({3V:"1C",3k:"7n",11:"2m",7h:"0",7e:"0"}).57(e.P)[0];E a=C.1h(e.P,"3k")||"3g";6(a=="3g")e.P.S.3k="76";3p=e.74;3o=e.71;6(a=="3g")e.P.S.3k="3g";e.P.3e(e)}});F p=="1u"?3p:3o}F C.34(e,p)},34:9(h,d,g){E i,1R=[],1V=[];9 2E(a){6(!C.N.1H)F M;E b=R.2L.3b(a,K);F!b||b.44("2E")==""}6(d=="1e"&&C.N.12){i=C.1j(h.S,"1e");F i==""?"1":i}6(d.2k(/3a/i))d=x;6(!g&&h.S[d])i=h.S[d];G 6(R.2L&&R.2L.3b){6(d.2k(/3a/i))d="3a";d=d.1v(/([A-Z])/g,"-$1").2D();E e=R.2L.3b(h,K);6(e&&!2E(h))i=e.44(d);G{I(E a=h;a&&2E(a);a=a.P)1R.42(a);I(a=0;a<1R.H;a++)6(2E(1R[a])){1V[a]=1R[a].S.11;1R[a].S.11="2m"}i=d=="11"&&1V[1R.H-1]!=K?"1T":R.2L.3b(h,K).44(d)||"";I(a=0;a<1V.H;a++)6(1V[a]!=K)1R[a].S.11=1V[a]}6(d=="1e"&&i=="")i="1"}G 6(h.41){E f=d.1v(/\\-(\\w)/g,9(m,c){F c.1I()});i=h.41[d]||h.41[f]}F i},3c:9(a,c){E r=[];c=c||R;C.J(a,9(i,b){6(!b)F;6(b.1b==3x)b=b.3s();6(1f b=="1E"){E s=C.2s(b).2D(),1m=c.6a("1m"),1P=[];E a=!s.U("<1Z")&&[1,"<2b>","</2b>"]||!s.U("<6L")&&[1,"<4V>","</4V>"]||s.2k(/^<(6I|1w|6H|6F|6D)/)&&[1,"<1A>","</1A>"]||!s.U("<3O")&&[2,"<1A><1w>","</1w></1A>"]||(!s.U("<6A")||!s.U("<6y"))&&[3,"<1A><1w><3O>","</3O></1w></1A>"]||!s.U("<6x")&&[2,"<1A><1w></1w><4T>","</4T></1A>"]||C.N.12&&[1,"1m<1m>","</1m>"]||[0,"",""];1m.2W=a[1]+b+a[2];20(a[0]--)1m=1m.3Y;6(C.N.12){6(!s.U("<1A")&&s.U("<1w")<0)1P=1m.1k&&1m.1k.2Z;G 6(a[1]=="<1A>"&&s.U("<1w")<0)1P=1m.2Z;I(E n=1P.H-1;n>=0;--n)6(C.W(1P[n],"1w")&&!1P[n].2Z.H)1P[n].P.3e(1P[n]);6(/^\\s/.1d(b))1m.2p(c.60(b.2k(/^\\s*/)[0]),1m.1k)}b=C.2V(1m.2Z)}6(0===b.H&&(!C.W(b,"38")&&!C.W(b,"2b")))F;6(b[0]==Q||C.W(b,"38")||b.6u)r.Y(b);G r=C.29(r,b)});F r},1j:9(c,d,a){E e=C.3E(c)?{}:C.4q;6(d=="28"&&C.N.1H)c.P.3j;6(e[d]){6(a!=Q)c[e[d]]=a;F c[e[d]]}G 6(C.N.12&&d=="S")F C.1j(c.S,"6p",a);G 6(a==Q&&C.N.12&&C.W(c,"38")&&(d=="6n"||d=="6m"))F c.6k(d).5S;G 6(c.4y){6(a!=Q)c.6j(d,a);6(C.N.12&&/5R|32/.1d(d)&&!C.3E(c))F c.3F(d,2);F c.3F(d)}G{6(d=="1e"&&C.N.12){6(a!=Q){c.5y=1;c.1l=(c.1l||"").1v(/5T\\([^)]*\\)/,"")+(3m(a).3s()=="6d"?"":"5T(1e="+a*6c+")")}F c.1l?(3m(c.1l.2k(/1e=([^)]*)/)[1])/6c).3s():""}d=d.1v(/-([a-z])/8I,9(z,b){F b.1I()});6(a!=Q)c[d]=a;F c[d]}},2s:9(t){F(t||"").1v(/^\\s+|\\s+$/g,"")},2V:9(a){E r=[];6(1f a!="8H")I(E i=0,1M=a.H;i<1M;i++)r.Y(a[i]);G r=a.3S(0);F r},4K:9(b,a){I(E i=0,1M=a.H;i<1M;i++)6(a[i]==b)F i;F-1},29:9(a,b){6(C.N.12){I(E i=0;b[i];i++)6(b[i].1t!=8)a.Y(b[i])}G I(E i=0;b[i];i++)a.Y(b[i]);F a},4d:9(a){E r=[],4O=C.1q++;2g{I(E i=0,69=a.H;i<69;i++)6(4O!=a[i].1q){a[i].1q=4O;r.Y(a[i])}}2h(e){r=a}F r},1q:0,2B:9(b,a,c){6(1f a=="1E")a=2T("M||9(a,i){F "+a+"}");E d=[];I(E i=0,3P=b.H;i<3P;i++)6(!c&&a(b[i],i)||c&&!a(b[i],i))d.Y(b[i]);F d},3M:9(c,b){6(1f b=="1E")b=2T("M||9(a){F "+b+"}");E d=[];I(E i=0,3P=c.H;i<3P;i++){E a=b(c[i],i);6(a!==K&&a!=Q){6(a.1b!=1K)a=[a];d=d.8x(a)}}F d}});E u=8w.8u.2D();C.N={6b:(u.2k(/.+(?:8s|8q|8p|8o)[\\/: ]([\\d.]+)/)||[])[1],1H:/61/.1d(u),2t:/2t/.1d(u),12:/12/.1d(u)&&!/2t/.1d(u),3J:/3J/.1d(u)&&!/(8n|61)/.1d(u)};E x=C.N.12?"3I":"4G";C.14({8m:!C.N.12||R.8l=="8k",3I:C.N.12?"3I":"4G",4q:{"I":"8j","8i":"18","3a":x,4G:x,3I:x,2W:"2W",18:"18",2A:"2A",30:"30",27:"27",8h:"8g",28:"28",8f:"8e"}});C.J({5Y:"a.P",4C:"16.4C(a)",8c:"16.25(a,2,\'2a\')",8b:"16.25(a,2,\'4B\')",88:"16.4A(a.P.1k,a)",87:"16.4A(a.1k)"},9(i,n){C.15[i]=9(a){E b=C.3M(7,n);6(a&&1f a=="1E")b=C.2R(a,b);F 7.1O(C.4d(b))}});C.J({57:"3H",86:"5v",2p:"5u",85:"5t"},9(i,n){C.15[i]=9(){E a=1a;F 7.J(9(){I(E j=0,1M=a.H;j<1M;j++)C(a[j])[n](7)})}});C.J({5d:9(a){C.1j(7,a,"");7.84(a)},83:9(c){C.18.1r(7,c)},81:9(c){C.18.23(7,c)},80:9(c){C.18[C.18.2N(7,c)?"23":"1r"](7,c)},23:9(a){6(!a||C.1l(a,[7]).r.H)7.P.3e(7)},3K:9(){20(7.1k)7.3e(7.1k)}},9(i,n){C.15[i]=9(){F 7.J(n,1a)}});C.J(["5Q","5P","5M","5L"],9(i,n){C.15[n]=9(a,b){F 7.1l(":"+n+"("+a+")",b)}});C.J(["1u","24"],9(i,n){C.15[n]=9(h){F h==Q?(7.H?C.1h(7[0],n):K):7.1h(n,h.1b==3n?h:h+"4t")}});E A=C.N.1H&&5K(C.N.6b)<7Z?"(?:[\\\\w*2l-]|\\\\\\\\.)":"(?:[\\\\w\\7Y-\\7V*2l-]|\\\\\\\\.)",5I=1s 3C("^[/>]\\\\s*("+A+"+)"),5H=1s 3C("^("+A+"+)(#)("+A+"+)"),5G=1s 3C("^([#.]?)("+A+"*)");C.14({4w:{"":"m[2]==\'*\'||16.W(a,m[2])","#":"a.3F(\'2j\')==m[2]",":":{5P:"i<m[3]-0",5M:"i>m[3]-0",25:"m[3]-0==i",5Q:"m[3]-0==i",2H:"i==0",2P:"i==r.H-1",5E:"i%2==0",5D:"i%2","2H-3z":"a.P.4L(\'*\')[0]==a","2P-3z":"16.25(a.P.3Y,1,\'4B\')==a","7U-3z":"!16.25(a.P.3Y,2,\'4B\')",5Y:"a.1k",3K:"!a.1k",5L:"(a.5Z||a.7T||\'\').U(m[3])>=0",4N:\'"1C"!=a.L&&16.1h(a,"11")!="1T"&&16.1h(a,"3V")!="1C"\',1C:\'"1C"==a.L||16.1h(a,"11")=="1T"||16.1h(a,"3V")=="1C"\',7R:"!a.30",30:"a.30",27:"a.27",28:"a.28||16.1j(a,\'28\')",2Q:"\'2Q\'==a.L",4e:"\'4e\'==a.L",3i:"\'3i\'==a.L",4v:"\'4v\'==a.L",5C:"\'5C\'==a.L",4u:"\'4u\'==a.L",5B:"\'5B\'==a.L",5A:"\'5A\'==a.L",1X:\'"1X"==a.L||16.W(a,"1X")\',39:"/39|2b|7P|1X/i.1d(a.W)",2N:"16.1F(m[3],a).H"},"[":"16.1F(m[2],a).H"},5x:[/^\\[ *(@)([\\w-]+) *([!*$^~=]*) *(\'?"?)(.*?)\\4 *\\]/,/^(\\[)\\s*(.*?(\\[.*?\\])?[^[]*?)\\s*\\]/,/^(:)([\\w-]+)\\("?\'?(.*?(\\(.*?\\))?[^(]*?)"?\'?\\)/,1s 3C("^([:.#]*)("+A+"+)")],2R:9(a,c,b){E d,1Y=[];20(a&&a!=d){d=a;E f=C.1l(a,c,b);a=f.t.1v(/^\\s*,\\s*/,"");1Y=b?c=f.r:C.29(1Y,f.r)}F 1Y},1F:9(t,l){6(1f t!="1E")F[t];6(l&&!l.1t)l=K;l=l||R;6(!t.U("//")){t=t.2K(2,t.H)}G 6(!t.U("/")&&!l.2I){l=l.3D;t=t.2K(1,t.H);6(t.U("/")>=1)t=t.2K(t.U("/"),t.H)}E d=[l],2q=[],2P;20(t&&2P!=t){E r=[];2P=t;t=C.2s(t).1v(/^\\/\\//,"");E k=M;E g=5I;E m=g.2d(t);6(m){E o=m[1].1I();I(E i=0;d[i];i++)I(E c=d[i].1k;c;c=c.2a)6(c.1t==1&&(o=="*"||c.W.1I()==o.1I()))r.Y(c);d=r;t=t.1v(g,"");6(t.U(" ")==0)5X;k=O}G{g=/^((\\/?\\.\\.)|([>\\/+~]))\\s*(\\w*)/i;6((m=g.2d(t))!=K){r=[];E o=m[4],1q=C.1q++;m=m[1];I(E j=0,2o=d.H;j<2o;j++)6(m.U("..")<0){E n=m=="~"||m=="+"?d[j].2a:d[j].1k;I(;n;n=n.2a)6(n.1t==1){6(m=="~"&&n.1q==1q)1J;6(!o||n.W.1I()==o.1I()){6(m=="~")n.1q=1q;r.Y(n)}6(m=="+")1J}}G r.Y(d[j].P);d=r;t=C.2s(t.1v(g,""));k=O}}6(t&&!k){6(!t.U(",")){6(l==d[0])d.4s();2q=C.29(2q,d);r=d=[l];t=" "+t.2K(1,t.H)}G{E h=5H;E m=h.2d(t);6(m){m=[0,m[2],m[3],m[1]]}G{h=5G;m=h.2d(t)}m[2]=m[2].1v(/\\\\/g,"");E f=d[d.H-1];6(m[1]=="#"&&f&&f.37&&!C.3E(f)){E p=f.37(m[2]);6((C.N.12||C.N.2t)&&p&&1f p.2j=="1E"&&p.2j!=m[2])p=C(\'[@2j="\'+m[2]+\'"]\',f)[0];d=r=p&&(!m[3]||C.W(p,m[3]))?[p]:[]}G{I(E i=0;d[i];i++){E a=m[1]!=""||m[0]==""?"*":m[2];6(a=="*"&&d[i].W.2D()=="4P")a="2O";r=C.29(r,d[i].4L(a))}6(m[1]==".")r=C.4r(r,m[2]);6(m[1]=="#"){E e=[];I(E i=0;r[i];i++)6(r[i].3F("2j")==m[2]){e=[r[i]];1J}r=e}d=r}t=t.1v(h,"")}}6(t){E b=C.1l(t,r);d=r=b.r;t=C.2s(b.t)}}6(t)d=[];6(d&&l==d[0])d.4s();2q=C.29(2q,d);F 2q},4r:9(r,m,a){m=" "+m+" ";E c=[];I(E i=0;r[i];i++){E b=(" "+r[i].18+" ").U(m)>=0;6(!a&&b||a&&!b)c.Y(r[i])}F c},1l:9(t,r,h){E d;20(t&&t!=d){d=t;E p=C.5x,m;I(E i=0;p[i];i++){m=p[i].2d(t);6(m){t=t.7N(m[0].H);m[2]=m[2].1v(/\\\\/g,"");1J}}6(!m)1J;6(m[1]==":"&&m[2]=="5l")r=C.1l(m[3],r,O).r;G 6(m[1]==".")r=C.4r(r,m[2],h);G 6(m[1]=="@"){E g=[],L=m[3];I(E i=0,2o=r.H;i<2o;i++){E a=r[i],z=a[C.4q[m[2]]||m[2]];6(z==K||/5R|32|28/.1d(m[2]))z=C.1j(a,m[2])||\'\';6((L==""&&!!z||L=="="&&z==m[5]||L=="!="&&z!=m[5]||L=="^="&&z&&!z.U(m[5])||L=="$="&&z.2K(z.H-m[5].H)==m[5]||(L=="*="||L=="~=")&&z.U(m[5])>=0)^h)g.Y(a)}r=g}G 6(m[1]==":"&&m[2]=="25-3z"){E e=C.1q++,g=[],1d=/(\\d*)n\\+?(\\d*)/.2d(m[3]=="5E"&&"2n"||m[3]=="5D"&&"2n+1"||!/\\D/.1d(m[3])&&"n+"+m[3]||m[3]),2H=(1d[1]||1)-0,d=1d[2]-0;I(E i=0,2o=r.H;i<2o;i++){E j=r[i],P=j.P;6(e!=P.1q){E c=1;I(E n=P.1k;n;n=n.2a)6(n.1t==1)n.4o=c++;P.1q=e}E b=M;6(2H==1){6(d==0||j.4o==d)b=O}G 6((j.4o+d)%2H==0)b=O;6(b^h)g.Y(j)}r=g}G{E f=C.4w[m[1]];6(1f f!="1E")f=C.4w[m[1]][m[2]];f=2T("M||9(a,i){F "+f+"}");r=C.2B(r,f,h)}}F{r:r,t:t}},4C:9(c){E b=[];E a=c.P;20(a&&a!=R){b.Y(a);a=a.P}F b},25:9(a,e,c,b){e=e||1;E d=0;I(;a;a=a[c])6(a.1t==1&&++d==e)1J;F a},4A:9(n,a){E r=[];I(;n;n=n.2a){6(n.1t==1&&(!a||n!=a))r.Y(n)}F r}});C.1c={1r:9(f,d,c,b){6(C.N.12&&f.3t!=Q)f=19;6(!c.22)c.22=7.22++;6(b!=Q){E e=c;c=9(){F e.T(7,1a)};c.V=b;c.22=e.22}6(!f.$1i)f.$1i={};6(!f.$1y)f.$1y=9(){E a;6(1f C=="Q"||C.1c.4n)F a;a=C.1c.1y.T(f,1a);F a};E g=f.$1i[d];6(!g){g=f.$1i[d]={};6(f.4m)f.4m(d,f.$1y,M);G f.7M("3r"+d,f.$1y)}g[c.22]=c;7.1D[d]=O},22:1,1D:{},23:9(c,b,a){E d=c.$1i,2c,45;6(d){6(b&&b.L){a=b.4l;b=b.L}6(!b){I(b 17 d)7.23(c,b)}G 6(d[b]){6(a)4k d[b][a.22];G I(a 17 c.$1i[b])4k d[b][a];I(2c 17 d[b])1J;6(!2c){6(c.4j)c.4j(b,c.$1y,M);G c.7L("3r"+b,c.$1y);2c=K;4k d[b]}}I(2c 17 d)1J;6(!2c)c.$1y=c.$1i=K}},1z:9(c,b,d){b=C.2V(b||[]);6(!d){6(7.1D[c])C("*").1r([19,R]).1z(c,b)}G{E a,2c,15=C.1g(d[c]||K);b.42(7.4i({L:c,1S:d}));6(C.1g(d.$1y))a=d.$1y.T(d,b);6(!15&&d["3r"+c]&&d["3r"+c].T(d,b)===M)a=M;6(15&&a!==M&&!(C.W(d,\'a\')&&c=="4h")){7.4n=O;d[c]()}7.4n=M}},1y:9(b){E a;b=C.1c.4i(b||19.1c||{});E c=7.$1i&&7.$1i[b.L],2e=1K.3v.3S.2S(1a,1);2e.42(b);I(E j 17 c){2e[0].4l=c[j];2e[0].V=c[j].V;6(c[j].T(7,2e)===M){b.2u();b.2X();a=M}}6(C.N.12)b.1S=b.2u=b.2X=b.4l=b.V=K;F a},4i:9(c){E a=c;c=C.14({},a);c.2u=9(){6(a.2u)a.2u();a.7I=M};c.2X=9(){6(a.2X)a.2X();a.7H=O};6(!c.1S&&c.5r)c.1S=c.5r;6(C.N.1H&&c.1S.1t==3)c.1S=a.1S.P;6(!c.4g&&c.4F)c.4g=c.4F==c.1S?c.7C:c.4F;6(c.5p==K&&c.66!=K){E e=R.3D,b=R.4z;c.5p=c.66+(e&&e.5o||b.5o||0);c.7z=c.7v+(e&&e.5m||b.5m||0)}6(!c.3Q&&(c.5k||c.5j))c.3Q=c.5k||c.5j;6(!c.5i&&c.5g)c.5i=c.5g;6(!c.3Q&&c.1X)c.3Q=(c.1X&1?1:(c.1X&2?3:(c.1X&4?2:0)));F c}};C.15.14({3l:9(c,a,b){F c=="5f"?7.5e(c,a,b):7.J(9(){C.1c.1r(7,c,b||a,b&&a)})},5e:9(d,b,c){F 7.J(9(){C.1c.1r(7,d,9(a){C(7).49(a);F(c||b).T(7,1a)},c&&b)})},49:9(a,b){F 7.J(9(){C.1c.23(7,a,b)})},1z:9(a,b){F 7.J(9(){C.1c.1z(a,b,7)})},1W:9(){E a=1a;F 7.4h(9(e){7.3T=0==7.3T?1:0;e.2u();F a[7.3T].T(7,[e])||M})},7p:9(f,g){9 3U(e){E p=e.4g;20(p&&p!=7)2g{p=p.P}2h(e){p=7};6(p==7)F M;F(e.L=="3W"?f:g).T(7,[e])}F 7.3W(3U).5b(3U)},1L:9(f){5a();6(C.36)f.T(R,[C]);G C.2C.Y(9(){F f.T(7,[C])});F 7}});C.14({36:M,2C:[],1L:9(){6(!C.36){C.36=O;6(C.2C){C.J(C.2C,9(){7.T(R)});C.2C=K}6(C.N.3J||C.N.2t)R.4j("59",C.1L,M);6(!19.7m.H)C(19).2f(9(){C("#4b").23()})}}});C.J(("7l,7k,2f,7j,7i,5f,4h,7g,"+"7f,7d,7c,3W,5b,7b,2b,"+"4u,7a,79,78,3f").2M(","),9(i,o){C.15[o]=9(f){F f?7.3l(o,f):7.1z(o)}});E w=M;9 5a(){6(w)F;w=O;6(C.N.3J||C.N.2t)R.4m("59",C.1L,M);G 6(C.N.12){R.75("<73"+"72 2j=4b 70=O "+"32=//:><\\/33>");E a=R.37("4b");6(a)a.6Z=9(){6(R.3d!="1x")F;C.1L()};a=K}G 6(C.N.1H)C.48=3t(9(){6(R.3d=="6Y"||R.3d=="1x"){47(C.48);C.48=K;C.1L()}},10);C.1c.1r(19,"2f",C.1L)}C.15.14({6X:9(c,b,a){7.2f(c,b,a,1)},2f:9(g,e,c,d){6(C.1g(g))F 7.3l("2f",g);c=c||9(){};E f="46";6(e)6(C.1g(e)){c=e;e=K}G{e=C.2O(e);f="55"}E h=7;C.31({1G:g,L:f,V:e,2F:d,1x:9(a,b){6(b=="1U"||!d&&b=="54")h.5W(a.43);4x(9(){h.J(c,[a.43,b,a])},13)}});F 7},6W:9(){F C.2O(7)},6V:9(){}});C.J("53,52,51,50,4Z,5h".2M(","),9(i,o){C.15[o]=9(f){F 7.3l(o,f)}});C.14({21:9(e,c,a,d,b){6(C.1g(c)){a=c;c=K}F C.31({L:"46",1G:e,V:c,1U:a,3G:d,2F:b})},6U:9(d,b,a,c){F C.21(d,b,a,c,1)},6T:9(b,a){F C.21(b,K,a,"33")},77:9(c,b,a){F C.21(c,b,a,"56")},6S:9(d,b,a,c){6(C.1g(b)){a=b;b={}}F C.31({L:"55",1G:d,V:b,1U:a,3G:c})},6R:9(a){C.3u.1Q=a},6Q:9(a){C.14(C.3u,a)},3u:{1D:O,L:"46",1Q:0,4Y:"6P/x-6O-38-6N",4X:O,2w:O,V:K},3h:{},31:9(s){s=C.14(O,s,C.14(O,{},C.3u,s));6(s.V){6(s.4X&&1f s.V!="1E")s.V=C.2O(s.V);6(s.L.2D()=="21"){s.1G+=(s.1G.U("?")>-1?"&":"?")+s.V;s.V=K}}6(s.1D&&!C.40++)C.1c.1z("53");E f=M;E h=19.4W?1s 4W("6M.6K"):1s 58();h.6J(s.L,s.1G,s.2w);6(s.V)h.4c("7r-7s",s.4Y);6(s.2F)h.4c("6G-3Z-6E",C.3h[s.1G]||"7w, 6C 7y 6B 4J:4J:4J 6z");h.4c("X-7D-7E","58");6(s.4U)s.4U(h);6(s.1D)C.1c.1z("5h",[h,s]);E g=9(d){6(!f&&h&&(h.3d==4||d=="1Q")){f=O;6(i){47(i);i=K}E c=d=="1Q"&&"1Q"||!C.5n(h)&&"3f"||s.2F&&C.5s(h,s.1G)&&"54"||"1U";6(c=="1U"){2g{E a=C.5q(h,s.3G)}2h(e){c="4I"}}6(c=="1U"){E b;2g{b=h.4f("4S-3Z")}2h(e){}6(s.2F&&b)C.3h[s.1G]=b;6(s.1U)s.1U(a,c);6(s.1D)C.1c.1z("4Z",[h,s])}G C.3X(s,h,c);6(s.1D)C.1c.1z("51",[h,s]);6(s.1D&&!--C.40)C.1c.1z("52");6(s.1x)s.1x(h,c);6(s.2w)h=K}};6(s.2w){E i=3t(g,13);6(s.1Q>0)4x(9(){6(h){h.6w();6(!f)g("1Q")}},s.1Q)}2g{h.6v(s.V)}2h(e){C.3X(s,h,K,e)}6(!s.2w)g();F h},3X:9(s,a,b,e){6(s.3f)s.3f(a,b,e);6(s.1D)C.1c.1z("50",[a,s,e])},40:0,5n:9(r){2g{F!r.26&&6t.6r=="4v:"||(r.26>=4R&&r.26<6q)||r.26==5z||C.N.1H&&r.26==Q}2h(e){}F M},5s:9(a,c){2g{E b=a.4f("4S-3Z");F a.26==5z||b==C.3h[c]||C.N.1H&&a.26==Q}2h(e){}F M},5q:9(r,a){E b=r.4f("6o-L");E c=a=="5F"||!a&&b&&b.U("5F")>=0;V=c?r.7W:r.43;6(c&&V.3D.4y=="4I")7X"4I";6(a=="33")C.4E(V);6(a=="56")V=2T("("+V+")");F V},2O:9(a){E s=[];6(a.1b==1K||a.3w)C.J(a,9(){s.Y(2y(7.6l)+"="+2y(7.2A))});G I(E j 17 a)6(a[j]&&a[j].1b==1K)C.J(a[j],9(){s.Y(2y(j)+"="+2y(7))});G s.Y(2y(j)+"="+2y(a[j]));F s.5w("&")}});C.15.14({1o:9(b,a){F b?7.1B({1u:"1o",24:"1o",1e:"1o"},b,a):7.1l(":1C").J(9(){7.S.11=7.2r?7.2r:"";6(C.1h(7,"11")=="1T")7.S.11="2m"}).3L()},1p:9(b,a){F b?7.1B({1u:"1p",24:"1p",1e:"1p"},b,a):7.1l(":4N").J(9(){7.2r=7.2r||C.1h(7,"11");6(7.2r=="1T")7.2r="2m";7.S.11="1T"}).3L()},5O:C.15.1W,1W:9(a,b){F C.1g(a)&&C.1g(b)?7.5O(a,b):a?7.1B({1u:"1W",24:"1W",1e:"1W"},a,b):7.J(9(){C(7)[C(7).3y(":1C")?"1o":"1p"]()})},6i:9(b,a){F 7.1B({1u:"1o"},b,a)},6h:9(b,a){F 7.1B({1u:"1p"},b,a)},6g:9(b,a){F 7.1B({1u:"1W"},b,a)},6f:9(b,a){F 7.1B({1e:"1o"},b,a)},89:9(b,a){F 7.1B({1e:"1p"},b,a)},6e:9(c,a,b){F 7.1B({1e:a},c,b)},1B:9(d,h,f,g){F 7.1n(9(){E c=C(7).3y(":1C"),1Z=C.5V(h,f,g),5U=7;I(E p 17 d){6(d[p]=="1p"&&c||d[p]=="1o"&&!c)F C.1g(1Z.1x)&&1Z.1x.T(7);6(p=="1u"||p=="24"){1Z.11=C.1h(7,"11");1Z.2z=7.S.2z}}6(1Z.2z!=K)7.S.2z="1C";7.2v=C.14({},d);C.J(d,9(a,b){E e=1s C.2Y(5U,1Z,a);6(b.1b==3x)e.3R(e.1Y()||0,b);G e[b=="1W"?c?"1o":"1p":b](d)});F O})},1n:9(a,b){6(!b){b=a;a="2Y"}F 7.J(9(){6(!7.1n)7.1n={};6(!7.1n[a])7.1n[a]=[];7.1n[a].Y(b);6(7.1n[a].H==1)b.T(7)})}});C.14({5V:9(b,a,c){E d=b&&b.1b==8G?b:{1x:c||!c&&a||C.1g(b)&&b,1N:b,35:c&&a||a&&a.1b!=8F&&a};d.1N=(d.1N&&d.1N.1b==3x?d.1N:{8D:8C,8B:4R}[d.1N])||8A;d.2U=d.1x;d.1x=9(){C.68(7,"2Y");6(C.1g(d.2U))d.2U.T(7)};F d},35:{62:9(p,n,b,a){F b+a*p},4H:9(p,n,b,a){F((-67.8z(p*67.8y)/2)+0.5)*a+b}},1n:{},68:9(b,a){a=a||"2Y";6(b.1n&&b.1n[a]){b.1n[a].4s();E f=b.1n[a][0];6(f)f.T(b)}},3N:[],2Y:9(f,e,g){E z=7;E y=f.S;z.a=9(){6(e.3q)e.3q.T(f,[z.2x]);6(g=="1e")C.1j(y,"1e",z.2x);G{y[g]=5K(z.2x)+"4t";6(g=="1u"||g=="24")y.11="2m"}};z.65=9(){F 3m(C.1h(f,g))};z.1Y=9(){E r=3m(C.34(f,g));F r&&r>-8v?r:z.65()};z.3R=9(c,b){z.4M=(1s 64()).63();z.2x=c;z.a();C.3N.Y(9(){F z.3q(c,b)});6(C.3N.H==1){E d=3t(9(){E a=C.3N;I(E i=0;i<a.H;i++)6(!a[i]())a.8t(i--,1);6(!a.H)47(d)},13)}};z.1o=9(){6(!f.2i)f.2i={};f.2i[g]=C.1j(f.S,g);e.1o=O;z.3R(0,7.1Y());6(g!="1e")y[g]="8r";C(f).1o()};z.1p=9(){6(!f.2i)f.2i={};f.2i[g]=C.1j(f.S,g);e.1p=O;z.3R(7.1Y(),0)};z.3q=9(a,c){E t=(1s 64()).63();6(t>e.1N+z.4M){z.2x=c;z.a();6(f.2v)f.2v[g]=O;E b=O;I(E i 17 f.2v)6(f.2v[i]!==O)b=M;6(b){6(e.11!=K){y.2z=e.2z;y.11=e.11;6(C.1h(f,"11")=="1T")y.11="2m"}6(e.1p)y.11="1T";6(e.1p||e.1o)I(E p 17 f.2v)C.1j(y,p,f.2i[p])}6(b&&C.1g(e.1x))e.1x.T(f);F M}G{E n=t-7.4M;E p=n/e.1N;z.2x=C.35[e.35||(C.35.4H?"4H":"62")](p,n,a,(c-a),e.1N);z.a()}F O}}})})();',62,541,'||||||if|this||function|||||||||||||||||||||||||||||||var|return|else|length|for|each|null|type|false|browser|true|parentNode|undefined|document|style|apply|indexOf|data|nodeName||push|||display|msie||extend|fn|jQuery|in|className|window|arguments|constructor|event|test|opacity|typeof|isFunction|css|events|attr|firstChild|filter|div|queue|show|hide|mergeNum|add|new|nodeType|height|replace|tbody|complete|handle|trigger|table|animate|hidden|global|string|find|url|safari|toUpperCase|break|Array|ready|al|duration|pushStack|tb|timeout|stack|target|none|success|swap|toggle|button|cur|opt|while|get|guid|remove|width|nth|status|checked|selected|merge|nextSibling|select|ret|exec|args|load|try|catch|orig|id|match|_|block||rl|insertBefore|done|oldblock|trim|opera|preventDefault|curAnim|async|now|encodeURIComponent|overflow|value|grep|readyList|toLowerCase|color|ifModified|val|first|ownerDocument|domManip|substr|defaultView|split|has|param|last|text|multiFilter|call|eval|old|makeArray|innerHTML|stopPropagation|fx|childNodes|disabled|ajax|src|script|curCSS|easing|isReady|getElementById|form|input|float|getComputedStyle|clean|readyState|removeChild|error|static|lastModified|checkbox|selectedIndex|position|bind|parseFloat|String|oWidth|oHeight|step|on|toString|setInterval|ajaxSettings|prototype|jquery|Number|is|child|ol|cloneNode|RegExp|documentElement|isXMLDoc|getAttribute|dataType|append|styleFloat|mozilla|empty|end|map|timers|tr|el|which|custom|slice|lastToggle|handleHover|visibility|mouseover|handleError|lastChild|Modified|active|currentStyle|unshift|responseText|getPropertyValue|index|GET|clearInterval|safariTimer|unbind|init|__ie_init|setRequestHeader|unique|radio|getResponseHeader|relatedTarget|click|fix|removeEventListener|delete|handler|addEventListener|triggered|nodeIndex|appendChild|props|classFilter|shift|px|submit|file|expr|setTimeout|tagName|body|sibling|previousSibling|parents|deep|globalEval|fromElement|cssFloat|swing|parsererror|00|inArray|getElementsByTagName|startTime|visible|num|object|prop|200|Last|colgroup|beforeSend|fieldset|ActiveXObject|processData|contentType|ajaxSuccess|ajaxError|ajaxComplete|ajaxStop|ajaxStart|notmodified|POST|json|appendTo|XMLHttpRequest|DOMContentLoaded|bindReady|mouseout|prevObject|removeAttr|one|unload|ctrlKey|ajaxSend|metaKey|keyCode|charCode|not|scrollTop|httpSuccess|scrollLeft|pageX|httpData|srcElement|httpNotModified|after|before|prepend|join|parse|zoom|304|reset|image|password|odd|even|xml|quickClass|quickID|quickChild|setArray|parseInt|contains|gt|execScript|_toggle|lt|eq|href|nodeValue|alpha|self|speed|html|continue|parent|textContent|createTextNode|webkit|linear|getTime|Date|max|clientX|Math|dequeue|fl|createElement|version|100|NaN|fadeTo|fadeIn|slideToggle|slideUp|slideDown|setAttribute|getAttributeNode|name|method|action|content|cssText|300|protocol|FORM|location|options|send|abort|col|th|GMT|td|1970|01|cap|Since|colg|If|tfoot|thead|open|XMLHTTP|leg|Microsoft|urlencoded|www|application|ajaxSetup|ajaxTimeout|post|getScript|getIfModified|evalScripts|serialize|loadIfModified|loaded|onreadystatechange|defer|clientWidth|ipt|scr|clientHeight|write|relative|getJSON|keyup|keypress|keydown|change|mousemove|mouseup|left|mousedown|dblclick|right|scroll|resize|focus|blur|frames|absolute|clone|hover|offsetWidth|Content|Type|offsetHeight|Width|clientY|Thu|border|Jan|pageY|padding|Left|toElement|Requested|With|Right|Bottom|cancelBubble|returnValue|Top|size|detachEvent|attachEvent|substring|line|textarea|weight|enabled|font|innerText|only|uFFFF|responseXML|throw|u0128|417|toggleClass|removeClass|wrap|addClass|removeAttribute|insertAfter|prependTo|children|siblings|fadeOut|noConflict|prev|next|Boolean|maxLength|maxlength|readOnly|readonly|class|htmlFor|CSS1Compat|compatMode|boxModel|compatible|ie|ra|it|1px|rv|splice|userAgent|10000|navigator|concat|PI|cos|400|fast|600|slow|reverse|Function|Object|array|ig'.split('|'),0,{}))
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
        data: 'location=' + encodeURIComponent(loc + getstr) + '&scriptid='
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
            if (seq > 1) {
                window.location.reload();
            }
            else {
                warned_nocookies = true;
                document.body.innerHTML = "<h1 align=\"center\" style="
                    + '"margin-top: 100px">' + php[0] + ';' + seq + '</h1>';
            }

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
            html(tmp).css('z-index', 5);
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
        fix_dpimages();
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
            var added = jQuery(document.createElement("div")).
                attr('id', jQuery(el).attr('id')).addClass(jQuery(el).
                attr('class')).html(jQuery(el).text()).
                appendTo(jQuery('#' + jQuery(el).attr('where')));
            if (jQuery(el).attr('css')) {
                var style = jQuery(el).attr('css').split(';');
                for (var i = 0, len=style.length, css; i < len; ++i) {
                    css = style[i].split(':');
                    added.css(jQuery.trim(css[0]), jQuery.trim(css[1]));
                }
            }
            update_dpinv();
        }
        fix_dpimages();
        break;
    case 'changeDpElement':
        var t = 'div[@id=' + id + ']';
        jQuery('dppage_body' == id ? t : t + '[@class^=title_img],' + t
            + '[@class^=dpobject]').html(jQuery(el).text());
        fix_dpimages();
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
        fix_dpimages();
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
    var menu_delay = 365 - (stop.getTime() - am_start.getTime());

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
    fix_dpimages();
    if (typeof history.navigationMode != 'undefined')
        history.navigationMode = 'compatible';
    jQuery('#dpaction').bind('keydown', bindKeyDown);
    bind_input();
});
