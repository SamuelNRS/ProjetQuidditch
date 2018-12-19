<doctype HTML>
<html>
	<head>
       <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	</head>
	<body>
        <div class="container">
            <div class="card">
                <div class="card-header"> Gestion des matchs</div>
                <div class="card-body">
                <?php
                include('Fonctions.php');

                //connexion au serveur
                $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root','');
                    
                //On crée le bouton home
                $accueil=new Bouton('Accueil','Accueil','btn btn-outline-light btn-lg','./index.php');
                $accueil->__toString();  
                
                // Bouton pour ajouter un joueur
                //formulaire pour ajouter un joueur
                $formulaireAjout=new form("","post");
                $formulaireAjout->setButton('CreerMatch','Ajouter un Match','btn btn-primary');
                $formulaireAjout->finalize();
                $formulaireAjout->__toString();

                //formulaire pour ajouter un joueur
                if(isset($_POST['CreerMatch'])){
                     echo "<div class='card'>
                            <div class='card-header'> Ajout</div>
                                <div class='card-body'>
                                    <form method='post' action='ajoutMatch.php'>
                                     <div class='row'>
                                            <div class='form-group col' >
                                                <input type='date' class='form-control' name='Horaire' placeholder='Date'>
                                            </div>
                                            <div class='form-group col' >
                                                <input type='text' class='form-control' name='EquipeAdverse' placeholder='Equipe Adverse'>
                                            </div>
                                            <div class='form-group col' >
                                                  <SELECT class='custom-select custom-select mb-3' name='LieuRencontre' >
                                                        <option> Domicile </option>
                                                        <option> Extérieur </option>
                                                  </SELECT>;
                                            </div>
                                        </div>";                             
                    $formulaireInscription=new Form('ajoutMatch.php','post');     
                    $formulaireInscription->setRoleJoueur('Attrapeur','Attrapeur');
                    $formulaireInscription->setRoleJoueur('Batteur1','Batteur');
                    $formulaireInscription->setRoleJoueur('Batteur2','Batteur');
                    $formulaireInscription->setRoleJoueur('Poursuiveur1','Poursuiveur');
                    $formulaireInscription->setRoleJoueur('Poursuiveur2','Poursuiveur');
                    $formulaireInscription->setRoleJoueur('Poursuiveur3','Poursuiveur');
                    $formulaireInscription->setRoleJoueur('Gardien','Gardien');
                    $formulaireInscription->setButton('ValiderInscription','Valider','btn btn-primary');
                    $formulaireInscription->setButtonReset('Reset','Reinitialiser','btn btn-danger ');
                    $formulaireInscription->finalize();
                    $formulaireInscription->__toString();

                }	
                    ?>
                    
                </div>
            </div>
        
        
                <?php
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
                        //verifie qu'un joueur n'est pas plusieur rôle



                        // Creation du tableau des joueurs selectionnes
                        $tab =  array(explode(" ",$_POST['A']),explode(" ",$_POST['B1']),explode(" ", $_POST['B2']),explode(" ",$_POST['P1']),explode(" ", $_POST['P2']),explode(" ",$_POST['P3']),explode(" ",$_POST['G'])); 


                        // Creation du tableau des roles
                        $tabR = array('Attrapeur','Batteur','Batteur','Poursuiveur','Poursuiveur','Poursuiveur','Gardien') ;



                        $i=0;
                        while($i<7){
                            $res = $linkpdo->query("SELECT * FROM joueur WHERE Nom='".$tab[$i][0]."' AND Prenom ='".$tab[$i][1]."';");
                            //$req = $linkpdo->prepare( "INSERT INTO 'partciper'('NumLicence', 'Horaire', 'Poste', 'Titulaire', 'Remplaçant') VALUES (".$_POST['Horaire'].", ".$joueur.", ".$tabR[$i].", 1, 0)") ;
                            while($joueur=$res->fetch()){
                                $req = $linkpdo->prepare( "INSERT INTO partciper(NumLicence, Horaire, Poste, Titulaire, Remplaçant) VALUES ('".$joueur[0]."', '".$_POST['Horaire']."', '".$tabR[$i]."', '1', '0');") ;
                                $req->execute();
                                echo 'Ligne du joueur '.$tab[$i][1].' ou '.$joueur[0].' ajoutée !' ;
                            }
                            $i=$i+1;
                        }
                    } else { //Dans le cas où un joueur a plusieur role
                        echo '<script>alert("Un joueur ne peut pas avoir plusieurs roles !");</script>';
                    }
                }


                
                // Affichage des matchs
                
                // Préparation de la requête SELECT
                $req = $linkpdo->prepare("SELECT * FROM rencontre WHERE Horaire>NOW() ");

                // Exécution de la requête
                $req->execute();

                // Affichage des résultats de la requête
                $Tableau = new Tableau();
                $Tableau->TabMatchs();

                //A TCHEKER
                while($data = $req->fetch()){
                    $Tableau->addLigne($data[0],$data[1],$data[2],0,0);
                }
                $Tableau->finalize();
                $Tableau->__toString();
                ?>
        
        </div>
	</body>
</html>