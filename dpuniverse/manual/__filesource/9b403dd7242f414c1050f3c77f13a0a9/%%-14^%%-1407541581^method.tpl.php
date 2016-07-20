<?php /* Smarty version 2.6.0, created on 2007-06-11 15:25:45
         compiled from method.tpl */ ?>
<a name='method_detail'></a>
<?php if (isset($this->_sections['methods'])) unset($this->_sections['methods']);
$this->_sections['methods']['name'] = 'methods';
$this->_sections['methods']['loop'] = is_array($_loop=$this->_tpl_vars['methods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['methods']['show'] = true;
$this->_sections['methods']['max'] = $this->_sections['methods']['loop'];
$this->_sections['methods']['step'] = 1;
$this->_sections['methods']['start'] = $this->_sections['methods']['step'] > 0 ? 0 : $this->_sections['methods']['loop']-1;
if ($this->_sections['methods']['show']) {
    $this->_sections['methods']['total'] = $this->_sections['methods']['loop'];
    if ($this->_sections['methods']['total'] == 0)
        $this->_sections['methods']['show'] = false;
} else
    $this->_sections['methods']['total'] = 0;
if ($this->_sections['methods']['show']):

            for ($this->_sections['methods']['index'] = $this->_sections['methods']['start'], $this->_sections['methods']['iteration'] = 1;
                 $this->_sections['methods']['iteration'] <= $this->_sections['methods']['total'];
                 $this->_sections['methods']['index'] += $this->_sections['methods']['step'], $this->_sections['methods']['iteration']++):
$this->_sections['methods']['rownum'] = $this->_sections['methods']['iteration'];
$this->_sections['methods']['index_prev'] = $this->_sections['methods']['index'] - $this->_sections['methods']['step'];
$this->_sections['methods']['index_next'] = $this->_sections['methods']['index'] + $this->_sections['methods']['step'];
$this->_sections['methods']['first']      = ($this->_sections['methods']['iteration'] == 1);
$this->_sections['methods']['last']       = ($this->_sections['methods']['iteration'] == $this->_sections['methods']['total']);
 if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['static']): ?>
<a name="method<?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
" id="<?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
"><!-- --></a>
<div class="evenrow">
    <div>
        <span class="method-title">static method <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
</span>&nbsp;&nbsp;<span class="smalllinenumber">[line <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['slink']):  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['slink'];  else:  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['line_number'];  endif; ?>]</span>
    </div>
	<div class="row-padding">
        <code>static <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_return']; ?>
 <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['returnsref']): ?>&amp;<?php endif;  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
