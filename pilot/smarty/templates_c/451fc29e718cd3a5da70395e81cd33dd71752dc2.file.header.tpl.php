<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 14:58:50
         compiled from "../smarty/templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:476487259548c461abcff72-88029015%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '451fc29e718cd3a5da70395e81cd33dd71752dc2' => 
    array (
      0 => '../smarty/templates/header.tpl',
      1 => 1417196261,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '476487259548c461abcff72-88029015',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <a href="../" class="navbar-brand"><?php echo $_smarty_tpl->getVariable('header')->value['name'];?>
</a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-main">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes"><?php echo $_smarty_tpl->getVariable('text')->value['terms'];?>
 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo $_smarty_tpl->getVariable('header')->value['link_without_term'];?>
"><?php echo $_smarty_tpl->getVariable('text')->value['all_terms'];?>
</a></li>
            <?php $_smarty_tpl->tpl_vars['lasttype'] = new Smarty_variable('', null, null);?>
            <?php  $_smarty_tpl->tpl_vars['term'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('header')->value['terms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['term']->key => $_smarty_tpl->tpl_vars['term']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['term']->key;
?>
              <?php if (($_smarty_tpl->getVariable('lasttype')->value!=$_smarty_tpl->getVariable('term')->value->type)){?>
              <li class="divider"></li>
              <?php $_smarty_tpl->tpl_vars['lasttype'] = new Smarty_variable($_smarty_tpl->getVariable('term')->value->type, null, null);?>
              <?php }?>
            <li><a href="<?php echo $_smarty_tpl->getVariable('header')->value['link_without_term'];?>
&term=<?php echo $_smarty_tpl->getVariable('term')->value->identifier;?>
"><?php echo $_smarty_tpl->getVariable('term')->value->name;?>
</a></li>
            <?php }} ?>
          </ul>
        </li>
        <li><a href="/?<?php echo $_smarty_tpl->getVariable('term_chunk')->value;?>
"><?php echo $_smarty_tpl->getVariable('text')->value['parties'];?>
</a></li>
        <li><a href="/vote-event.php?<?php echo $_smarty_tpl->getVariable('term_chunk')->value;?>
"><?php echo $_smarty_tpl->getVariable('text')->value['vote_events'];?>
</a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/widgets/"><?php echo $_smarty_tpl->getVariable('text')->value['widgets'];?>
</a></li>
        <li><a href="/methodology/"><?php echo $_smarty_tpl->getVariable('text')->value['methodology'];?>
</a></li>
      </ul>
    </div>
  </div>
</div>
