<?php
/**
 * Vue liste des visiteurs avec fiches validées/ à rembourser
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Efrat Seneor
 * @author    Beth Sefer
 */


?>
    <h2>Suivre le paiement des fiches de frais</h2>
    <div class="row">
        <div class="col-md-4">
            <form action="index.php?uc=suiviPaiement&action=afficherFrais" 
                  method="post" role="form">

             <?php //liste deroulante du visiteur ?>
              <div class="form-group">
                <label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                
                    foreach ($visiteurs as $unVisiteur) {
 
                        $id = $unVisiteur['id'];             
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        
                        if ($id == $visiteursASelectionner) {
                            ?>
                            <option selected value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?> 
                    
                </select>
            </div>
            
             <?php //liste deroulante du mois ?>
              <div class="form-group">
                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                
                    foreach ($lesMois as $unMois) {

                        $mois = $unMois['mois'];                       
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?> 
                    

                </select>
            </div>  
            <div style="text-align: center">
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                    role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                    role="button">
            </div>
        </form>

    </div>
</div>