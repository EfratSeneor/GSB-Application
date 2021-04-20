<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Efrat Seneor
 * @author    Beth Sefer
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$id = $_SESSION['id'];
$mois=getMois(date('d/m/Y'));

switch ($action) {
    
case 'selectionnerVisiteur':
        //visiteur
    $visiteurs = $pdo->getLesVisiteurs();
    $clesVisiteur = array_keys($visiteurs);
    $visiteursASelectionner = $clesVisiteur[1];
       //mois
    $mois = getMois(date('d/m/Y')); 
    $lesMois = getlesDouzeDerniersMois($mois);
    $clesMois = array_keys($lesMois);
    $moisASelectionner = $clesMois[0];
  
    include 'vues/v_listeVisiteurs.php';
    break;

case 'afficheFrais':     
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $visiteurs = $pdo->getLesVisiteurs();
        $clesVisiteur = array_keys($visiteurs);
        $visiteursASelectionner = $idVisiteur; 
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = getlesDouzeDerniersMois($mois);
        $moisASelectionner = $leMois;
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        if (!is_array($lesInfosFicheFrais)){
            ajouterErreur('Pas de fiche de frais pour ce visiteur et ce mois');
            include 'vues/v_erreurs.php';
            include 'vues/v_listeVisiteurs.php';
        }
        else{ 
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $nbJustificatifs = $pdo->getnbJustificatifs($idVisiteur, $leMois);
        include 'vues/v_affichageFrais.php';
        }
      
    break;

case 'corrigerFF':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getLesVisiteurs();
    $visiteursASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois= getlesDouzeDerniersMois($mois);
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    $moisASelectionner =$leMois;
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
    include 'vues/v_affichageFrais.php';
    break;
    
 case 'corrigerFHF':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getLesVisiteurs();
    $visiteursASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois= getlesDouzeDerniersMois($mois);
    $moisASelectionner =$leMois;
    $leLibelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $laDate = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $idFrais = filter_input(INPUT_POST, 'frais', FILTER_SANITIZE_NUMBER_INT);
    $leMontant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    
    if (isset ($_POST['corriger'])){
        valideInfosFrais($laDate, $leLibelle, $leMontant);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
        } else {
            $pdo->MajFraisHorsForfait($idVisiteur,$leMois,$leLibelle,$laDate,$leMontant,$idFrais); 
        }
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
        include 'vues/v_affichageFrais.php';
    }
    
    if (isset ($_POST['reporter'])){
        $pdo->majLibelle($idFrais);
        $moisSuivant= getMoisSuivant($leMois);
        if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)) {
        $pdo->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);
        }
        $moisAReporter=$pdo->reporterFHF($idFrais,$moisSuivant); 
        ?>
        <div class="alert alert-info" role="alert">
        <p>La fiche a bien été validée! <a href="index.php">Cliquez ici</a>
            pour revenir à la page d'accueil.</p>
        </div>
        <?php
    }
    break;
    
case 'validerFrais':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getLesVisiteurs();
    $visiteursASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois= getlesDouzeDerniersMois($mois);
    $moisASelectionner =$leMois;
    $nbJustificatifs = filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);
    $idFrais = filter_input(INPUT_POST, 'frais', FILTER_SANITIZE_NUMBER_INT);
    $etat= 'VA';
    $pdo->majEtatFicheFrais($idVisiteur, $leMois, $etat);
    $montantFF = $pdo->montantFF($idVisiteur, $leMois);
   
    // Gestion de l'indemnité kilometrique: voir c_gereFrais dans validerMajFraisForfait (calculerKM);
    $montantFFKM =($pdo->getMontantVehicule($idVisiteur)[0][0])*($pdo->getQteKm($idVisiteur, $leMois)[0][0]);
   
    $montantFHF = $pdo->montantFHF($idVisiteur, $leMois);
    $total= $montantFFKM+$montantFF[0][0]+$montantFHF[0][0];
   
    $pdo->montantValide($idVisiteur, $leMois, $total, $nbJustificatifs);
    
    ?> 
    <div class="alert alert-info" role="alert">
        <p>La fiche a bien été validée! <a href="index.php">Cliquez ici</a>
            pour revenir à la page d'accueil.</p>
    </div>
    <?php
    break;
}