
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="../css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="../css/pagination.css" rel="stylesheet" type="text/css"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
        <title>rdfsLabels + IMS Cluster</title>
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
                                Summary - <small id="SearchTime"></small>
                            </div>
                            <div class="card-body">
                                <table id="tbl" class="table table-striped table-bordered" style="width:100%" >
                                    <thead>
                                        <th>rdfsLabel</th>
                                        <th># of Nanopublications</th>
                                        <th>Topic IRI (IMS)</th>
                                        <th># of Nanopublications</th>
                                    </thead>
                                    <tbody id="tblTopic">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
            
            <footer>
                
            </footer>
        </div>
        
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

        <script src="../Scripts/jquery-3.4.1.min.js"></script>
        <script src="../Scripts/popper-1.14.7.min.js"></script>
        <script src="../Scripts/bootstrap-4.3.1.min.js"></script>
        <script src="../Scripts/jquery.dataTables.min.js"></script>
        <script src="../Scripts/dataTables.bootstrap4.min.js"></script>
        <script src="../Scripts/pagination.min.js"></script>
        <script>
            $(document).ready(function() {
                // $('#tbl').DataTable( {
                //     "processing": true,
                //     "serverSide": true,
                //     "ajax": "../Code/cluster_label.php",
                //     "order": [[ 1, "desc" ], [ 0, 'desc' ]]
                // } );
                // $('#tbl').DataTable( {
                //     "processing": true,
                //     "order": [[ 1, "desc" ]]
                // } );

                $.ajax({
                            url: '../Code/cluster_label_ims.php',
                            data: {},
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                               let jsonData = response;
                               let table = '';

                               for(let i=0;i<jsonData.length;i++)
                               {
                                    let totalNanoLabel = jsonData[i].labelNanoCount;
                                    let totalNanoIMS = jsonData[i].nano_count;
                                    let display_content = '';
                                    let resolve_url = '';
                                    let label = '';

                                    if(jsonData[i].topicIRI.indexOf("www.nextprot") !== -1)
                                    {
                                        display_content = "nextprot:"+ jsonData[i].topicIRI.split("#")[1];
                                        resolve_url = "https://www.nextprot.org/entry/"+ jsonData[i].topicIRI.split("#")[1];
                                    }
                                    else if(jsonData[i].topicIRI.indexOf("identifiers.org") !== -1){
                                        let splitIRI = jsonData[i].topicIRI.split("/");
                                        display_content = "id-"+ splitIRI[splitIRI.length - 2] + ":" + splitIRI[splitIRI.length - 1];
                                        resolve_url = jsonData[i].topicIRI;
                                    }
                                    label = jsonData[i].rdfsLabel;

                                    table += `<tr>
                                        <td><a href="${resolve_url}" target="_blank">${label}</a></td>
                                        <td><span style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano('${jsonData[i].topicIRI}', ${totalNanoLabel}, '${label}', '${resolve_url}')"> ${totalNanoLabel}</span></td>
                                        <td><a href="${resolve_url}" target="_blank">${display_content}</a></td>
                                        <td><span style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano('${jsonData[i].topicIRI}', ${totalNanoIMS}, '${display_content}', '${resolve_url}')"> ${totalNanoIMS}</span></td>
                                    </tr>`;
                               }

                               $('#tblTopic').html(table);
                               $('#loader').hide();
                               //$('#tbl').DataTable();
                               $('#tbl').DataTable( {
                                    //"order": [[ 1, "desc" ]]
                                    "ordering": false
                                } );
                            },
                            error: function (jqXHR, textStatus, errorThrown) { 
                                alert( "Request failed: " + textStatus );
                             }
                        })
            });

            function AJAXCallForNano(oiri, count, label, resolveIRI)
            {
                $('#modalTitle').html(`<a href="${resolveIRI}" target="_blank">${label}</a> <br />
                                    <span style="font-size:12px"> ${count} nanopublications</span>`);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                $('#pagesNano').pagination({
                dataSource: function(done) {
                        $.ajax({
                            url: '../Code/getTopicInfo.php',
                            data: { lbl :label },
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

