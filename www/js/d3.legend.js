d3.legend = function() {
    //defaults
    var width = 400,
        label = ['legend'],
        icons = [];
    //note: other defaults - for individual icons - are defined inside the code
    
    function legend(selection) {
        selection.each(function(d,i) {
            //parameters may be functions and we MUST to rename them
            var width_val = (typeof(width) === "function" ? width(d) : width),
                label_val = (typeof(label) === "function" ? label(d) : label),
                icons_val = (typeof(icons) === "function" ? icons(d) : icons);
            
            var nicons = icons_val.length;
            
            //scales
            var xScale = d3.scale.linear()
	          .domain([0, 1])
	          .range([0, width_val]),
	        yScale = d3.scale.linear()
              .domain([0, 1])
              .range([0,width_val/8]);
            
            var element = d3.select(this);
                        
            element.selectAll('.legend-label')
                .data(label_val)
              .enter()
                .append("text")
                .attr('font-size',yScale(0.33))
                .attr('font-family', "'Helvetica Neue', Helvetica, Arial, sans-serif")
                .attr('x', xScale(0.5))
                .attr('y', yScale(0.4))
                .attr('text-anchor',"middle")
                .attr('fill','gray')
                .text(function(d) {return d})
                .classed("legend-label", true);
            
            element.selectAll('.legend-icon')
                .data(icons_val)
              .enter()
                .append("text")
                .attr('x',function(d,i) {
                  return xScale((i+1)/(nicons+1));
                })
                .attr('y',yScale(0.9))
                .attr('font-size',yScale(0.33))
                .attr("text-anchor","middle")
                .classed("legend-icon",true)
                .append("tspan")
                  .attr('font-family', 'FontAwesome')
                  .text(function(d) {
                    if (typeof(d.icon) === 'undefined') return '\uf0a3';
                    else return d.icon;
                  })
                  .attr('fill', function(d) {
                      if (typeof(d.color) === 'undefined') return 'gray';
                      else return d.color;
                  })
                  .append("tspan")
                    .attr('font-family', "'Helvetica Neue', Helvetica, Arial, sans-serif")
                    .text(function(d) {return ' ' + d.text})
                    .attr('fill', function(d) {
                      if (typeof(d.color) === 'undefined') return 'gray';
                      else return d.color;
                    });

        });
    }
    
    legend.width = function(value) {
        if (!arguments.length) return value;
        width = value;
        return legend;
    };  
    legend.label = function(value) {
        if (!arguments.length) return value;
        label = value;
        return legend;
    };
    legend.icons = function(value) {
        if (!arguments.length) return value;
        icons = value;
        return legend;
    }; 
    
    return legend; 
    
}
