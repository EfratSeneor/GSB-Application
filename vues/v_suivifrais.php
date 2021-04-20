<?php
/**
 * Vue Fiches de frais validées à rembourser
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Efrat Seneor
 * @author    Beth Sefer
 */

?>
<hr>
<form action="index.php?uc=suiviPaiement&action=miseEnPaiement"
      method="post" role="form"> 
<div class="panel panel-primary-c">
   <div class="panel-heading">Fiche de frais du mois
       <?php echo $numMois . '-' . $numAnnee ?> : </div>
   <div class="panel-body">
       <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
       depuis le <?php echo $dateModif ?> <br>
       <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
   </div>
</div>
<div class="panel panel-info1">
   <div class="panel-heading" style="color:white">Eléments forfaitisés</div>
   <table class="table table-bordered table-responsive">
       <tr>
           <?php
           foreach ($lesFraisForfait as $unFraisForfait) {
               $libelle = $unFraisForfait['libelle']; ?>
               <th> <?php echo htmlspecialchars($libelle) ?></th>
               <?php
           }
           ?>
       </tr>
       <tr>
           <?php
           foreach ($lesFraisForfait as $unFraisForfait) {
               $quantite = $unFraisForfait['quantite']; ?>
               <td class="qteForfait"><?php echo $quantite ?> </td>
               <?php
           }
           ?>
       </tr>
       
   </table>
</div>
<div class="panel panel-info1">
   <div class="panel-heading" style="color:white">Descriptif des éléments hors forfait -
       <?php echo $nbJustificatifs ?> justificatifs reçus</div>
   <table class="table table-bordered table-responsive">
       <tr>
           <th class="date">Date</th>
           <th class="libelle">Libellé</th>
           <th class='montant'>Montant</th>                
       </tr>
       <?php
       foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
           $date = $unFraisHorsForfait['date'];
           $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
           $montant = $unFraisHorsForfait['montant']; 
           $visiteur= $unFraisHorsForfait['idvisiteur']?>
           <tr>
               <td><?php echo $date ?></td>
               <td><?php echo $libelle ?></td>
               <td><?php echo $montant ?></td>
           </tr>
           <?php
       }
       ?>
   </table>
</div>
    <div style="text-align: center">
        <input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">
    <input id="ok" type="submit" value="Mettre en paiement" class="btn btn-success" role="button">
    </div>
</form>