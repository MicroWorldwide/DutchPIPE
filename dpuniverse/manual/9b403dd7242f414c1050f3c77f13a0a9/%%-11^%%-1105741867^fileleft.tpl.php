<?php /* Smarty version 2.6.0, created on 2007-06-06 00:38:02
         compiled from fileleft.tpl */ ?>
<?php if (count($_from = (array)$this->_tpl_vars['fileleftindex'])):
    foreach ($_from as $this->_tpl_vars['subpackage'] => $this->_tpl_vars['files']):
?>
  <div class="package">
	<?php if ($this->_tpl_vars['subpackage'] != ""):  echo $this->_tpl_vars['subpackage']; ?>
<br /><?php endif; ?>
	<?php if (isset($this->_sections['files'])) unset($this->_sections['files']);
$this->_sections['files']['name'] = 'files';
$this->_sections['files']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['files']['show'] = true;
$this->_sections['files']['max'] = $this->_sections['files']['loop'];
$this->_sections['files']['step'] = 1;
$this->_sections['files']['start'] = $this->_sections['files']['step'] > 0 ? 0 : $this->_sections['files']['loop']-1;
if ($this->_sections['files']['show']) {
    $this->_sections['files']['total'] = $this->_sections['files']['loop'];
    if ($this->_sections['files']['total'] == 0)
        $this->_sections['files']['show'] = false;
} else
    $this->_sections['files']['total'] = 0;
if ($this->_sections['files']['show']):

            for ($this->_sections['files']['index'] = $this->_sections['files']['start'], $this->_sections['files']['iteration'] = 1;
                 $this->_sections['files']['iteration'] <= $this->_sections['files']['total'];
                 $this->_sections['files']['index'] += $this->_sections['files']['step'], $this->_sections['files']['iteration']++):
$this->_sections['files']['rownum'] = $this->_sections['files']['iteration'];
$this->_sections['files']['index_prev'] = $this->_sections['files']['index'] - $this->_sections['files']['step'];
$this->_sections['files']['index_next'] = $this->_sections['files']['index'] + $this->_sections['files']['step'];
$this->_sections['files']['first']      = ($this->_sections['files']['iteration'] == 1);
$this->_sections['files']['last']       = ($this->_sections['files']['iteration'] == $this->_sections['files']['total']);
?>
    <?php if ($this->_tpl_vars['subpackage'] != ""): ?><div style="padding-left: 1em;"><?php endif; ?>
		<?php if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['link'] != ''): ?><a href="<?php echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['link']; ?>
"><?php endif;  echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['title'];  if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['link'] != ''): ?></a><?php endif;  if (isset($this->_sections['fileclasses'])) unset($this->_sections['fileclasses']);
$this->_sections['fileclasses']['name'] = 'fileclasses';
$this->_sections['fileclasses']['loop'] = is_array($_loop=$this->_tpl_vars['files'][$this->_sections['files']['index']]['fileclasses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fileclasses']['show'] = true;
$this->_sections['fileclasses']['max'] = $this->_sections['fileclasses']['loop'];
$this->_sections['fileclasses']['step'] = 1;
$this->_sections['fileclasses']['start'] = $this->_sections['fileclasses']['step'] > 0 ? 0 : $this->_sections['fileclasses']['loop']-1;
if ($this->_sections['fileclasses']['show']) {
    $this->_sections['fileclasses']['total'] = $this->_sections['fileclasses']['loop'];
    if ($this->_sections['fileclasses']['total'] == 0)
        $this->_sections['fileclasses']['show'] = false;
} else
    $this->_sections['fileclasses']['total'] = 0;
if ($this->_sections['fileclasses']['show']):

            for ($this->_sections['fileclasses']['index'] = $this->_sections['fileclasses']['start'], $this->_sections['fileclasses']['iteration'] = 1;
                 $this->_sections['fileclasses']['iteration'] <= $this->_sections['fileclasses']['total'];
                 $this->_sections['fileclasses']['index'] += $this->_sections['fileclasses']['step'], $this->_sections['fileclasses']['iteration']++):
$this->_sections['fileclasses']['rownum'] = $this->_sections['fileclasses']['iteration'];
$this->_sections['fileclasses']['index_prev'] = $this->_sections['fileclasses']['index'] - $this->_sections['fileclasses']['step'];
$this->_sections['fileclasses']['index_next'] = $this->_sections['fileclasses']['index'] + $this->_sections['fileclasses']['step'];
$this->_sections['fileclasses']['first']      = ($this->_sections['fileclasses']['iteration'] == 1);
$this->_sections['fileclasses']['last']       = ($this->_sections['fileclasses']['iteration'] == $this->_sections['fileclasses']['total']);
 if ($this->_sections['fileclasses']['first']): ?>: <?php endif; ?>
            <?php if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['fileclasses'][$this->_sections['fileclasses']['index']]['link'] != ''): ?><a href="<?php echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['fileclasses'][$this->_sections['fileclasses']['index']]['link']; ?>
"><?php endif;  echo $this->_tpl_vars['files'][$this->_sections['files']['index']]['fileclasses'][$this->_sections['fileclasses']['index']]['title'];  if ($this->_tpl_vars['files'][$this->_sections['files']['index']]['fileclasses'][$this->_sections['fileclasses']['index']]['link'] != ''): ?></a><?php endif;  if (! $this->_sections['fileclasses']['last']): ?>,<?php endif; ?>
        <?php endfor; endif; ?>
        </div>
	<?php endfor; endif; ?>
  </div>
<?php endforeach; unset($_from); endif; ?>