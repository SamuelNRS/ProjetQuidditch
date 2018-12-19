<doctype HTML>
<html>
	<head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style.css">
	    <meta charset="UTF-8">
	</head>
	<body>
		<?php
		include('Fonctions.php');
        ?>
        <?php 
            $gestionJoueurs= new Bouton('GestionJoueur','Gérer les Joueurs','btn btn-outline-light btn-lg','./gestionJoueurs.php'); 
            $gestionMatchs= new Bouton('GestionMatchs','Gérer les matchs','btn btn-outline-light btn-lg','./gestionMatch.php');
            $gestionStats= new Bouton('VoirStats','Accéder aux statistiques','btn btn-outline-light btn-lg','./statistiques.php');
        ?>
        <div class="container">
            <div class="card">
                <div class="card-header"> Accueil </div>
                <div class="card-body">
                    <div><?php $gestionJoueurs->__toString(); ?> </div>
                    <div><?php $gestionMatchs->__toString(); ?> </div>
                    <div><?php $gestionStats->__toString(); ?> </div>
                </div>
            </div>
        </div>
	</body>
</html>