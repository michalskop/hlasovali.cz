//tooltip

//limits:
var limits = {
  'start_date': 9999,
  'end_date': 0,
  'maxsize': 0,
  'minsize': 1000000000000,
}
var yearso = {};
for (key in data['series']){
  serie = data['series'][key];
  for (k in serie["data"]) {
    datum = serie["data"][k];
    if (datum['x'] < limits['start_date'])
      limits['start_date'] = datum['x'];
    if (datum['x'] > limits['end_date'])
      limits['end_date'] = datum['x'];
    if (typeof(datum["size"]) !== "undefined") po = datum["size"];
      else po = 1;
    if (po > limits['maxsize'])
      limits['maxsize'] = po;
    if (po < limits['minsize'])
      limits['minsize'] = po;
    
    //yearso[datum['x']] = //datum['x'];
  }
  
}

//years = [];
//for (year in yearso) {
//  years.push(year);
//}


var margin = {top: 20, right: 20, bottom: 40, left: 50},
    width = options['width'] - margin.left - margin.right,
    height = options['height'] - margin.top - margin.bottom;

var x = d3.scale.linear()
    .range([0, width])
    .domain([limits['start_date']-0.25,limits['end_date']+0.25]);
var y = d3.scale.linear()
    .range([height, 0])
    .domain([0,100]);
var r = d3.scale.sqrt()
    .range([0, options['height']/20])
    .domain([0,limits['maxsize']]);    

var period_values = [];
for (item in data['period_names'])
    period_values.push(item);
var xAxis = d3.svg.axis()
    .scale(x)
    .tickValues(period_values)
    .tickFormat(function(d,i) {return data['period_names'][d]; })
    .orient("bottom");
var yAxis = d3.svg.axis()
    .scale(y)
    .ticks(3)
    .tickFormat(function(d) { return d + "%"; })
    .orient("left");

var svg = d3.select("#chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
svg.append("g")
  .attr("class", "x axis")
  .attr("transform", "translate(0," + height + ")")
  .call(xAxis)
 .selectAll("text")
    .attr("y", 0)
    .attr("x", 9)
    .attr("dy", ".35em")
    .attr("transform", function() {
      if (data['period_type'] == 'year') return "rotate(45) translate(-5,15)";
      else return "translate (-35 15)";
    })
    .style("text-anchor", "start")
    .style("font-size", "12px")
 .append("text")
  .attr("x", x(limits['end_date']))
  .attr("y", "-1em")
  .style("text-anchor", "end")
  .text(options['xlabel']);
   
svg.append("g")
  .attr("class", "y axis")
  .call(yAxis)
 .append("text")
  .attr("transform", "rotate(-90)")
  .attr("y", 6)
  .attr("dy", ".71em")
  .style("text-anchor", "end")
  .text(options['ylabel']);

var line = d3.svg.line()
    .interpolate("cardinal")
    .x(function(d) { return x(d.x); })
    .y(function(d) { return y(d.y); });

//prepare points:
points = [];
for (key in data['series'].reverse()){
  serie = data['series'][key];
  for (k in serie["data"]) {
    datum = serie["data"][k];
    point = {
      "x": datum["x"],
      "y": datum["y"],
      "name": serie["name"]
    }
    if (typeof(datum["color"]) !== "undefined") point["color"] = datum["color"];
    else if (typeof(serie["color"]) !== "undefined") point["color"] = serie["color"];
    else point["color"] = "gray";
    if (typeof(datum["size"]) !== "undefined") point["size"] = datum["size"];
    else point["size"] = 1;
    points.push(point);
  }
}
//prepare lines:
lines = [];
for (key in data['series'].reverse()){
  serie = data['series'][key];
  l = []
  for (k in serie["data"]) {
    datum = serie["data"][k];
    point = {
      "x": datum["x"],
      "y": datum["y"],
    }
    if (typeof(datum["color"]) !== "undefined") point["color"] = datum["color"];
    else if (typeof(serie["color"]) !== "undefined") point["color"] = serie["color"];
    else point["color"] = "gray";
    l.push(point);
  }
  lines.push(l);
}

//prepare bands
bands = [
  [[limits['start_date']-0.25,0],[limits['end_date']+0.25,0],[limits['end_date']+0.25,33.33],[limits['start_date']-0.25,33.33]],
  [[limits['start_date']-0.25,100],[limits['end_date']+0.25,100],[limits['end_date']+0.25,66.66],[limits['start_date']-0.25,66.66]],
];

/* Initialize tooltip */
    tip = d3.tip().attr('class', 'd3-tip').html(function(d) { 
      return "<span class=\'stronger\'>" + d["name"] + " " + d['x'] + ": "+ d['y'] +"%</span><br>";
    });

    /* Invoke the tip in the context of your visualization */
    svg.call(tip)

var area = d3.svg.line()
    .x(function(d) {return x(d[0]);})
    .y(function(d) {return y(d[1]);});
    
var areas = svg.selectAll("path.area")
    .data(bands)
  .enter()
    .append("path")
      .attr("class",function(d, i) {if (i == 0) return "band band-lower"; else return "band band-upper";})
      .attr("d",area);


svg.selectAll('.lines')
    .data(lines)
  .enter().append('path')
    .attr("d", function(d) { return line(d); })
    .attr("class","line")
    .attr("stroke",function(d) { return d[0]['color']; })
    .attr("stroke-linecap","round")
    .attr("style", function(d) { return "stroke-width:3px"; });

svg.selectAll('circle')
    .data(points)
  .enter().append('circle')
    .attr("cx", function(d) {return x(d.x) })
    .attr("cy", function(d) {return y(d.y) })
    .attr("r", function(d) {return 5 })
    .attr("fill", function(d) {return d.color })
    .attr("stroke", function(d) {return d.color })
    .attr("stroke-width", function(d) {if (d.size > 0.5*limits['maxsize']) return 3; else if (d.size > 0.25*limits['maxsize']) return 2; else return 1;})
    .attr("title",function(d) {return d.name})
    .attr("data-tooltip",function(d) {return d.name}) 
    .on("mouseover", tip.show)
	.on("mouseout", tip.hide);

