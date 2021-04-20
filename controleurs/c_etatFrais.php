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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$id = $_SESSION['id'];
switch ($action) {
case 'selectionnerMois':
   $lesMois = $pdo->getLesMoisDisponibles($id);
   // Afin de sélectionner par défaut le dernier mois dans la zone de liste
   // on demande toutes les clés, et on prend la première,
   // les mois étant triés décroissants
   $lesCles = array_keys($lesMois);
   $moisASelectionner = $lesCles[0];
   include 'vues/v_listeMois.php';
   break;
case 'voirEtatFrais':
   $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);//on recupere ce qui a ete selectionné ds la liste deroulante de nummois(qui se trouve dans v_listemois).
   $lesMois = $pdo->getLesMoisDisponibles($id);
   $moisASelectionner = $leMois;
   include 'vues/v_listeMois.php';//ca affiche la liste deroulante.
   $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id, $leMois);
   $lesFraisForfait = $pdo->getLesFraisForfait($id, $leMois);
   $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($id, $leMois);
   $numAnnee = substr($leMois, 0, 4);
   $numMois = substr($leMois, 4, 2);
   $libEtat = $lesInfosFicheFrais['libetat'];
   $montantValide = $lesInfosFicheFrais['montantvalide'];
   $nbJustificatifs = $lesInfosFicheFrais['nbjustificatifs'];
   $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['datemodif']);
   include 'vues/v_etatFrais.php';
}
