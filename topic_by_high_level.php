
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
        <title>Topic Mapping By High Level View</title>
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
                                        <th>High Level Label</th>
                                        <th>Count (Nanopub)</th>
                                    </thead>
                                    <tbody id="tblTopic"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                <br />
                    <div id="TopicMap" class="col-md-12 card" style="display:none;" class="animate-bottom">
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
                                <svg id="svgImage" viewBox="0 0 2000 2000" preserveAspectRatio="xMinYMin meet"></svg>
                            </div>
                        </div>
                </div>
            </main>
            <br />
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
      document.getElementById("TopicMap").style.display = "block";
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
        if (localStorage.getItem('topicByHighLevel') != null) {
            $('#message').html('');
            DisplayData(localStorage.getItem('topicByHighLevel'));
        }
        else 
        {
            var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "Code/getJSONTopicHighLevel.php");
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                  let json_data = this.responseText;
                  localStorage.setItem('topicByHighLevel', json_data);
                  /////////////////////////////
                  // Display count in table
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
                  for(let i=0;i<parseJson.length;i++)
                  {
                            table += `<tr>
                                        <td>${parseJson[i].name}</td>
                                        <td style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="getNanopublitions('${parseJson[i].name}', ${parseJson[i].count})">${parseJson[i].count}</td>
                                    </tr>`;
                            array.push({'Name': parseJson[i].name, 'Count': parseJson[i].count});
                  }
                  $('#tblTopic').html(table);
                  $('#divTable').show();
                  $('#tbl').DataTable();
                  ////////////////////////
                  /// Visualize the topics
                  let data = {
                    "children": array
                  };
                  visualise(data);
                  var end = performance.now();
                  let time = millisToMinutesAndSeconds(end - start);
                  $('#SearchTime').html(`Request time  (${time} seconds)`);
                  hideLoader();
    }
    $('#svgImage').on('load', function(){
        var xmlhttp = new XMLHttpRequest();
              xmlhttp.open("GET", "Code/getJSONTopicHighLevel.php");
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                  let json_data = this.responseText;
                  localStorage.setItem('topicByHighLevel', json_data);
                } else {
                }
           }
    });
    function millisToMinutesAndSeconds(millis) {
        var minutes = Math.floor(millis / 60000);
        var seconds = ((millis % 60000) / 1000).toFixed(0);
        return minutes + " minutes, " + (seconds < 10 ? '0' : '') + seconds;
    }
    
    function visualise(dataset)
        {
        var diameter = 2000;
        var color = d3.scaleOrdinal(d3.schemeCategory20);

        var bubble = d3.pack(dataset)
            .size([diameter, diameter])
            .padding(1.5);

        var svg = d3.select("svg")
            .attr("width", diameter)
            .attr("height", diameter)
            .attr("class", "bubble");

        var nodes = d3.hierarchy(dataset)
            .sum(function(d) { return d.Count; });

        var node = svg.selectAll(".node")
            .data(bubble(nodes).descendants())
            .enter()
            .filter(function(d){
                return  !d.children
            })
            .append("g")
            .attr("class", "node")
            .attr("transform", function(d) {
                return "translate(" + d.x + "," + d.y + ")";
            })
            .on("click", function(d){
                getNanopublitions(d.data.Name, d.data.Count);
                //alert(d.data.Name);
            })
            // .call(d3.zoom().on("zoom", function () {
            //     svg.attr("transform", d3.event.transform)
            // }));

        node.append("title")
            .text(function(d) {
                return d.data.Name + ": " + d.data.Count;
            });

        node.append("circle")
            .attr("r", function(d) {
                return (d.r);
            })
            .style("fill", function(d,i) {
                return color(i);
            });

        node.append("text")
            .attr("dy", ".2em")
            .style("text-anchor", "middle")
            .text(function(d) {
                return d.data.Name.substring(0, d.r / 3);
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", function(d){
                return d.r/5;
            })
            .attr("fill", "white");

        node.append("text")
            .attr("dy", "1.3em")
            .style("text-anchor", "middle")
            .text(function(d) {
                return d.data.Count;
            })
            .attr("font-family",  "Gill Sans", "Gill Sans MT")
            .attr("font-size", function(d){
                return d.r/5;
            })
            .attr("fill", "white");

        d3.select(self.frameElement)
            .style("height", diameter + "px");

          }

    function getNanopublitions(label, count)
          {
            $('#modalTitle').html(`${label}<br />
                                    <span style="font-size:12px"> ${count} nanopublications</span>`);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                $('#pagesNano').pagination({
                    dataSource: function(done) {
                        $.ajax({
                            url: 'Code/getTopicInfo.php',
                            data: { topic :label },
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
                    //let iri = result[0].IRI.split("/")
                    let d = ''
                    // `<h5>Topic IRI</h5><hr />
                    //               <a href="${result[0].IRI}" target="_blank">${iri[iri.length - 1]}</a> <br /> <br />
                    //              <h5>Topic Label</h5> <hr />
                    //              ${result[0].rdfsLabel}
                    //               <h5>Nanopublications</h5><hr />`'';
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

