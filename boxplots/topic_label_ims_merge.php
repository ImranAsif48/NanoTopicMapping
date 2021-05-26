
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="../css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
        <title>IRI & rdfsLabel & IMS & IMS+Merge Boxplot</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
               <?php require('../menu.php') ?>
            </header>
            <br />
            <div id="loader"></div>
            <main class="row">
                    <div id="divTable" class="col-md-12">
                        <br />
                        <div class="card">
                            <div class="card-header">
                                Boxplot b/w IRI & rdfsLabel & IMS - <small id="SearchTime"></small>
                            
                                <div class="dropdown dropleft" style="float:right;cursor:pointer;">
                                    <!--Trigger-->
                                    
                                    <a  type="button" id="dropdownMenu2" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                        

                                    <!--Menu-->
                                    <div class="dropdown-menu dropdown-primary">
                                        <a class="dropdown-item" href="#" onclick="downloadimage('svg', 800, 1380, 'image')"><i class="fas fa-file-export"></i> Export to SVG</a>
                                        <a class="dropdown-item" href="#" onclick="downloadimage('png', 800, 1380, 'image')"><i class="fas fa-file-export"></i> Export to PNG</a>
                                        <a class="dropdown-item" href="#" onclick="downloadInnerHtml('plot.html', 'all', 'text/html')"><i class="fas fa-file-export"></i> Export to HTML</a>
                                    </div>
                                </div> 
                            </div>
                            <div class="card-body" id="all">
                            <div class="row" id="divNano">
                                    <!-- <div class="col-xl-4 col-lg-6">
                                        <div class="card card-inverse card-danger">
                                        <div class="card-block bg-danger">
                                            <div class="rotate">
                                            <i class="fas fa-link fa-5x"></i>
                                            </div>
                                            <h6>Total Nanopubs by IRIs</h6>
                                            <h1 class="display-4">4,818,670</h1>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6">
                                        <div class="card card-inverse card-primary">
                                        <div class="card-block bg-primary">
                                            <div class="rotate">
                                            <i class="fas fa-tags fa-5x"></i>
                                            </div>
                                            <h6>Total Nanopubs by rdfsLabel</h6>
                                            <h1 class="display-4">4,792,370</h1>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6">
                                        <div class="card card-inverse card-info">
                                        <div class="card-block bg-info">
                                            <div class="rotate">
                                            <i class="fas fa-sitemap fa-5x"></i>
                                            </div>
                                            <h6>Total Nanopubs by IMS</h6>
                                            <h1 class="display-4">114,846</h1>
                                        </div>
                                        </div>
                                    </div> -->
                                </div>
                                <div id="plot">
                                </div>
                                
                                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                                <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
                                <svg id="js-plotly-tester" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="position: absolute; left: -10000px; top: -10000px; width: 9000px; height: 9000px; z-index: 1;"><path class="js-reference-point" d="M0,0H1V1H0Z" style="stroke-width: 0; fill: black;"></path></svg>
                                
                            </div>
                        </div>
                    </div>
                    
                    
            </main>
            <br />
            
            <footer>
                
            </footer>
        </div>
        
        <script src="../Scripts/jquery-3.4.1.min.js"></script>
        <script src="../Scripts/popper-1.14.7.min.js"></script>
        <script src="../Scripts/bootstrap-4.3.1.min.js"></script>
        <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
        <script>
            $('#divNano').hide();
            /////////////////////////////////////////////////////
            var start = performance.now();
                $.ajax({
                    url: '../Code/boxplot_iri_label_ims_merge.php',
                    data: {opt:'all'},
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        var trace1 = {
                            y: response.Topic_IRI,
                            type: 'box',
                            name: 'IRI (Clusters: ' + response.Topic_IRI.length.toLocaleString() + ')'
                        };
                        
                        var trace2 = {
                            y: response.rdfsLabel,
                            type: 'box',
                            name: 'rdfsLabel (Clusters: ' + response.rdfsLabel.length.toLocaleString() + ')'
                        };

                        var trace3 = {
                            y: response.IMS,
                            type: 'box',
                            name: 'IMS (Clusters: ' + response.IMS.length.toLocaleString() + ')'
                        };

                        // var trace4 = {
                        //     y: response.IMS_Merge,
                        //     type: 'box',
                        //     name: 'IMS + Merge (Clusters: ' + response.IMS_Merge.length.toLocaleString() + ')'
                        // };
                        
                        var layout = {
                            title: 'Concept Grouping Distribution b/w IRI, rdfsLabel & IMS',
                            //showlegend: false
                            autosize: false,
                            width: 1380,
                            height: 600,
                            font: {
                                size: 16,
                              },
                            legend:{
                               x:0.74,
                               y:1
                            }
                        }

                        var data = [trace1, trace2, trace3];
                        Plotly.newPlot('plot', data, layout, {displayModeBar: false});
                        var end = performance.now();
                        let time = millisToMinutesAndSeconds(end - start);
                        $('#SearchTime').html(`Request time  (${time} seconds)`);
                        document.getElementById("loader").style.display = "none";
                        $('#divNano').show();
                    },
                    error: function (jqXHR, textStatus, errorThrown) { 
                        console.log(jqXHR);
                    }
                })
                function millisToMinutesAndSeconds(millis) {
                var minutes = Math.floor(millis / 60000);
                var seconds = ((millis % 60000) / 1000).toFixed(4);
                //return minutes + "." + (seconds < 10 ? '0' : '') + seconds;
                return seconds;
            }

            function downloadimage(format, height, width, filename) {
                var p = document.getElementById('plot');
                Plotly.downloadImage(p, 
                 {format: format, height: height, width: width, filename:
                filename});
            };

            function downloadInnerHtml(filename, elId, mimeType) {
                var elHtml = document.getElementById(elId).innerHTML;
                var link = document.createElement('a');
                mimeType = mimeType || 'text/plain';

                link.setAttribute('download', filename);
                link.setAttribute('href', 'data:' + mimeType  +  ';charset=utf-8,' + encodeURIComponent(elHtml));
                link.click(); 
            }

        </script>
    </body>
</html>
