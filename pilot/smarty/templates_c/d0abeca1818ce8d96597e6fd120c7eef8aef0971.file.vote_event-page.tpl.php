<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:869445849548c461aa8e930-95309370%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0abeca1818ce8d96597e6fd120c7eef8aef0971' => 
    array (
      0 => '../smarty/templates/vote_event-page.tpl',
      1 => 1415846165,
      2 => 'file',
    ),
    '26f0757f5183c5af464d9c2775732ea33f7e91cf' => 
    array (
      0 => '../smarty/templates/page.tpl',
      1 => 1418480653,
      2 => 'file',
    ),
    '18206816d881143e951bf7330bf7c95365ba761c' => 
    array (
      0 => '../smarty/templates/vote_event-page-top.tpl',
      1 => 1416814714,
      2 => 'file',
    ),
    'f7bd1033c2440c144fbc7ba62717afaf8c9193e1' => 
    array (
      0 => '../smarty/templates/vote_event-hemicycle-legend.tpl',
      1 => 1418478384,
      2 => 'file',
    ),
    '58807dbb4dcc5f77770d7b35e473ee80d11d22a2' => 
    array (
      0 => '../smarty/templates/vote_event-table.tpl',
      1 => 1418480786,
      2 => 'file',
    ),
    '7f3fc0b1fdab87724ed6eb13046077ea21233920' => 
    array (
      0 => '../smarty/templates/vote_event-hemicycle-script.tpl',
      1 => 1418472224,
      2 => 'file',
    ),
    'ec2c8727d9dea78fc0038bce33b651e2ede7c5a2' => 
    array (
      0 => '../smarty/templates/vote_event-tablesorter.tpl',
      1 => 1415843761,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '869445849548c461aa8e930-95309370',
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

    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="js/d3.hemicycle_rosnicka.js"></script>
    <script src="js/d3.tip.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href="css/tablesorter.css" rel="stylesheet">
 
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
      <?php $_template = new Smarty_Internal_Template("vote_event-page-top.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '869445849548c461aa8e930-95309370';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-page-top.tpl" */ ?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/usr/local/lib/php/Smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_escape')) include '/usr/local/lib/php/Smarty/plugins/modifier.escape.php';
?>      <div class="row">
        <div class="col-md-6">  
          <ul id="infobox_info">
            <li><h1><strong><?php echo $_smarty_tpl->getVariable('vote_event')->value->name;?>
</strong></h1></li>
            <li><strong><?php echo $_smarty_tpl->getVariable('vote_event')->value->description;?>
</strong></li>
            <li><strong><?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('vote_event')->value->start_date);?>
</strong></li>
            <?php if (isset($_smarty_tpl->getVariable('vote_event',null,true,false)->value->links[0]->url)&&($_smarty_tpl->getVariable('vote_event')->value->links[0]->url!='')){?><li> <a href="<?php echo $_smarty_tpl->getVariable('vote_event')->value->links[0]->url;?>
"><?php echo $_smarty_tpl->getVariable('vote_event')->value->motion->text;?>
&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a></li><?php }?>
            <li><a href="http://www.psp.cz/sqw/hlasy.sqw?g=<?php echo $_smarty_tpl->getVariable('vote_event')->value->identifier;?>
"><?php echo $_smarty_tpl->getVariable('text')->value['vote_event'];?>
&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a></li>
            <?php $_smarty_tpl->tpl_vars['ve_id'] = new Smarty_variable($_smarty_tpl->getVariable('vote_event')->value->identifier, null, null);?>
            <?php if (isset($_smarty_tpl->getVariable('issue',null,true,false)->value->vote_events->{$_smarty_tpl->getVariable('ve_id',null,true,false)->value}->subcategory)){?>
              <li> <?php echo $_smarty_tpl->getVariable('text')->value['tags'];?>
:
              <?php  $_smarty_tpl->tpl_vars['subcategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('issue')->value->vote_events->{$_smarty_tpl->getVariable('ve_id')->value}->subcategory; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
</a></span><?php if (!$_smarty_tpl->tpl_vars['subcategory']->last){?>, <?php }?>
              <?php }} ?>
              </li>
            <?php }?>
          </ul>
        </div> <!-- /col -->
        <div class="col-md-6">
          <div id="chart"></div>
          <div id="legend">
            <?php $_template = new Smarty_Internal_Template("vote_event-hemicycle-legend.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '1296991107548c4c99c5cb61-89766654';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-hemicycle-legend.tpl" */ ?>
<div class="row">
  <div class="col-sm-6">
    <i class="fa fa-star ko-color"> </i><?php echo $_smarty_tpl->getVariable('text')->value['legend_ko'];?>

  </div>
  <div class="col-sm-6">
    <i class="fa fa-star ok-color"> </i><?php echo $_smarty_tpl->getVariable('text')->value['legend_ok'];?>

  </div>
</div>
 <hr/>
<div class="row" style="margin: 0">
<?php echo $_smarty_tpl->getVariable('text')->value['legend'];?>
:<br/>
<p class="text-center">
<?php  $_smarty_tpl->tpl_vars['party'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('parties')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['party']->key => $_smarty_tpl->tpl_vars['party']->value){
?>
  <i class="fa fa-user" style="color:<?php echo $_smarty_tpl->getVariable('party')->value->color;?>
">&nbsp;</i><?php echo $_smarty_tpl->getVariable('party')->value->abbreviation;?>
&nbsp;
<?php }} ?>
</p>
</div>
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_event-hemicycle-legend.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
          </div>
        </div> <!-- /col -->
      </div> <!-- /row -->

