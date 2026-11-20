<?php
session_start();

require_once __DIR__ . '/../config/db.php';
if ($mysqli->connect_errno) {
    exit("Erreur connexion BDD : " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);

$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();

// ── Vérification 1 : paramètres présents dans l'URL
if (!isset($_GET['sel_id']) || !isset($_GET['elem_id'])) {
    $erreur = "Paramètres manquants. Veuillez sélectionner une offre valide depuis le menu.";
}

// ── Vérification 2 : paramètres entiers positifs
elseif (!ctype_digit($_GET['sel_id']) || !ctype_digit($_GET['elem_id'])) {
    $erreur = "Identifiants invalides. Les paramètres doivent être des nombres entiers positifs.";
}

else {
    $sel_id  = intval($_GET['sel_id']);
    $elem_id = intval($_GET['elem_id']);

    // ── Vérification 3 : la sélection existe et est active
    $res_sel = $mysqli->query("SELECT * FROM t_selection_sel WHERE sel_id = " . $sel_id . " AND sel_etat = 'A'");
    if (!$res_sel || $res_sel->num_rows == 0) {
        $erreur = "Sélection introuvable. La sélection n°" . $sel_id . " n'existe pas ou n'est pas disponible.";
    } else {
        $selection = $res_sel->fetch_assoc();

        // ── Vérification 4 : l'élément appartient à cette sélection
        $res_in = $mysqli->query("SELECT elt_id FROM t_element_elt 
                                  JOIN t_rassemblement_ras USING(elt_id)
                                  WHERE sel_id = " . $sel_id . " AND elt_id = " . $elem_id);
        if (!$res_in || $res_in->num_rows == 0) {
            $erreur = "Élément hors intervalle. L'élément n°" . $elem_id . " n'appartient pas à la sélection n°" . $sel_id . ".";
        } else {

            // ── Vérification 5 : l'élément est publié (P)
            $res_elt = $mysqli->query("SELECT * FROM t_element_elt WHERE elt_id = " . $elem_id . " AND elt_etat = 'P'");
            if (!$res_elt || $res_elt->num_rows == 0) {
                // Élément en brouillon → chercher le 1er élément P de la sélection
                $res_first = $mysqli->query("SELECT elt_id FROM t_element_elt 
                                             JOIN t_rassemblement_ras USING(elt_id)
                                             WHERE sel_id = " . $sel_id . " AND elt_etat = 'P'
                                             ORDER BY elt_id ASC LIMIT 1");
                if ($res_first && $res_first->num_rows > 0) {
                    // Redirection possible car aucun HTML n'a encore été envoyé
                    $first = $res_first->fetch_assoc();
                    header("Location: ./affichage_selection.php?sel_id=" . $sel_id . "&elem_id=" . $first['elt_id']);
                    exit();
                } else {
                    // Tous les éléments sont en brouillon
                    $erreur = "Aucun élément publié. Cette sélection ne contient aucun élément disponible pour le moment.";
                }
            } else {
                $element = $res_elt->fetch_assoc();

                // Suivant
                $res_suiv = $mysqli->query("SELECT elt_id FROM t_element_elt 
                                            JOIN t_rassemblement_ras USING(elt_id)
                                            WHERE sel_id = " . $sel_id . "
                                            AND elt_etat = 'P'
                                            AND elt_id > " . $elem_id . "
                                            ORDER BY elt_id ASC LIMIT 1");
                // Précédent
                $res_prec = $mysqli->query("SELECT elt_id FROM t_element_elt 
                                            JOIN t_rassemblement_ras USING(elt_id)
                                            WHERE sel_id = " . $sel_id . "
                                            AND elt_etat = 'P'
                                            AND elt_id < " . $elem_id . "
                                            ORDER BY elt_id DESC LIMIT 1");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Repair Center Pro - Détail de l'offre</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/selection.css" rel="stylesheet" />
    <link href="../css/affichage_selection.css" rel="stylesheet" />
</head>
<body>

    <div class="tm-nav-container-outer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg" id="tm-main-nav">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-nav">
                            <span><i class="fas fa-bars"></i></span>
                        </button>
                        <div class="collapse navbar-collapse tm-nav" id="navbar-nav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../index.php">
                                        <i class="fas fa-home"></i> Accueil
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link tm-nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-layer-group"></i> Sélections
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="./sel.php">
                                            <i class="fas fa-star"></i> Sélection niveau 1
                                        </a>
                                        <a class="dropdown-item" href="./select.php">
                                            <i class="fas fa-star-half-alt"></i> Sélection niveau 2
                                        </a>
                                        <a class="dropdown-item" href="./selection.php">
                                            <i class="fas fa-gem"></i> Sélection niveau 3
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../inscription/inscription.php">
                                        <i class="fas fa-user-plus"></i> Inscription
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../connexion/session.php">
                                        <i class="fas fa-sign-in-alt"></i> Connexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-section">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="tm-text-primary mb-3">
                        <?= htmlspecialchars($structure['pre_nom_structure']) ?>
                    </h1>
                    <a class="navbar-brand" href="../index.php">
                        <img src="../ressources/<?= htmlspecialchars($structure['pre_img']) ?>" height="120" alt="Logo">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">

                    <?php if (isset($erreur)) { ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?= htmlspecialchars($erreur) ?>
                        </div>

                    <?php } else { ?>

                        <div class="selection-detail-card">
                            <div class="selection-header">
                                <h2>
                                    <i class="fas fa-tag mr-2"></i>
                                    <?= htmlspecialchars($selection['sel_intitule']) ?>
                                </h2>
                            </div>

                            <div class="element-content">
                                <div class="element-title">
                                    <i class="fas fa-cog mr-2"></i>
                                    <?= htmlspecialchars($element['elt_intitule']) ?>
                                </div>

                                <div class="element-description">
                                    <?= nl2br(htmlspecialchars($element['elt_descriptif'])) ?>
                                </div>

                                <?php if (!empty($element['elt_fichier_img'])) { ?>
                                    <div class="element-image">
                                        <img src="../ressources/<?= htmlspecialchars($element['elt_fichier_img']) ?>"
                                            alt="<?= htmlspecialchars($element['elt_intitule']) ?>"
                                            onerror="this.src='../ressources/default-image.jpg'">
                                    </div>
                                <?php } ?>

                                <div class="nav-buttons">
                                    <?php if ($res_prec && $res_prec->num_rows == 1) {
                                        $precedent = $res_prec->fetch_assoc(); ?>
                                        <a href="./affichage_selection.php?sel_id=<?= $sel_id ?>&elem_id=<?= $precedent['elt_id'] ?>"
                                            class="btn nav-btn nav-btn-prev">
                                            <i class="fas fa-chevron-left"></i> Précédent
                                        </a>
                                    <?php } ?>

                                    <?php if ($res_suiv && $res_suiv->num_rows == 1) {
                                        $suivant = $res_suiv->fetch_assoc(); ?>
                                        <a href="./affichage_selection.php?sel_id=<?= $sel_id ?>&elem_id=<?= $suivant['elt_id'] ?>"
                                            class="btn nav-btn nav-btn-next">
                                            Suivant <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="./selection.php" class="btn btn-outline-custom">
                        <i class="fas fa-arrow-left"></i> Retour aux sélections
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <h5><?= htmlspecialchars($structure['pre_nom_structure']) ?></h5>
                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($structure['pre_adresse']) ?></p>
                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($structure['pre_num_telephone']) ?></p>
                    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($structure['pre_adresse_mail']) ?></p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="copyright">© 2024 <?= htmlspecialchars($structure['pre_nom_structure']) ?> - Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/parallax.min.js"></script>
    <script src="../js/tooplate-script.js"></script>

    <?php $mysqli->close(); ?>
</body>
</html>