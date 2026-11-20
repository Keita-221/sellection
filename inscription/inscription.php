<!DOCTYPE html>
<html lang="fr">
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
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Tech Repair Center Pro</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="../css/templatemo-business-oriented.css" rel="stylesheet" />
    <link href="../css/inscription.css" rel="stylesheet" />
</head>
<body>

<div class="form-wrapper">
    <div class="form-card">
        <div class="form-header">
            <h2>
                <i class="fas fa-user-plus mr-2"></i>Créer un compte
            </h2>
            <p>Rejoignez Tech Repair Center Pro</p>
        </div>
        
        <div class="form-body">
            <?php
            if (!empty($_GET['erreur'])) {
                echo "<div class='alert alert-danger alert-custom'>";
                echo "<i class='fas fa-exclamation-circle mr-2'></i>";
                echo htmlspecialchars($_GET['erreur']);
                echo "</div>";
            }
            ?>

            <form action="action.php" method="post" novalidate>
                
               
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>Informations personnelles
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pro_nom">
                                Nom <span class="required-star">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="pro_nom" name="pro_nom"
                                    placeholder="Votre nom"  />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pro_prenom">
                                Prénom <span class="required-star">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="pro_prenom" name="pro_prenom"
                                    placeholder="Votre prénom"  />
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="separator">

               
                <div class="section-title">
                    <i class="fas fa-key"></i>Code d'inscription
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Code d'accès :</strong> Fourni par l'administrateur
                </div>

                <div class="form-group">
                    <label for="code_inscription">
                        Code d'inscription <span class="required-star">*</span>
                    </label>
                    <div class="password-group">
                        <input type="password" class="form-control" id="code_inscription" name="code_inscription"
                            placeholder="Entrez votre code d'inscription" />
                    </div>
                </div>

                <hr class="separator">

                <div class="section-title">
                    <i class="fas fa-sign-in-alt"></i>Identifiants de connexion
                </div>
                
                <div class="form-group">
                    <label for="email">
                        Adresse email <span class="required-star">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="votre@email.com" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mot_de_passe">
                                Mot de passe <span class="required-star">*</span>
                            </label>
                            <div class="password-group">
                                <input type="password" class="form-control" id="mot_de_passe"
                                    name="mot_de_passe" placeholder="10 caractères min"
                                    minlength="10" required />
                                <i class="fas fa-eye password-toggle" onclick="togglePassword('mot_de_passe', this)"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mot_de_passe_confirm">
                                Confirmation <span class="required-star">*</span>
                            </label>
                            <div class="password-group">
                                <input type="password" class="form-control" id="mot_de_passe_confirm"
                                    name="mot_de_passe_confirm" placeholder="Répétez" />
                                <i class="fas fa-eye password-toggle" onclick="togglePassword('mot_de_passe_confirm', this)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <small class="text-muted-small">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Mot de passe : minimum 10 caractères
                </small>

                <hr class="separator">
              
                <div class="d-flex justify-content-between align-items-center" style="margin-top: 30px;">
                    <a href="../index.php" class="btn btn-outline-custom btn-custom">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary-custom btn-custom">
                        <i class="fas fa-check mr-2"></i> S'inscrire
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

 <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/parallax.min.js"></script>
    <script src="../js/tooplate-script.js"></script>
<script>
function togglePassword(inputId, element) {
    const input = document.getElementById(inputId);
    
    if (input.type === "password") {
        input.type = "text";
        element.classList.remove("fa-eye");
        element.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        element.classList.remove("fa-eye-slash");
        element.classList.add("fa-eye");
    }
}


</script>

<?php $mysqli->close(); ?>
</body>
</html>