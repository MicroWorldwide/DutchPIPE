<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo dp_text('DutchPIPE - PHP/AJAX Interactive Virtual Environments on Websites') ?></title>
        <meta name="generator" content="<?php echo dp_text('DutchPIPE') ?>" />
        <meta name="keywords" content="<?php echo dp_text('DutchPIPE, A Dutchman\'s Persistant Interactive Page Environments, PHP, AJAX, DOM, DHTML, PHP5, PHP 5, Virtual, Multi User, Object Oriented, Persistent State, Lennert Stock, Shoutbox') ?>" />
        <meta name="description" content="<?php echo dp_text('DutchPIPE stands for a Dutchman\'s Persistant Interactive Page Environments. With DutchPIPE, each page becomes an abstracted environment or location where visitors and other items on the page are visualized. This status is retained as visitors move around. A whole lot of real-time interaction is possible. DutchPIPE uses AJAX/DOM/PHP.') ?>" />
        <link rel="stylesheet" href="<?php echo DPSERVER_CLIENT_DIR; ?>dpclient.css" type="text/css" />
        <link rel="icon" href="<?php echo DPSERVER_CLIENT_DIR; ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo DPSERVER_CLIENT_DIR; ?>favicon.ico" type="image/x-icon" />
        <script type="text/javascript" src="<?php echo DPSERVER_CLIENTJS_URL; ?>"></script>
        <noscript><?php echo dp_text('Your browser must have JavaScript enabled in order to view this page.<br />') ?></noscript>
        <style>
            #dpfooter { float: none }
            body { margin: auto; margin-top: 10px; text-align: center }
            body, .page_wrap { width: 800px; height: 600px;  }
            .page_wrap { text-align: left; background-image: url(<?php echo DPUNIVERSE_IMAGE_URL; ?>grasslands.jpg); border-bottom: solid 1px #8080b0; border-right: solid 1px #bebec8; border-left: solid 1px #bebec8 }
            .title_img, .title_img_me { color: #ffcc66 }
            #dppage { height: 551px; background-color: transparent; border-right: none; border-left: none}
            #dpmessagearea { position: absolute; top: 300px; width: 800px; background-color: transparent; border-right: none; border-left: none }
            #dpinput_wrap { position: absolute; top: 561px; width: 800px; background-color: transparent; border-bottom: none; border-right: none; border-left: none }
            table.dpinput_say_table td.dpinput_say_top, table.dpinput_table { display: none }
        </style>
<?php echo $scripts ?>
<?php echo $dpelements ?>
    </head>
    <body>
        <div class="page_wrap">
            <?php echo $windows ?><?php echo $body ?>
            <div id="dpmessagearea">
                <div id="dpmessagearea_inner">
                    <div id="messages"<?php echo $messages_style ?>><?php echo $messages ?></div><div class="dpclr">&nbsp;</div>
                </div>
            </div>
            <div id="dpinput_wrap">
                <div id="dpinput"><div id="dpinput_inner"><?php echo $dpinput ?></div></div>
                <div id="dpinput_say"><div id="dpinput_inner"><?php echo $dpinput_say ?></div></div>
            </div>
        </div>
        <div id="dpfooter">
            <a href="<?php echo DPSERVER_CLIENT_URL; ?>?location=/page/copyright.php">Legal
            Notices</a> | Running <a href="http://dutchpipe.org/" target="_blank">DutchPIPE</a> 0.4.1 by Lennert Stock.
        </div>
        &#160;<a name="bottom"></a>
    </body>
</html>