(
    <?php if (count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'] )): ?>
    <?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?>
    <?php if ($this->_sections['params']['iteration'] != 1): ?>, <?php endif; ?>
    <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?>[<?php endif;  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['type']; ?>

    <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['name'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?> = <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']; ?>
]<?php endif; ?>
    <?php endfor; endif; ?>
    &nbsp;
    <?php endif; ?>)</code><br /><br />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "docblock.tpl", 'smarty_include_vars' => array('sdesc' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['sdesc'],'desc' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['desc'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php if (count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'] ) > 0 || count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['api_tags'] ) > 0 || count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['info_tags'] ) > 0 || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides'] || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'] || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']): ?>
        <br />
        <div style="position: relative; width: 100%">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params']): ?>
          <?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?>
          <tr>
            <?php if ($this->_sections['params']['first']): ?>
            <td width="1%" rowspan="<?php echo $this->_sections['params']['total']; ?>
" style="background-color: #eeeeee"><span class="label-letter">PARAMETERS:</span></td>
            <?php endif; ?>
            <td width="1%" nowrap="nowrap" style="background-color: #eeeeee"><span class="var-type"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['datatype']; ?>
</span>&nbsp;&nbsp;</td>
            <td width="1%" nowrap="nowrap" style="background-color: #eeeeee"><span class="var-name"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['var']; ?>
&nbsp;</span></td>
            <td width="97%" style="background-color: #eeeeee"><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['data']): ?><span class="var-description"> <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['data']; ?>
</span><?php endif; ?></td>
          </tr>
          <?php endfor; endif; ?>
        <?php endif; ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tags.tpl", 'smarty_include_vars' => array('api_tags' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['api_tags'],'info_tags' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['info_tags'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">REDEFINITION&#160;OF:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['link'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['sdesc']): ?>: <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['sdesc'];  endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">IMPLEMENTATION&#160;OF:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php if (isset($this->_sections['imp'])) unset($this->_sections['imp']);
$this->_sections['imp']['name'] = 'imp';
$this->_sections['imp']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['imp']['show'] = true;
$this->_sections['imp']['max'] = $this->_sections['imp']['loop'];
$this->_sections['imp']['step'] = 1;
$this->_sections['imp']['start'] = $this->_sections['imp']['step'] > 0 ? 0 : $this->_sections['imp']['loop']-1;
if ($this->_sections['imp']['show']) {
    $this->_sections['imp']['total'] = $this->_sections['imp']['loop'];
    if ($this->_sections['imp']['total'] == 0)
        $this->_sections['imp']['show'] = false;
} else
    $this->_sections['imp']['total'] = 0;
if ($this->_sections['imp']['show']):

            for ($this->_sections['imp']['index'] = $this->_sections['imp']['start'], $this->_sections['imp']['iteration'] = 1;
                 $this->_sections['imp']['iteration'] <= $this->_sections['imp']['total'];
                 $this->_sections['imp']['index'] += $this->_sections['imp']['step'], $this->_sections['imp']['iteration']++):
$this->_sections['imp']['rownum'] = $this->_sections['imp']['iteration'];
$this->_sections['imp']['index_prev'] = $this->_sections['imp']['index'] - $this->_sections['imp']['step'];
$this->_sections['imp']['index_next'] = $this->_sections['imp']['index'] + $this->_sections['imp']['step'];
$this->_sections['imp']['first']      = ($this->_sections['imp']['iteration'] == 1);
$this->_sections['imp']['last']       = ($this->_sections['imp']['iteration'] == $this->_sections['imp']['total']);
?>
                    <dl>
                        <dt><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['link']; ?>
</dt>
                        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['sdesc']): ?>
                        <dd><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['sdesc']; ?>
</dd>
                        <?php endif; ?>
                    </dl>
                <?php endfor; endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">REDEFINED&#160;AS:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php if (isset($this->_sections['dm'])) unset($this->_sections['dm']);
$this->_sections['dm']['name'] = 'dm';
$this->_sections['dm']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dm']['show'] = true;
$this->_sections['dm']['max'] = $this->_sections['dm']['loop'];
$this->_sections['dm']['step'] = 1;
$this->_sections['dm']['start'] = $this->_sections['dm']['step'] > 0 ? 0 : $this->_sections['dm']['loop']-1;
if ($this->_sections['dm']['show']) {
    $this->_sections['dm']['total'] = $this->_sections['dm']['loop'];
    if ($this->_sections['dm']['total'] == 0)
        $this->_sections['dm']['show'] = false;
} else
    $this->_sections['dm']['total'] = 0;
if ($this->_sections['dm']['show']):

            for ($this->_sections['dm']['index'] = $this->_sections['dm']['start'], $this->_sections['dm']['iteration'] = 1;
                 $this->_sections['dm']['iteration'] <= $this->_sections['dm']['total'];
                 $this->_sections['dm']['index'] += $this->_sections['dm']['step'], $this->_sections['dm']['iteration']++):
$this->_sections['dm']['rownum'] = $this->_sections['dm']['iteration'];
$this->_sections['dm']['index_prev'] = $this->_sections['dm']['index'] - $this->_sections['dm']['step'];
$this->_sections['dm']['index_next'] = $this->_sections['dm']['index'] + $this->_sections['dm']['step'];
$this->_sections['dm']['first']      = ($this->_sections['dm']['iteration'] == 1);
$this->_sections['dm']['last']       = ($this->_sections['dm']['iteration'] == $this->_sections['dm']['total']);
?>
                    <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['link'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['sdesc']): ?>: <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['sdesc'];  endif; ?><br />
                <?php endfor; endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif;  endfor; endif; ?>

