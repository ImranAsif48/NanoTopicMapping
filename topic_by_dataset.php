
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="css/pagination.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <title>Topic Mapping By Datasets</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
            <?php require('menu.php') ?>
            </header>
            <div id="loader"></div>
            <div id="message">First Time Loading....</div>
            <main class="h-100">
                <div class="row">
                    <div id="divTable" class="col-md-12">
                        <br />
                        <div class="card">
                            <div class="card-header">
                                Summary - <small id="SearchTime"></small>
                            </div>
                            <div class="card-body">
                                <table id="tbl" class="table table-striped table-bordered" style="width:100%" >
                                    <thead>
                                        <th>Topic</th>
                                        <th>Topic IRI</th>
                                        <th>Dataset</th>
                                        <th>Count (Nanopub)</th>
                                    </thead>
                                    <tbody id="tblTopic"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row h-100">
                    <div id="map" class="col-md-12 card" style="display:none;" class="animate-bottom">
                            <div class="card-header">
                                Visualization
                                <div class="dropdown dropleft" style="float:right">
                                    <!--Trigger-->
                                    
                                    <a  type="button" id="dropdownMenu2" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                        

                                    <!--Menu-->
                                    <div class="dropdown-menu dropdown-primary">
                                        <a class="dropdown-item" href="#" onclick="saveImage()"><i class="fas fa-file-export"></i> Export to PNG</a>
                                    </div>
                                </div>  
                            </div>
                            <div class="card-body">
                              <svg id="svgImage" width="800" height="800" viewBox="0 0 800 800" preserveAspectRatio="xMinYMin meet"></svg>
                            </div>
                    </div>
                </div>
            </main>
            
            <footer>
                
            </footer>
            
            <button id="btnModal" style="display: none;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            </button>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div id="divSpin">
                          <span class="spinner-border text-primary"></span>
                          <span class="text-primary">Loading...</span>
                      </div>
                      <div id="divContent">
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div id="pagesNano"></div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        
        <script src="Scripts/jquery-3.4.1.min.js"></script>
        <script src="Scripts/popper-1.14.7.min.js"></script>
        <script src="Scripts/bootstrap-4.3.1.min.js"></script>
        <script src="Scripts/jquery.dataTables.min.js"></script>
        <script src="Scripts/dataTables.bootstrap4.min.js"></script>
        <script src="https://d3js.org/d3.v4.min.js"></script>
        <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
        <script src="Scripts/pagination.min.js"></script>
        <script src="Scripts/saveSvgAsPng.js"></script>
        <script>
    //////////////////////////////////
    //// Loader 
    function hideLoader() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("map").style.display = "block";
      $('#message').html('');
    }
    function saveImage()
    {
        saveSvgAsPng(document.getElementsByTagName("svg")[0], "plot.png", {scale: 2, backgroundColor: "#FFFFFF"});
    }
    $('#divTable').hide();
     //////////////////////////////////////////////////////////////////////////////////
        var start = performance.now();
        var end;
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
        
        if (localStorage.getItem('topicByDataset') != null) {
            $('#message').html('');
            DisplayData(localStorage.getItem('topicByDataset'));
        }
        else{
          var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "Code/getTopicJSON.php");
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                  let json_data = this.responseText;
                  localStorage.setItem('topicByDataset', json_data);
                  DisplayData(json_data);
                } else {
                }
            }
        }

     function DisplayData(json)
     {
          let parseJson = JSON.parse(json);
                  let table = '';
                  let array = [];
                  for(let i=0;i<parseJson.children.length;i++)
                  {
                     let dataset = parseJson.children[i].name;

                     for(let j=0; j<parseJson.children[i].children.length; j++)
                     {
                       if(dataset.toLowerCase() === 'wikipathways')
                       {
                           for(let k=0;k<parseJson.children[i].children[j].children.length; k++)
                           {
                              let IRI = parseJson.children[i].children[j].children[k].name;
                              let resolveIRI = IRI;
                              if(IRI.toLowerCase().indexOf('rdf.wikipathways') !== -1)
                              {
                                    let splitIRI = IRI.split("_");
                                    let splitWP = splitIRI[0].split("/");
                                    resolveIRI = 'https://www.wikipathways.org/index.php/Pathway:'+splitWP[splitWP.length-1]; 
                              }
                               table += `<tr>
                                        <td>${parseJson.children[i].children[j].name}</td>
                                        <td><a href="${resolveIRI}" target="_blank">${IRI}</a></td>
                                        <td>${dataset}</td>
                                        <td style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano('${IRI}', ${parseJson.children[i].children[j].children[k].value}, '${parseJson.children[i].children[j].name}', '${resolveIRI}')">${parseJson.children[i].children[j].children[k].value}</td>
                                    </tr>`;
                           }
                              
                       }
                       else if(dataset.toLowerCase() === 'liddi')
                       {
                         let IRI = parseJson.children[i].children[j].name;
                         table += `<tr>
                                        <td>Drug</td>
                                        <td><a href="${IRI}" target="_blank">${IRI}</a></td>
                                        <td>${dataset}</td>
                                        <td style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano('${IRI}', ${parseJson.children[i].children[j].value}, 'Drug', '${IRI}')">${parseJson.children[i].children[j].value}</td>
                                    </tr>`;
                       }
                       else if(dataset.toLowerCase() === 'nextprot')
                       {
                           let IRI = parseJson.children[i].children[j].name;
                           let splitIRI = IRI.split("#");
                           let resolveIRI = 'https://www.nextprot.org/entry/' + splitIRI[splitIRI.length-1];
                            
                            table += `<tr>
                                        <td>Protein</td>
                                        <td><a href="${resolveIRI}" target="_blank">${IRI}</a></td>
                                        <td>${dataset}</td>
                                        <td style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano('${IRI}', ${parseJson.children[i].children[j].value}, 'Protein', '${resolveIRI}')">${parseJson.children[i].children[j].value}</td>
                                    </tr>`;
                       }
                     }
                  }
                  $('#tblTopic').html(table);
                  $('#divTable').show();
                  $('#tbl').DataTable();

                  //////////////////////////////////////////////////
                  visualise(parseJson);
                  //////////////////////////////
                  //Calculate time
                  var end = performance.now();
                  let time = millisToMinutesAndSeconds(end - start);
                  $('#SearchTime').html(`Request time  (${time} seconds)`);
                  hideLoader();
     }
     $('#svgImage').on('load', function(){
       var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "Code/getTopicJSON.php");
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                  let json_data = this.responseText;
                  localStorage.setItem('topicByDataset', json_data);
                } else {
                }
            }
    });
     function millisToMinutesAndSeconds(millis) {
        var minutes = Math.floor(millis / 60000);
        var seconds = ((millis % 60000) / 1000).toFixed(0);
        return minutes + " minutes, " + (seconds < 10 ? '0' : '') + seconds;
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
              .style("display", function(d) { 
                return d.parent === root ? "inline" : "none"; })
              .text(function(d) {
                      var splitIRI = d.data.name.split("/");
                      return splitIRI[splitIRI.length - 1];
                  });


          node = g.selectAll("circle,text");

          node.append('title')
                .style("fill-opacity", function(d) { 
                  return d.parent === root ? 1 : 0; })
                 .style("display", function(d) { 
                   return d.parent === root ? "inline" : "none"; })
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
                  .filter(function(d) { 
                    return d.parent === focus || this.style.display === "inline"; 
                  })
                    .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
                    .on("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
                    .on("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });

                transition.selectAll("title")
                  .filter(function(d) { 
                    //return d.parent === focus || this.style.display === "inline"; 
                  })
                    .style("fill-opacity", function(d) {
                       return d.parent === focus ? 1 : 0; })
                    .on("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
                    .on("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
            }
            
        
            if(d.children == undefined)
            {
                //alert(d.data.name + ' ' + d.value);
                let IRI = d.data.name;
                let resolveIRI = IRI;
                if(IRI.indexOf('#')!=-1)
                {
                    var splitIRI = IRI.split("#");
                    resolveIRI = 'https://www.nextprot.org/entry/' + splitIRI[splitIRI.length -1];
                }
                else if(IRI.indexOf('rdf.wikipathways')!=-1){
                    let splitIRI = IRI.split("_");
                    let splitWP = splitIRI[0].split("/");
                    resolveIRI = 'https://www.wikipathways.org/index.php/Pathway:'+splitWP[splitWP.length-1]; 
              }
                AJAXCallForNano(d.data.name, d.data.value, '', resolveIRI)
            }
          }

          function zoomTo(v) {
            var k = diameter / v[2]; view = v;
            node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
            circle.attr("r", function(d) { return d.r * k; });
          }

    function AJAXCallForNano(oiri, count, label, resolveIRI)
    {
            //alert(d.data.name + ' ' + d.value);
            if(oiri.indexOf('#')!=-1)
            {
                var splitIRI = oiri.split("#");
            }
            else{
              var splitIRI = oiri.split("/");

            }
                $('#modalTitle').html(`<a href="${resolveIRI}" target="_blank">${label} - ${splitIRI[splitIRI.length-1]}</a> <br />
                                    <span style="font-size:12px"> ${count} nanopublications</span>`);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                $('#pagesNano').pagination({
                dataSource: function(done) {
                        $.ajax({
                            url: 'Code/getTopicInfo.php',
                            data: { iri :oiri },
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                                done(response);
                                $('#divContent').show();
                                $('#divSpin').hide();
                            },
                            error: function (jqXHR, textStatus, errorThrown) { 
                                alert( "Request failed: " + textStatus );
                             }
                        })
                    },
            pageSize: 10,
            callback: function(data, pagination) {
                // template method of yourself
                    let result = data;
                    let d = '';
                    for(var i=0;i<result.length;i++)
                    {
                        d += `<a href="${result[i].trustyURI}" target="_blank">${result[i].trustyURI}</a><br />`;
                    }
                
                //d += `<div class="hr-line-dashed">`;    
                //var html = template(data);
                $("#divContent").html(d);
            }
        })
    }
        </script>
    </body>
</html>

