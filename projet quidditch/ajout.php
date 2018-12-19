<!DOCTYPE HTML>
<html>
	<head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style.css">
	    <meta charset="UTF-8">
	</head>
	<body>
		<?php
        include('Fonctions.php');
        
        //on vérifie que l'utilisateur vient bien de la page de gestion des joueurs
        if(stristr($_SERVER['HTTP_REFERER'], "gestionJoueurs.php")) {
            
            //connexion au serveur
            $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');

            //récupération de la valeur des diverses variables concernant le joueur à ajouter
            if(empty($_POST['NumLicence'])||empty($_POST['Nom'])||empty($_POST['DateNaissance'])||empty($_POST['Taille'])||empty($_POST['Poids'])||empty($_POST['Prenom'])){
                echo "<p style=text-align:center> Des données entrées sont manquantes, veuillez remplir CORRECTEMENT le formulaire <br/>";
                echo "Redirection vers la page précédente dans 3s... </p> <br/>";
                header("Refresh: 3 ;URL=./gestionJoueurs.php");
                exit();
            }
            $NumLicense=$_POST['NumLicence'] ;
            $Nom=$_POST['Nom'];
            $DateNaissance=$_POST['DateNaissance'];
            $Taille=$_POST['Taille'];
            $PostePrefere=$_POST['PostePrefere'];
            $Statut=$_POST['Statut'];
            $Poids=$_POST['Poids'];
            $Prenom=$_POST['Prenom'];
            $Commentaire=$_POST['Commentaire'];
            $Photo=$_POST['Photo'];

            // verification de la validité des infos

            $infovalides=true;
            
            //Numero de Licence -> 5 chiffres
            $num_length = strlen((string)$NumLicense);
            if(($num_length)!=5){
                echo "<p style=text-align:center> La taille du numéro de licence est incorrect, le joueur n'as pas été ajouté </p> <br/>";
                $infovalides=false;
            }

            //Numero de Licence -> UNIQUEMENT des chiffres
            if((is_numeric($NumLicense))==false){
                $infovalides=false;
                echo "<p style=text-align:center> Le numéro de licence ne contient pas que des chiffres, le joueur n'as pas été ajouté </p> <br/>";
            }

            //Nom, Prenom doivent être uniquement une chaine de caracteres, pas de nombres par ex
            if(!(is_string($Nom)||is_string($Prenom))){
                echo " <p style=text-align:center> Le nom ainsi que le prénom ne peuvent être que des lettres, pas de nombres autorisés </p> <br/>";
                $infovalides=false;
            }
            
            //si toutes les infos sont valides on prépare et on exécute la requête
            if($infovalides==true){
                
                //création de l'objet Joueur à insérer dans la BDD
                $joueur = new joueur($NumLicense,$Nom,$Prenom,$DateNaissance,$Taille,$Poids,$Statut,$PostePrefere,$Photo,$Commentaire);
 
                //d'abord on compte le nombre de ligne dans la table "joueur", pour la vérification ensuite
                $GetNBLignes=$linkpdo->prepare("SELECT COUNT(*) FROM joueur;");
                $GetNBLignes->execute();
                $data = $GetNBLignes->fetch();
                $nbLignesAvantAjout= $data[0];
                
                //on crée la requête qui ajoute le joueur
                $prep= $joueur->getRequeteInsertion();
                //on la prepare 
                $req = $linkpdo->prepare($prep);

                //on l'exécute
                try{
                $req->execute();
                }
                catch(Exception $e){
                    echo "<p style=text-align:center> Une erreur à eu lieu lors de l'ajout.. veuillez réessayer $e->getMessage()<br/>";
                    echo "Redirection vers la page précédente dans 3s... </p><br/>";
                    header("Refresh: 3 ;URL=./gestionJoueurs.php");
                    exit();
                }
                //on vérifie que le joueur à bien été ajouté à la BDD, en comptant le nbr de ligne
                $GetNBLignes=$linkpdo->prepare("SELECT COUNT(*) FROM joueur;");
                $GetNBLignes->execute();
                $data = $GetNBLignes->fetch();
                $nbLignesApresAjout= $data[0];
                
                // si le nbr de ligne de la table joueur a augmenté de 1, alors l'ajout à fonctionner
                if($nbLignesApresAjout==$nbLignesAvantAjout+1){
                echo "<p style=text-align:center> Joueur ajouté à la base de données :) <br/>
                        Redirection vers la page de gestion dans 3 secondes... </p>";
                        header("Refresh: 3 ;URL=./index.php");
                        exit();
                }
                
                //sinon, on affiche une erreur
                else{
                    echo "<p style=text-align:center> Une erreur à eu lieu lors de l'ajout.. veuillez réessayer <br/>";
                    echo "Redirection vers la page précédente dans 3s... </p><br/>";
                    header("Refresh: 3 ;URL=./gestionJoueurs.php");
                    exit();
                }
            }
            else {
                echo "<p style=text-align:center> Les données entrées sont manquantes ou incorrectes, veuillez remplir CORRECTEMENT le formulaire <br/>";
                echo "Redirection vers la page précédente dans 5s... </p> <br/>";
                header("Refresh: 5 ;URL=./gestionJoueurs.php");
                exit();
            }
        }
        // si l'user ne provient pas de la page de gestion de joueurs (situation anormale) on le redirige instant sur la page de gestion de joueurs
        else{
            header("Location: ./gestionJoueurs.php");
            exit();
        }
        ?>
	</body>
</html>