d3.hemicycle = function() {
    //defaults
    var gap = 1.2,
        widthIcon = 0.39,
        width = 400,
        arcs=[];
        //note: other defaults - for people - are defined inside the code


    function hemicycle(selection) {
        selection.each(function(d, index) {
            //parameters may be functions and we MUST to rename them
            var gap_val = (typeof(gap) === "function" ? gap(d) : gap),
                widthIcon_val = (typeof(widthIcon) === "function" ? widthIcon(d) : widthIcon),
                width_val = (typeof(width) === "function" ? width(d) : width);
                people_val = (typeof(people) === "function" ? people(d) : people),
                n_val = (typeof(n) === "function" ? n(d) : (typeof(n) === 'undefined' ? [people_val.length] : n));
            var arcs_val = (typeof(arcs) === "function" ? arcs(d) : arcs),
                score_val = (typeof(score) === "function" ? score(d) : score);

            var rmax = 1 + n_val.length *gap_val*widthIcon_val;

            //scales
            var xScale = d3.scale.linear()
                  .domain([-1*rmax, rmax])
                  .range([0, width_val]),
                xxScale = d3.scale.linear()
                  .domain([0, 2*rmax])
                  .range([0, width_val])
                yScale = d3.scale.linear()
                  .domain([0, rmax])
                  .range([width_val/2,0]);

            //prepare data for chart
            var data = [],
                s = [];
            for (i in n_val) {
                var ss = (Math.PI/widthIcon_val + Math.PI*i*gap_val-n_val[i])/(n_val[i] - 1);
                if (ss = Infinity)
                    s.push(0);
                else
                    s.push((Math.PI/widthIcon_val + Math.PI*i*gap_val-n_val[i])/(n_val[i] - 1));
                var ninrow = n_val[i],
                    radwidth = Math.PI/(n_val[i]+(n_val[i]-1)*s[i]),
                    radspace = radwidth*s[i],
                    r = 1 + i*gap_val*widthIcon_val;
                for (j=0;j<ninrow;j++) {
                    var x = Math.cos(radwidth*(0.5+j)+j*radspace)*r,
                        y = Math.sin(radwidth*(0.5+j)+j*radspace)*r,
                        rot = -1*(radwidth*(0.5+j)+j*radspace)/Math.PI*180+90;
                    data.push({'x':x,'y':y,'rot':rot});
                }
            }

            data.sort(function(x,y) {
              if (x['rot'] < y['rot']) return -1;
              if (x['rot'] > y['rot']) return 1;
              return 0
            });

            // assign attributes to data
            i = 0;
            for (key in people_val) {
                person = people_val[key];
                for (var attrname in person) {
                  data[i][attrname] = person[attrname];
                }
                data[i]['widthIcon'] = widthIcon_val;
                i++;
            }

            //get this
            var element = d3.select(this);

            // ARCS
            for (key in arcs_val) {
                arc = arcs_val[key];
                    //correct of empty arcs:
                if ((arc.start > arc.end)) {
                    arc.startangle = -90;
                    arc.endangle = -90;
                } else {
                        //correct for +-90:
                    if (arc.start == 0) arc.startangle = -90;
                    else arc.startangle = (data[arc.start].rot + data[arc.start-1].rot)/2;
                    if (arc.end == (data.length -1)) arc.endangle = 90;
                    else arc.endangle = (data[arc.end].rot + data[arc.end+1].rot)/2;
                }

            }
            var arci = d3.svg.arc()
                .startAngle(function(d){
                  return d.startangle/180*Math.PI;
                })
                .endAngle(function(d){return d.endangle/180*Math.PI})
                .outerRadius(xxScale(rmax))
                .innerRadius(0);
            var position = [xScale(0),yScale(0)];
            element.selectAll('.arc')
                .data(arcs_val)
              .enter()
                .append("path")
                .attr("d",arci)
                .attr("transform", "translate(" + position + ")")
                .attr("fill",function(d) {
                    if (typeof d.color == 'undefined') return 'gray';
                    else return d.color;
                })
                .attr("fill-opacity",function(d) {
                    if (typeof d.opacity == 'undefined') return 0.25;
                    else return d.opacity;
                })
                .attr("class","arc");

            // ICONS
            //create <a> for each person
            var icons = element.selectAll(".icon")
                    .data(data)
                .enter().append("a")
                    .attr("transform",function(d) {return "rotate("+d.rot+","+xScale(d.x)+","+yScale(d.y)+")"})
                    .attr("xlink:href",function(d) {return d.link})
                    .on("mouseover", function(d){
                      if (typeof(tip) !== 'undefined')
                        tip.show(d);
                    })
                    //.on("mouseover",tip.show)
                    .on("mouseout", function(d){
                      if (typeof(tip) !== 'undefined') tip.hide(d);
                    })
                    //.on("mouseout",tip.hide)
                        //add all attributes to <a data-...="...">
                    .each(function(d) {
                        icon = d3.select(this);
                        d3.keys(d).forEach(function(key) {
                          icon.attr('data-'+key,d[key]);
                        });
                    })
                    ;

            //append icons (badges)
            icons.append("text")
              .attr('font-size',xxScale(
                d.widthIcon*1.15/2))
              .attr('font-family', 'FontAwesome')
               .attr('x',function(d) {return xScale(d.x+d.widthIcon/2.8);})
               .attr('y',function(d) {return yScale(d.y+d.widthIcon/2.8);})
               .attr('fill',function(d) {
                  if (d.option_code == 1) return 'green';
                  else return 'red';
                })
               .attr('fill-opacity', function(d) {
                  if (typeof d.badge_opacity == 'undefined') return 1;
                  else return d.badge_opacity;
                })
               .attr('text-anchor',"middle")
               .text(function(d) {
                  if (typeof d.badge == 'undefined') return '\uf0a3';
                  else return d.badge;
                });

            //append icons (persons)
            icons.append("text")
                .attr('font-family', 'FontAwesome')
                .attr('font-size',xxScale(
                    d.widthIcon*1.15)) //the icon is about 1.15times higher then wide
                    .attr('fill', function(d) {
                      if (d.color == 'undefined') return 'gray';
                      else return d.color;
                })
                .attr('fill-opacity', function(d) {
                  if (typeof d.opacity == 'undefined') return 1;
                  else return d.opacity;
                })
                .attr('stroke-width', function(d) {return 1;})
                .attr('stroke-opacity',function(d) {
                  if (typeof d.stroke_opacity == 'undefined') return 0.9;
                  else return d.stroke_opacity;
                })
                .attr('text-anchor',"middle")
                .attr('class', 'shadow')
                .attr('x',function(d) {return xScale(d.x);})
                .attr('y',function(d) {return yScale(d.y);})
                .text(function(d) {
                  if (typeof d.icon == 'undefined') return '\uf007';
                  else return d.icon;
                })
                ;

            //SCORE
            svg.append("text")
              .attr('font-family', 'sans-serif')
              .attr('font-size',xxScale(d.widthIcon))        //adjust as needed
              .attr('font-weight','bold')
              .attr('text-anchor',"end")
              .attr('fill', score['for']['color'])
              .attr('x',parseFloat(xScale(0))-parseFloat(xxScale(d.widthIcon*0.15)))
              .attr('y',yScale(0))
              .text(score['for']['value']);
            svg.append("text")
              .attr('font-family', 'sans-serif')
              .attr('font-size',xxScale(d.widthIcon))        //adjust as needed
              .attr('font-weight','bold')
              .attr('text-anchor',"start")
              .attr('fill', score['against']['color'])
              .attr('x',parseFloat(xScale(0))+parseFloat(xxScale(d.widthIcon*0.15)))
              .attr('y',yScale(0))
              .text(score['against']['value']);
            svg.append("text")
              .attr('font-family', 'sans-serif')
              .attr('font-size',xxScale(d.widthIcon))        //adjust as needed
              .attr('font-weight','bold')
              .attr('text-anchor',"middle")
              .attr('fill', 'gray')
              .attr('x',xScale(0))
              .attr('y',yScale(0))
              .text(':');

        });
    }


    hemicycle.n = function(value) {
        if (!arguments.length) return value;
        n = value;
        return hemicycle;
    };
    hemicycle.gap = function(value) {
        if (!arguments.length) return value;
        gap = value;
        return hemicycle;
    };
    hemicycle.widthIcon = function(value) {
        if (!arguments.length) return value;
        widthIcon = value;
        return hemicycle;
    };
    hemicycle.width = function(value) {
        if (!arguments.length) return value;
        width = value;
        return hemicycle;
    };
    hemicycle.people = function(value) {
        if (!arguments.length) return value;
        people = value;
        return hemicycle;
    };
    hemicycle.arcs = function(value) {
        if (!arguments.length) return value;
        arcs = value;
        return hemicycle;
    };
    hemicycle.score = function(value) {
        if (!arguments.length) return value;
        score = value;
        return hemicycle;
    };

    return hemicycle;
}
