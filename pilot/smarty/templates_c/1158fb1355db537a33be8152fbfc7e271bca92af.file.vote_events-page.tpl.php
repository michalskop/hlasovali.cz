<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:24:19
         compiled from "../smarty/templates/vote_events-page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:299772035548c4b12bfacc2-67853615%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1158fb1355db537a33be8152fbfc7e271bca92af' => 
    array (
      0 => '../smarty/templates/vote_events-page.tpl',
      1 => 1416009374,
      2 => 'file',
    ),
    '26f0757f5183c5af464d9c2775732ea33f7e91cf' => 
    array (
      0 => '../smarty/templates/page.tpl',
      1 => 1418480653,
      2 => 'file',
    ),
    '3afb4a879666b7101caee4f136cd23f012d860d7' => 
    array (
      0 => '../smarty/templates/vote_events-page-top.tpl',
      1 => 1416012679,
      2 => 'file',
    ),
    'bc0486109abb641b47eec0411be0c58e7a4c62ae' => 
    array (
      0 => '../smarty/templates/vote_events-page-table.tpl',
      1 => 1416011318,
      2 => 'file',
    ),
    '31be2d7137e8358bc2812b4e3024984686f1537c' => 
    array (
      0 => '../smarty/templates/vote_events-page-vote_event.tpl',
      1 => 1418478771,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '299772035548c4b12bfacc2-67853615',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->getVariable('text')->value['lang'];?>
">
  <head>
    <title><?php echo $_smarty_tpl->getVariable('title')->value;?>
</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $_smarty_tpl->getVariable('text')->value['description'];?>
">
    <meta name="keywords" content="<?php echo $_smarty_tpl->getVariable('text')->value['keywords'];?>
">
    <meta name="author" content="<?php echo $_smarty_tpl->getVariable('text')->value['author'];?>
">
    
    <meta property="og:image" content="<?php echo $_smarty_tpl->getVariable('text')->value['og:image'];?>
"/>
	<meta property="og:title" content="<?php echo $_smarty_tpl->getVariable('text')->value['og:title'];?>
"/>
	<meta property="og:url" content="<?php echo $_smarty_tpl->getVariable('text')->value['og:url'];?>
"/>
	<meta property="og:site_name" content="<?php echo $_smarty_tpl->getVariable('text')->value['og:site_name'];?>
"/>
	<meta property="og:type" content="website"/>
	
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/sandstone/bootstrap.min.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/page.css">
    <link rel="shortcut icon" title="Shortcut icon" href="http://www.zelenykruh.cz/favicon.ico" />
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type='text/javascript' src="http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/js/filter.js"></script> 
  </head>
  <body>
<?php $_template = new Smarty_Internal_Template("header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<div class="container">
    <div class="well opaque">
        <div class="form-group col-md-8" style="float:none">
            <div class="input-group">
                <input type="texst" placeholder="<?php echo $_smarty_tpl->getVariable('text')->value['select_people_parties'];?>
" class="form-control input-lg non-opaque typeahead" id="search-input">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
            </div>
        </div>
    </div>
</div>

<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      <?php $_template = new Smarty_Internal_Template("vote_events-page-top.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '299772035548c4b12bfacc2-67853615';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:24:19
         compiled from "../smarty/templates/vote_events-page-top.tpl" */ ?>
<div class="row">
<ul id="infobox_info">
    <li><h1><strong><?php echo $_smarty_tpl->getVariable('text')->value['vote-events'];?>
</strong></h1></li>
    <li><?php echo $_smarty_tpl->getVariable('text')->value['term'];?>
: <strong><?php echo $_smarty_tpl->getVariable('term')->value->name;?>
</strong></li>
    <?php if (($_smarty_tpl->getVariable('tag')->value!='')){?>
    <li><?php echo $_smarty_tpl->getVariable('text')->value['tags'];?>
: <strong><?php echo $_smarty_tpl->getVariable('tag')->value;?>
</strong></li>
    <?php }?>
</ul>
</div> <!-- /row -->
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_events-page-top.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
    </div> <!-- /modal-header -->
    <div class="modal-body">
      <?php $_template = new Smarty_Internal_Template("vote_events-page-table.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '299772035548c4b12bfacc2-67853615';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:24:19
         compiled from "../smarty/templates/vote_events-page-table.tpl" */ ?>
<div style="clear:both;"></div>

<div class="infobox_content modal-body">
 <?php  $_smarty_tpl->tpl_vars['ve'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('vote_events')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['ve']->iteration=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['ve']->key => $_smarty_tpl->tpl_vars['ve']->value){
 $_smarty_tpl->tpl_vars['ve']->iteration++;
?>
   <?php if (!($_smarty_tpl->tpl_vars['ve']->iteration % 2)){?>
     <div class="row">
   <?php }?>
   <?php $_template = new Smarty_Internal_Template("vote_events-page-vote_event.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '810387000548c4c138d6379-16136206';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:24:19
         compiled from "../smarty/templates/vote_events-page-vote_event.tpl" */ ?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/usr/local/lib/php/Smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_escape')) include '/usr/local/lib/php/Smarty/plugins/modifier.escape.php';
?><div class="col-md-6 each_vote_container">
  <div class="panel panel-<?php if ($_smarty_tpl->getVariable('ve')->value->ok==-1){?>danger<?php }elseif($_smarty_tpl->getVariable('ve')->value->ok==1){?>success<?php }else{ ?>default<?php }?>">
    <div class="panel-heading">
      <h3><a href="./vote-event.php?identifier=<?php echo $_smarty_tpl->getVariable('ve')->value->identifier;?>
<?php echo $_smarty_tpl->getVariable('term_chunk')->value;?>
"><?php echo $_smarty_tpl->getVariable('ve')->value->name;?>
</a></h3>
	    <div><small>
	      <?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('ve')->value->start_date);?>

	      <?php if (isset($_smarty_tpl->getVariable('ve',null,true,false)->value->links[0]->url)&&($_smarty_tpl->getVariable('ve')->value->links[0]->url!='')){?>, <a href="<?php echo $_smarty_tpl->getVariable('ve')->value->links[0]->url;?>
"><?php echo $_smarty_tpl->getVariable('ve')->value->motion->text;?>
</a><?php }?>
          <br/>
           <?php echo $_smarty_tpl->getVariable('text')->value['tags'];?>
: <?php  $_smarty_tpl->tpl_vars['subcategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('ve')->value->subcategory; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['subcategory']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['subcategory']->iteration=0;
if ($_smarty_tpl->tpl_vars['subcategory']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['subcategory']->key => $_smarty_tpl->tpl_vars['subcategory']->value){
 $_smarty_tpl->tpl_vars['subcategory']->iteration++;
 $_smarty_tpl->tpl_vars['subcategory']->last = $_smarty_tpl->tpl_vars['subcategory']->iteration === $_smarty_tpl->tpl_vars['subcategory']->total;
?>
                <span class="tag label label-warning"><a href="vote-event.php?tag=<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['subcategory']->value,'url');?>
<?php echo $_smarty_tpl->getVariable('term_chunk')->value;?>
"><?php echo $_smarty_tpl->tpl_vars['subcategory']->value;?>
</a><?php if (!$_smarty_tpl->tpl_vars['subcategory']->last){?></span>, <?php }?>
           <?php }} ?>
	      </small>
	    </div>
    </div>
    <div class="panel-body">
      <p><?php echo $_smarty_tpl->getVariable('ve')->value->description;?>
</p>
    </div>
  </div>
</div>
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_events-page-vote_event.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
   <?php if (!($_smarty_tpl->tpl_vars['ve']->iteration % 2)){?>
     </div>
   <?php }?> 
 <?php }} ?>
<div style="clear:both;margin-bottom: 20px;"></div>
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_events-page-table.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
    </div> <!-- /modal-body-->
  </div> <!-- /modal content -->
</div> <!-- /modal -->  
  
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("google_analytics.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </body>
</html>
