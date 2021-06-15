
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
                        <h1>Topic Mapping b/w Nanopublications</h1>
                        <p>More than 10 Million of nanopublications have been published in life Sciences domain across over the world. We
                           took 4.8 Million from them for our analysis. They are from neXtProt, DisGeNET, and Wikipathways datasets.
                           They do not provide the interoperability between the datasets. Here we proposd the framework that brings the
                           nanopublications together on particular topic with timeline of discourse.</p>
                      </div>
                    </div>
              </main>
            <div class="row">
                    <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-success">
                      <div class="card-block bg-success">
                        <div class="rotate">
                          <i class="far fa-file-alt fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">Nanopubs</h6>
                        <!-- Without blank nodes -->
                        <h1 class="display-4" id="nanopubs">4,818,670</h1>
                        <!-- With blank nodes -->
                        <!-- <h1 class="display-4" id="nanopubs">5,463,258</h1> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-danger">
                      <div class="card-block bg-danger">
                        <div class="rotate">
                          <i class="fas fa-times fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">neXtProt Nanopubs</h6>

                        <!-- with blank nodes  -->
                        <!-- <h1 class="display-4">4,021,762</h1> -->

                        <!-- Without blank nodes -->
                        <h1 class="display-4">3,377,258</h1>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-primary">
                      <div class="card-block bg-primary">
                        <div class="rotate">
                          <i class="fas fa-project-diagram fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">DisGeNET Nanopubs</h6>
                        <h1 class="display-4">1,414,902</h1>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-info">
                      <div class="card-block bg-info">
                        <div class="rotate">
                        <i class="fas fa-bezier-curve fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">WikiPathways Nanopubs</h6>
                        <h1 class="display-4">26,510</h1>
                      </div>
                    </div>
                  </div>
            </div>

            <div class="row">
                    <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-warning">
                      <div class="card-block bg-warning">
                        <div class="rotate">
                         <i class="fas fa-link fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">Total IRIs</h6>
                        <!-- Total IRIs without eliminating the blank nodes -->
                        <!-- <h1 class="display-4">11,510,709</h1> -->

                        <!-- Total IRIs after eliminating blank nodes-->
                        <h1 class="display-4">9,570,950</h1>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-danger">
                      <div class="card-block bg-danger">
                        <div class="rotate">
                          <i class="fas fa-link fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">neXtProt IRIs</h6>
                        <h1 class="display-4">5,211,398</h1>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-primary">
                      <div class="card-block bg-primary">
                        <div class="rotate">
                          <i class="fas fa-link fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">DisGeNET IRIs</h6>
                        <h1 class="display-4">4,244,706</h1>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-inverse card-info">
                      <div class="card-block bg-info">
                        <div class="rotate">
                        <i class="fas fa-link fa-5x"></i>
                        </div>
                        <h6 class="text-uppercase">WikiPathways IRIs</h6>
                        <h1 class="display-4">114,846</h1>
                      </div>
                    </div>
                  </div>
            </div>
            <footer>

            </footer>
        </div>

        <script src="Scripts/jquery-3.4.1.min.js"></script>
        <script src="Scripts/popper-1.14.7.min.js"></script>
        <script src="Scripts/bootstrap-4.3.1.min.js"></script>
        <script>
    //////////////////////////////////
    //// Loader
    function hideLoader() {
      document.getElementById("loader").style.display = "none";
    }
    </script>
    </body>
</html>
