<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo dptext('DutchPIPE - PHP/AJAX Interactive Virtual Environments on Websites') ?></title>
        <meta name="generator" content="<?php echo dptext('DutchPIPE') ?>" />
        <meta name="keywords" content="<?php echo dptext('DutchPIPE, A Dutchman\'s Persistant Interactive Page Environments, PHP, AJAX, DOM, DHTML, PHP5, PHP 5, Virtual, Multi User, Object Oriented, Persistent State, Lennert Stock, Shoutbox') ?>" />
        <meta name="description" content="<?php echo dptext('DutchPIPE stands for a Dutchman\'s Persistant Interactive Page Environments. With DutchPIPE, each page becomes an abstracted environment or location where visitors and other items on the page are visualized. This status is retained as visitors move around. A whole lot of real-time interaction is possible. DutchPIPE uses AJAX/DOM/PHP.') ?>" />
        <link rel="stylesheet" href="<?php echo DPSERVER_CLIENT_DIR; ?>dpclient.css" type="text/css" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <script type="text/javascript" src="<?php echo DPSERVER_CLIENTJS_URL; ?>"></script>
        <noscript><?php echo dptext('Your browser must have JavaScript enabled in order to view this page.<br />') ?></noscript>
<?php echo $scripts ?>
<?php echo $dpelements ?>
    </head>
    <body>
        <?php echo $windows ?><?php echo $body ?>
        <div id="dpmessagearea">
            <div id="dpmessagearea_inner">
                <div id="messages"><br clear="all" /><?php echo $messages ?></div><br clear="all" />
                <div style="position: relative; width: 100%">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><form id="actionform" method="get" onSubmit="return action_dutchpipe()"><input id="dpaction" type="text" name="dpaction" value="" size="40" maxlength="255" style="float: left; margin-top: <?php echo $inputtopmargin ?>px" /></form></td>
                            <td width="11"><div style="margin: 0px; float: right; width: 11px; padding-left: 6px"><img id="buttop" src="images/top.gif" width="11" height="11" border="0" alt="<?php echo dptext('Go to Top') ?>" title="<?php echo dptext('Go to Top') ?>" onClick="_gel('dpaction').focus(); setTimeout('gototop()', 10);" /></div><br clear="all" /></td>
                        </tr>
                    </table>
                </div>
            </div><br clear="all" />
        </div>
        <div id="dpfooter">
            <a href="<?php echo DPSERVER_CLIENT_URL; ?>?location=/page/copyright.php">Legal
            Notices</a> | Running DutchPIPE 0.2.1 by Lennert Stock.
        </div>
        &#160;<a name="bottom"></a>
    </body>
</html>
