<?php
session_start(); // ← manquait

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location:../connexion/session.php");
    exit();
}

// Connexion BDD
require_once __DIR__ . '/../config/db.php';
if ($mysqli->connect_errno) {
    exit("Erreur connexion BDD");
}
$mysqli->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);

// ============================================
// CAS 1 : AJOUT d'une sélection
// ← séparé du cas suppression
// ============================================
if (!empty($_POST["sel_intitule"]) && !empty($_POST["sel_texte"])) {

    $intitule = htmlspecialchars($_POST["sel_intitule"]);
    $texte    = htmlspecialchars($_POST["sel_texte"]);

    // Vérifier si elle existe déjà
    $verif = "SELECT * FROM t_selection_sel 
              WHERE sel_intitule = '" . $intitule . "' 
              AND sel_texte_introductif = '" . $texte . "'";
    $res = $mysqli->query($verif);

    if ($res->num_rows > 0) {
        echo "Cette sélection existe déjà, veuillez en entrer une autre.";
        exit();
    } else {
        // ← t_selection_sel corrigé (était t_slection_sel)
        // ← virgule après NULL ajoutée
        // ← NOW() sans guillemets (était 'Now()')
        $sql_ajoute = "INSERT INTO t_selection_sel
                           (sel_id, cpt_mail, sel_intitule, sel_texte_introductif, sel_date_ajoute, sel_etat)
                       VALUES 
                           (NULL, '" . $_SESSION['login'] . "', '" . $intitule . "', '" . $texte . "', NOW(), 'A')";

        if ($mysqli->query($sql_ajoute) === true) {
            header("Location:admin_selections.php"); // ← Location: corrigé
            exit();
        } else {
            echo "Erreur lors de l'ajout : " . $mysqli->error;
            exit();
        }
    }
}

// ============================================
// CAS 2 : SUPPRESSION d'une sélection
// ← séparé du cas ajout
// ============================================
elseif (!empty($_POST["id_a_supprimer"])) {

    $sel = intval($_POST["id_a_supprimer"]); // intval pour sécuriser

    // Étape 1 : supprimer les liens dans t_rassemblement_ras
    // ← deux requêtes séparées (pas en une seule)
    $sql_del_ras = "DELETE FROM t_rassemblement_ras WHERE sel_id = " . $sel;
    $mysqli->query($sql_del_ras);

    // Étape 2 : supprimer la sélection elle-même
    $sql_del_sel = "DELETE FROM t_selection_sel WHERE sel_id = " . $sel;
    $del = $mysqli->query($sql_del_sel);

    // ← $del retourne true/false, pas num_rows
    if ($del === true) {
        header("Location:admin_selections.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $mysqli->error;
        exit();
    }
}

// ============================================
// CAS 3 : aucun champ rempli → retour
// ============================================
else {
    header("Location:admin_selections.php");
    exit();
}

$mysqli->close();
?>