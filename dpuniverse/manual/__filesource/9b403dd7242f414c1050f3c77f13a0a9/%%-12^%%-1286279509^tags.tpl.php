<?php /* Smarty version 2.6.0, created on 2007-06-11 15:25:45
         compiled from tags.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'tags.tpl', 9, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['api_tags'] ) > 0):  if (isset($this->_sections['tag'])) unset($this->_sections['tag']);
$this->_sections['tag']['name'] = 'tag';
$this->_sections['tag']['loop'] = is_array($_loop=$this->_tpl_vars['api_tags']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['tag']['show'] = true;
$this->_sections['tag']['max'] = $this->_sections['tag']['loop'];
$this->_sections['tag']['step'] = 1;
$this->_sections['tag']['start'] = $this->_sections['tag']['step'] > 0 ? 0 : $this->_sections['tag']['loop']-1;
if ($this->_sections['tag']['show']) {
    $this->_sections['tag']['total'] = $this->_sections['tag']['loop'];
    if ($this->_sections['tag']['total'] == 0)
        $this->_sections['tag']['show'] = false;
} else
    $this->_sections['tag']['total'] = 0;
if ($this->_sections['tag']['show']):

            for ($this->_sections['tag']['index'] = $this->_sections['tag']['start'], $this->_sections['tag']['iteration'] = 1;
                 $this->_sections['tag']['iteration'] <= $this->_sections['tag']['total'];
                 $this->_sections['tag']['index'] += $this->_sections['tag']['step'], $this->_sections['tag']['iteration']++):
$this->_sections['tag']['rownum'] = $this->_sections['tag']['iteration'];
$this->_sections['tag']['index_prev'] = $this->_sections['tag']['index'] - $this->_sections['tag']['step'];
$this->_sections['tag']['index_next'] = $this->_sections['tag']['index'] + $this->_sections['tag']['step'];
$this->_sections['tag']['first']      = ($this->_sections['tag']['iteration'] == 1);
$this->_sections['tag']['last']       = ($this->_sections['tag']['iteration'] == $this->_sections['tag']['total']);
?>
  <?php if ($this->_tpl_vars['api_tags'][$this->_sections['tag']['index']]['keyword'] == 'return'): ?>
  <tr>
    <td width="1%" style="background-color: #eeeeee"><span class="label-letter">RETURNS:</span>&nbsp;&nbsp;</td><td width="1%" style="background-color: #eeeeee"><span class="var-type"><?php echo $this->_tpl_vars['api_tags'][$this->_sections['tag']['index']]['returntype']; ?>
</span>&nbsp;&nbsp;</td><td width="98%" colspan="2" style="background-color: #eeeeee"><?php echo $this->_tpl_vars['api_tags'][$this->_sections['tag']['index']]['data']; ?>
</td>
  </tr>
  <?php else: ?>
  <tr>
    <td width="1%" style="background-color: #eeeeee"><span class="label-letter"><?php echo ((is_array($_tmp=$this->_tpl_vars['api_tags'][$this->_sections['tag']['index']]['keyword'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
:</span>&nbsp;&nbsp;</td><td width="99%" colspan="3" style="background-color: #eeeeee"><?php echo $this->_tpl_vars['api_tags'][$this->_sections['tag']['index']]['data']; ?>
</td>
  </tr>
  <?php endif;  endfor; endif;  endif; ?>

<?php if (count ( $this->_tpl_vars['info_tags'] ) > 0):  if (isset($this->_sections['tag'])) unset($this->_sections['tag']);
$this->_sections['tag']['name'] = 'tag';
$this->_sections['tag']['loop'] = is_array($_loop=$this->_tpl_vars['info_tags']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['tag']['show'] = true;
$this->_sections['tag']['max'] = $this->_sections['tag']['loop'];
$this->_sections['tag']['step'] = 1;
$this->_sections['tag']['start'] = $this->_sections['tag']['step'] > 0 ? 0 : $this->_sections['tag']['loop']-1;
if ($this->_sections['tag']['show']) {
    $this->_sections['tag']['total'] = $this->_sections['tag']['loop'];
    if ($this->_sections['tag']['total'] == 0)
        $this->_sections['tag']['show'] = false;
} else
    $this->_sections['tag']['total'] = 0;
if ($this->_sections['tag']['show']):

            for ($this->_sections['tag']['index'] = $this->_sections['tag']['start'], $this->_sections['tag']['iteration'] = 1;
                 $this->_sections['tag']['iteration'] <= $this->_sections['tag']['total'];
                 $this->_sections['tag']['index'] += $this->_sections['tag']['step'], $this->_sections['tag']['iteration']++):
$this->_sections['tag']['rownum'] = $this->_sections['tag']['iteration'];
$this->_sections['tag']['index_prev'] = $this->_sections['tag']['index'] - $this->_sections['tag']['step'];
$this->_sections['tag']['index_next'] = $this->_sections['tag']['index'] + $this->_sections['tag']['step'];
$this->_sections['tag']['first']      = ($this->_sections['tag']['iteration'] == 1);
$this->_sections['tag']['last']       = ($this->_sections['tag']['iteration'] == $this->_sections['tag']['total']);
?>
  <tr>
    <td width="1%" style="background-color: #eeeeee"><span class="label-letter"><?php echo ((is_array($_tmp=$this->_tpl_vars['info_tags'][$this->_sections['tag']['index']]['keyword'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
:</span>&nbsp;&nbsp;</td><td width="99%" colspan="3" style="background-color: #eeeeee"><?php echo $this->_tpl_vars['info_tags'][$this->_sections['tag']['index']]['data']; ?>
</td>
  </tr>
<?php endfor; endif;  endif; ?>