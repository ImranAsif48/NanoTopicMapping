
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link href="css/bootstrap-4.3.1.min.css" rel="stylesheet" />
        <link href="css/pagination.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="css/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <title>Search in Nanopublications</title>
        <style>
            .ui-autocomplete-loading {
                background: white url("https://jqueryui.com/resources/demos/autocomplete/images/ui-anim_basic_16x16.gif") right center no-repeat;
            }
            .menu {
                position: absolute;
                top: 0;
                right: 0;
                margin: 10px;
                z-index: 9999;
            }
            .modal-dialog-slideout {min-height: 100%; margin: 0 0 0 auto;background: #fff;}
            .modal.fade .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(100%,0)scale(1);transform: translate(100%,0)scale(1);}
            .modal.fade.show .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(0,0);transform: translate(0,0);display: flex;align-items: stretch;-webkit-box-align: stretch;height: 100%;}
            .modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body{overflow-y: auto;overflow-x: hidden;}
            .modal-dialog-slideout .modal-content{border: 0;}
            .modal-dialog-slideout .modal-header, .modal-dialog-slideout .modal-footer {height: 69px; display: block;}
            .modal-dialog-slideout .modal-header h5 {float:left;}
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
                            <h2>Search By Keyword</h2>
                            <hr />
                                <div class="search-form">
                                        <div class="input-group">
                                            <input type="text" id="txtSearch" placeholder="Search term" name="search" class="form-control input-lg">
                                            &nbsp;
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary" id="btnSearch" type="button" onclick="SearchTerm()">
                                                    Search
                                                </button>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="ios-segmented-control">
                                                <span class="selection"></span>
                                                <div class="option">
                                                    <input type="radio" id="rdoAll" onchange="SearchTerm()" name="search" value="all" checked>
                                                    <label for="rdoAll"><span>All</span></label>
                                                </div>
                                                <div class="option">
                                                    <input type="radio" id="rdoIRI" onchange="SearchTerm()" name="search" value="iri">
                                                    <label for="rdoIRI"><span>Group by IRI</span></label>
                                                </div>
                                                <div class="option">
                                                    <input type="radio" id="rdoLabel" onchange="SearchTerm()" name="search" value="label">
                                                    <label for="rdoLabel"><span>Group by Label</span></label>
                                                </div>
                                                <div class="option">
                                                    <input type="radio" id="rdoIMS" onchange="SearchTerm()" name="search" value="ims">
                                                    <label for="rdoIMS"><span>Group by IMS</span></label>
                                                </div>
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
              <div class="modal-dialog" id="divDialog" style="max-width: 50%;" role="document">
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
                      <div id="divTabs">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="npHash-tab" data-toggle="tab" href="#npHash" role="tab">Hash</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">Timeline</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="npHash" role="tabpanel">
                                    <div id="divContent">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="timeline" role="tabpanel">
                                        <div class="menu" id="visButtons">
                                            <i class="fas fa-search-plus" id="zoomIn" title="Zoom in" style="cursor:pointer;"></i>
                                            <i class="fas fa-search-minus" id="zoomOut" title="Zoom out" style="cursor:pointer;"></i>
                                            <i class="fas fa-arrow-left" id="moveLeft" title="Move left" style="cursor:pointer;"></i>
                                            <i class="fas fa-arrow-right" id="moveRight" title="Move right" style="cursor:pointer;"></i>
                                        </div>
                                    <div id="visualization">

                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                    <div id="pagesNano"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
                <div class="modal-dialog modal-dialog-slideout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">More Timeline content</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" id="moreTimelineNano">
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
        <script src="Scripts/vis-timeline-graph2d.min.js"></script>
        <script>

    ////////////////
    //// When user Press 'Enter'
    $('#txtSearch').keyup(function(e){
        if(e.keyCode === 13)
        {
            e.preventDefault();
            $('#btnSearch').trigger('click');
        }
    });
    /////////////////////////////////////////////
    // Adjust the Modal Size
    $('#npHash-tab').click(function(){
        $('#divDialog').animate({maxWidth: '50%'}, 1000);
        $('#pagesNano').show();
        $('#visButtons').hide();
    });

    $('#timeline-tab').click(function(){
        $('#divDialog').animate({maxWidth: '90%'}, 1000);
        $('#pagesNano').hide();
    });

    //////////////////////////////////
    //// Loader
      $("#loader").hide();
    //////////////////////////////
    // Auto Complete
    //var option = 'iri';
    $(function() {
        var cacheIRI = {};
        var cacheLabel = {};
        var cacheLabelIMS = {};

        $("#txtSearch").autocomplete({
        minLength: 2,
        autoFocus: true,
        source: function( request, response ) {
                var term = request.term.toLowerCase().replace("'", "\\'");
                if(document.getElementById('rdoIRI').checked)
                {
                    request.option = 'iri';
                    request.search = 'keyword';
                    if ( term in cacheIRI ) {
                        response( cacheIRI[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoLabel').checked || document.getElementById('rdoAll').checked)
                {
                    request.option = 'label';
                    request.search = 'keyword';
                    if ( term in cacheLabel ) {
                        response( cacheLabel[ term ] );
                        return;
                    }
                }
                else if(document.getElementById('rdoIMS').checked)
                {
                    request.option = 'label_ims';
                    request.search = 'keyword';
                    if ( term in cacheLabelIMS ) {
                        response( cacheLabelIMS[ term ] );
                        return;
                    }
                }
            //////////////////////////////////////
            $.getJSON( "Code/searchAutoComplete.php", request, function( data, status, xhr ) {

                if(document.getElementById('rdoIRI').checked)
                {
                    cacheIRI[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoLabel').checked || document.getElementById('rdoAll').checked)
                {
                    cacheLabel[term] = data;
                    response( data );
                }
                else if(document.getElementById('rdoIMS').checked)
                {
                    cacheLabelIMS[term] = data;
                    response( data );
                }
            });
        }
      });
    });

    /////////////////////////
    function SearchTerm()
    {
        if($('#txtSearch').val()!='')
        {
            if(document.getElementById('rdoAll').checked)
            {
                callAJAXAll('searchByAll.php');
            }
            else if(document.getElementById('rdoIRI').checked)
            {
                callAJAXIRI('groupByIRI.php');
            }
            else if(document.getElementById('rdoLabel').checked)
            {
                callAJAXLabel('groupByLabel.php');
            }
            else if(document.getElementById('rdoIMS').checked)
            {
                callAJAXIMS('groupByLabelIMS.php');
            }
        }
    }

    function callAJAXAll(pageName)
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
                                data: { searchTerm : $('#txtSearch').val(), searchBy:'lbl' },
                                dataType: "json",
                                success: function(response) {
                                    done(response);
                                    var end = performance.now();
                                    let time = millisToMinutesAndSeconds(end - start);
                                    $('#divSearchContent').show();
                                    $('#totalSearchCount').html(`${response.length.toLocaleString()} results found for: <span class="text-navy">"${$('#txtSearch').val()}"</span>`)
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
                                let iri = result[i].IRI;
                                obj = getResolvableIRI(iri);
                                let display_content_ex = obj.display_content_ex;
                                let resolve_url_ex = obj.resolve_url_ex;
                                d += `<div class="card" style="margin-bottom:0.4%;">
                                        <div class="card-header" id="heading${i}">
                                            <h5 class="mb-0 d-inline text-primary">
                                                ${result[i].rdfsLabel}
                                            </h5>
                                            <a class="float-right" href="https://openphacts.cs.man.ac.uk/nanopub/server/${result[i].npHash}" target="_blank" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;background-color: transparent;">Show Nanopublication</span>
                                        </div>
                                        <div class="card-body">
                                          <div class="search-result">
                                              <a href="${resolve_url_ex}" class="search-link" target="_blank">${display_content_ex}</a>
                                              <p>
                                                  Dataset(s): ${result[i].dataset}
                                              </p>
                                          </div>
                                        </div>
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

    function callAJAXIRI(pageName)
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
                                data: { searchTerm : $('#txtSearch').val().replace("'", "\\'"), searchBy:'keyword' },
                                dataType: "json",
                                success: function(response) {
                                    done(response.data);
                                    var end = performance.now();
                                    let time = millisToMinutesAndSeconds(end - start);
                                    $('#divSearchContent').show();
                                    $('#totalSearchCount').html(`${response.data.length.toLocaleString()} Concept Grouping results found for: <span class="text-navy">"${$('#txtSearch').val()}" with ${response.total_nano_count.toLocaleString()} Nanopublications.</span>`)
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
                                let iri = result[i].IRI;
                                obj = getResolvableIRI(iri);
                                let display_content_ex = obj.display_content_ex;
                                let resolve_url_ex = obj.resolve_url_ex;
                                d += `<div class="card" style="margin-bottom:0.4%;">
                                        <div class="card-header" id="heading${i}">
                                            <h5 class="mb-0 d-inline text-primary">
                                                ${result[i].rdfsLabel}
                                            </h5>
                                            <span class="my-2 float-right" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;" onclick="getNanos('${iri}', ${result[i].nano_count}, '${result[i].rdfsLabel.replace("'", "\\'")}')">Total Nanopubs: ${parseInt(result[i].nano_count).toLocaleString()}</span>
                                        </div>
                                        <div class="card-body">
                                          <div class="search-result">
                                              <a href="${obj.resolve_url_ex}" class="search-link" target="_blank">${obj.display_content_ex}</a>
                                              <p>
                                                  Dataset(s): ${result[i].datasets}
                                              </p>
                                          </div>
                                        </div>
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

    function callAJAXLabel(pageName)
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
                                data: { searchTerm : $('#txtSearch').val().replace("'", "\\'"), searchBy:'keyword'},
                                dataType: "json",
                                success: function(response) {
                                    done(response.data);
                                    var end = performance.now();
                                    let time = millisToMinutesAndSeconds(end - start);
                                    $('#divSearchContent').show();
                                    $('#totalSearchCount').html(`${response.data.length.toLocaleString()} Concept Grouping results found for: <span class="text-navy">"${$('#txtSearch').val()}" with ${response.total_nano_count.toLocaleString()} Nanopublications.</span>`)
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
                        let obj = '';
                        if(result.length>0)
                        {
                            for(var i=0;i<result.length;i++)
                            {
                                let display_content_ex = '';
                                let resolve_url_ex = '';
                                //result[i].rdfsLabel = result[i].rdfsLabel.replace("'", "\\'");
                                if(result[i].iri.indexOf(",")>-1)
                                {
                                  d += `<div class="card" style="margin-bottom:0.4%;">
                                          <div class="card-header" id="heading${i}">
                                              <h5 class="mb-0 d-inline text-primary">
                                                  ${result[i].rdfsLabel}
                                              </h5>
                                              <span class="my-2 float-right" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;" onclick="getNanos('${result[i].rdfsLabel.replace("'", "\\'")}', ${result[i].nano_count}, '${result[i].rdfsLabel.replace("'", "\\'")}')">Total Nanopubs: ${result[i].nano_count.toLocaleString()}</span>
                                          </div>
                                          <div class="card-body">
                                            <div class="card-columns">`;
                                              for(let child of result[i].child)
                                              {
                                                  obj = getResolvableIRI(child.iri);
                                                  display_content_ex = obj.display_content_ex;
                                                  resolve_url_ex = obj.resolve_url_ex;
                                                  d+= `<div class="card">
                                                          <div class="card-header">
                                                              <span class="text-primary">${display_content_ex}</span>
                                                              <span data-toggle="tooltip" title="Nanopubs count" class="badge badge-primary float-right" style="cursor:pointer;" onclick="getNanos('${child.iri}', ${child.nano_count}, '${result[i].rdfsLabel.replace("'", "\\'")}', 'iri')">${parseInt(child.nano_count).toLocaleString()}</span>
                                                          </div>
                                                          <div class="card-body">
                                                              <div class="search-result">
                                                                  <a href="${resolve_url_ex}" class="search-link" target="_blank">${display_content_ex}</a>
                                                                  <p>
                                                                      Dataset(s): ${child.dataset}
                                                                  </p>
                                                              </div>
                                                          </div>
                                                      </div>`;
                                              }
                                    d+= `</div></div>
                                        </div>`;
                                }
                                else{
                                    let iri = result[i].iri;
                                    obj = getResolvableIRI(iri);
                                    display_content_ex = obj.display_content_ex;
                                    resolve_url_ex = obj.resolve_url_ex;
                                    d += `<div class="card" style="margin-bottom:0.4%;">
                                            <div class="card-header" id="heading${i}">
                                                <h5 class="mb-0 d-inline text-primary">
                                                    ${result[i].rdfsLabel}
                                                </h5>
                                                <span class="my-2 float-right" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;" onclick="getNanos('${result[i].rdfsLabel.replace("'", "\\'")}', ${result[i].nano_count}, '${result[i].rdfsLabel.replace("'", "\\'")}')">Total Nanopubs: ${result[i].nano_count.toLocaleString()}</span>
                                            </div>
                                            <div class="card-body">
                                              <div class="search-result">
                                                  <a href="${obj.resolve_url_ex}" class="search-link" target="_blank">${obj.display_content_ex}</a>
                                                  <p>
                                                      Dataset(s): ${result[i].datasets}
                                                  </p>
                                              </div>
                                            </div>
                                          </div>`;
                                }
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

    function callAJAXIMS(pageName)
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
                                data: { searchTerm : $('#txtSearch').val().replace("'", "\\'"), searchBy:'keyword'  },
                                dataType: "json",
                                success: function(response) {
                                    done(response.data);
                                    var end = performance.now();
                                    let time = millisToMinutesAndSeconds(end - start);
                                    $('#divSearchContent').show();
                                    $('#totalSearchCount').html(`${response.data.length.toLocaleString()} Concept Grouping results found for: <span class="text-navy">"${$('#txtSearch').val()}" with ${response.total_nano_count.toLocaleString()} Nanopublications.</span>`)
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
                                if(result[i].child.length>0)
                                {
                                    let obj = getResolvableIRI(result[i].iri);
                                    let child = result[i].child;
                                    d += `<div">
                                            <div class="card" style="margin-bottom:0.4%;">
                                                <div class="card-header" id="heading${i}">
                                                    <h5 class="mb-0 d-inline text-primary">
                                                        ${result[i].rdfsLabel}
                                                    </h5>
                                                    <span class="my-2 float-right" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;" onclick="getNanos('${result[i].IRIs}', ${result[i].total_nano}, '${result[i].rdfsLabel.replace("'", "\\'")}')">Total Nanopubs: ${result[i].total_nano.toLocaleString()}</span>
                                                </div>
                                                <div class="card-body">
                                                    <div class="card-columns">`;
                                                    for(let j=0; j<child.length;j++)
                                                    {
                                                        let objChild = getResolvableIRI(child[j].ims_iri);
                                                        d+= `<div class="card">
                                                                <div class="card-header">
                                                                    <span class="text-primary">${child[j].rdfsLabel}</span>
                                                                    <span data-toggle="tooltip" title="Nanopubs count" class="badge badge-primary float-right" style="cursor:pointer;" onclick="getNanos('${child[j].ims_iri}', ${child[j].nano_count}, '${child[j].rdfsLabel.replace("'", "\\'")}')">${parseInt(child[j].nano_count).toLocaleString()}</span>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="search-result">
                                                                        <a href="${objChild.resolve_url_ex}" class="search-link" target="_blank">${objChild.display_content_ex}</a>
                                                                        <p>
                                                                            Dataset(s): ${child[j].datasets}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>`;
                                                    }

                                        d+=`<div class="card">
                                                                <div class="card-header">
                                                                    <span class="text-primary">${result[i].rdfsLabel}</span>
                                                                    <span data-toggle="tooltip" title="Nanopubs count" class="badge badge-primary float-right" style="cursor:pointer;" onclick="getNanos('${result[i].iri}', ${result[i].nano_count}, '${result[i].rdfsLabel.replace("'", "\\'")}')">${parseInt(result[i].nano_count).toLocaleString()}</span>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="search-result">
                                                                        <a href="${obj.resolve_url_ex}" class="search-link" target="_blank">${obj.display_content_ex}</a>
                                                                        <p>
                                                                            Dataset(s): ${result[i].datasets}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>`;
                                }
                                else
                                {
                                    let obj = getResolvableIRI(result[i].iri);
                                    d += `<div class="card" style="margin-bottom:0.4%;">
                                              <div class="card-header" id="heading${i}">
                                                  <h5 class="mb-0 d-inline text-primary">
                                                      ${result[i].rdfsLabel}
                                                  </h5>
                                                  <span class="my-2 float-right" style="font-weight: 400;color: #007bff;text-decoration: none;cursor:pointer;" onclick="getNanos('${result[i].IRIs}', ${result[i].total_nano}, '${result[i].rdfsLabel.replace("'", "\\'")}')">Total Nanopubs: ${result[i].total_nano.toLocaleString()}</span>
                                              </div>
                                              <div class="card-body">
                                                <div class="search-result">
                                                    <a href="${obj.resolve_url_ex}" class="search-link" target="_blank">${obj.display_content_ex}</a>
                                                    <p>
                                                        Dataset(s): ${result[i].datasets}
                                                    </p>
                                                </div>
                                              </div>
                                            </div>`;
                                }
                            }
                        }
                        else{
                            data = 'There is no record to show'
                        }

                    d += `<div class="hr-line-dashed">`;
                    //var html = template(data);
                    $("#divSearchContent").html(d);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            })
        }
    }

    function getResolvableIRI(iri)
    {
        let obj = {
            display_content_ex: '',
            resolve_url_ex: ''
        }
        obj = {};
        if(iri.indexOf("www.nextprot")>-1)
        {
        	if(iri.split("#").length>1)
        	{
        		obj.display_content_ex = "nextprot:"+ iri.split("#")[1];
        	    obj.resolve_url_ex = "https://www.nextprot.org/entry/"+ iri.split("#")[1];
        	}
        	else{
        		let splitIRI = iri.split("/");
        		obj.display_content_ex = "nextprot:"+ splitIRI[splitIRI.length-1];
        		obj.resolve_url_ex = iri;
        	  }
        }
        else if(iri.indexOf("linkedlifedata.com")>-1)
        {
        	splitIRI = iri.split("/");
        	obj.display_content_ex = "linkedlifedata:"+ splitIRI[splitIRI.length-1];
        	obj.resolve_url_ex = iri;
        }
        else if(iri.indexOf("identifiers.org")>-1){
        	splitIRI = iri.split("/");
        	obj.display_content_ex = "id-"+ splitIRI[splitIRI.length-2] + ":" + splitIRI[splitIRI.length-1];
        	obj.resolve_url_ex = iri;
        }
        else if(iri.indexOf("disgenet.org")>-1){
        	splitIRI = iri.split("/");
        	obj.display_content_ex = "gda:" + splitIRI[splitIRI.length-1];
        	obj.resolve_url_ex = 'http://rdf.disgenet.org/lodestar/describe?uri=http%3A%2F%2Frdf.disgenet.org%2Fresource%2Fgda%2F'+splitIRI[splitIRI.length-1];
        }
        else{
            obj.display_content_ex = iri;
        	obj.resolve_url_ex = iri;
        }

        return obj;
    }

    function millisToMinutesAndSeconds(millis) {
        var minutes = Math.floor(millis / 60000);
        var seconds = ((millis % 60000) / 1000).toFixed(4);
        //return minutes + "." + (seconds < 10 ? '0' : '') + seconds;
        return seconds;
    }

    var timeline;
    function getNanos(oiri, count, label, opt)
    {
            document.getElementById('visualization').innerHTML = '';
            //alert(d.data.name + ' ' + d.value);
            if(oiri.indexOf('#')!=-1)
            {
                var splitIRI = oiri.split("#");
                $('#modalTitle').html(`${label} - ${splitIRI[1]} <br />`);
            }
            else if(label != '')
            {
                $('#modalTitle').html(`${label} <br />`);
            }
            else if(label == '')
            {
                $('#modalTitle').html(`${oiri} <br />`);
            }
                $('#modalTitle').html($('#modalTitle').html() + `<span style="font-size:12px"> ${count.toLocaleString()} nanopublications</span>`);
                $('#divTabs').hide();
                $('#divSpin').show();
                $('#visButtons').hide();
                document.getElementById("btnModal").click();
                $('#npHash-tab').click();
                ///////////////////////////////////////////////////////
                var searchData;

                if(document.getElementById('rdoIRI').checked || document.getElementById('rdoIMS').checked)
                {
                    searchData = { iri :oiri };
                }
                else if(document.getElementById('rdoLabel').checked)
                {
                  if(opt == 'iri')
                  {
                    searchData = { iri :oiri };
                  }
                  else {
                    searchData = { lbl :label.replace("'", "\\'") };
                  }
                }
                ///////////////////////////////////////////////////////
                $('#pagesNano').pagination({
                dataSource: function(done) {
                        $.ajax({
                            url: 'Code/getNanoInfo.php',
                            data: searchData,
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                                done(response);

                                let objTimeline = {id:0, content: '', start:''};
                                let timelineArray = [];
                                let i = 1;
                                let groupedData = [];

                                if(response.length > 1000)
                                {
                                  groupedData = groupBy(response, function(item)
                                                      {
                                                          return [item.createdOn];
                                                      });
                                }
                                else {
                                  groupedData = groupBy(response, function(item)
                                                      {
                                                          return [item.pubDate];
                                                      });
                                }

                                for(let i=0;i<groupedData.length;i++)
                                {
                                    objTimeline = {};
                                    objTimeline.id = i;
                                    let np_claim = groupedData[i][0].np_claim;
                                    let trustyURI = groupedData[i][0].trustyURI;
                                    let dataset = groupedData[i][0].dataset;
                                    let createdOn = '';

                                    if(dataset==='wikipathways')
                                    {
                                        createdOn = groupedData[i][0].pubDate;
                                        objTimeline.content = `<a title="${np_claim}" href="${trustyURI}" target="_blank">Interaction in: ${np_claim.substring(0, 50)}...</a>` ;
                                    }
                                    else
                                    {
                                        createdOn = groupedData[i][0].createdOn;
                                        objTimeline.content = `<a title="${np_claim}" href="${trustyURI}" target="_blank">${np_claim.substring(0, 50)}...</a> `;
                                    }

                                    if(groupedData[i].length > 1)
                                    {
                                        let jsonString = JSON.stringify(groupedData[i]).replace(/[\']/g, "&apos;");
                                        objTimeline.content += `<br /> <span onclick='showMoreTimeline(${jsonString})' style="font-size:12px;color: #007bff;cursor:pointer;" data-toggle="modal" data-target="#exampleModal4">${groupedData[i].length - 1} more...</span>`;
                                    }

                                    objTimeline.content += `<br /> <span>${dataset}</span>`;

                                    objTimeline.start = createdOn;
                                    //i++;
                                    timelineArray.push(objTimeline);
                                }

                                // DOM element where the Timeline will be attached

                                var container = document.getElementById('visualization');

                                // Create a DataSet (allows two way data-binding)
                                var items = new vis.DataSet(timelineArray);

                                // Configuration for the Timeline
                                var options = {
                                    stack: true,
                                    orientation: {axis: 'both', item: 'top'},
                                    //orientation: {item: 'top'},
                                    loadingScreenTemplate: function() {
                                         return '<h3>Loading timeline...</h3>'
                                    },
                                    verticalScroll: true,
                                    zoomKey: 'ctrlKey',
                                    maxHeight: 500
                                };

                                // Create a Timeline
                                timeline = new vis.Timeline(container, items, options);

                                $('#divTabs').show();
                                $('#divSpin').hide();
                                $('#visButtons').show();
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
                    let d = '<br />';
                    let trustyURI = '';
                    for(var i=0;i<result.length;i++)
                    {
                        d += `<a href="${result[i].trustyURI}" target="_blank">${result[i].npHash}</a><br />`;
                    }

                //d += `<div class="hr-line-dashed">`;
                //var html = template(data);
                $("#divContent").html(d);
            }
        })
    }

    function showMoreTimeline(moreTimeline)
    {
        let htmlData = '';
        $('#moreTimelineNano').html('');
        for(let i=1; i < moreTimeline.length; i++)
        {
            let np_claim = moreTimeline[i].np_claim;
            let trustyURI = moreTimeline[i].trustyURI;
            let dataset = moreTimeline[i].dataset;
            let createdOn = '';//moreTimeline[i].createdOn;
            if(dataset==='wikipathways')
            {
                createdOn = moreTimeline[i].pubDate;
                htmlData += `<a title="${np_claim}" href="${trustyURI}" target="_blank">Interaction in: ${np_claim}</a>`;

            }
            else
            {
                createdOn = moreTimeline[i].createdOn;
                htmlData += `<a title="${np_claim}" href="${trustyURI}" target="_blank">${np_claim}</a>`;
            }

            htmlData += `<br /> <span>${createdOn}</span>`;
            htmlData += `<br /> <span>${dataset}</span> <hr />`;
        }

        $('#moreTimelineNano').html(htmlData);
    }

    function move (percentage) {
        var range = timeline.getWindow();
        var interval = range.end - range.start;

        timeline.setWindow({
            start: range.start.valueOf() - interval * percentage,
            end:   range.end.valueOf()   - interval * percentage
        });
    }

    // attach events to the navigation buttons
    document.getElementById('zoomIn').onclick    = function () { timeline.zoomIn( 0.4); };
    document.getElementById('zoomOut').onclick   = function () { timeline.zoomOut( 0.4); };
    document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
    document.getElementById('moveRight').onclick = function () { move(-0.2); };

    function groupBy( array , f )
    {
        var groups = {};
        array.forEach( function( o )
        {
            var group = JSON.stringify( f(o) );
            groups[group] = groups[group] || [];
            groups[group].push( o );
        });
        return Object.keys(groups).map( function( group )
        {
            return groups[group];
        })
    };
    ////////////////////////////////////////////////////////////////////////////////////////
    ///// IOS Radio button code
    // Constants

                const SEGMENTED_CONTROL_BASE_SELECTOR = ".ios-segmented-control";
                const SEGMENTED_CONTROL_INDIVIDUAL_SEGMENT_SELECTOR = ".ios-segmented-control .option input";
                const SEGMENTED_CONTROL_BACKGROUND_PILL_SELECTOR = ".ios-segmented-control .selection";


                // Main

                document.addEventListener("DOMContentLoaded", setup);

                // Body functions

                function setup() {
                forEachElement(SEGMENTED_CONTROL_BASE_SELECTOR, elem => {
                    elem.addEventListener("change", updatePillPosition);
                })
                    window.addEventListener("resize", updatePillPosition); // Prevent pill from detaching from element when window resized. Becuase this is rare I haven't bothered with throttling the event
                }

                function updatePillPosition() {
                forEachElement(SEGMENTED_CONTROL_INDIVIDUAL_SEGMENT_SELECTOR, (elem, index) => {
                     if (elem.checked) moveBackgroundPillToElement(elem, index);
                    })
                }

                function moveBackgroundPillToElement(elem, index) {
                    document.querySelector(SEGMENTED_CONTROL_BACKGROUND_PILL_SELECTOR).style.transform = "translateX(" + (elem.offsetWidth * index) + "px)";
                }

                // Helper functions

                function forEachElement(className, fn) {
                    Array.from(document.querySelectorAll(className)).forEach(fn);
                }
        </script>
    </body>
</html>
