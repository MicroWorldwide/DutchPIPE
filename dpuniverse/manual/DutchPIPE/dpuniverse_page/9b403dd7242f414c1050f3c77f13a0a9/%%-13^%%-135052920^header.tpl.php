<?php /* Smarty version 2.6.0, created on 2007-06-11 15:25:48
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'assign', 'header.tpl', 27, false),array('function', 'eval', 'header.tpl', 31, false),array('modifier', 'capitalize', 'header.tpl', 60, false),)), $this); ?>
<table border="0" cellpadding="0" cellspacing="0" style="width: 98%">
  <tr valign="top">
    <td class="menu">
<?php if ($this->_tpl_vars['tutorials']): ?>
    <div class="package">
        <div class="package-title"><a href="/manual/index.html" class="package-title"><?php echo $this->_tpl_vars['package']; ?>
 Manual</a></div><br />
        <?php if ($this->_tpl_vars['tutorials']['pkg']): ?>
            <b>Chapters</b><br />
            <?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($_loop=$this->_tpl_vars['tutorials']['pkg']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
                <?php echo $this->_tpl_vars['tutorials']['pkg'][$this->_sections['ext']['index']]; ?>

            <?php endfor; endif; ?><br />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['tutorials']['proc']): ?>
            <b>File Tutorials</b><br />
            <?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($_loop=$this->_tpl_vars['tutorials']['proc']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
                <?php echo $this->_tpl_vars['tutorials']['proc'][$this->_sections['ext']['index']]; ?>

            <?php endfor; endif; ?><br />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['tutorials']['cls']): ?>
            <b>Class Tutorials</b><br />
            <?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($_loop=$this->_tpl_vars['tutorials']['cls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
                <?php echo $this->_tpl_vars['tutorials']['cls'][$this->_sections['ext']['index']]; ?>

            <?php endfor; endif; ?><br />
        <?php endif; ?>
    </div>
<?php endif; ?>
      <?php if (! $this->_tpl_vars['noleftindex']):  echo smarty_function_assign(array('var' => 'noleftindex','value' => false), $this); endif; ?>
      <?php if (! $this->_tpl_vars['noleftindex']): ?>
      <?php if ($this->_tpl_vars['compiledfileindex']): ?>
      <b>File/Class Source Reference</b><br />
      <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['compiledfileindex']), $this);?>

      <?php endif; ?>
      <br />
      <?php if ($this->_tpl_vars['compiledinterfaceindex']): ?>
      <b>Source Reference Interfaces</b><br />
      <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['compiledinterfaceindex']), $this);?>

      <?php endif; ?>
      <br />
      <?php endif; ?>
        <div class="package">
<?php if (count ( $this->_tpl_vars['ric'] ) >= 1): ?>
        <?php if (isset($this->_sections['ric'])) unset($this->_sections['ric']);
$this->_sections['ric']['name'] = 'ric';
$this->_sections['ric']['loop'] = is_array($_loop=$this->_tpl_vars['ric']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ric']['show'] = true;
$this->_sections['ric']['max'] = $this->_sections['ric']['loop'];
$this->_sections['ric']['step'] = 1;
$this->_sections['ric']['start'] = $this->_sections['ric']['step'] > 0 ? 0 : $this->_sections['ric']['loop']-1;
if ($this->_sections['ric']['show']) {
    $this->_sections['ric']['total'] = $this->_sections['ric']['loop'];
    if ($this->_sections['ric']['total'] == 0)
        $this->_sections['ric']['show'] = false;
} else
    $this->_sections['ric']['total'] = 0;
if ($this->_sections['ric']['show']):

            for ($this->_sections['ric']['index'] = $this->_sections['ric']['start'], $this->_sections['ric']['iteration'] = 1;
                 $this->_sections['ric']['iteration'] <= $this->_sections['ric']['total'];
                 $this->_sections['ric']['index'] += $this->_sections['ric']['step'], $this->_sections['ric']['iteration']++):
$this->_sections['ric']['rownum'] = $this->_sections['ric']['iteration'];
$this->_sections['ric']['index_prev'] = $this->_sections['ric']['index'] - $this->_sections['ric']['step'];
$this->_sections['ric']['index_next'] = $this->_sections['ric']['index'] + $this->_sections['ric']['step'];
$this->_sections['ric']['first']      = ($this->_sections['ric']['iteration'] == 1);
$this->_sections['ric']['last']       = ($this->_sections['ric']['iteration'] == $this->_sections['ric']['total']);
?>
            <a href="<?php echo $this->_tpl_vars['subdir'];  echo $this->_tpl_vars['ric'][$this->_sections['ric']['index']]['file']; ?>
"><?php echo $this->_tpl_vars['ric'][$this->_sections['ric']['index']]['name']; ?>
</a>&nbsp;|
        <?php endfor; endif;  endif;  if ($this->_tpl_vars['hastodos']): ?>
            <a href="<?php echo $this->_tpl_vars['subdir'];  echo $this->_tpl_vars['todolink']; ?>
">Todo List</a>&nbsp;|
<?php endif; ?>
        <a href="<?php echo $this->_tpl_vars['subdir']; ?>
classtrees_<?php echo $this->_tpl_vars['package']; ?>
.html">Class Tree</a>&nbsp;|
        <a href="<?php echo $this->_tpl_vars['subdir']; ?>
elementindex_<?php echo $this->_tpl_vars['package']; ?>
.html">Index</a>
        </div>
    </td>
    <td>
      <table cellpadding="10" cellspacing="0" width="100%" border="0"><tr><td valign="top">
<span id="gototop" style="position:absolute;z-index:100"><a href="javascript:window.scrollTo(0,0)"><img id="buttop" src="/images/top.gif" width="11" height="11" border="0" alt="Go to Top" title="Go to Top" /></a></span>
<script src="/jumptop.js"></script>
<?php if (! $this->_tpl_vars['hasel']):  echo smarty_function_assign(array('var' => 'hasel','value' => false), $this); endif;  if ($this->_tpl_vars['eltype'] == 'class' && $this->_tpl_vars['is_interface']):  echo smarty_function_assign(array('var' => 'eltype','value' => 'interface'), $this); endif;  if ($this->_tpl_vars['hasel']): ?>
<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['eltype'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
: <?php echo $this->_tpl_vars['class_name']; ?>
</h1>
<span class="label-letter">SOURCE LOCATION:</span>&nbsp; <?php echo $this->_tpl_vars['source_location']; ?>
<br />
<?php endif; ?>