<?php if (isset($this->_sections['methods'])) unset($this->_sections['methods']);
$this->_sections['methods']['name'] = 'methods';
$this->_sections['methods']['loop'] = is_array($_loop=$this->_tpl_vars['methods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['methods']['show'] = true;
$this->_sections['methods']['max'] = $this->_sections['methods']['loop'];
$this->_sections['methods']['step'] = 1;
$this->_sections['methods']['start'] = $this->_sections['methods']['step'] > 0 ? 0 : $this->_sections['methods']['loop']-1;
if ($this->_sections['methods']['show']) {
    $this->_sections['methods']['total'] = $this->_sections['methods']['loop'];
    if ($this->_sections['methods']['total'] == 0)
        $this->_sections['methods']['show'] = false;
} else
    $this->_sections['methods']['total'] = 0;
if ($this->_sections['methods']['show']):

            for ($this->_sections['methods']['index'] = $this->_sections['methods']['start'], $this->_sections['methods']['iteration'] = 1;
                 $this->_sections['methods']['iteration'] <= $this->_sections['methods']['total'];
                 $this->_sections['methods']['index'] += $this->_sections['methods']['step'], $this->_sections['methods']['iteration']++):
$this->_sections['methods']['rownum'] = $this->_sections['methods']['iteration'];
$this->_sections['methods']['index_prev'] = $this->_sections['methods']['index'] - $this->_sections['methods']['step'];
$this->_sections['methods']['index_next'] = $this->_sections['methods']['index'] + $this->_sections['methods']['step'];
$this->_sections['methods']['first']      = ($this->_sections['methods']['iteration'] == 1);
$this->_sections['methods']['last']       = ($this->_sections['methods']['iteration'] == $this->_sections['methods']['total']);
 if (! $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['static']): ?>
<a name="method<?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
" id="<?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
"><!-- --></a>
<div class="evenrow">
    <div>
        <span class="method-title"><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['constructor']): ?>Constructor <?php elseif ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['destructor']): ?>Destructor <?php endif;  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
</span>&nbsp;&nbsp;<span class="smalllinenumber">[line <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['slink']):  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['slink'];  else:  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['line_number'];  endif; ?>]</span>
    </div>
	<div class="row-padding">
            <code><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_return']; ?>
 <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['returnsref']): ?>&amp;<?php endif;  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
(
    <?php if (count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'] )): ?>
    <?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?>
    <?php if ($this->_sections['params']['iteration'] != 1): ?>, <?php endif; ?>
    <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?>[<?php endif;  echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['type']; ?>

    <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['name'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?> = <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']; ?>
]<?php endif; ?>
    <?php endfor; endif; ?>
    &nbsp;
    <?php endif; ?>)</code><br /><br />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "docblock.tpl", 'smarty_include_vars' => array('sdesc' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['sdesc'],'desc' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['desc'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php if (count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'] ) > 0 || count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['api_tags'] ) > 0 || count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['info_tags'] ) > 0 || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides'] || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'] || $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']): ?>
        <br />
        <div style="position: relative; width: 100%">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params']): ?>
          <?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?>
          <tr>
            <?php if ($this->_sections['params']['first']): ?>
            <td width="1%" rowspan="<?php echo $this->_sections['params']['total']; ?>
" style="background-color: #eeeeee"><span class="label-letter">PARAMETERS:</span></td>
            <?php endif; ?>
            <td width="1%" nowrap="nowrap" style="background-color: #eeeeee"><span class="var-type"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['datatype']; ?>
</span>&nbsp;&nbsp;</td>
            <td width="1%" nowrap="nowrap" style="background-color: #eeeeee"><span class="var-name"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['var']; ?>
&nbsp;</span></td>
            <td width="97%" style="background-color: #eeeeee"><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['data']): ?><span class="var-description"> <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['params'][$this->_sections['params']['index']]['data']; ?>
</span><?php endif; ?></td>
          </tr>
          <?php endfor; endif; ?>
        <?php endif; ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tags.tpl", 'smarty_include_vars' => array('api_tags' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['api_tags'],'info_tags' => $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['info_tags'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">REDEFINITION&#160;OF:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['link'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['sdesc']): ?>: <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_overrides']['sdesc'];  endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">IMPLEMENTATION&#160;OF:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php if (isset($this->_sections['imp'])) unset($this->_sections['imp']);
$this->_sections['imp']['name'] = 'imp';
$this->_sections['imp']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['imp']['show'] = true;
$this->_sections['imp']['max'] = $this->_sections['imp']['loop'];
$this->_sections['imp']['step'] = 1;
$this->_sections['imp']['start'] = $this->_sections['imp']['step'] > 0 ? 0 : $this->_sections['imp']['loop']-1;
if ($this->_sections['imp']['show']) {
    $this->_sections['imp']['total'] = $this->_sections['imp']['loop'];
    if ($this->_sections['imp']['total'] == 0)
        $this->_sections['imp']['show'] = false;
} else
    $this->_sections['imp']['total'] = 0;
if ($this->_sections['imp']['show']):

            for ($this->_sections['imp']['index'] = $this->_sections['imp']['start'], $this->_sections['imp']['iteration'] = 1;
                 $this->_sections['imp']['iteration'] <= $this->_sections['imp']['total'];
                 $this->_sections['imp']['index'] += $this->_sections['imp']['step'], $this->_sections['imp']['iteration']++):
$this->_sections['imp']['rownum'] = $this->_sections['imp']['iteration'];
$this->_sections['imp']['index_prev'] = $this->_sections['imp']['index'] - $this->_sections['imp']['step'];
$this->_sections['imp']['index_next'] = $this->_sections['imp']['index'] + $this->_sections['imp']['step'];
$this->_sections['imp']['first']      = ($this->_sections['imp']['iteration'] == 1);
$this->_sections['imp']['last']       = ($this->_sections['imp']['iteration'] == $this->_sections['imp']['total']);
?>
                    <dl>
                        <dt><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['link']; ?>
</dt>
                        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['sdesc']): ?>
                        <dd><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['method_implements'][$this->_sections['imp']['index']]['sdesc']; ?>
