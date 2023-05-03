<?php
    require_once('./fichiers.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./ASSETS/STYLES/Bootstrap/BootStrap.css" rel="stylesheet" />
    <link href="./ASSETS/STYLES/style.css" rel="stylesheet" />
   
    <title>Ajouter</title>
</head>
<body>
    <?php

        //Ajouter dossier
        if(isset($_GET['new_rep'])){
            $_SESSION['nd'] = $_GET['new_rep'];
            $_SESSION['dc'] = $_GET['dos'].'/'.$_GET['new_rep'];
            mkdir($_GET['dos'].'/'.$_GET['new_rep']);
        }
        //Ajouter dossier fin

        //Ajouter fichier 
        if(isset($_FILES['file'])){
            $total_count = count($_FILES['file']['name']);
            for ($i = 0; $i < $total_count; $i++) {
                $split = explode('/',$_POST['dossier']);
                $dossier = $split[count($split) - 1];
                if(isset($_SESSION['nd'])){
                    $dossier = $_SESSION['nd'];
                }
                $file = $_FILES['file']['name'][$i];
                $extension = strtolower(pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION));
                $size = $_FILES['file']['size'][$i];
                $path = $dossier .'/'. $_FILES['file']['name'][$i];
                if(isset($_SESSION['dc'])){
                    $path = $_SESSION['dc'] .'/'. $_FILES['file']['name'][$i];
                }

                if (in_array($extension,$extensionTable) && $size < 500000) {
                    $f = new fichiers($file, $extension, $dossier, $size, $path);
                    if($f->Add()){
                        move_uploaded_file($_FILES['file']['tmp_name'][$i], $path);
                    }
                    else{
                        echo '<script>alert("EXIST DEJA");</script>';
                    }
                } else {
                    echo "<script>alert('Just les fichier de type {jpeg, jpg, png, pdf, docx} sont autorise')</script>";
                }
            }
        session_unset();
        header('Location: pagenation.php');
        }
        //Ajouter fichier Fin
    ?>
    <div class="body">
        <form class="login-form" action="AjouterFichier.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formFile" class="form-label"></label>
                <input class="form-control" multiple="multiple" type="file" id="formFile" name="file[]" required>
                <input type="hidden" name="dossier" value="<?php if(isset($_GET["dos"])){echo $_GET["dos"];} else{echo"./";}?>">
            </div>
            <input id="btn" class="btn btn-outline-primary" type="submit">
        </form>
    </div>
</body>
</html>