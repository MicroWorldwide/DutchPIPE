<?php /* Smarty version 2.6.0, created on 2007-06-11 15:25:23
         compiled from tutorial.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['nav']): ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="33%" align="left" valign="bottom"><?php if ($this->_tpl_vars['prev']): ?><img src="/images/arrl.gif" width="6" height="11" border="0" alt=" &gt; " align="absmiddle" class="arr" /><a href="<?php echo $this->_tpl_vars['prev']; ?>
"><?php echo $this->_tpl_vars['prevtitle']; ?>
</a><?php else: ?>&nbsp;<?php endif; ?></td>
<td width="34%" align="center" valign="bottom"><?php if ($this->_tpl_vars['up']): ?><a href="<?php echo $this->_tpl_vars['up']; ?>
">Up: <?php echo $this->_tpl_vars['uptitle']; ?>
</a><?php else: ?>&nbsp;<?php endif; ?></td>
<td width="33%" align="right" valign="bottom" style="text-align: right"><?php if ($this->_tpl_vars['next']): ?><a href="<?php echo $this->_tpl_vars['next']; ?>
"><?php echo $this->_tpl_vars['nexttitle']; ?>
</a><img src="/images/arr3.gif" width="6" height="11" border="0" alt=" &gt; " align="absmiddle" class="arrr" /><?php else: ?>&nbsp;<?php endif; ?></td>
</tr>
</table>
<?php endif;  echo $this->_tpl_vars['contents']; ?>

<?php if ($this->_tpl_vars['nav']): ?>
<br clear="all" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="33%" align="left" valign="top"><?php if ($this->_tpl_vars['prev']): ?><img src="/images/arrl.gif" width="6" height="11" border="0" alt=" &gt; " align="absmiddle" class="arr" /><a href="<?php echo $this->_tpl_vars['prev']; ?>
"><?php echo $this->_tpl_vars['prevtitle']; ?>
</a><?php else: ?>&nbsp;<?php endif; ?></td>
<td width="34%" align="center" valign="top"><?php if ($this->_tpl_vars['up']): ?><a href="<?php echo $this->_tpl_vars['up']; ?>
">Up: <?php echo $this->_tpl_vars['uptitle']; ?>
</a><?php else: ?>&nbsp;<?php endif; ?></td>
<td width="33%" align="right" valign="top" style="text-align: right"><?php if ($this->_tpl_vars['next']): ?><a href="<?php echo $this->_tpl_vars['next']; ?>
"><?php echo $this->_tpl_vars['nexttitle']; ?>
</a><img src="/images/arr3.gif" width="6" height="11" border="0" alt=" &gt; " align="absmiddle" class="arrr" /><?php else: ?>&nbsp;<?php endif; ?></td>
</tr>
</table>
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>