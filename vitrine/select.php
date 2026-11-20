<?php

require_once __DIR__ . '/../config/db.php';
if ($mysqli->connect_errno) {
    echo "<div class='alert alert-danger m-3'>";
    echo "<strong>Erreur de connexion à la BDD</strong><br>";
    echo "Errno: " . $mysqli->connect_errno . "<br>";
    echo "Error: " . $mysqli->connect_error;
    echo "</div>";
    exit();
}
$mysqli->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);

$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();

$req_sel = "SELECT * FROM t_selection_sel WHERE sel_etat = 'A'";
$res_sel = $mysqli->query($req_sel);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Repair Center Pro - Sélections</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/selection.css" rel="stylesheet" />
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
                                        <a class="dropdown-item active" href="./selection.php">
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
        <?php if ($res_sel && $res_sel->num_rows > 0) { ?>
            <div class="container-fluid mb-7">
                <div class="row">
                    <div class="col-12">
                        <div class="section-header text-center">
                            <h3 class="section-title">
                                <i class="fas fa-clipboard-list"></i> Nos Sélections
                            </h3>
                            <hr>
                            <p class="section-subtitle">Découvrez nos offres et services disponibles</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><i class="fas fa-tag"></i> Sélection</th>
                                        <th><i class="fas fa-align-left"></i> Description</th>
                                        <th><i class="fas fa-user"></i> Auteur</th>
                                        <th><i class="fas fa-cogs"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($sel = $res_sel->fetch_assoc()) {
                                        $id_sel = $sel['sel_id'];
                                        $req_elem = "SELECT elt_id FROM t_rassemblement_ras
                                                WHERE sel_id = '" . $id_sel . "' AND elt_id > 0 LIMIT 1";
                                        $res_elem = $mysqli->query($req_elem);
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($sel['sel_intitule']) ?></strong>
                                                <?php if (!empty($sel['sel_etat'])){ ?>
                                                    <span class="badge-actif">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td><?= (htmlspecialchars($sel['sel_texte_introductif'])) ?></td>
                                            <td>
                                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($sel['cpt_mail']) ?>
                                            </td>
                                            <td>
                                                <?php if ($res_elem && $res_elem->num_rows > 0) {
                                                    $premier_elem = $res_elem->fetch_assoc();
                                                    $id_premier_elem = $premier_elem['elt_id'];
                                                    ?>
                                                    <a href="affichageselect.php?sel_id=<?= $id_sel ?>&elem_id=<?= $id_premier_elem ?>"
                                                        class="btn btn-primary-custom btn-sm">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-ban"></i> Aucun élément
                                                    </span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="container-fluid mb-7">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Aucune sélection disponible pour le moment.
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
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
                    <p class="copyright">© 2026 <?= htmlspecialchars($structure['pre_nom_structure']) ?> - Tous droits
                        réservés</p>
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