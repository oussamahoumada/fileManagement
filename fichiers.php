<?php
require_once("connexion.php");
class fichiers
{
    public $id;
    public $fichier;
    public $extension;
    public $dossier;
    public $taille;
    public $path;

    public function __construct($fichier = NULL, $extension = NULL, $dossier = NULL, $taille = NULL, $path = NULL)
    {
        $this->fichier = $fichier;
        $this->extension = $extension;
        $this->dossier = $dossier;
        $this->taille = $taille;
        $this->path = $path;
    }

    //Ajouter un fichier après la verification de son existence
    function Add()
    {
        if($this->check() > 0){
            return false;
        }
        try {
            $cnx = new connexion();
            $req = "INSERT INTO file (fichier,extension,dossier,taille,path) VALUES (:fichier, :extension, :dossier, :taille, :path)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':fichier' => $this->fichier,
                    ':extension' => $this->extension,
                    ':dossier' => $this->dossier,
                    ':taille' => $this->taille,
                    ':path' => $this->path
                )
            );
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return true;
    }

    //Verifier  l'existence d'un fichier
    function check()
    {
        try {
            $cnx = new connexion();
            $req = "SELECT count(*) as 'len' FROM file where (fichier = :fichier AND extension = :extension AND dossier = :dossier AND taille = :taille AND path = :path)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':fichier' => $this->fichier,
                    ':extension' => $this->extension,
                    ':dossier' => $this->dossier,
                    ':taille' => $this->taille,
                    ':path' => $this->path
                )
            );
            $result = $prep->fetch(PDO::FETCH_ASSOC)['len'];
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
}

//fonction qui retourne le nombre des page pour la pagenation
function tst($nbr)
{
    if ((($nbr / 4) * 100000) > (((int) ($nbr / 4)) * 100000)) {
        return ((int) ($nbr / 4)) + 1;
    }
    return ($nbr / 4);
}



//recuperation des element pour chaque page (4 elements par page)
function getData($nbr = 1, $fltr)
{
    $filArray = array();
    try {
        if ($fltr->dossier != "") {
            $doss = $fltr->dossier;
        }else {
            $doss = '%';
        }
        
        if ($fltr->extension != "") {
            if($fltr->extension=="pdf"){
                $ext = "pdf";
            }else if($fltr->extension=="txt"){
                $ext = "txt";
            }else if($fltr->extension=="img"){
                $ext = "jpg";
            }

        } else {
            $ext = '%';
        }

        $cnx = new connexion();
        $query = 'SELECT * FROM file WHERE  (dossier LIKE :dossier AND extension LIKE :extension) LIMIT :nbr,4';
        $prep = $cnx->prepare($query);
        $prep->bindValue(':dossier', $doss);
        $prep->bindValue(":extension", $ext);
        $prep->bindValue(':nbr', (($nbr - 1) * 4), PDO::PARAM_INT);
        $prep->execute();
        while ($ligne = $prep->fetch(PDO::FETCH_ASSOC)) {
            $fil = new fichiers($ligne['fichier'], $ligne["extension"], $ligne["dossier"], $ligne["taille"], $ligne["path"]);
            $filArray[] = $fil;
        }
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    return $filArray;
}


//returne le nombre d'element pour la pagenation
function table_length($fltr)
{
    try {
        
        if ($fltr->dossier != "") {
            $doss = $fltr->dossier;
        } else {
            $doss = '%';
        }
        
        if ($fltr->extension != "") {
            $ext = $fltr->extension;
        } else {
            $ext = '%';
        }

        $cnx = new connexion();
        $query = 'SELECT COUNT(*) as "len" FROM file WHERE dossier LIKE :dossier AND extension LIKE :extension';
        $prep = $cnx->prepare($query);
        $prep->bindValue(":dossier", $doss);
        $prep->bindValue(":extension", $ext);
        
        $prep->execute();
        $c = (int) $prep->fetch(PDO::FETCH_ASSOC)['len'];
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    return $c;
}


$extensionTable = ['pdf', 'txt', 'docx', 'jpg', 'jpeg', 'png'];