
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="../css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
        <title>Topic IRI & rdfsLabel & IMS & IMS+Merge Boxplot</title>
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
                                Boxplot b/w Topic IRI & rdfsLabel & IMS & IMS+Merge - <small id="SearchTime"></small>
                            </div>
                            <div class="card-body">
                                <div id="plot">
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
            
            <footer>
                
            </footer>
        </div>
        
        <script src="../Scripts/jquery-3.4.1.min.js"></script>
        <script src="../Scripts/popper-1.14.7.min.js"></script>
        <script src="../Scripts/bootstrap-4.3.1.min.js"></script>
        <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
        <script>
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
                            name: 'Topic IRI (Clusters: ' + response.Topic_IRI.length.toLocaleString() + ')'
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

                        var trace4 = {
                            y: response.IMS_Merge,
                            type: 'box',
                            name: 'IMS + Merge (Clusters: ' + response.IMS_Merge.length.toLocaleString() + ')'
                        };
                        
                        var layout = {
                            title: 'Cluster Distribution b/w Topic IRI, rdfsLabel, IMS & (IMS+Merge)',
                            //showlegend: false
                            autosize: false,
                            width: 1380,
                            height: 600,
                            font: {
                                size: 20,
                              }
                            }

                        var data = [trace1, trace2, trace3, trace4];
                        Plotly.newPlot('plot', data, layout, {displayModeBar: false});
                        var end = performance.now();
                        let time = millisToMinutesAndSeconds(end - start);
                        $('#SearchTime').html(`Request time  (${time} seconds)`);
                        document.getElementById("loader").style.display = "none";
                    },
                    error: function (jqXHR, textStatus, errorThrown) { 
                        console.log(jqXHR);
                    }
                })
                function millisToMinutesAndSeconds(millis) {
                var minutes = Math.floor(millis / 60000);
                var seconds = ((millis % 60000) / 1000).toFixed(0);
                return minutes + " minutes, " + (seconds < 10 ? '0' : '') + seconds;
            }
        </script>
    </body>
</html>
