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
                 
                //On crée le bouton retour
                $accueil=new Bouton('Retour','Retour','btn btn-outline-light btn-lg','./gestionMatch.php');
                $accueil->__toString();
                    
                //on récupère la clé primaire qui identifie le match à modifier
                $Date_Rencontre=$_GET['Date'];
                 
                //on récupère la renconte à modifier via objet
                $Rencontre=getRencontre($Date_Rencontre);
                    
                 echo "<div class='card'>
                        <div class='card-header'> Modification </div>
                            <div class='card-body'>
                                <form method='post' action=''>
                                 <div class='row'>
                                        <div class='form-group col' >
                                            <input type='date' class='form-control' name='Horaire' value='".$Rencontre->getDate()."'>
                                        </div>
                                        <div class='form-group col' >
                                            <input type='text' class='form-control' name='EquipeAdverse' value='".$Rencontre->getEquipeAdverse()."'>
                                        </div>
                                        <div class='form-group col' >
                                            <input type='text' class='form-control' name='LieuRencontre' value='".$Rencontre->getLieu()."'>
                                        </div>
                                    </div>
                                </div>
                            </div>";                             
                ?>
                </div>
            </div>
        </div>
    </body>
</html>