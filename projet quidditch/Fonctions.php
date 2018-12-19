<?php

//MISE EN PLACE DES DIVERSES CLASSES

//fonction globales
function getJoueurAvecNom($Nom){
     //connexion  à la bDD
    $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');

    //on récup les stats du joueur
    $joueur= ($linkpdo->query("SELECT * FROM `joueur` WHERE `Nom` = $Nom"))->fetch();

    if($joueur[8]==''){
        $joueur[8]="Pas de photo disponible";
    }
}
function getJoueur($NumLicence){
    //connexion  à la bDD
    $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');

    //on récup les stats du joueur
    $joueur= ($linkpdo->query("SELECT * FROM `joueur` WHERE `NumLicence` = $NumLicence"))->fetch();

    if($joueur[8]==''){
        $joueur[8]="Pas de photo disponible";
    }

    //on recrée le joueur avec les stats extraites de la bdd
    $res=new Joueur($joueur[0],$joueur[1],$joueur[2],$joueur[3],$joueur[4],$joueur[5],$joueur[6],$joueur[7],$joueur[8],$joueur[9]);

    /*  Valeurs récupérées :
        $joueur[0] ->NumLicence
        $joueur[1] ->Nom
        $joueur[2] ->Prénom
        $joueur[3] ->Date de naissance
        $joueur[4] ->Taille
        $joueur[5] ->Poids
        $joueur[6] ->Statut
        $joueur[7] ->Poste Prefere
        $joueur[8] ->Photo
        $joueur[9] ->Commentaires
    */
    return $res;          
}

function getRencontre($Date){
    $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');
    $req=($linkpdo->query("SELECT * FROM `rencontre` WHERE `Horaire`='$Date'"))->fetch();
    $rencontre= new Rencontre($req[0],$req[1],$req[2],$req[3],$req[4]);
    return $rencontre;
}

function getParticiper($Date){
    $liste=array();
    $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');
    $req=($linkpdo->query("SELECT 'NumLicence' FROM `participer` WHERE `Horaire`='$Date'"));
    $i=0;
    while ($row = mysqli_fetch_array($req, MYSQL_NUM)) {
        $liste[$i]=getJoueur($row[0]);
     }    
    return $liste;
}

