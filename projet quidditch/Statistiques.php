<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	    <meta charset="UTF-8">
    </head>
    <body>
         <div class="container">
            <div class="card">
                <div class="card-header"> Statistiques </div>
                    <div class="card-body">

                        <?php
                        include('Fonctions.php');

                        //connexion au serveur
                        $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');


                        //On crée le bouton home
                        $accueil=new Bouton('Accueil','Accueil','btn btn-outline-light btn-lg','./index.php');
                        $accueil->__toString();


                        //On récupères les différentes statistiques à afficher:

                        ?>

                            <div class="card">
                                <div class="card-header">
                                    Nombre de matchs
                                </div>

                                <div class="card-body">
                                    <?php
                                    //Nombre de matchs
                                    $NbMatchs= ($linkpdo->query("SELECT COUNT(*) FROM `rencontre`;"))->fetch();
                                    echo $NbMatchs[0];?>
                                </div>
                            </div>

                             <div class="card">
                                <div class="card-header">
                                    Nombre de victoires
                                </div>

                                <div class="card-body">  
                                     <?php
                                    //Nombre de victoires
                                    $NbMatchs= ($linkpdo->query("SELECT COUNT(*) FROM `rencontre` WHERE NbNosPoints>NbPointsAdversaire;"))->fetch();
                                    echo $NbMatchs[0];?>
                                 </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    Taux de victoire
                                </div>
                                <div class="card-body">
                                    <?php
                                    //Taux de victoire
                                    $NbVictoire= ($linkpdo->query("SELECT COUNT(*) FROM `rencontre` WHERE NbNosPoints>NbPointsAdversaire;"))->fetch();
                                    if(($NbVictoire[0])==0) {
                                        echo '0';
                                        exit();
                                    }
                                    $NbVictoire[0]= ($NbVictoire[0]/$NbMatchs[0])*100;
                                    echo $NbVictoire[0] ; ?>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </body>
</html>