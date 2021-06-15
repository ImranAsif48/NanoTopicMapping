
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
        <title>IMS Concept Grouping</title>
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
               <?php require('../menu.php') ?>
            </header>
            <br />
            <!-- <div id="loader"></div> -->
            <main class="row">
                    <div id="divTable" class="col-md-12">
                        <br />
                        <div class="card">
                            <div class="card-header">
                                Summary - <small id="SearchTime"></small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tbl" class="table table-striped table-bordered dt-responsive nowrap" >
                                        <thead>
                                            <th>Grouped by IMS</th>
                                            <th># of Nanopublications</th>
                                        </thead>
                                        <tbody id="tblTopic">
                                        </tbody>
                                    </table>
                                </div>
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
            var start = performance.now();
            $('#tbl').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    "type"   : "GET",
                    "url"    : "../Code/cluster_ims_merge.php",
                    "dataSrc": function (json) {
                        let end = performance.now();
                        let time = millisToMinutesAndSeconds(end - start);
                        $('#SearchTime').html(`Processing time  (${time} seconds)`);
                        return json.data;
                    }
                },
                "order": [[ 1, "desc" ], [ 1, "desc" ]]
            } );

            function millisToMinutesAndSeconds(millis) {
                var minutes = Math.floor(millis / 60000);
                var seconds = ((millis % 60000) / 1000).toFixed(4);
                //return minutes + "." + (seconds < 10 ? '0' : '') + seconds;
                return seconds;
            }
        } );
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
            ///////////////////////////////////////////////////////////////////////
            // Display more IMS IRIs
            function AJAXCallForMoreIRI(cId)
            {
                $('#modalTitle').html(`More IMS IRIs`);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                $('#pagesNano').pagination({
                dataSource: function(done) {
                        $.ajax({
                            url: '../Code/get_more_ims_iris.php',
                            data: { c_id :cId },
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                                $('#modalTitle').html(`More IMS IRIs (Total: ${response.length})`);
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
                        //let display_content_ex = '';
						//let resolve_url_ex = '';
                        let iri = result[i].ims_iri;
                        // if(iri.indexOf("www.nextprot")>-1)
						// {
						// 	if(iri.split("#").length>1)
						// 	{
						// 		display_content_ex = "nextprot:"+ iri.split("#")[1];
						// 	    resolve_url_ex = "https://www.nextprot.org/entry/"+ iri.split("#")[1];
						// 	}
						// 	else{
						// 		let splitIRI = iri.split("/");
						// 		display_content_ex = "nextprot:"+ splitIRI[splitIRI.length-1];
						// 		resolve_url_ex = iri;
						// 	  }
						// }
						// else if(iri.indexOf("linkedlifedata.com")>-1)
						// {
						// 	splitIRI = iri.split("/");
						// 	display_content_ex = "linkedlifedata:"+ splitIRI[splitIRI.length-1];
						// 	resolve_url_ex = iri;
						// }
						// else if(iri.indexOf("identifiers.org")>-1){
						// 	splitIRI = iri.split("/");
						// 	display_content_ex = "id-"+ splitIRI[splitIRI.length-2] + ":" + splitIRI[splitIRI.length-1];
						// 	resolve_url_ex = iri;
						// }
                        d += `<a href="${iri}" target="_blank">${iri}</a><br />`;
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
