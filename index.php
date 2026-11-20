<?php

require_once __DIR__ . '/config/db.php';
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

$requete_total = "SELECT COUNT(*) AS total_actualites FROM t_actualite_act";
$result_total = $mysqli->query($requete_total);
$total = $result_total->fetch_assoc();

$requete_actu = "SELECT 
    act_titre AS titre,
    act_texte AS description,
    act_date_publie AS date_publication,
    cpt_mail AS auteur
    FROM t_actualite_act
    WHERE act_etat = 'A'
    ORDER BY act_date_publie DESC 
    LIMIT 5";

$result_actu = $mysqli->query($requete_actu);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Repair Center Pro - Accueil</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/index.css" rel="stylesheet" />
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
                            <li class="nav-item active">
                                <a class="nav-link tm-nav-link" href="index.php">
                                    <i class="fas fa-home"></i> Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tm-nav-link" href="vitrine/selection.php">
                                    <i class="fas fa-tools"></i> Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tm-nav-link" href="inscription/inscription.php">
                                    <i class="fas fa-user-plus"></i> Inscription
                                </a>
                            </li>
                             <li class="nav-item">
                                        <a class="nav-link tm-nav-link" href="connexion/session.php">
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
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col-12 text-center py-5">
                <h1 class="tm-text-primary mb-3">
                    <?= htmlspecialchars($structure['pre_nom_structure']) ?>
                    <br>
                    <a class="navbar-brand" href="index.php">
                        <img src="ressources/<?= htmlspecialchars($structure['pre_img']) ?>" height="120" alt="Logo">
                    </a>
                </h1>
                <p class="lead mb-4"><?= htmlspecialchars($structure['pre_texte_bienvenue']) ?></p>
            </div>
        </div>
    </div>
</div>

<?php if ($result_actu && $result_actu->num_rows > 0) { ?>
<div class="container-fluid mb-7">
    <div class="row">
        <div class="col-12">
            <h3 class="section-title text-center">
                <i class="fas fa-newspaper"></i> Les 5 dernières actualités
            </h3>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-heading"></i> Titre</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-user"></i> Auteur</th>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($actu = $result_actu->fetch_assoc()) { ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($actu['titre']) ?></strong></td>
                            <td><?= htmlspecialchars($actu['description']) ?></td>
                            <td><i class="fas fa-envelope"></i> <?= htmlspecialchars($actu['auteur']) ?></td>
                            <td><i class="fas fa-clock"></i> <?= htmlspecialchars($actu['date_publication']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info mt-3 text-center">
                <strong><i class="fas fa-chart-bar"></i> Nombre total d'actualités dans la base :</strong>
                <span class="badge badge-primary ml-2"><?= $total['total_actualites'] ?></span>
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
                Aucune actualité disponible pour le moment.
            </div>
            
            <div class="alert alert-info mt-3 text-center">
                <strong><i class="fas fa-chart-bar"></i> Nombre total d'actualités dans la base :</strong>
                <span class="badge badge-primary ml-2"><?= $total['total_actualites'] ?></span>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="container-fluid mb-7">
    <div class="row">
        <div class="col-12">
            <h3 class="section-title text-center">
                <i class="fas fa-gem"></i> Nos Services Premium
            </h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="service-card">
                <i class="fas fa-mobile-alt"></i>
                <h4>Réparation Téléphones</h4>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="service-card">
                <i class="fas fa-laptop"></i>
                <h4>Réparation Ordinateurs</h4>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="service-card">
                <i class="fas fa-unlock-alt"></i>
                <h4>Déblocage</h4>
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
                <p style="font-size: 14px;">© 2024 <?= htmlspecialchars($structure['pre_nom_structure']) ?> - Tous droits réservés</p>
            </div>
        </div>
    </div>
</footer>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/parallax.min.js"></script>
<script src="js/tooplate-script.js"></script>
<?php $mysqli->close(); ?>
</body>
</html>