function getNbJValide($bdd){
    $req = $bdd->prepare("SELECT COUNT(*) as res
            FROM joueur
            WHERE Statut='Actif';") ;
    $req->execute();
    $s = $req->fetch();
    $req->closeCursor();
    return $s['res'] ;
}

function verifierPasLesMemeJoueur($tab){
    $i=0;
    $boo=false;
    while($i<sizeof($tab)){
        $j=0;
        while($j<sizeof($tab)){
            if($i!=$j){
                if($tab[$i]==$tab[$j]){
                    $boo=true;
                }
            }
        $j+=1;
        }
    $i+=1;
    }
    return $boo;
}


// -------------------------------------------CLASSE FORMULAIRE -----------------------------------------//
class form {

    private $form;

    //constructeur
    public function __construct($action,$methode){
        $this->form=array();
        $base="<form action='$action' method='$methode'>
                    <fieldset>";
        array_push($this->form,$base);
    }

    //termine le formulaire par les balises adéquates
    public function finalize(){
        $fin="</fieldset>
                    </form>";
        array_push($this->form,$fin);
    }

    //ajoute un input texte au formulaire
    public function setText($name, $label,$classe){
        $text="<br/>
                    <div class='$classe' >
                        <input type=text name=$name placeholder=$label> 
                    </div>
                </div>";                   
        array_push($this->form,$text);
    }

    //ajoute un input hidden disabled pour faire passer des variables
    public function setTextHidden($name,$value){
        $text="<br/>
                <div class='form-group' >
                <input hidden type='text' name='$name' value='$value'> <br/> </div>" ;
        array_push($this->form,$text);
    }

     //ajoute un input  disabled pour afficher des variables noon modifiables
    public function setTextDisabled($name,$value){
        $text="<br/>
                <input disabled type='text' name='$name' value='$value'> <br/>" ;
        array_push($this->form,$text);
    }

    //ajoute un input nombre au formulaire
    public function setNumber($name, $label,$classe){
        $text="<br/><div class='form-group' >
                <input type='number' name='$name' placeholder='$label'> <br/> </div>";
        array_push($this->form,$text);
    }

    //ajoute un input date au formulaire
    public function setDate($name,$classe){
        $text="<br/><div class='form-group' >
                <input type='date' name=$name> <br/> </div>";
        array_push($this->form,$text);
    }

    //ajoute une liste déroulante pour les postes préférés
    public function setListeDeroulantePostes($name,$label,$defaultValue,$classe){
         switch ($defaultValue){
            case 'Gardien':
                 $text="<br/><div class='$classe' >
                <SELECT label=$label name=$name size='5'>
                <OPTION selected= 'selected' >Gardien </OPTION>
                <OPTION>Batteur </OPTION>
                <OPTION>Poursuiveur </OPTION>
                <OPTION>Attrapeur </OPTION>
                <OPTION>Aucun </OPTION>
                </SELECT> </div>";
                 break;
            case 'Batteur':
                 $text="<br/> <div class='$classe' >
                <SELECT label=$label name=$name size='5'>
                <OPTION>Gardien </OPTION>
                <OPTION selected='selected' >Batteur </OPTION>
                <OPTION>Poursuiveur </OPTION>
                <OPTION>Attrapeur </OPTION>
                <OPTION>Aucun </OPTION>
                </SELECT> </div>";
                 break;
            case 'Poursuiveur':
                 $text="<br/><div class='$classe' >
                <SELECT label=$label name=$name size='5'>
                <OPTION >Gardien </OPTION>
                <OPTION>Batteur </OPTION>
                <OPTION selected='selected' >Poursuiveur </OPTION>
                <OPTION>Attrapeur </OPTION>
                <OPTION>Aucun </OPTION>
                </SELECT> </div>";
                 break;

            case 'Attrapeur':
                 $text="<br/> <div class='$classe' >
                <SELECT label=$label name=$name size='5'>
                <OPTION >Gardien </OPTION>
                <OPTION>Batteur </OPTION>
                <OPTION>Poursuiveur </OPTION>
                <OPTION selected='selected'>Attrapeur </OPTION>
                <OPTION>Aucun </OPTION>
                </SELECT> </div> ";
                 break;
             default:
                 $text="<br/> <div class='$classe' >
                <SELECT class='form-group' label=$label name=$name size='5'>
                <OPTION >Gardien </OPTION>
                <OPTION>Batteur </OPTION>
                <OPTION>Poursuiveur </OPTION>
                <OPTION >Attrapeur </OPTION>
                <OPTION selected>Aucun </OPTION>
                </SELECT> </div> ";
         }
        array_push($this->form,$text);   
    }

    //ajoute une liste déroulante pour le statut du joueur
    public function setListeDeroulanteStatut($name,$label,$defaultValue,$classe){
        switch ($defaultValue){
            case 'Actif':
                 $text=
                "<br/><div class='$classe' >
                <SELECT label=$label name=$name size='4'>
                <OPTION selected>Actif </OPTION>
                <OPTION>Blessé </OPTION>
                <OPTION>Suspendu </OPTION>
                <OPTION>Absent </OPTION>
                </SELECT> </div>";
                break;
            case 'Absent':
                $text=
                "<br/><div class='$classe' >
                <SELECT label=$label name=$name size='4'>
                <OPTION >Actif </OPTION>
                <OPTION >Blessé </OPTION>
                <OPTION>Suspendu </OPTION>
                <OPTION selected>Absent </OPTION>
                </SELECT> </div>";
                break;
            case 'Suspendu':
                $text=
                "<br/><div class='$classe' >
                <SELECT label=$label name=$name size='4'>
                <OPTION >Actif </OPTION>
                <OPTION >Blessé </OPTION>
                <OPTION selected>Suspendu </OPTION>
                <OPTION >Absent </OPTION>
                </SELECT> </div> ";
                break;
            case "Blessé":
                $text=
                "<br/><div class='$classe' >
                <SELECT label=$label name=$name size='4'>
                <OPTION >Actif </OPTION>
                <OPTION selected >Blessé </OPTION>
                <OPTION>Suspendu </OPTION>
                <OPTION>Absent </OPTION>
                </SELECT>  </div>";
                break;
            default:
                $text="<br/><div class='$classe' >
                <SELECT label=$label name=$name size='4'>
                <OPTION selected >Actif </OPTION>
                <OPTION >Blessé </OPTION>
                <OPTION>Suspendu </OPTION>
                <OPTION>Absent </OPTION>
                </SELECT> </div>";

        }
        array_push($this->form,$text);   
    }

    //ajoute un bouton au formulaire
    public function setButton($name, $label,$classe){
       $text="<br/>
                <input type='submit' class='$classe'  name='$name' value='$label' >
              <br/>"; 
        array_push($this->form,$text);
    }

    //ajoute un bouton reset
    public function setButtonReset($name, $label,$classe){
       $text="<br/>
                <input type='reset' class='$classe'  name='$name' value='$label' >
              <br/>"; 
        array_push($this->form,$text);
    }
    
    //ajoute un upload au formulaire
    public function setUpload($name,$label,$classe){
        $text="<br/>
                <input class='$classe' type='file' name='$name' label='$label'> <br/> ";
        array_push($this->form,$text);
    }
    
    function getJValide($bdd){
        $req = $bdd->prepare("SELECT * FROM joueur WHERE Statut='Actif';") ;
        $req->execute();
        $i=0;
        while ($s = $req->fetch()){
            $jretour[$i]=$s[1]." ".$s[2];
            $i+=1;
        }
        $req->closeCursor();
        return $jretour ;
    }
    
    public function setRoleJoueur($nom, $poste){
        $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');
        $joueur=$this->getJValide($linkpdo) ;
        $taille=getNbJValide($linkpdo);
        $text=" <br/>$poste
            <SELECT class='custom-select custom-select-sm mb-3' label=$nom name=$nom >" ;
        $i = 0 ;
        while($i<$taille){
            $text.='<br/><OPTION>'.$joueur[$i];
            $i+=1;
        }
        $text.='</SELECT><br/>';
        array_push($this->form,$text);
    }

    //affiche le formulaire
    public function __toString(){
        echo implode("" ,$this->form);
    }
}




// -------------------------------------------CLASSE JOUEUR -----------------------------------------//
class joueur {

    private $NumLicence;
    private $Nom;
    private $Prenom;
    private $DateNaissance;
    private $Taille;
    private $PostePrefere;
    private $Statut;
    private $Poids;
    private $Photo;
    private $Note;
    private $Commentaire;

    //constructeur
    public function __construct($NumLicence,$Nom,$Prenom,$DateNaissance,$Taille,$Poids,$Statut,$PostePrefere,$Photo,$Commentaire){ 
        $this->NumLicence=$NumLicence;
        $this->Nom=$Nom;
        $this->Prenom=$Prenom;
        $this->DateNaissance=$DateNaissance;
        $this->Taille=$Taille;
        $this->PostePrefere=$PostePrefere;
        $this->Statut=$Statut;
        $this->Poids=$Poids;
        $this->Photo=$Photo;
        $this->Commentaire=$Commentaire;
    }

    //retourne la requete d'insertion du joueur dans la bDD
    public function getRequeteInsertion(){
        return "INSERT INTO joueur(NumLicence,Nom,Prenom,DateNaissance,Taille,Poids,Statut,PostePrefere,Photo,Commentaire) VALUES ('".$this->getLicence(). "','". $this->getNom(). "','".$this->getPrenom(). "','".$this->getDateNaissance(). "','".$this->getTaille(). "','".$this->getPoids(). "','".$this->getStatut(). "','". $this->getPostePrefere(). "','".$this->getPhoto()."','".$this->getCommentaire()."');" ;
    }

    //modifie la note du joueur
    public function setNote($note){
        $this->Note=$note;
    }

    //modifie la note du joueur
    public function setCommentaire($Commentaire){
        $this->Commentaire=$Commentaire;
    }

    //retourne le commentaire du joueur
    public function getCommentaire(){
         return $this->Commentaire;    
    }

    //retourne le Nom du joueur
    public function getNom(){
        return $this->Nom;      
    }

    //retourne le Prenom du joueur
    public function getPrenom(){
       return $this->Prenom;
    }

    //retourne la Date de Naissance du joueur
    public function getDateNaissance(){
        return $this->DateNaissance;
    }

    //retourne la Taille du joueur
    public function getTaille(){
        return $this->Taille;
    }

    //retourne le PostePrefere du joueur
    public function getPostePrefere(){
        return $this->PostePrefere;
    }

    //retourne le Statut du joueur
    public function getStatut(){
        return $this->Statut;
    }

    //retourne le Poids du joueur
    public function getPoids(){
        return $this->Poids;
    }

    //retourne la Photo du joueur
    public function getPhoto(){
        return $this->Photo;
    }

    //retourne le NumLicence du joueur
    public function getLicence(){
        return $this->NumLicence;
    }

    //retourne la note du joueur
    public function getNote(){
        return $this->Note;
    } 

}
	

// -------------------------------------------CLASSE BOUTON -----------------------------------------//
class Bouton{

    private $texte;

    // création de la fonction Creer bouton
    public function __construct($nom,$label,$classe,$cible){
        if($classe==NULL ){
                $classe='';
            }
        $this->texte="<button type='button' class='$classe'><a name=$nom href=$cible>$label</a> </button> ";
    }

    //pour afficher le bouton de façon générale
    public function __toString(){
        echo $this->texte;
    }

    //pour l'affichage dans le tableau des joueurs
    public function __toString2(){
        return $this->texte;
    }
}


// -------------------------------------------CLASSE TABLEAU -----------------------------------------//
class Tableau {

    private $contenu;

    public function __construct(){
        $this->contenu=array();
    }
    
    public function TabJoueurs(){
        $debut="<div class='card'>
                    <div class='card-header'> Liste </div>
                        <table class='table table-hover table-sm'>
                                <thead >
                                    <th scope='col'> # </th>
                                    <th scope='col'> Nom </th>
                                    <th scope='col'> Prenom </th>
                                    <th scope='col' style='width:200px'> Date de naissance </th>
                                    <th scope='col'> Taille </th>
                                    <th scope='col'> Poids </th>
                                    <th scope='col'> Note moyenne </th> 
                                    <th scope='col'> Statut </th> 
                                    <th scope='col'> Poste préféré </th>
                                    <th scope='col'> Photo </th>
                                    <th scope='col'> Commentaires </th>          
                                    <th scope='col'> Lien </th> 
                                </thead> 
                ";
        array_push($this->contenu,$debut);
    }

    public function TabMatchs(){
        $this->contenu=array();
        $debut="<div class='card'>
                    <div class='card-header'> Liste </div>
                        <table class='table table-hover table-sm'>
                            <thead >
                                <th scope='col'> Date </th>
                                <th scope='col'> EquipeAdverse </th>
                                <th scope='col'> Lieu </th>
                                <th scope='col'> Nb points Adverse</th>
                                <th scope='col'> Nb nos points</th> 
                                <th scope='col'> </th> 
                            </thead> 
                ";
        array_push($this->contenu,$debut);
    }

    
    public function addLigne($Date,$EquipeAdverse,$Lieu,$NbPA,$NbPN){
        $lien = new Bouton('Modifier','Modifier','btn btn-outline-light',"./ModificationMatch.php?Date=$Date");
        $ligne= "<tbody>
                    <tr>
                        <th scope='row'> $Date </th>
                        <td> $EquipeAdverse </td>
                        <td> $Lieu </td>
                        <td> $NbPA </td>
                        <td> $NbPN </td>
                        <td>" .$lien->__toString2()." </td> 
                    </tr>
                </tbody> ";
        array_push($this->contenu,$ligne);
    }
    
    //ligne affichée dans le tableau de gestions avec bouton modifier 
    public function addLigneStats($NumLicence){   
        //on se co à la bDD
        $linkpdo= new PDO('mysql:host=localhost;dbname=gestionquidditch;charset=utf8','root');

        //on crée le bouton pour modifier le joueur
        $lien = new Bouton('Modifier','Modifier','btn btn-outline-light',"./ModificationJoueurs.php?NumLicence=$NumLicence");

        // On extrait toutes les données nécessaires de la bDD à partir de la clé primaire (NumLicence)
        $joueur=getJoueur($NumLicence);
        $req = $linkpdo->prepare("SELECT * FROM joueur ");

        // Exécution de la requête
        $req->execute();
        $ligne= "<tbody>
                    <tr >
                        <th scope='row' > ".$joueur->getLicence()." </th>
                        <td > ".$joueur->getNom()."</td>
                        <td > ".$joueur->getPrenom()." </td> 
                        <td style=width:200px'> ".$joueur->getDateNaissance()." </td>
                        <td > ".$joueur->getTaille()." </td>
                        <td > ".$joueur->getPoids()." </td>
                        <td > ".$joueur->getNote()." </td>
                        <td > ".$joueur->getStatut()." </td>
                        <td > ".$joueur->getPostePrefere()." </td>
                        <td > ".$joueur->getPhoto()." </td>
                        <td > ".$joueur->getCommentaire()." </td>
                        <td >" .$lien->__toString2()." </td> 						
                    </tr>
                </tbody>
                ";
        array_push($this->contenu,$ligne);
    }
    
    //termine le tableau avec les balises adaptées
    public function finalize(){
        $fin= "</table>
        </div>
        ";
        array_push($this->contenu,$fin);
    }

    //affiche le tableau
    public function __toString(){
        echo implode("" ,$this->contenu);
    }
}
    class rencontre {
				
		private $Horaire;
		private $Ea; #EquipeAdverse
		private $LieuRencontre;
		private $NbPointsAdversaire;
		private $NbNosPoints;
		
		
		public function __construct($Horaire, $Ea, $LieuRencontre,$NbPointsAdversaire, $NbNosPoints){
			$this->Horaire=$Horaire ;
			$this->Ea=$Ea ;
			$this->LieuRencontre=$LieuRencontre ;
			$this->NbPointsAdversaire=$NbPointsAdversaire ;
			$this->NbNosPoints=$NbNosPoints ;
		}
		
		public function getRequeteRencontre(){
			return "INSERT INTO rencontre(Horaire,EquipeAdverse,LieuRencontre,NbPointsAdversaire,NbNosPoints) VALUES ('".$this->Horaire. "','". $this->Ea. "','".$this->LieuRencontre. "','".$this->NbPointsAdversaire. "','".$this->NbNosPoints. "');" ;
		}
        
        public function getDate(){
            return $this->Horaire;
        }
        
        public function getEquipeAdverse(){
            return $this->Ea;
        }
        
        public function getNbPointsAdversaire(){
            return $this->NbPointsAdversaire;
        }
        
        public function getNbNosPoints(){
            return $this->NbNosPoints;
        }
        
        public function getLieu(){
            return $this->LieuRencontre;
        }
    }   

?>