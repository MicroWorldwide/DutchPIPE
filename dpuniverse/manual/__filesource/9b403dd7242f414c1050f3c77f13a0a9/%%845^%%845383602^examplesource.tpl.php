<?php /* Smarty version 2.6.0, created on 2006-08-13 20:43:07
         compiled from examplesource.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
<div class="src-code"><span class="php">
<?php echo $this->_tpl_vars['source']; ?>

</span></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>