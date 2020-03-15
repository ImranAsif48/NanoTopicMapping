
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="css/pagination.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <title>Search in Nanopublications</title>
        <style>
            .ui-autocomplete-loading {
                background: white url("https://jqueryui.com/resources/demos/autocomplete/images/ui-anim_basic_16x16.gif") right center no-repeat;
            }
        </style>
        
    </head>
    <body>
        <div class="container-fluid h-100">
            <header>
                <?php require('menu.php') ?>
            </header>
            
            <main class="h-100">
                <div class="row h-100">
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div class="search-form">
                                        <div class="col-lg-6">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-secondary active">
                                                          <input type="radio" name="options" value="name" onclick="clearText()" id="rdoProteinName" autocomplete="off" checked> Protein Name
                                                        </label>
                                                        <label class="btn btn-secondary">
                                                          <input type="radio" name="options" value="id" id="rdoProteinID" onclick="clearText()" autocomplete="off"> Protein ID
                                                        </label>
                                                        <label class="btn btn-secondary">
                                                                <input type="radio" name="options" value="gene" id="rdoGene" onclick="clearText()" autocomplete="off"> Gene
                                                        </label>
                                                        <label class="btn btn-secondary">
                                                                <input type="radio" name="options" value="iri" id="rdoIRI" onclick="clearText()" autocomplete="off"> By IRI
                                                        </label>
                                                        <label class="btn btn-secondary">
                                                                <input type="radio" name="options" value="iri" id="rdoLabel" onclick="clearText()" autocomplete="off"> By Label
                                                        </label>
                                                      </div>
                                        </div> <br />
                                        <div class="input-group">            
                                            <input type="text" id="txtSearch" placeholder="Search term" name="search" class="form-control input-lg">
                                            &nbsp;
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary" type="button" onclick="SearchTerm()">
                                                    Search
                                                </button>
                                            </div>
                                        </div>
                                </div><br />
                                <h2 id="totalSearchCount">
                                    </h2>
                                    <small id="SearchTime"></small>
                                <div id="loader"></div>
                                <div id="divSearchContent">
                                </div>
                                <div id="page-selection"></div>
                            </div>
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
        <script src="Scripts/pagination.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
    //////////////////////////////////
    //// Loader 
      $("#loader").hide();
    //////////////////////////////
    // Clear search text box
    $('.btn-group-toggle > label').click(clearText);
    $('#txtSearch').focus();
    function clearText()
    {
        $('#txtSearch').focus();
        $('#txtSearch').val('');
        $('#txtSearch').focus();
    }
    /////////////////////////////////
    // Auto Complete
    var option = 'proteinName';
    $(function() {
        var cacheProteinName = {};
        var cacheProteinID = {};
        var cacheGene = {};
        var cacheIRI = {};
        var cacheLabel = {};

        $("#txtSearch").autocomplete({
        minLength: 2,
        autoFocus: true,
        source: function( request, response ) {
                var term = request.term.toLowerCase();
                if(document.getElementById('rdoProteinName').checked)
                {
                    request.option = 'proteinName';
                    if ( term in cacheProteinName ) {
                        response( cacheProteinName[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoProteinID').checked)
                {
                    request.option = 'proteinId';
                    if ( term in cacheProteinID ) {
                        response( cacheProteinID[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoGene').checked)
                {
                    request.option = 'gene';
                    if ( term in cacheGene ) {
                        response( cacheGene[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoIRI').checked)
                {
                    request.option = 'iri';
                    if ( term in cacheIRI ) {
                        response( cacheIRI[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoLabel').checked)
                {
                    request.option = 'label';
                    if ( term in cacheLabel ) {
                        response( cacheLabel[ term ] );
                        return;
                    }
                }
            //////////////////////////////////////
            $.getJSON( "Code/searchAutoComplete.php", request, function( data, status, xhr ) {
                
                if(document.getElementById('rdoProteinName').checked)
                {
                    cacheProteinName[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoProteinID').checked)
                {
                    cacheProteinID[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoGene').checked)
                {
                    cacheGene[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoIRI').checked)
                {
                    cacheIRI[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoLabel').checked)
                {
                    cacheLabel[term] = data;
                    response( data );
                }
            });
        }
      });
    });

    /////////////////////////
    function SearchTerm()
    {
        if(document.getElementById('rdoProteinName').checked)
        {
            callAJAX('searchProteinByName.php');
        } 
        else if(document.getElementById('rdoProteinID').checked)
        {
            callAJAX('searchProteinByID.php');
        }
        else if(document.getElementById('rdoGene').checked)
        {
            callAJAX('searchByGene.php');
        }
        else if(document.getElementById('rdoIRI').checked)
        {
            callAJAX('searchByIRI.php');
        }
        else if(document.getElementById('rdoLabel').checked)
        {
            callAJAX('searchByLabel.php');
        }
    }

    function callAJAX(pageName)
    {
        if($('#txtSearch').val()!=='')
        {
            var searchTerm = $('#txtSearch').val();
            $('#divSearchContent').hide();
            $('#loader').show()
            $('#totalSearchCount').html('')
            $('#SearchTime').html('');
            var start = performance.now();
            var end;
            $('#page-selection').pagination({
                dataSource: function(done) {
                            $.ajax({
                                url: 'Code/' + pageName,
                                method: "POST",
                                data: { search : $('#txtSearch').val() },
                                dataType: "json",
                                success: function(response) {
                                    done(response);
                                    var end = performance.now();
                                    let time = millisToMinutesAndSeconds(end - start);
                                    $('#divSearchContent').show();
                                    $('#totalSearchCount').html(`${response.length} results found for: <span class="text-navy">"${$('#txtSearch').val()}"</span>`)
                                    $('#SearchTime').html(`Request time  (${time} seconds)`);
                                    $('#loader').hide();
                                },
                                error: function (jqXHR, textStatus, errorThrown) { 
                                    alert( "Request failed: " + textStatus );
                                    $('#divSearchContent').hide();
                                    $('#loader').hide();
                                }
                            })
                        },
                pageSize: 5,
                callback: function(data, pagination) {
                    // template method of yourself
                        let result = data;
                        let d = '';
                        if(result.length>0)
                        {
                            for(var i=0;i<result.length;i++)
                            {
                                let IRI = '';
                                if(result[i].dataset.toLowerCase() === 'nextprot')
                                {
                                    IRI = result[i].IRI;
                                }
                                else{
                                    IRI = result[i].OrignalIRI;
                                }
                                var fullName = "", description = "";
                                if(result[i].fullName!=null)
                                {
                                    fullName = result[i].fullName;
                                }
                                if(result[i].description!=null)
                                {
                                    description = result[i].description;
                                }

                                d += `<div class="hr-line-dashed"></div><div class="search-result">
                                                <h3><a href="${IRI}" target="_blank">${fullName}</a></h3>
                                                <a href="${IRI}" class="search-link" target="_blank">${IRI}</a>
                                                <p>
                                                    ${description}
                                                </p>
                                                <a href="#" style="font-size:12px" onclick="getNanos('${result[i].OrignalIRI}', ${result[i].NanoCount}, '${fullName}')">Available in ${result[i].NanoCount} Nanopublications.
                                            </div>`;
                            }
                        }
                        else{
                            data = 'There is no record to show'
                        }
                    
                    d += `<div class="hr-line-dashed">`;    
                    //var html = template(data);
                    $("#divSearchContent").html(d);
                }
            })
        }
    }

    function millisToMinutesAndSeconds(millis) {
        var minutes = Math.floor(millis / 60000);
        var seconds = ((millis % 60000) / 1000).toFixed(0);
        return minutes + "." + (seconds < 10 ? '0' : '') + seconds;
    }

    function getNanos(oiri, count, label)
    {
            //alert(d.data.name + ' ' + d.value);
            if(oiri.indexOf('#')!=-1)
            {
                var splitIRI = oiri.split("#");
                $('#modalTitle').html(`${label} - ${splitIRI[1]} <br />`);
            }
            else if(label == '')
            {
                $('#modalTitle').html(`${oiri} <br />`);
            }
                $('#modalTitle').html($('#modalTitle').html() + `<span style="font-size:12px"> ${count} nanopublications</span>`);
                $('#divContent').hide();
                $('#divSpin').show();
                document.getElementById("btnModal").click();
                ///////////////////////////////////////////////////////
                var searchData;
                if(document.getElementById('rdoProteinName').checked)
                {
                    searchData = { proteinName :label };
                } 
                else if(document.getElementById('rdoProteinID').checked)
                {
                    searchData = { iri :oiri };
                }
                else if(document.getElementById('rdoGene').checked)
                {
                    searchData = { iri :oiri };
                }
                else if(document.getElementById('rdoIRI').checked)
                {
                    searchData = { iri :oiri };
                }
                else if(document.getElementById('rdoLabel').checked)
                {
                    searchData = { label :label };
                }
                ///////////////////////////////////////////////////////
                $('#pagesNano').pagination({
            dataSource: function(done) {
                        $.ajax({
                            url: 'Code/getTopicInfo.php',
                            data: searchData,
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

