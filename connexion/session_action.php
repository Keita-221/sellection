<?php
session_start();

if (!empty($_POST["pseudo"]) && !empty($_POST["mdp"])) {
    $id = htmlspecialchars($_POST["pseudo"]);
    $motdepasse = htmlspecialchars($_POST["mdp"]);

    // Connexion à la BDD
    require_once __DIR__ . '/../config/db.php';
    if ($mysqli->connect_errno) {
        header("Location: session.php?erreur=Erreur de connexion à la base de données");
        exit();
    }
    mysqli_report(MYSQLI_REPORT_OFF);

    // Requête de vérification
    $sql = "SELECT *
            FROM t_compte_cpt 
            LEFT JOIN t_profil_pro USING (cpt_mail)
            WHERE cpt_mail='" . $id . "'
            AND cpt_mot_de_passe=MD5('" . $motdepasse . "')
            AND (pro_validite='A' OR cpt_mail='" . ADMIN_EMAIL . "')";

    $resultat = $mysqli->query($sql);
    
    if ($resultat == false) {
        header("Location: session.php?erreur=Erreur lors de l'accès à la base de données");
        exit();
    }

    if ($resultat->num_rows == 1) {
        $ligne = $resultat->fetch_assoc();
        $_SESSION['login'] = $id;
        
        if ($_SESSION['login'] == ADMIN_EMAIL) {
            $_SESSION['role'] = 'A';
        } else {
            $_SESSION['role'] = $ligne['pro_statut'];
        }
        
        header("Location: ./admin_acceuill.php");
        exit();
    } else {
        // Message d'erreur plus précis
        $erreur = "Identifiants ou mot de passe incorrects ";
        header("Location: session.php?erreur=" . urlencode($erreur));
        exit();
    }
    
    $mysqli->close();
} else {
    // Cas où les champs sont vides
    header("Location: session.php?erreur=Veuillez remplir tous les champs");
    exit();
}
?>