<?php
/**
 * Gestion des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Efrat Seneor
 * @author    Beth Sefer
 */

$id = $_SESSION['id'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'saisirFrais':
    if ($pdo->estPremierFraisMois($id, $mois)) {
        $pdo->creeNouvellesLignesFrais($id, $mois);
    }
    break;
case 'validerMajFraisForfait':
    if(isset ($_POST['ajouter'])){
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT,FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($id, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
    }
    if(isset ($_POST['calculerKM'])){
        $montantVehicule= $pdo->getMontantVehicule($id);
        $montant=$montantVehicule[0][0];

        $qte= $pdo->getQteKm($id, $mois);
        $qteKm= $qte[0][0]; 
        ?>
            <label><?php echo ('  '.$qteKm* $montant.' €') ?></label>
         <?php
    
    }
    break;
case 'validerCreationFrais':
    $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
   // var_dump($montant);
    valideInfosFrais($dateFrais, $libelle, $montant);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->creeNouveauFraisHorsForfait($id, $mois, $libelle, $dateFrais, $montant );
    }
    break;
case 'supprimerFrais':
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
    $pdo->supprimerFraisHorsForfait($idFrais);
    break;
case 'calculerFraisKm2':
    $montantVehicule= $pdo->getMontantVehicule($id);
    $montant=$montantVehicule[0][0];

    $qte= $pdo->getQteKm($id, $mois);
    $qteKm= $qte[0][0]; 
    break;
}


$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($id, $mois);


require 'vues/v_listeFraisForfait.php';
require 'vues/v_listeFraisHorsForfait.php';
