<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo dptext('DutchPIPE - PHP/AJAX Interactive Virtual Environments on Websites') ?></title>
        <meta name="generator" content="<?php echo dptext('DutchPIPE') ?>" />
        <meta name="keywords" content="<?php echo dptext('DutchPIPE, A Dutchman\'s Persistant Interactive Page Environments, PHP, AJAX, DOM, DHTML, PHP5, PHP 5, Virtual, Multi User, Object Oriented, Persistent State, Lennert Stock, Shoutbox') ?>" />
        <meta name="description" content="<?php echo dptext('DutchPIPE stands for a Dutchman\'s Persistant Interactive Page Environments. With DutchPIPE, each page becomes an abstracted environment or location where visitors and other items on the page are visualized. This status is retained as visitors move around. A whole lot of real-time interaction is possible. DutchPIPE uses AJAX/DOM/PHP.') ?>" />
        <link rel="stylesheet" href="<?php echo DPSERVER_CLIENT_DIR; ?>dpclient.css" type="text/css" />
        <link rel="icon" href="<?php echo DPSERVER_CLIENT_DIR; ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo DPSERVER_CLIENT_DIR; ?>favicon.ico" type="image/x-icon" />
        <noscript><?php echo dptext('Your browser must have JavaScript enabled in order to view this page.<br />') ?></noscript>
    </head>
    <body>
        <div id="dppage">
            <div id="dppage_inner1">
                <div id="titlebar">
                    <div id="titlebarleft">
                        <div id="navlink"><img src="<?php echo DPUNIVERSE_IMAGE_URL; ?>navlogo.gif" align="left" width="73" height="15" border="0" alt="DutchPIPE" style="margin-top: 1px" /></div>
                    </div>
                    <div id="titlebarright">&#160;</div>
                </div>
                <div class="dppage_inner2" id="dppage_inner2">
                    <div id="dppage_body" style="text-align: center">
                        <div style="margin-top: 100px; margin-bottom: 100px; padding: 20px; width: 300px; border: solid 1px #000066; text-align: center; margin-left: auto; margin-right: auto" align="center">
                            <img src="<?php echo DPUNIVERSE_AVATAR_URL; ?>user18.gif" border="0" alt="" title="" style="float: left" />
                            <?php echo $body ?><br clear="all" />
                        </div>
                    </div><br clear="all" />
                </div>
            </div>
        </div>
        <div id="dpinput_wrap">
        </div>
        &#160;<a name="bottom"></a>
    </body>
</html>