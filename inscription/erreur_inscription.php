<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur d'inscription - Tech Repair Center Pro</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="../css/templatemo-business-oriented.css" rel="stylesheet" />
    <link href="../css/erreur.css" rel="stylesheet" />
    
</head>
<body>

<div class="error-container">
    <div class="error-card">
        <div class="error-header">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Oups ! Une erreur s'est produite</h2>
            <p>Veuillez corriger les erreurs ci-dessous pour continuer</p>
        </div>
        
        <div class="error-body">
            <?php
            // Récupération des données d'erreur
            $erreur_message = isset($_GET['erreur']) ? htmlspecialchars($_GET['erreur']) : "Une erreur inconnue est survenue.";
            $pro_nom = isset($_GET['pro_nom']) ? htmlspecialchars($_GET['pro_nom']) : '';
            $pro_prenom = isset($_GET['pro_prenom']) ? htmlspecialchars($_GET['pro_prenom']) : '';
            $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
            $code_inscription = isset($_GET['code_inscription']) ? htmlspecialchars($_GET['code_inscription']) : '';
            ?>
            
            <!-- Message d'erreur -->
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Erreur de validation</strong>
                <div class="error-message-text">
                    <?php echo $erreur_message; ?>
                </div>
            </div>
            
            <!-- Données saisies -->
            <div class="data-card">
                <div class="data-header">
                    <h6>
                        <i class="fas fa-info-circle"></i>
                        Données que vous avez saisies
                    </h6>
                </div>
                <div class="data-grid">
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-user"></i> Nom
                        </span>
                        <div class="data-value">
                            <?php echo !empty($pro_nom) ? $pro_nom : '<span class="text-muted"><i class="fas fa-minus-circle"></i> Non saisi</span>'; ?>
                        </div>
                    </div>
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-user"></i> Prénom
                        </span>
                        <div class="data-value">
                            <?php echo !empty($pro_prenom) ? $pro_prenom : '<span class="text-muted"><i class="fas fa-minus-circle"></i> Non saisi</span>'; ?>
                        </div>
                    </div>
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-envelope"></i> Email
                        </span>
                        <div class="data-value">
                            <?php echo !empty($email) ? $email : '<span class="text-muted"><i class="fas fa-minus-circle"></i> Non saisi</span>'; ?>
                        </div>
                    </div>
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-key"></i> Code d'inscription
                        </span>
                        <div class="data-value">
                            <?php echo !empty($code_inscription) ? '<i class="fas fa-lock"></i> ••••••' : '<span class="text-muted"><i class="fas fa-minus-circle"></i> Non saisi</span>'; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Boutons d'action -->
            <div class="action-buttons">
                <a href="inscription.php" class="btn-custom btn-outline-custom">
                    <i class="fas fa-plus-circle"></i>
                    Nouvelle inscription
                </a>
                <a href="javascript:history.back()" class="btn-custom btn-primary-custom">
                    <i class="fas fa-redo-alt"></i>
                    Réessayer
                </a>
            </div>
            
            <!-- Lien retour accueil -->
            <div class="home-link">
                <a href="../index.php">
                    <i class="fas fa-home"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

 <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/parallax.min.js"></script>
    <script src="../js/tooplate-script.js"></script>
</body>
</html>