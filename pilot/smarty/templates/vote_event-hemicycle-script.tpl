<script type="text/javascript">
    var hemicycle = [{
      //"n":[8,11,15,19,22,26,29,33,37],
      "n": [6,9],
      "gap": 1.20,
      //"widthIcon": 0.39,
      "widthIcon": 0.52,
      "width": 400,
      "groups": {$virtualparties}
    }];
   /* Initialize tooltip */	
    tip = d3.tip().attr("class", "d3-tip").html(function(d) {
      if (d['single_match'] == 1) textoption = '{$text['ok_options'][1]}';
      else if (d['single_match'] == -1) textoption = '{$text['ok_options'][-1]}';
      else textoption = '{$text['ok_options'][0]}';
      return "<span class=\'stronger\'>" + d["name"] + ":</span><br>" + textoption + "<br>("+d["option"]+")";
    }); 
         {literal} 
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
    {/literal}
</script>
