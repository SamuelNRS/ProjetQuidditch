<!DOCTYPE HTML>
<html>
    <head>
       <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style.css">
	    <meta charset="UTF-8">
    </head>
    <body>
            <div class="container">
            <div class="card">
                <div class="card-header">  Gestion des joueurs< </div>
                <div class="card-body">
                    
                <?php
                include('Fonctions.php');
                //connexion  à la bDD
                $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');
               
                //On crée le bouton home
                $accueil=new Bouton('Accueil','Accueil','btn btn-outline-light btn-lg','./index.php');
                $accueil->__toString();
                    
                //On crée le bouton retour
                $accueil=new Bouton('Retour','Retour','btn btn-outline-light btn-lg','./gestionJoueurs.php');
                $accueil->__toString();
                ?>
                </div>
            </div>
               
                <?php
                 //on vérifie que l'utilisateur vient bien de la page de gestion des joueurs
                if((stristr($_SERVER['HTTP_REFERER'], "gestionJoueurs.php"))||(stristr($_SERVER['HTTP_REFERER'], "ModificationJoueurs.php"))){

                    // on  vérifie l'état de l'utilisateur: il vient d'arriver depuis la page de gestion, ou bien il vient de valider la modification?

                    //si il vient de la page de gestion on récupère le Numéro de Licence du joueur à modifier
                    if (stristr($_SERVER['HTTP_REFERER'], "gestionJoueurs.php")){

                        // on récupère le numéro de licence (clé primaire) pour afficher le bon joueur.
                        $joueur=getJoueur($_GET['NumLicence']);
                        $NumLicence=$joueur->getLicence();

                        //on prépare la requête permmettant d'extraire les données du joueurs de la bDD

                        $linkpdo->prepare("SELECT COUNT(*) FROM joueur;");

                        echo "  <div class='card'>
                                    <div class='card-header'> Modification </div>
                                    <div class='card-body'>
                                        <form method='post' action='ModificationJoueurs.php'>
                                          <div class='row'>
                                                <div class='form-group col' >
                                                    <input type='number' class='form-control' name='NumLicence' value='$NumLicence' >
                                                </div>
                                                <div class='form-group col' >
                                                    <input type='text' class='form-control' name='Nom' value=' ". $joueur->getNom()." '>                                   </div>
                                                <div class='form-group col' >
                                                    <input type='text' class='form-control' name='Prenom' value='".$joueur->getPrenom()." '>  
                                                </div>
                                                <div class='form-group col' >
                                                    <textarea type='text' class='form-control' name='Commentaire' value='".$joueur->getCommentaire()."'>
                                                    </textarea>
                                                </div>
                                            </div>

                                            <div class='row'>
                                                <div class='form-group col'>
                                                    <input type='date' class='form-control' name='DateNaissance' value='".$joueur->getDateNaissance()."'>  
                                                </div>
                                                <div class='form-group col'>
                                                    <input type='number' class='form-control' name='Taille' value='".$joueur->getTaille()."'>  
                                                </div>
                                                <div class='form-group col'>
                                                    <input type='number' class='form-control' name='Poids' value='".$joueur->getPoids()."'>  
                                                </div>
                                            </div>

                                            <div class='row'>
                                                <div class='form-group col'>
                                                      <input type='file'  name='Photo' placeholder='Photo'>
                                                </div>
                                            </div>

                                            <div class='row'>
                                                <div class='form-group col'>
                                                    <SELECT class='custom-select custom-select-lg mb-3' label='Poste Prefere' name=PostePrefere size='5'>
                                                        <OPTION selected>Aucun </OPTION>
                                                        <OPTION >Gardien </OPTION>
                                                        <OPTION>Batteur </OPTION>
                                                        <OPTION>Poursuiveur </OPTION>
                                                        <OPTION >Attrapeur </OPTION>
                                                    </SELECT> 
                                                </div>

                                                <div class='form-group col'>
                                                     <SELECT  class='custom-select custom-select-lg mb-3' label=Statut name=Statut size='5'>
                                                        <OPTION selected >Actif </OPTION>
                                                        <OPTION >Blessé </OPTION>
                                                        <OPTION>Suspendu </OPTION>
                                                        <OPTION>Absent </OPTION>
                                                        </SELECT> 
                                                </div>
                                            </div>	

                                            <div class='row'>
                                                <div class='form-group col'>
                                                    <input type='submit' class='btn btn-primary'>
                                                    <input type='reset' class='btn btn-danger'>
                                                </div>
                                            </div>    
                                        </form>
                                    </div>
                                </div>  ";
                    }

                    // sinon l'utilisateur vient de cliquer sur le bouton valider pour enregistrer la modification donc Traitement et hop!
                    else{
                        //on récup le joueur à modifier
                        $joueur=getJoueur($_POST['NumLicence']);
                        $NumLicence=$joueur->getLicence();

                        //on prépare la requête de modification
                        $req=$linkpdo->prepare("UPDATE `joueur` SET `Nom`=:Nom,`Prenom`=:Prenom,`DateNaissance`=:DateNaissance,`Taille`=:Taille,`Poids`=:Poids,`Statut`=:Statut,`PostePrefere`=:PostePrefere,`Photo`=:Photo WHERE NumLicence=$NumLicence");

                        //on exécute la requête de modification
                        try{
                            $req->execute(array(':Nom' => $_POST['Nom'], 
                                               ':Prenom'=>$_POST['Prenom'],
                                               ':DateNaissance' =>$_POST['DateNaissance'],
                                               ':Taille'=>$_POST['Taille'],
                                               ':Poids'=> $_POST['Poids'],
                                               ':Statut' =>$_POST['Statut'],
                                               ':PostePrefere' =>$_POST['PostePrefere'],
                                               ':Photo'=>$_POST['Photo']));
                            echo "<p style='text-align:center;'> Modification effectuée! </p> <br/>";
                            echo "<p style=text-align:center> Redirection vers la page de gestion des joueurs dans 3 secondes... </p>";
                            header("Refresh: 3 ;URL=./gestionJoueurs.php");
                            exit();
                        }
                            catch (Exception $e){
                                echo "Erreur sql: $e";
                            }
                        }
                    }
                else{
                     // si l'user ne provient pas de la page de gestion de joueurs (situation anormale) on le redirige instant sur la page d'accueil
                    header("Location:./index.php");
                }
                ?>
            </div>
    </body>
</html>
