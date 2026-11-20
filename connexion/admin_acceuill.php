<?php
session_start();

// Accessible à TOUS les connectés (admin ET responsable)
if (!isset($_SESSION['login'])) {
    header("Location:../connexion/session.php");
    exit();
}

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

// Récupération des informations de la structure
$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();

// Récupération des informations du profil (si existe)
$login = $_SESSION['login'];
$sql_profil = "SELECT * FROM t_compte_cpt LEFT JOIN t_profil_pro USING(cpt_mail) WHERE cpt_mail = '$login'";
$result_profil = $mysqli->query($sql_profil);
$profil = $result_profil->fetch_assoc();

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administration - <?= htmlspecialchars($structure['pre_nom_structure']) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/admin-accueil.css" rel="stylesheet" />
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
                            <?php if (isset($_SESSION['login'])){ ?>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="../connexion/deconnexion.php">
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
                    <img src="../ressources/<?= htmlspecialchars($structure['pre_img']) ?>" height="100" alt="Logo">
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-7">
    <div class="row">
        <div class="col-12">
            <div class="admin-header text-center">
                <h2 class="section-title">
                    <i class="fas fa-user-shield"></i> Espace Administration
                </h2>
                <hr>
            </div>
        </div>
    </div>

    <!-- MENU selon le rôle -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="admin-nav">
                <?php if ($_SESSION['role'] === 'A') { ?>
                    <a href="../bac/admin_comptes.php" class="admin-nav-link">
                        <i class="fas fa-users"></i> Gestion des comptes
                    </a>
                    <a href="../bac/admin_selections.php" class="admin-nav-link">
                        <i class="fas fa-list-ul"></i> Gestion des sélections
                    </a>
                    <a href="../connexion/deconnexion.php" class="admin-nav-link logout">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php } else { ?>
                    <a href="../bac/admin_selections.php" class="admin-nav-link">
                        <i class="fas fa-list-ul"></i> Gestion des sélections
                    </a>
                    <a href="../connexion/deconnexion.php" class="admin-nav-link logout">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- MESSAGE DE BIENVENUE et informations du profil -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Mon profil</h3>
                </div>
                <div class="profile-card-body">
                    <!-- Message de bienvenue plus joli -->
                    <div class="welcome-box">
                        <div class="welcome-icon">
                            <?php if ($_SESSION['role'] === 'A') { ?>
                                <i class="fas fa-crown"></i>
                            <?php } else { ?>
                                <i class="fas fa-user-tie"></i>
                            <?php } ?>
                        </div>
                        <div class="welcome-text">
                            <h4>Bonjour, <?= htmlspecialchars($_SESSION['login']) ?> !</h4>
                            <p>
                                <?php if ($_SESSION['role'] === 'A') { ?>
                                    <span class="role-badge admin-badge">
                                        <i class="fas fa-shield-alt"></i> Administrateur
                                    </span>
                                    <span class="welcome-note">Vous avez tous les droits d'administration.</span>
                                <?php } else { ?>
                                    <span class="role-badge respo-badge">
                                        <i class="fas fa-chart-line"></i> Responsable
                                    </span>
                                    <span class="welcome-note">Vous pouvez gérer les sélections.</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>

                    <div class="profile-info">
                        <!-- Email -->
                        <div class="info-item">
                            <label><i class="fas fa-envelope"></i> Email :</label>
                            <p>
                                <?php 
                                if (!empty($profil['cpt_mail'])) {
                                    echo htmlspecialchars($profil['cpt_mail']);
                                } else {
                                    echo htmlspecialchars($_SESSION['login']);
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Nom -->
                        <div class="info-item">
                            <label><i class="fas fa-user"></i> Nom :</label>
                            <p>
                                <?php 
                                if (!empty($profil['pro_nom'])) {
                                    echo htmlspecialchars($profil['pro_nom']);
                                } else {
                                    echo "Non renseigné";
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Prénom -->
                        <div class="info-item">
                            <label><i class="fas fa-user"></i> Prénom :</label>
                            <p>
                                <?php 
                                if (!empty($profil['pro_prenom'])) {
                                    echo htmlspecialchars($profil['pro_prenom']);
                                } else {
                                    echo "Non renseigné";
                                }
                                ?>
                            </p>
                        </div>

                        <!-- Statut / Rôle -->
                        <div class="info-item">
                            <label><i class="fas fa-tag"></i> Statut :</label>
                            <p>
                                <?php 
                               
                                    if ($_SESSION['role'] === 'A') {
                                        echo '<span class="status-admin">Administrateur</span>';
                                    } else {
                                        echo '<span class="status-respo">Responsable</span>';
                                    }
                
                                ?>
                            </p>
                        </div>
                     <div class="info-item">
                            <label><i class="fas fa-user"></i> Etat :</label>
                            <p>
                                <?php 
                                   if ($_SESSION['login'] === ADMIN_EMAIL) {
                                    echo htmlspecialchars($profil['pro_validite']='Acitve');
                               
                                   } else {
                                    echo htmlspecialchars($profil['pro_validite']);
                                   }
                                ?>
                            </p>
                        </div>
                        <!-- Date d'inscription -->
                        <div class="info-item">
                            <label><i class="fas fa-calendar-alt"></i> Date d'inscription :</label>
                            <p>
                                <?php                      
                                if (!empty($profil['pro_date_de_creation'])) {
                                    echo htmlspecialchars($profil['pro_date_de_creation']);
                                } else {
                                    echo "Non renseignée";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>
</html>