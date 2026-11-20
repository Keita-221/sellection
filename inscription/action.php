<?php

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

// ÉTAPE 1 — Vérification que tous les champs obligatoires sont bien remplis
if (
    !empty($_POST["code_inscription"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["mot_de_passe"]) &&
    !empty($_POST["mot_de_passe_confirm"]) &&
    !empty($_POST["pro_nom"]) &&
    !empty($_POST["pro_prenom"])
) {
    // Récupération des données
    $code = $mysqli->real_escape_string($_POST["code_inscription"]);
    $email = $mysqli->real_escape_string($_POST["email"]);
    $mdp = $_POST["mot_de_passe"];
    $mdp_confirm = $_POST["mot_de_passe_confirm"];
    $pro_nom = $mysqli->real_escape_string($_POST["pro_nom"]);
    $pro_prenom = $mysqli->real_escape_string($_POST["pro_prenom"]);

    $erreur_message = "";

    // ÉTAPE 2 — Vérification mot de passe
    if (strcmp($mdp, $mdp_confirm) !== 0) {
        $erreur_message = "Les deux mots de passe saisis ne correspondent pas.";
    } elseif (strlen($mdp) < 10) {
        $erreur_message = "Le mot de passe doit contenir au moins 10 caractères.";
    } else {
        // ÉTAPE 3 — Vérification code de validation (colonne réelle : pre_code_validation)
        $result_code = $mysqli->query("SELECT pre_code_validation FROM t_presentation_pre WHERE pre_code_validation = '$code'");

        if ($result_code->num_rows == 0) {
            $erreur_message = "Le code de validation est incorrect.";
        } else {
            // ÉTAPE 4 — Vérification email unique
            $result_email = $mysqli->query("SELECT cpt_mail FROM t_compte_cpt WHERE cpt_mail = '$email'");
            if ($result_email->num_rows > 0) {
                $erreur_message = "Cette adresse email est déjà utilisée. Veuillez en choisir une autre.";
            }
        }
    }

    // ÉTAPE 5 — Redirection si erreur vers la page d'erreur
    if (!empty($erreur_message)) {
        // Redirection vers la page d'erreur avec les données pour pré-remplir le formulaire
        $error_data = array(
            'erreur' => $erreur_message,
            'pro_nom' => $pro_nom,
            'pro_prenom' => $pro_prenom,
            'email' => $email,
            'code_inscription' => $code
        );
        header("Location: erreur_inscription.php?" . http_build_query($error_data));
        exit();
    } else {
        $mdp_hash = md5($mdp);

        // INSERT dans t_compte_cpt
        $sql1 = "INSERT INTO t_compte_cpt (cpt_mail, cpt_mot_de_passe) 
                 VALUES ('$email', '$mdp_hash')";

        if ($mysqli->query($sql1) === true) {

            $sql2 = "INSERT INTO t_profil_pro (cpt_mail, pro_nom, pro_prenom, pro_statut, pro_validite, pro_date_de_creation) 
                     VALUES ('$email', '$pro_nom', '$pro_prenom', 'R', 'D', CURDATE())";

            if ($mysqli->query($sql2)) {
                $mysqli->close();
                ?>
                <!DOCTYPE html>
                <html lang="fr">

                <head>
                    <meta charset="UTF-8">
                    <title>Inscription réussie</title>
                    <link href="../css/bootstrap.min.css" rel="stylesheet" />
                    <link href="../fontawesome/css/all.min.css" rel="stylesheet" />
                    <style>
                        body {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            min-height: 100vh;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-family: 'Open Sans', sans-serif;
                        }
                        .success-card {
                            background: white;
                            border-radius: 15px;
                            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                            padding: 40px;
                            max-width: 500px;
                            text-align: center;
                        }
                        .success-icon {
                            font-size: 64px;
                            color: #28a745;
                            margin-bottom: 20px;
                        }
                        .btn-primary-custom {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border: none;
                            padding: 10px 30px; 
                            border-radius: 8px;
                            color: white;
                            text-decoration: none;
                            display: inline-block;
                            margin-top: 20px;
                            transition: transform 0.3s ease;
                        }
                        .btn-primary-custom:hover {
                            transform: translateY(-2px);
                            color: white;
                            text-decoration: none;
                        }
                        .info-text {
                            background: #f8f9fa;
                            padding: 15px;
                            border-radius: 8px;
                            margin: 20px 0;
                            text-align: left;
                        }
                    </style>
                </head>

                <body>
                    <div class="success-card">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Inscription réussie !</h2>
                        <p>Votre compte a été créé avec succès.</p>
                        
                        <div class="info-text">
                            <strong><i class="fas fa-user mr-2"></i>Informations du compte :</strong><br>
                            <i class="fas fa-envelope mr-2"></i>Email : <?= htmlspecialchars($email) ?><br>
                            <i class="fas fa-user mr-2"></i>Nom : <?= htmlspecialchars($pro_nom) ?><br>
                            <i class="fas fa-user mr-2"></i>Prénom : <?= htmlspecialchars($pro_prenom) ?>
                        </div>
                        
                        <a href="../index.php" class="btn-primary-custom">
                            <i class="fas fa-home mr-2"></i> Retour à l'accueil
                        </a>
                    </div>
                </body>

                </html>
                <?php
                exit();
            } else {
                $mysqli->query("DELETE FROM t_compte_cpt WHERE cpt_mail = '$email'");
                $erreur_message = "Erreur lors de la création du profil. Opération annulée.";
                $error_data = array(
                    'erreur' => $erreur_message,
                    'pro_nom' => $pro_nom,
                    'pro_prenom' => $pro_prenom,
                    'email' => $email,
                    'code_inscription' => $code
                );
                header("Location: erreur_inscription.php?" . http_build_query($error_data));
                exit();
            }
        } else {
            $erreur_message = "Erreur lors de la création du compte.";
            $error_data = array(
                'erreur' => $erreur_message,
                'pro_nom' => $pro_nom,
                'pro_prenom' => $pro_prenom,
                'email' => $email,
                'code_inscription' => $code
            );
            header("Location: erreur_inscription.php?" . http_build_query($error_data));
            exit();
        }
    }
} else {
    $erreur_message = "Tous les champs doivent être remplis.";
    $error_data = array(
        'erreur' => $erreur_message,
        'pro_nom' => isset($_POST["pro_nom"]) ? $_POST["pro_nom"] : '',
        'pro_prenom' => isset($_POST["pro_prenom"]) ? $_POST["pro_prenom"] : '',
        'email' => isset($_POST["email"]) ? $_POST["email"] : '',
        'code_inscription' => isset($_POST["code_inscription"]) ? $_POST["code_inscription"] : ''
    );
    header("Location: erreur_inscription.php?" . http_build_query($error_data));
    exit();
}


$mysqli->close();
?>