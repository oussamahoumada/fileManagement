<?php
    require_once("connexion.php");
    require_once("./fichiers.php");
    require_once("./LireRecursDir.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
       .card-columns {
            @include media-breakpoint-only(lg) {
                column-count: 4;
            }
            @include media-breakpoint-only(xl) {
                column-count: 5;
            }
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 30%;
        }
    </style>
    <link rel="stylesheet" href="./ASSETS/STYLES/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    
    <link href="./ASSETS/STYLES/Bootstrap/6.0.0.min.css" rel="stylesheet" />
    <link href="./ASSETS/STYLES/Bootstrap/font.google.min.css" rel="stylesheet"/>
    <link href="./ASSETS/STYLES/Bootstrap/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="./ASSETS/SCRIPTS/mdb-ui-kit.min.js"></script>

</head>
<body>

    <?php
        set_time_limit (500);
        $path= "./rep1";

        //Apelle de la fonction explorerDir()
        echo'<div id="hierarchy">
        <a class="all" href="?all=1"> ALL </a> ';
            explorerDir($path);
        echo '</div>';
    ?>

    <div class="right">
        <a class="dropdown-toggle hidden-arrow btn btn-primary" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false"> Filtrer </a>
        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdownMenuLink">
            <li><hr class = "dropdown-divider" /></li>
            <li><a class = "dropdown-item" href="?filter=pdf"> PDF   </a></li>
            <li><a class = "dropdown-item" href="?filter=img"> IMAGE </a></li>
            <li><a class = "dropdown-item" href="?filter=txt"> TXT   </a></li>
        </ul>

        <?php

            //Filtrer
            $fltr = new fichiers();
            if(isset($_GET['all'])){
                session_unset();
            }

            if(isset($_GET['filter'])){
                $fltr->extension = $_GET['filter'];
                $_SESSION["filter"] = $_GET['filter'];
            }else{
                if(isset($_SESSION["filter"])){
                    $fltr->extension = $_SESSION["filter"];
                }
                else{
                    $fltr->extension = "";
                }
            }


            if(isset($_GET['rep'])){
                session_unset();
                $fltr->extension = "";
                $_SESSION["rep"] = $_GET['rep'];
                $fltr->dossier = $_GET['rep'];
            }
            else{
                if(isset($_SESSION["rep"])){
                    $fltr->dossier = $_SESSION["rep"];
                }
                else{
                    $fltr->dossier = "";
                }
            }
            //Filtrer fin

            $c = table_length($fltr);

            //Aficher la List des fichiers par page
            if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p'] <= tst($c)){
                $lst = getData($_GET['p'],$fltr);
            }
            else{
                $lst = getData(1,$fltr);
            }

            echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
            foreach($lst as $l){
                echo'<div class="col">
                        <div class="card">';
                        if($l->extension==".pdf"){
                            echo ' <img class="center"  src="./ASSETS/images/pdf_Image.png" alt="PDF">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $l->fichier . '</h5>
                                        <p class="card-text">' . $l->path . '</p>
                                    </div>';
                        }
                        else if($l->extension==".jpg" || $l->extension==".png" || $l->extension==".jpeg" ){
                            echo ' <img class="center" src=".'.$l->path.$l->extension.'" alt="IMAGE">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $l->fichier . '</h5>
                                        <p class="card-text">' . $l->path . '</p>
                                    </div>';
                        }
                        else if($l->extension==""){
                            echo ' <img class="center" src="./ASSETS/images/folder.jpg" alt="IMAGE">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $l->fichier . '</h5>
                                        <p class="card-text">' . $l->path . '</p>
                                    </div>';
                        }
                        else{
                            echo ' <img class="center"  src="./ASSETS/images/images.jfif" alt=TXT FILE">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $l->fichier . '</h5>
                                        <p class="card-text">' . $l->path . '</p>
                                    </div>';
                        }
                    echo'</div>
                    </div>';
            }
            echo '</div>';
            //List fichier Fin


            //Pagination
            echo ('<br>');
            echo '<div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
            ';
            for($i=1;$i<=tst($c);$i++){
                echo '<li class="page-item"><a class="page-link" href="?p='.$i.'">'.$i.'</a></li>';
            }
            echo "      </ul>
                    </nav>
                </div>";
            //Pagination Fin
        ?> 
    </div>

    <script src="./ASSETS/SCRIPTS/script.js"></script>
</body>
</html>