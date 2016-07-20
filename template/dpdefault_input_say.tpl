<div class="dpclr">&nbsp;</div>
<table cellpadding="0" cellspacing="0" border="0" class="dpinput_say_table">
<tr>
    <td class="dpinput_say_left">
        <form id="actionform" method="get" onSubmit="return action_dutchpipe()">
            <input id="dpaction" name="dpaction" type="text" value="" size="80" maxlength="255" autocomplete="off" />
            <input id="dpinputpersistent" type="hidden" value="<?php echo $inputpersistent; ?>" />
        </form>
    </td>
    <td class="dpinput_say_right" width="28"><img id="dpinput_menu" class="dpinput_menu" src="<?php echo DPUNIVERSE_IMAGE_URL; ?>options.gif" width="13" height="13" border="0"
        alt="<?php echo dp_text('Input Field Options'); ?>" title="<?php echo dp_text('Input Field Options'); ?>" onclick="open_options(this,event)" /><img class="dpinput_close"
        src="<?php echo DPUNIVERSE_IMAGE_URL; ?>close.gif" width="13" height="13" border="0" alt="<?php echo dp_text('Close Input Field'); ?>" title="<?php echo dp_text('Close Input Field'); ?>"
        onclick="close_input()" /></td>
    <td width="11" class="dpinput_say_top"><img id="dpbuttop" src="<?php echo DPUNIVERSE_IMAGE_URL; ?>top.gif" width="11" height="7" border="0" alt="<?php echo dp_text('Go to Top') ?>" title="<?php echo dp_text('Go to Top') ?>" onClick="_gel('dpaction').focus(); setTimeout('gototop()', 10);" /></td>
</tr>
</table>
<div class="dpclr2">&nbsp;</div>
