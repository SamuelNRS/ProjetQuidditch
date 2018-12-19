<doctype HTML>
<html>
	<head>
       <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	</head>
	<body>
		<?php
		include('Fonctions.php');
		
		//connexion au serveur
		$linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root','');
		
        if(isset($_POST['ValiderInscription'])){		
		 $horaire=$_POST['Horaire'] ;
                $eA=$_POST['EquipeAdverse'];
                $lieu=$_POST['LieuRencontre']; 
                $C1=verifierPasLesMemeJoueur(array($_POST['Attrapeur'],$_POST['Batteur1'],$_POST['Batteur2'],$_POST['Poursuiveur1'],$_POST['Poursuiveur2'],$_POST['Poursuiveur3'],$_POST['Gardien'])) ;
                if(!$C1){

                    //création de l'objet Rencontre à insérer dans la BDD
                    $match = new rencontre($horaire,$eA,$lieu,0,0);
                    $prep= $match->getRequeteRencontre();

                    //prepare requete
                    $req = $linkpdo->prepare($prep);

                    //exécute la requête d'ajout
                    $req->execute();
                    //verifie qu'un joueur n'ai pas plusieur rôle


                    // Creation du tableau des joueurs selectionnes
                    $tab =  array(explode(" ",$_POST['Attrapeur']),explode(" ",$_POST['Batteur1']),explode(" ", $_POST['Batteur2']),explode(" ",$_POST['Poursuiveur1']),explode(" ", $_POST['Poursuiveur2']),explode(" ",$_POST['Poursuiveur3']),explode(" ",$_POST['Gardien'])); 

                    // Creation du tableau des roles
                    $tabR = array('Attrapeur','Batteur','Batteur','Poursuiveur','Poursuiveur','Poursuiveur','Gardien') ;

                    $i=0;
                    while($i<7){
                        $res = $linkpdo->query("SELECT * FROM joueur WHERE Nom='".$tab[$i][0]."' AND Prenom ='".$tab[$i][1]."';");
                        while($joueur=$res->fetch()){
                            $req = $linkpdo->prepare( "INSERT INTO partciper(NumLicence, Horaire, Poste, Titulaire, Remplaçant) VALUES ('".$joueur[0]."', '".$_POST['Horaire']."', '".$tabR[$i]."', '1', '0');") ;
                            $req->execute();
                        }
                        $i=$i+1;
                    }
                } else { //Dans le cas où un joueur a plusieur role
                    echo '<script>alert("Un joueur ne peut pas avoir plusieurs roles !");</script>';
                    echo "Redirection vers la page précédente dans 5s... </p> <br/>";
                    header("Refresh: 5 ;URL=./gestionMatch.php");
                    exit();
                    
                }
            }


    ?>
	</body>
</html>
