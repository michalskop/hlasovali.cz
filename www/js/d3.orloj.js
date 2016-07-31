d3.orloj = function() {
    //defaults
    var width = 400,
        rows = 1,
        icons = [],
        iconHeight = null,
        line = true;
    //note: other defaults - for individual icons - are defined inside the code


    function orloj(selection) {
        selection.each(function(d,i) {
            //parameters may be functions and we MUST to rename them
            var width_val = (typeof(width) === "function" ? width(d) : width),
                rows_val = (typeof(rows) === "function" ? rows(d) : rows),
                iconHeight_val = (typeof(iconHeight) === "function" ? iconHeight(d) : iconHeight),
                icons_val = (typeof(icons) === "function" ? icons(d) : icons),
                line_val = (typeof(line) === "function" ? line(d) : line);
                
            var nicons = icons_val.length,
                maxinrow = Math.ceil(nicons/rows_val)
            
            //scales
            var xScale = d3.scale.linear()
	          .domain([0, 1])
	          .range([0, width_val]),
	        yScale = d3.scale.linear()
              .domain([0, 1])
              .range([0,rows_val*width_val/16]);
            
            var element = d3.select(this);
            
            if (line_val) 
                element.append("line")
                    .attr("x1",xScale(0))
                    .attr("y1",yScale(0))
                    .attr("x2",xScale(1))
                    .attr("y2",yScale(0))
                    .attr("stroke-width",1)
                    .attr("stroke","gray")
                    .attr("stroke-opacity",0.5);
            
            element.selectAll('.orloj-icon')
                .data(icons_val)
              .enter()
                .append("text")   
                .attr('x', function(d,i) {
                    row = Math.floor(i/maxinrow)+1;
                    if (row == rows_val) inrow = nicons - (rows_val-1)*maxinrow;
                    else inrow = maxinrow;
                    posinrow = i % maxinrow;
                    return xScale((posinrow+0.5)/(inrow));
                })
                .attr('y', function(d,i) {
                  row = Math.floor(i/maxinrow)+1;
                  return yScale(0.05+0.9/rows_val*row);
                })
                .attr('font-size',function(d) {
                  if (!iconHeight_val) return yScale(0.9/rows_val)
                  else return iconHeight_val;
                })
                .attr("text-anchor","middle")
                .classed("orloj-icon",true)
                .append("tspan")
                  .attr("font-family","FontAwesome")
                  .text(function(d) {
                    if (typeof(d.icon) === 'undefined') return '\uf007';
                    else return d.icon;
                  })
                  .attr("fill", function(d) {
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
            
        })      
    }

    orloj.width = function(value) {
        if (!arguments.length) return value;
        width = value;
        return orloj;
    };

    orloj.rows = function(value) {
        if (!arguments.length) return value;
        rows = value;
        return orloj;
    };
    orloj.icons = function(value) {
        if (!arguments.length) return value;
        icons = value;
        return orloj;
    }; 
    orloj.iconHeight = function(value) {
        if (!arguments.length) return value;
        iconHeight = value;
        return orloj;
    };
    orloj.line = function(value) {
        if (!arguments.length) return value;
        line = value;
        return orloj;
    }; 
    
    return orloj; 
  
}
