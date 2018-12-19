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
                <div class="card-header"> Gestion des joueurs</div>
                <div class="card-body">
                    <?php
                    include('Fonctions.php');

                    //connexion au serveur
                    $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');

                    //On crée le bouton home
                    $accueil=new Bouton('Accueil','Accueil','btn btn-outline-light btn-lg','./index.php');
                    $accueil->__toString();

                    //formulaire pour ajouter un joueur
                    $formulaireAjout=new form("gestionJoueurs.php","post");
                    $formulaireAjout->setButton('Ajouter','Ajouter un joueur','btn btn-primary');
                    $formulaireAjout->finalize();
                    $formulaireAjout->__toString();
                    ?>
                </div>
            </div>
            
            <?php
            if(isset($_POST['Ajouter'])){
                echo "<div class='card'>
                    <div class='card-header'> Ajout</div>
                        <div class='card-body'>
                            <form method='post' action='ajout.php'>
                                <div class='row'>
                                    <div class='form-group col' >
                                        <input type='number' class='form-control' name='NumLicence' placeholder='NumLicence'>
                                    </div>
                                    <div class='form-group col' >
                                        <input type='text' class='form-control' name='Nom' placeholder='Nom'>
                                    </div>
                                    <div class='form-group col' >
                                        <input type='text' class='form-control' name='Prenom' placeholder='Prenom'>
                                    </div>
                                    <div class='form-group col' >
                                        <textarea type='text' class='form-control' name='Commentaire' placeholder='Commentaire'></textarea>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='form-group col'>
                                        <input type='date' class='form-control' name='DateNaissance' placeholder='DateNaissance'>
                                    </div>
                                    <div class='form-group col'>
                                        <input type='number' class='form-control' name='Taille' placeholder='Taille'>
                                    </div>
                                    <div class='form-group col'>
                                        <input type='number' class='form-control' name='Poids' placeholder='Poids'>
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
                                         <SELECT  class='custom-select custom-select-lg mb-3'' label=Statut name=Statut size='5'>
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
                    </div> ";
            } ?>
            <?php
            // Affichage des joueurs

            // Préparation de la requête SELECT
            $req = $linkpdo->prepare("SELECT * FROM joueur ");

            // Exécution de la requête
            $req->execute();

            // Affichage des résultats de la requête
            $Tableau = new Tableau();
            $Tableau->TabJoueurs();
            while($data = $req->fetch()){
                $Tableau->addLigneStats($data['NumLicence']);
            }
            $Tableau->finalize();
            $Tableau->__toString();

            $req->closeCursor();
            ?>
        </div>
	</body>
</html>