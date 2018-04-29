<?php include('inc/head.php'); ?>

    <!DOCTYPE html>
    <html>

<script
        src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
</script>

    <body>


    <div>
        <?php


        function explore($cheminDossier)
        {

            $dir = openDir($cheminDossier);
            if (false === $dir) {
                echo 'Echec: Impossible d\'explorer le dossier';
            } else {
                while ($file = readDir($dir)) {
                    if (($file != '.') && ($file != '..')) {
                        if (is_dir($cheminDossier . '/' . $file)){
                            $titre = ucfirst($file);
                            ?><br><div class="dossier" ><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span><br><?php
                            echo $titre;?></div> <?php
                        } else {
                            ?><div><br><span class="glyphicon glyphicon-file" aria-hidden="true"><?php
                            echo " " . $file;?></div><br><?php
                        }
                        ?>
                        <form action="index.php" method="post" name="delname" >
                            <input type="submit" value="Supprimer" class="btn btn-danger">
                            <input type="hidden" name="delname" value="<?= $cheminDossier . '/' . $file ?>"><br>
                        </form>
                        <?php
                        if (isset($_POST['delname'])) {
                            @unlink($_POST['delname']);
                        }

                        isset($file) ? $extension = substr(strrchr($file, "."), 1) : '';
                        if (is_dir($cheminDossier . '/' . $file) == false && $extension == 'txt' ||
                            $extension == 'html') { ?>
                            <form  action="" method="post" name="modifname">
                                <input type="submit" value="Editer" class="btn btn-success">
                                <input type="hidden" name="modifname"
                                       value="<?= $cheminDossier . '/' . $file ?>"><br><br>
                            </form> <?php

                        } ?>
                        <ul class="fichier" ><?php
                        if (is_dir($cheminDossier . '/' . $file)) {
                            explore($cheminDossier . '/' . $file);
                        }?>
                            </ul> <?php

                    }
                }
                closeDir($dir);
            }
        }

            if (isset($_POST['modifname'])) {
                $modifname = file_get_contents($_POST['modifname']);
            }


            if (isset($_POST['editcontent']) && isset($_POST['path'])) {
                $fichier = $_POST['path'];
                $ouvre = fopen($fichier, 'w');
                fwrite($ouvre, $_POST['editcontent']);
                fclose($ouvre);
            }


        explore('files');
        ?>

        <form action="" method="post" name="editcontent">
            <textarea name="editcontent" style="width: 100%; height: 200px"><?= $modifname ?? '' ?></textarea>
            <input type="hidden" name="path" value="<?= $_POST['modifname'] ?>">
            <input type="submit" value="Envoyer" class="btn btn-success">
        </form>


    </div>

    <script>
        $(".dossier").on('click', function(e) {
            $('.fichier').toggle('.fichier');
        });



    </script>

    </body>


    </html>


<?php include('inc/foot.php'); ?>