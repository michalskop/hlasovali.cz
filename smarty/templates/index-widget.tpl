{extends file='widget.tpl'}
{block name=additionalHead}
<script src="http://d3js.org/d3.v3.js"></script>
<script src="../js/d3.tip.js"></script>
{/block}
{block name=body}
<a href="../"><div id="chart"></div></a>
{include "chart.tpl"}
{include "chart_legend.tpl"}

{/block}
