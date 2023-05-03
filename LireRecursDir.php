<?php
require_once("connexion.php");
require_once("./fichiers.php");

	//hierarchy
	function explorerDir($path)
	{
		$folder = opendir($path);
		echo '<div class="foldercontainer">';
		$split = explode("/", $path);
		$split_ext = explode(".", $path);
		echo '<span class="folder fa-folder-o" data-isexpanded="true"><a class="a_link" href="?rep='.$split[count($split)-1].'">'.$split[count($split)-1].'</a></span>';
		echo ('<span class="span_a_link"><a onclick="add_folder(this)" class="a_link" href="./AjouterFichier.php?dos='.$path.'">+</a></span>');

		$file = new fichiers($split[count($split)-1],"",$split[count($split)-2],filesize($path),$path);
		$file->Add();
		
		while($entree = readdir($folder))
		{		
			if($entree != "." && $entree != "..")
			{
				if(is_dir($path."/".$entree))
				{
					$sav_path = $path;
					$path .= "/".$entree;			
					explorerDir($path);
					$path = $sav_path;
				}
				else
				{
					$path_source = $path."/".$entree;

					$split_ext = explode(".", $path_source);
				    $split_name = explode("/", $path_source);

					$ext = "fa-file-excel-o";
					if($split_ext[count($split_ext)-1]=="pdf"){
						$ext = "fa-file-pdf-o";
					}
				    else if($split_ext[count($split_ext)-1]=="txt"){
						$ext = "fa-file-excel-o";
					}
					else if($split_ext[count($split_ext)-1]=="jpg"){
						$ext = "fa-file-code-o";
					}	
				    echo '<span class="file '.$ext.'">'.$split_name[count($split_name)-1].'</span>';

					$file = new fichiers($split_name[count($split_name)-1],$split_ext[count($split_ext)-1],$split_name[count($split_name)-2],filesize($path_source),$path_source);
					$file->Add();
				}
			}
		}
		closedir($folder);
		echo '</div>';
	}
?>