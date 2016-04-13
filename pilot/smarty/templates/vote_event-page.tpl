{extends file='page.tpl'}
{block name=additionalHead}
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="js/d3.hemicycle_rosnicka.js"></script>
    <script src="js/d3.tip.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href="css/tablesorter.css" rel="stylesheet">
{/block} 
{block name=body}
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      {include "vote_event-page-top.tpl"}
    </div> <!-- /modal-header -->
    <div class="modal-body">
      {include "vote_event-table.tpl"}
    </div> <!-- /modal-body-->
  </div> <!-- /modal content -->
</div> <!-- /modal -->
{include "vote_event-hemicycle-script.tpl"}
{include "vote_event-tablesorter.tpl"}
{/block}
