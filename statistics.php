
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <title>Statistics Page</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
               <?php require('menu.php') ?>
            </header>
            <!-- <div id="loader"></div> -->
            <br />
            <main class="row">
               <div id="divTable" class="col-md-12">
                        <br />
                        <div class="card" style="height:auto">
                            <div class="card-header">
                                Statistics (Cluster Distribution)
                            </div>
                            <div class="card-body h-100">
                            <table id="tblSummary" class="table" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Cluster Name</th>
                                        <th>Total Clusters</th>
                                        <th>Total Nano</th>
                                        <th>Total Nulls</th>
                                    </tr>
                                </thead>
                                <tbody id="tblData">
                                    
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <br />
                        <div class="card" style="height:auto">
                            <div class="card-header">
                                Equivalent IRIs b/w Datasets
                            </div>
                            <div class="card-body h-100">
                            <table id="tblSummaryEqual" class="table" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Topic IRI</th>
                                        <th>Total Nano</th>
                                        <th>Topic IRI</th>
                                        <th>Total Nano</th>
                                        <th>Total Nano Count</th>
                                    </tr>
                                </thead>
                                <tbody id="tblDataEqual">
                                    
                                </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </main>
            
            <footer>
                
            </footer>
        </div>
        
        <script src="Scripts/jquery-3.4.1.min.js"></script>
        <script src="Scripts/popper-1.14.7.min.js"></script>
        <script src="Scripts/bootstrap-4.3.1.min.js"></script>

        <script>
            var data = [];
            let p = new Promise((resolve, reject) => {
                $.ajax({
                    url: 'Code/getClusterByIRI_Label.php',
                    data: {opt:'stat'},
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        data = response;
                        document.getElementById('tblData').innerHTML = '';
                        for(let i=0;i<data.length;i++)
                        {
                            let noOfClusters = parseInt(data[i].no_of_clusters);
                            let noOfValues = parseInt(data[i].no_of_Nano);
                            let noOfNuls = parseInt(data[i].no_of_nulls);
                            document.getElementById('tblData').innerHTML += `<tr>
                                                                <td>${data[i].clusterName}</td>
                                                                <td>${noOfClusters.toLocaleString()}</td>
                                                                <td>${noOfValues.toLocaleString()}</td>
                                                                <td>${noOfNuls.toLocaleString()}</td>
                                                                </tr>`;
                        }
                        document.getElementById("tblSummary").style.display = "block";
                        resolve("success");
                    },
                    error: function (jqXHR, textStatus, errorThrown) { 
                        reject(jqXHR);
                     }
                })
            });
            //////////////////////////////////////////
            var dataEqual = [];
            let p2 = new Promise((resolve, reject) => {
                $.ajax({
                    url: 'Code/getClusterByIRI_Label.php',
                    data: {opt:'equal'},
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        dataEqual = response;
                        document.getElementById('tblDataEqual').innerHTML = '';
                        for(let i=0;i<dataEqual.length;i++)
                        {
                            let totalNano1 = parseInt(dataEqual[i].total_nano_1);
                            let totalNano2 = parseInt(dataEqual[i].total_nano_2);
                            let all_Nanos = totalNano1 + totalNano2;
                            let url = '';
                            if(dataEqual[i].topicIRI.indexOf("http://www.nextprot")!=-1)
                            {
                                url = "https://www.nextprot.org/entry/"+dataEqual[i].topicIRI.split("#")[1];
                            }
                            else{
                                url = dataEqual[i].topicIRI;
                            }
                            
                            document.getElementById('tblDataEqual').innerHTML += `<tr>
                                                                <td><a href="${url}" target="_blank">${url}</a></td>
                                                                <td>${totalNano1.toLocaleString()}</td>
                                                                <td><a href="${dataEqual[i].mappingIRI}"  target="_blank">${dataEqual[i].mappingIRI}</a></td>
                                                                <td>${totalNano2.toLocaleString()}</td>
                                                                <td>${all_Nanos.toLocaleString()}</td>
                                                                </tr>`;
                        }
                        document.getElementById("tblSummaryEqual").style.display = "block";
                        resolve("success");
                    },
                    error: function (jqXHR, textStatus, errorThrown) { 
                        reject(jqXHR);
                     }
                })
            });
        </script>
    </body>
</html>

