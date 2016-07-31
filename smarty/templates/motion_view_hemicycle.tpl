<div id="chart"></div>
<!-- hemicycle https://github.com/KohoVolit/widget-hemicycle -->
<script src="{$settings->app_url}libs/d3.v3.min.js"></script>
<script src="{$settings->app_url}js/d3.hemicycle.js"></script>
<script src="{$settings->app_url}js/d3.legend.js"></script>
<script src="{$settings->app_url}js/d3.orloj.js"></script>
<script src="{$settings->app_url}libs/d3.tip.js"></script>
<script src="{$settings->app_url}libs/md5.js"></script>

<script>
var data = {$hemicycle['data']};
var dat = {$hemicycle['dat']};

       //width and height
       var w = "400",
           rowsOrloj = 2,
           iconHeightOrloj = w/22, // w/22 for 2 rowsOrloj
           h = w*(1/2+1/8+rowsOrloj*1/16);

       //append svg
       var svg=d3.select("#chart")
           .append("svg")
           .attr("width",w)
           .attr("height",h)
           .attr("id","chart-svg");

       var hemicycleData = [{ 'widthIcon': dat.w, 'gap': dat.g, 'n': dat.n} ];
       hemicycleData[0]['width'] = w;
       hemicycleData[0]['people'] = data;


      // Initialize tooltip
       tip = d3.tip().direction('e').attr("class", "d3-tip").html(function(d) {
           if (typeof(d["description"]) === 'undefined')
               chunk = '';
           else
               chunk = "<br>" + d["description"];
           if (d['option'] == 'yes')
               chunk = "{$t->get('ve_yes')}";
           if (d['option'] == 'no')
               chunk = "{$t->get('ve_no')}";
           if (d['option'] == 'abstain')
               chunk = "{$t->get('ve_abstain')}";
           if (d['option'] == 'absent')
               chunk = "{$t->get('ve_absent')}";
           return "<span class=\'stronger\'>" + d["name"] + "</span><br>" + d["party"] + '<br>' + chunk;
       });

       // function for chart
       var counts = {$hemicycle['counts']};
       var myChart = d3.hemicycle()
               .n(function(d) { return d.n;})
               .gap(function(d) { return d.gap;})
               .widthIcon(function(d) { return d.widthIcon;})
               .width(function(d) { return d.width;})
               .people(function(d) { return d.people;})
               .arcs([{ "start":counts['all'] - counts['for'],
               "end":counts['all']-1,"color":"green", "opacity":0.15}, { "start":0,"end":counts['against'] - 1, "color":"darkred", "opacity":0.15}])
               .score({ "against":{ "color":"red","value":counts['against']}, "for":{ "color":"green","value":counts['for']}})
               ;

      //create hemicycle
      var hc = svg.selectAll(".hc")
           .data(hemicycleData)
          .enter()
           .append("svg:g")
           .attr("width",w)
           .attr("height",w/2)
             .call(myChart);

       // Invoke the tip in the context of your visualization
       svg.call(tip);

       // Add tooltip div
       var div = d3.select("body").append("div")
       .attr("class", "tooltip")
       .style("opacity", 1e-6);

       //legend data
       var legendData = [{ "label":["{$motion->name|htmlspecialchars|truncate:50:"...":true}"],"icons":[{ "color":"red","text":"{$t->get('against')|upper}","class":"legend-option-against"},{ "color":"green","text":"{$t->get('pro')|upper}","class":"legend-option-for"}]}];

       var myLegend = d3.legend()
               .label(function(d) { return d.label;})
               .width(w)
               .icons(function(d) { return d.icons;});

       svg.selectAll(".legend")
             .data(legendData)
          .enter()
           .append("svg:g")
           .attr("width",w)
           .attr("height",w/8)
           .attr("transform", "translate(0," + w/2 + ")")
           .call(myLegend);

        var orlojData = [{
               //'label' : ['Legenda:'],
               'rows': rowsOrloj,
               'iconHeight': iconHeightOrloj,
               'icons' : {$hemicycle['orloj']}
           }];

       var myOrloj = d3.orloj()
               //.label(function(d) { return d.label;})
               .rows(function(d) { return d.rows;})
               .iconHeight(function(d) { return d.iconHeight;})
               .icons(function(d) { return d.icons;})
               .width(w);

       movey = w/2 + w/8;
       svg.selectAll(".orloj")
             .data(orlojData)
          .enter()
           .append("svg:g")
           .attr("width",w)
           .attr("height",w/16*orlojData[0].rows)
           .attr("transform", "translate(0," + movey + ")")
           .call(myOrloj);

        //and export it as png
        postdata = {
            'url':CryptoJS.MD5(window.location.href).toString(), 'svg':$('#chart').html().replace(/<strong>/g,'').replace(/<\/strong>/g,'').replace(/<br>/g,''), 'nocache': getParameterByName('nocache')};
        $.post('pages/create_png.php',postdata);
        var nothing = 0;
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
</script>
