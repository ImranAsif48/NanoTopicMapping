
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
            <!-- <div id="loader"></div> -->
            <br />
            <main class="row">
               <div id="divTable" class="col-md-12">
                        <br />
                        <div class="card" style="height:auto">
                            <div class="card-header">
                                Box-and-whisker plot
                            </div>
                            <div id="plot" class="card-body h-100">
                                <iframe class="col-md-12" style="height:730px;border:0px" src="show_box_plot.html"></iframe>
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
    //////////////////////////////////
    //// Loader 
    // var start = performance.now();
    // function hideLoader() {
    //   //document.getElementById("loader").style.display = "none";
    //   //document.getElementById("map").style.display = "block";
    //   var end = performance.now();
    //   let time = millisToMinutesAndSeconds(end - start);
    //   $('#SearchTime').html(`Request time  (${time} seconds)`);
    // }
    // function millisToMinutesAndSeconds(millis) {
    //             var minutes = Math.floor(millis / 60000);
    //             var seconds = ((millis % 60000) / 1000).toFixed(0);
    //             return minutes + " minutes, " + (seconds < 10 ? '0' : '') + seconds;
    //         }
    // $('#plot').load('show_box_plot.html', function(){
    //     var end = performance.now();
    //     let time = millisToMinutesAndSeconds(end - start);
    //     $('#SearchTime').html(`Request time  (${time} seconds)`);
    //     hideLoader();
    // });
    //hideLoader();
    </script>
    </body>
</html>

