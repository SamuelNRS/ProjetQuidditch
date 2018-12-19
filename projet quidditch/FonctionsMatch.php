	<?php
	//Quelques fonctions utiles
	
	//MISE EN PLACE DES DIVERSES CLASSES
	
	// création de la classe formulaire
	class form {
		
		private $form;
		private $linkpdo;
		
		public function __construct($action,$methode,$linkpdo){
			$this->form=array();
			$base="<form action='$action' method='$methode'>
						<fieldset>";
			array_push($this->form,$base);
			$this->linkpdo=$linkpdo;
		}

		public function finalize(){
			$fin="</form>
						</fieldset>";
			array_push($this->form,$fin);
		}
		
		public function setText($name, $label){
			$text="<br/>
					<input type=text name=$name placeholder=$label> <br/> ";
			array_push($this->form,$text);
		}
		
		public function setDate($name, $label){
			$text="<br/>
					<input type=date name=$name value=$label> <br/> ";
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
			$joueur=$this->getJValide($this->linkpdo) ;
			$taille=getNbJValide($this->linkpdo);
			$text=" <br/>$poste
				<SELECT label=$nom name=$nom >" ;
			$i = 0 ;
			while($i<$taille){
				$text.='<br/><OPTION>'.$joueur[$i];
				$i+=1;
			}
			$text.='</SELECT><br/>';
			array_push($this->form,$text);
		}
		
		public function setListeDeroulante($name,$label){
		    $text="<br/>
		            <SELECT name='nom' size='5'>
		            <OPTION>Gardien
		            <OPTION>Batteur
		            <OPTION>Poursuiveur
		            <OPTION>Attrapeur
		            <OPTION>Aucun
		            </SELECT> <br/>";
		    array_push($this->form,$text);
		    
		}
		public function setButton($name, $label,$style){
			if($style==NULL ){
				$style="'' ";
			}
			$text="<br/>
					<input style=$style type='submit' name=$name label=$label> <br/> ";
			array_push($this->form,$text);
		}
		
		public function setUpload($name,$label,$style){
			if($style==NULL ){
				$style="'' ";
			}
			$text="<br/>
					<input style=$style type='file' name=$name label=$label> <br/> ";
			array_push($this->form,$text);
		}
		
		public function __toString(){
			echo implode("" ,$this->form);
		}
	}

	
	
	class Bouton{
		
		private $texte;
		
		// création de la fonction Creer bouton
		public function __construct($nom,$label,$style,$cible){
			if($style==NULL ){
					$style='';
				}
			$this->texte="<button><a name=$nom style='$style' href=$cible>$label</a> </button> ";
		}
		
		//pour afficher le bouton
		public function __toString(){
			echo $this->texte;
		}
		
		public function __toString2(){
			return $this->texte;
		}
	}
	
	//Pour les matchs
	class Tableau2 {
		
		private $contenu;
		private $styleCase;
		private $styleLigne;
		private $style;
		
		public function __construct($style,$styleCase,$styleLigne){
			if($style==NULL){
				$style="'' ";
			}
			if($styleCase==NULL){
				$styleCase="'' ";
			}
			if($styleLigne==NULL){
				$styleLigne="'' ";
			}
			
			$this->styleCase=$styleCase;
			$this->styleLigne=$styleLigne;
			$this->style=$style;
			
			$this->contenu=array();
			$debut="<table style=$style>
					<tr style='$this->styleLigne' >
						<th style='$styleCase'> Date </th>
						<th style='$styleCase'> EquipeAdverse </th>
						<th style='$styleCase'> Lieu </th>
						<th style='$styleCase'> Nb points Adverse</th>
						<th style='$styleCase'> Nb nos points</th> 
						<th style='$styleCase'> </th> 
					</tr>
					";
			array_push($this->contenu,$debut);
		}
		
		public function addLigne($Date,$EquipeAdverse,$Lieu,$NbPA,$NbPN){
			$lien = new Bouton('Modifier','Modifier','',"./modifier?Date=$Date");
			$ligne= "<tr style='$this->styleCase'>
						<td style='$this->styleLigne'> $Date </td>
						<td style='$this->styleLigne'> $EquipeAdverse </td>
						<td style='$this->styleLigne'> $Lieu </td>
						<td style='$this->styleLigne'> $NbPA </td>
						<td style='$this->styleLigne'> $NbPN </td>
						<td style='$this->styleLigne'>" .$lien->__toString2()." </td> 						
					</tr>
					";
			array_push($this->contenu,$ligne);
		}
		
		public function finalize(){
			$fin= "</table>";
			array_push($this->contenu,$fin);
		}
		
		public function __toString(){
			echo implode("" ,$this->contenu);
		}
	}
	
	// création de la fonction pour saut de ligne
	function SautDeLigne(){
		echo " <br/> ";
	}
	
	
	//Classe Rencontre a creer
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
		
		
		public function setNote($note){
			$this->Note=$note;
		}
		
	}	
	?>