<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_event-page-top.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
    </div> <!-- /modal-header -->
    <div class="modal-body">
      <?php $_template = new Smarty_Internal_Template("vote_event-table.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '869445849548c461aa8e930-95309370';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-table.tpl" */ ?>

      <table id="vote-event-table" class="table tablesorter">
        <thead>
          <tr>
            <th><?php echo $_smarty_tpl->getVariable('text')->value['name'];?>
</th>
            <th><?php echo $_smarty_tpl->getVariable('text')->value['party'];?>
</th>
            <th><?php echo $_smarty_tpl->getVariable('text')->value['voted'];?>
</th>
          </tr>
        </thead>
        <tbody>
        <?php  $_smarty_tpl->tpl_vars['party'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('parties')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['party']->key => $_smarty_tpl->tpl_vars['party']->value){
?>
          <?php  $_smarty_tpl->tpl_vars['person'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('party')->value->people; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['person']->key => $_smarty_tpl->tpl_vars['person']->value){
?>
            <tr>
              <td><a href="<?php echo $_smarty_tpl->getVariable('person')->value->link;?>
"><?php echo $_smarty_tpl->getVariable('person')->value->name;?>
</a></td>
              <td><a href="<?php echo $_smarty_tpl->getVariable('party')->value->link;?>
"><?php echo $_smarty_tpl->getVariable('party')->value->name;?>
</a></td>
              <td><?php echo ucfirst($_smarty_tpl->getVariable('person')->value->option);?>
</td>
            </tr>
          <?php }} ?>
        <?php }} ?>
        </tbody>
      </table>

<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_event-table.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
    </div> <!-- /modal-body-->
  </div> <!-- /modal content -->
</div> <!-- /modal -->
<?php $_template = new Smarty_Internal_Template("vote_event-hemicycle-script.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '869445849548c461aa8e930-95309370';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-hemicycle-script.tpl" */ ?>
<script type="text/javascript">
    var hemicycle = [{
      //"n":[8,11,15,19,22,26,29,33,37],
      "n": [6,9],
      "gap": 1.20,
      //"widthIcon": 0.39,
      "widthIcon": 0.52,
      "width": 400,
      "groups": <?php echo $_smarty_tpl->getVariable('virtualparties')->value;?>

    }];
   /* Initialize tooltip */	
    tip = d3.tip().attr("class", "d3-tip").html(function(d) {
      if (d['single_match'] == 1) textoption = '<?php echo $_smarty_tpl->getVariable('text')->value['ok_options'][1];?>
';
      else if (d['single_match'] == -1) textoption = '<?php echo $_smarty_tpl->getVariable('text')->value['ok_options'][-1];?>
';
      else textoption = '<?php echo $_smarty_tpl->getVariable('text')->value['ok_options'][0];?>
';
      return "<span class=\'stronger\'>" + d["name"] + ":</span><br>" + textoption + "<br>("+d["option"]+")";
    }); 
          
    var w=400,h=205,
        svg=d3.select("#chart")
            .append("svg")
            .attr("width",w)
            .attr("height",h);
    var hc = d3.hemicycle()
                .n(function(d) {return d.n;})
                .gap(function(d) {return d.gap;})
                .widthIcon(function(d) {return d.widthIcon;})
                .width(function(d) {return d.width;})
                .groups(function(d) {return d.groups;});  
    
    var item = svg.selectAll(".hc")
          .data(hemicycle)
       .enter()
        .append("svg:g")
        .call(hc);
        
	/* Invoke the tip in the context of your visualization */
    svg.call(tip);
	
	// Add tooltip div
    var div = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 1e-6);
    
</script>
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_event-hemicycle-script.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("vote_event-tablesorter.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '869445849548c461aa8e930-95309370';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 15:26:33
         compiled from "../smarty/templates/vote_event-tablesorter.tpl" */ ?>
<script>
$(document).ready(function(){
    $(function(){
        $("#vote-event-table").tablesorter();
    });
});
</script>
<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "../smarty/templates/vote_event-tablesorter.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
  
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("google_analytics.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </body>
</html>
