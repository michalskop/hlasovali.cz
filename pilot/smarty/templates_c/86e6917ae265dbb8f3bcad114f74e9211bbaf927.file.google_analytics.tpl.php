<?php /* Smarty version Smarty-3.0.7, created on 2014-12-13 14:58:50
         compiled from "../smarty/templates/google_analytics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:866541944548c461ac2ac27-16382010%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86e6917ae265dbb8f3bcad114f74e9211bbaf927' => 
    array (
      0 => '../smarty/templates/google_analytics.tpl',
      1 => 1416612701,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '866541944548c461ac2ac27-16382010',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $_smarty_tpl->getVariable('text')->value["google_analytics_code"];?>
', 'auto');
  ga('send', 'pageview');

</script>
