{extends file='widget.tpl'}
{block name=additionalHead}
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="../js/d3.hemicycle_rosnicka.js"></script>
    <script src="../js/d3.tip.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
{/block} 
{block name=body}
{include "vote_event-widget-body.tpl"}
{/block}
