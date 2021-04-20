<?php
/**
 * Gestion de deconnexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Efrat Seneor
 * @author    Beth Sefer
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$action) {
    $action = 'demandeconnexion';
}

switch ($action) {
case 'demandeDeconnexion':
    include 'vues/v_deconnexion.php';
    break;
case 'valideDeconnexion':
    if (estConnecte()) {
        include 'vues/v_deconnexion.php';
    } else {
        ajouterErreur("Vous n'êtes pas connecté");
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