</dd>
                        <?php endif; ?>
                    </dl>
                <?php endfor; endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']): ?>
          <tr>
            <td width="1%" style="background-color: #eeeeee"><span class="label-letter">REDEFINED&#160;AS:</span>&nbsp;&nbsp;</td>
            <td width="99%" style="background-color: #eeeeee" colspan="3">
                <?php if (isset($this->_sections['dm'])) unset($this->_sections['dm']);
$this->_sections['dm']['name'] = 'dm';
$this->_sections['dm']['loop'] = is_array($_loop=$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dm']['show'] = true;
$this->_sections['dm']['max'] = $this->_sections['dm']['loop'];
$this->_sections['dm']['step'] = 1;
$this->_sections['dm']['start'] = $this->_sections['dm']['step'] > 0 ? 0 : $this->_sections['dm']['loop']-1;
if ($this->_sections['dm']['show']) {
    $this->_sections['dm']['total'] = $this->_sections['dm']['loop'];
    if ($this->_sections['dm']['total'] == 0)
        $this->_sections['dm']['show'] = false;
} else
    $this->_sections['dm']['total'] = 0;
if ($this->_sections['dm']['show']):

            for ($this->_sections['dm']['index'] = $this->_sections['dm']['start'], $this->_sections['dm']['iteration'] = 1;
                 $this->_sections['dm']['iteration'] <= $this->_sections['dm']['total'];
                 $this->_sections['dm']['index'] += $this->_sections['dm']['step'], $this->_sections['dm']['iteration']++):
$this->_sections['dm']['rownum'] = $this->_sections['dm']['iteration'];
$this->_sections['dm']['index_prev'] = $this->_sections['dm']['index'] - $this->_sections['dm']['step'];
$this->_sections['dm']['index_next'] = $this->_sections['dm']['index'] + $this->_sections['dm']['step'];
$this->_sections['dm']['first']      = ($this->_sections['dm']['iteration'] == 1);
$this->_sections['dm']['last']       = ($this->_sections['dm']['iteration'] == $this->_sections['dm']['total']);
?>
                    <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['link'];  if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['sdesc']): ?>: <?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['descmethod'][$this->_sections['dm']['index']]['sdesc'];  endif; ?><br />
                <?php endfor; endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif;  endfor; endif; ?>