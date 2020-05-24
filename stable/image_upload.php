<?php
include 'include.php';

$tmp = $_FILES['image']['tmp_name'];
$type = $_FILES['image']['type'];
$size = $_FILES['image']['size'];
$max_size = 5242880; //dimensione massima in byte consentita
if ($_POST['logo_si'] == "yes"){
	$folder = "../accounts/".$endofurl."/img/";
} else {
	$folder = "../accounts/".$endofurl."/images/"; //cartella di destinazione dell'immagine
}

function check_ext($tipo) {
 
    switch($tipo) {
        case "image/png":
            return true;
            break;
        case "image/jpg":
            return true;
            break;
        case "image/jpeg":
            return true;
            break;
        case "image/gif":
            return true;
            break;
        default:
            return false;
            break;
    }
 
}
 
function get_ext($tipo) {
 
    switch($tipo) {
        case "image/png":
            return ".png";
            break;
        case "image/jpg":
            return ".jpg";
            break;
        case "image/jpeg":
            return ".jpg";
            break;
        case "image/gif":
            return ".gif";
            break;
        default:
            return false;
            break;
    }
 
}
 
function get_error($tmp, $type, $size, $max_size) {
 
    if(!is_uploaded_file($tmp)) {
        echo "File caricato in modo non corretto<br /><br />";
    }
    if(!check_ext($type)) {
        echo "Estensione del file non ammesso<br />
        Accertati che il file sia <i>jpg, jpeg, png, gif</i><br />
        ";
    }
    if($size > $max_size) {
        echo "Dimensione del file troppo grande<br />
        Accertati che il file non sia superiore ai 5 MB<br />
        ";
    }
    echo '<a href="javascript:open(location,\'_self\').close()">CHIUDI</a>';
 
}
 
if(is_uploaded_file($tmp) && check_ext($type) && $size <= $max_size) {
 
    $ext = get_ext($type); //estensione dell'immagine
    $name = time().rand(0,999); //timestamp attuale + un numero random compreso tra 0 e 999
    $name = $name.$ext; //aggiungo al nome appena creato l'estensione
    //esempio risultato finale: folder/timestamp657.gif
    //
    
    //aggiorno l'articolo in mysql
    $id_art = $_POST['id_art'];
    if ($id_art != ""){
	    $sql = "UPDATE mercusa_articoli SET url_img='images/$name' WHERE id=$id_art";
	    if (!mysqli_query($con,$sql)) {
			die('Errore: funzione di caricamento immagini' . mysqli_error($con));
		}
		mysqli_close($con);
    } elseif ($_POST['logo_si'] == "yes"){
    	    $sql = "UPDATE mercusa_settings SET contenuto='$name' WHERE nome='url_imgLogo' ";
	    if (!mysqli_query($con,$sql)) {
			die('Errore: funzione di caricamento immagini' . mysqli_error($con));
		}
		mysqli_close($con);
    }
	$name1 = $folder.$name; //aggiungo il folder di destinazione
    if(move_uploaded_file($tmp,$name1)) {
    	    $url_img = $imagesURL."images/".$name;
    	    thumbize ($name, $url_img, 135, 180);
        echo 'Immagine caricata con successo: '.$name.' <br />';
        echo '';
    } else {
        echo "Non &egrave; stato possibile caricare l'immagine $endofurl<br />";
        echo '<a href="javascript:open(location,\'_self\').close()">CHIUDI</a>';
    }
} else {
 
    get_error($tmp, $type, $size, $max_size);
 
}
?>
