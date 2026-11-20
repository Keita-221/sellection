<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    header("Location:../connexion/session.php");
    exit();
}

require_once __DIR__ . '/../config/db.php';
if ($mysqli->connect_errno) {
    exit("Erreur connexion BDD");
}
$mysqli->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);

$sql = "SELECT * FROM t_compte_cpt LEFT JOIN t_profil_pro USING(cpt_mail)";
$result = $mysqli->query($sql);

// Récupération des informations de la structure
$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des comptes - <?= htmlspecialchars($structure['pre_nom_structure']) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/admin-comptes.css" rel="stylesheet" />
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
                                        <a class="dropdown-item" href="../vitrine/sel.php">
                                            <i class="fas fa-star"></i> Sélection niveau 1
                                        </a>
                                        <a class="dropdown-item" href="../vitrine/select.php">
                                            <i class="fas fa-star-half-alt"></i> Sélection niveau 2
                                        </a>
                                        <a class="dropdown-item" href="../vitrine/selection.php">
                                            <i class="fas fa-gem"></i> Sélection niveau 3
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../inscription/inscription.php">
                                        <i class="fas fa-user-plus"></i> Inscription
                                    </a>
                                </li>
                                <?php if (isset($_SESSION['login'])): ?>
                                    <li class="nav-item">
                                        <a class="nav-link tm-nav-link" href="../connexion/deconnexion.php">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link tm-nav-link" href="../connexion/session.php">
                                            <i class="fas fa-sign-in-alt"></i> Connexion
                                        </a>
                                    </li>
                                <?php endif; ?>
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
                        <img src="../ressources/<?= htmlspecialchars($structure['pre_img']) ?>" height="100" alt="Logo">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mb-7">
        <div class="row">
            <div class="col-12">
                <div class="page-header text-center">
                    <h2 class="section-title">
                        <i class="fas fa-users"></i> Gestion des comptes
                    </h2>
                    <hr>
                </div>
            </div>
        </div>



        <?php if ($result && $result->num_rows > 0) { ?>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th><i class="fas fa-envelope"></i> COMPTE</th>
                                    <th><i class="fas fa-user"></i> NOM</th>
                                    <th><i class="fas fa-user"></i> PRENOM</th>
                                    <th><i class="fas fa-toggle-on"></i> ETAT</th>
                                    <th><i class="fas fa-tag"></i> STATUT</th>
                                    <th><i class="fas fa-calendar-alt"></i> Date de création</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($res = $result->fetch_assoc()) {
                                    // Cas particulier : le compte gestionnaire1 n'a pas de profil
                                    if ($res['cpt_mail'] == ADMIN_EMAIL) {
                                        $nom = '';
                                        $prenom = '';
                                        $etat = '';
                                        $statut = '';

                                        if (!empty($res['cpt_date_creation'])) {
                                            $date_creation = $res['cpt_date_creation'];
                                        } else {
                                            $date_creation = '';
                                        }
                                    } else {
                                        // Pour les autres comptes, on prend les données du profil
                                        if (!empty($res['pro_nom'])) {
                                            $nom = $res['pro_nom'];
                                        } else {
                                            $nom = '';
                                        }

                                        if (!empty($res['pro_prenom'])) {
                                            $prenom = $res['pro_prenom'];
                                        } else {
                                            $prenom = '';
                                        }

                                        $etat = $res['pro_validite'];
                                        $statut = $res['pro_statut'];

                                        if (!empty($res['pro_date_de_creation'])) {
                                            $date_creation = $res['pro_date_de_creation'];
                                        } elseif (!empty($res['cpt_date_creation'])) {
                                            $date_creation = $res['cpt_date_creation'];
                                        } else {
                                            $date_creation = '';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($res['cpt_mail']) ?></strong></td>
                                        <td><?= htmlspecialchars($nom) ?></td>
                                        <td><?= htmlspecialchars($prenom) ?></td>
                                        <td>
                                            <?php
                                            if ($etat == 'A') {
                                                echo '<span class="badge-valide actif"><i class="fas fa-check-circle"></i> Actif</span>';
                                            } elseif ($etat == '') {
                                                echo '';  // Rien affiché pour le gestionnaire
                                            } else {
                                                echo '<span class="badge-valide inactif"><i class="fas fa-times-circle"></i> Inactif</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($statut == 'A') {
                                                echo '<span class="badge-statut admin"><i class="fas fa-shield-alt"></i> Admin</span>';
                                            } elseif ($statut == '') {
                                                echo '';  // Rien affiché pour le gestionnaire
                                            } else {
                                                echo '<span class="badge-statut responsable"><i class="fas fa-user-check"></i> Responsable</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($date_creation) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucun compte disponible pour le moment.
                    </div>
                </div>
            </div>


        <?php } ?>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="../connexion/admin_acceuill.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
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
                    <p class="copyright">© 2024 <?= htmlspecialchars($structure['pre_nom_structure']) ?> - Tous droits
                        réservés</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>

</html>
<?php $mysqli->close(); ?>