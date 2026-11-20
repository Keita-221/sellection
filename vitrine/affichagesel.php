<?php
session_start();

// Connexion à la base de données
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

// La presentation
$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();

// Récupération des paramètres
if (isset($_GET['elem_id']) && isset($_GET['sel_id'])) {
    $eid = intval($_GET['elem_id']);
    $sid = intval($_GET['sel_id']);
} else {
    echo "<div class='alert alert-danger text-center m-5'>";
    echo "<i class='fas fa-exclamation-triangle mr-2'></i>";
    echo "Paramètres manquants ! Veuillez sélectionner une offre valide.";
    echo "</div>";
    exit();
}

$sql1 = "SELECT * FROM t_selection_sel WHERE sel_id = " . $sid." AND sel_etat = 'A'";
$res_sel = $mysqli->query($sql1);
$sel = $res_sel->fetch_assoc();

$reql2 = "SELECT elt_id, elt_intitule, elt_fichier_img, elt_descriptif FROM t_rassemblement_ras
          LEFT JOIN t_element_elt USING (elt_id)
          WHERE sel_id = " . $sid . " AND elt_id > 0 AND elt_etat = 'P'";
$res_elts = $mysqli->query($reql2);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($sel['sel_intitule']) ?> — <?= htmlspecialchars($structure['pre_nom_structure']) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/affichagesel.css" rel="stylesheet" />
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
                                <a class="nav-link tm-nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                            <?php if (isset($_SESSION['login'])){ ?>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../deconnexion/logout.php">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </a>
                                </li>
                            <?php }else{ ?>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../connexion/session.php">
                                        <i class="fas fa-sign-in-alt"></i> Connexion
                                    </a>
                                </li>
                            <?php } ?>
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
    <div class="container-fluid mb-7">
        <div class="row">
            <div class="col-12">
                <div class="selection-header text-center">
                    <h3 class="section-title">
                        <i class="fas fa-clipboard-list"></i> <?= htmlspecialchars($sel['sel_intitule']) ?>
                    </h3>
                    <hr>
                    <p class="section-subtitle">Découvrez les éléments de cette sélection</p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php if ($res_elts && $res_elts->num_rows > 0){?>
                <?php while($element = $res_elts->fetch_assoc()){ ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="element-card">
                            <?php if (!empty($element['elt_fichier_img'])){ ?>
                                <div class="element-card-image">
                                    <img src="../ressources/<?= htmlspecialchars($element['elt_fichier_img']) ?>" 
                                         alt="<?= htmlspecialchars($element['elt_intitule']) ?>"
                                         >
                                </div>
                            <?php } ?>
                            <div class="element-card-body">
                                <h4 class="element-card-title">
                                    <i class="fas fa-cube"></i> <?= htmlspecialchars($element['elt_intitule']) ?>
                                </h4>
                                <p class="element-card-description">
                                    <?= nl2br(htmlspecialchars(substr($element['elt_descriptif'], 0, 150))) ?>
                                    <?php if(strlen($element['elt_descriptif']) > 150){ ?>...<?php } ?>
                                </p>
                              
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucun élément disponible dans cette sélection pour le moment.
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="./selection.php" class="btn btn-outline-custom">
                     <i class="fas fa-home"></i> Retour aux sélections
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