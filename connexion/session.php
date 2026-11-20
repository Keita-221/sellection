<!DOCTYPE html>
<html lang="fr">
<?php
session_start();

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
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= htmlspecialchars($structure['pre_nom_structure']) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/connexion.css" rel="stylesheet" />
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
                            <li class="nav-item active">
                                <a class="nav-link tm-nav-link" href="session.php">
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
                    <img src="../ressources/<?= htmlspecialchars($structure['pre_img']) ?>" height="100" alt="Logo">
                </a>
                <p class="lead mt-3">Connectez-vous à votre compte</p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-7">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            
            <div class="connexion-card">
                <div class="connexion-header">
                    <div class="connexion-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2>Connexion</h2>
                    <p>Accédez à votre espace personnel</p>
                </div>
                
                <div class="connexion-body">
                    <?php if (isset($_GET['erreur'])){ ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= htmlspecialchars($_GET['erreur']) ?>
                        </div>
                    <?php } ?>
                    
                    <?php if (isset($_GET['deconnexion'])){?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle mr-2"></i>
                            Vous avez été déconnecté avec succès.
                        </div>
                    <?php } ?>
                    
                    <form action="session_action.php" method="post">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Pseudo / Email
                            </label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="text" name="pseudo" placeholder="Votre pseudo ou email" class="form-control-modern" required>
                            </div>
                        </div>
                        
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-key"></i> Mot de passe
                            </label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="mdp" placeholder="Votre mot de passe" class="form-control-modern" required>
                                <span class="toggle-password" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-connexion">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </form>
                </div>
                
                <div class="connexion-footer">
                    <p>Pas encore de compte ? 
                        <a href="../inscription/inscription.php">
                            <i class="fas fa-user-plus"></i> Inscrivez-vous
                        </a>
                    </p>
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

<script>
function togglePassword(element) {
    const input = element.parentElement.querySelector('input');
    const icon = element.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php $mysqli->close(); ?>
</body>
</html>