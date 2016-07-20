<?php /* Smarty version 2.6.0, created on 2007-06-11 15:25:24
         compiled from tutorial_toc.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'assign', 'tutorial_toc.tpl', 4, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['toc'] )):  if (isset($this->_sections['toc'])) unset($this->_sections['toc']);
$this->_sections['toc']['name'] = 'toc';
$this->_sections['toc']['loop'] = is_array($_loop=$this->_tpl_vars['toc']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['toc']['show'] = true;
$this->_sections['toc']['max'] = $this->_sections['toc']['loop'];
$this->_sections['toc']['step'] = 1;
$this->_sections['toc']['start'] = $this->_sections['toc']['step'] > 0 ? 0 : $this->_sections['toc']['loop']-1;
if ($this->_sections['toc']['show']) {
    $this->_sections['toc']['total'] = $this->_sections['toc']['loop'];
    if ($this->_sections['toc']['total'] == 0)
        $this->_sections['toc']['show'] = false;
} else
    $this->_sections['toc']['total'] = 0;
if ($this->_sections['toc']['show']):

            for ($this->_sections['toc']['index'] = $this->_sections['toc']['start'], $this->_sections['toc']['iteration'] = 1;
                 $this->_sections['toc']['iteration'] <= $this->_sections['toc']['total'];
                 $this->_sections['toc']['index'] += $this->_sections['toc']['step'], $this->_sections['toc']['iteration']++):
$this->_sections['toc']['rownum'] = $this->_sections['toc']['iteration'];
$this->_sections['toc']['index_prev'] = $this->_sections['toc']['index'] - $this->_sections['toc']['step'];
$this->_sections['toc']['index_next'] = $this->_sections['toc']['index'] + $this->_sections['toc']['step'];
$this->_sections['toc']['first']      = ($this->_sections['toc']['iteration'] == 1);
$this->_sections['toc']['last']       = ($this->_sections['toc']['iteration'] == $this->_sections['toc']['total']);
 if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'refsect1'):  echo smarty_function_assign(array('var' => 'context','value' => 'refsect1'), $this);?>

<img src="/images/block.gif" width="7" height="11" border="0" alt=" &gt; " align="absmiddle" class="block" /><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
<br />
<?php endif;  if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'refsect2'):  echo smarty_function_assign(array('var' => 'context','value' => 'refsect2'), $this);?>

&nbsp;&nbsp;&nbsp;<img src="/images/block.gif" width="7" height="11" border="0" alt=" &gt; " align="absmiddle" class="block" /><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
<br />
<?php endif;  if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'refsect3'):  echo smarty_function_assign(array('var' => 'context','value' => 'refsect3'), $this);?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/block.gif" width="7" height="11" border="0" alt=" &gt; " align="absmiddle" class="block" /><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
<br />
<?php endif;  if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'table'):  if ($this->_tpl_vars['context'] == 'refsect2'): ?>&nbsp;&nbsp;&nbsp;<?php endif;  if ($this->_tpl_vars['context'] == 'refsect3'): ?>&nbsp;&nbsp;&nbsp;<?php endif; ?>
Table: <?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>

<?php endif;  if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'example'):  if ($this->_tpl_vars['context'] == 'refsect2'): ?>&nbsp;&nbsp;&nbsp;<?php endif;  if ($this->_tpl_vars['context'] == 'refsect3'): ?>&nbsp;&nbsp;&nbsp;<?php endif; ?>
Table: <?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>

<?php endif;  endfor; endif; ?><br />
<?php endif; ?>