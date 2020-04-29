
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
                        <h1>Topic Mapping of Nanopublications</h1>
                        <p>This is the online application that will show the graphical view of topics distributed among the nanopublications</p>
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
    //////////////////////////////////
    //// Loader 
    function hideLoader() {
      document.getElementById("loader").style.display = "none";
    }
    </script>
     //////////////////////////////////////////////////////////////////////////////////
    </body>
</html>

