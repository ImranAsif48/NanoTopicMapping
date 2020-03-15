
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <title>Home Page</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
               <?php require('menu.php') ?>
            </header>
            <br />
            <main class="row">
                    <div class="col-md-12">
                      <div class="jumbotron">
                        <h1>Topic Mapping of Nanopublications</h1>
                        <p>This is the online application that will show the graphical view of topics distributed among the nanopublications</p>
                      </div>
                    </div>
            </main>
            
            <footer>
                
            </footer>
        </div>
        
        <script src="Scripts/jquery-3.4.1.min.js"></script>
        <script src="Scripts/popper-1.14.7.min.js"></script>
        <script src="Scripts/bootstrap-4.3.1.min.js"></script>
        <script src="https://d3js.org/d3.v4.min.js"></script>
        <script>
    //////////////////////////////////
    //// Loader 
    function hideLoader() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("map").style.display = "block";
    }
        
     //////////////////////////////////////////////////////////////////////////////////

        var svg = d3.select("svg"),
            margin = 20,
            diameter = +svg.attr("width"),
            g = svg.append("g").attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

        var color = d3.scaleLinear()
            .domain([-1, 5])
            .range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
            .interpolate(d3.interpolateHcl);

        var pack = d3.pack()
            .size([diameter - margin, diameter - margin])
            .padding(2);

        var node, text, view, circle, title;
        var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "Code/getTopicJSON.php");
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                  json = this.responseText;
                  visualise(JSON.parse(json));
                  hideLoader();
                } else {
                }
            }

        function visualise(root)
        {
          root = d3.hierarchy(root)
              .sum(function(d) { return d.value; })
              .sort(function(a, b) { return b.value - a.value; });

          var focus = root,
              nodes = pack(root).descendants();


          circle = g.selectAll("circle")
            .data(nodes)
            .enter().append("circle")
              .attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--root"; })
              .style("fill", function(d) { return d.children ? color(d.depth) : null; })
              .on("click", function(d) { if (focus !== d) zoom(d), d3.event.stopPropagation(); });

          text = g.selectAll("text")
            .data(nodes)
            .enter().append("text")
              .attr("class", "label")
              .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
              .style("display", function(d) { return d.parent === root ? "inline" : "none"; })
              .text(function(d) {
                      var splitIRI = d.data.name.split("/");
                      return splitIRI[splitIRI.length - 1];
                  });


          node = g.selectAll("circle,text");

          node.append('title')
                .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
                 .style("display", function(d) { return d.parent === root ? "inline" : "none"; })
                  .text(function (d) {
                      return (d.data.name + '\n' + d.value);
                  });

          svg
             // .style("background", color(-1))
              .on("click", function() { zoom(root); });

          zoomTo([root.x, root.y, root.r * 2 + margin]);
        }

        function zoom(d) {
            var focus0 = focus; focus = d;

            if(d.children != undefined)
            {
                var transition = d3.transition()
                .duration(d3.event.altKey ? 7500 : 750)
                .tween("zoom", function(d) {
                  var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
                  return function(t) { zoomTo(i(t)); };
                });

                transition.selectAll("text")
                  .filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
                    .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
                    .on("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
                    .on("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });

                transition.selectAll("title")
                  .filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
                    .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
                    .on("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
                    .on("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
            }
            
        
            if(d.children == undefined)
            {
                //alert(d.data.name + ' ' + d.value);
                var splitIRI = d.data.name.split("/");
                $('#modalTitle').html(splitIRI[splitIRI.length - 1]);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", "Code/getTopicInfo.php?iri="+d.data.name);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState === 4 && this.status === 200) {
                    json = this.responseText;
                    var result = JSON.parse(json);
                    var iri = result[0].IRI.split("/")
                    var data = `<h5>Topic IRI</h5><hr />
                                  <a href="${result[0].IRI}" target="_blank">${iri[iri.length - 1]}</a> <br /> <br />
                                 <h5>Nanopublications</h5><hr />`;
                    
                    for(var i=0;i<result.length;i++)
                    {
                        data += `<a href="${result[i].trustyURI}" target="_blank">${result[i].trustyURI}</a><br />`;
                    }
                    
                    document.getElementById('divContent').innerHTML = data;
                    $('#divContent').show();
                    $('#divSpin').hide();
                  }
                  else
                  {
                      //alert('Server Error');
                  }
              }
            }
          }

          function zoomTo(v) {
            var k = diameter / v[2]; view = v;
            node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
            circle.attr("r", function(d) { return d.r * k; });
          }

        </script>
    </body>
</html>

