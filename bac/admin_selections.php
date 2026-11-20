<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location:../connexion/session.php");
    exit();
}

require_once __DIR__ . '/../config/db.php';
if ($mysqli->connect_errno) { exit("Erreur connexion BDD"); }
$mysqli->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);

$sql_sel = "SELECT * FROM t_selection_sel";
$result  = $mysqli->query($sql_sel);

// Récupération des informations de la structure
$result_structure = $mysqli->query("SELECT * FROM t_presentation_pre LIMIT 1");
$structure = $result_structure->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des sélections - <?= htmlspecialchars($structure['pre_nom_structure']) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Open+Sans" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/admin-selections.css" rel="stylesheet" />
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
                    <i class="fas fa-list-ul"></i> Gestion des sélections
                </h2>
                <hr>
            </div>
        </div>
    </div>

    <!-- Message de bienvenue -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="welcome-box">
                <div class="welcome-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="welcome-text">
                    <h4>Bonjour, <?= htmlspecialchars($_SESSION['login']) ?> !</h4>
                    <p>Gérez les sélections de services</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-6 col-md-8">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-plus-circle"></i> Ajouter une sélection
                </div>
                <div class="form-card-body">
                    <form action="selections_action.php" method="post">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Intitulé
                            </label>
                            <input type="text" name="sel_intitule" class="form-control-modern" placeholder="Entrez l'intitulé" required>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i> Texte introductif
                            </label>
                            <input type="text" name="sel_texte" class="form-control-modern" placeholder="Entrez le texte" required>
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Ajouter la sélection
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de suppression -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-6 col-md-8">
            <div class="form-card delete-card">
                <div class="form-card-header delete-header">
                    <i class="fas fa-trash-alt"></i> Supprimer une sélection
                </div>
                <div class="form-card-body">
                    <form action="selections_action.php" method="post">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-list"></i> Sélection à supprimer
                            </label>
                            <select name="id_a_supprimer" class="form-control-modern">
                                <?php
                                $result_select = $mysqli->query("SELECT sel_id, sel_intitule FROM t_selection_sel");
                                if ($result_select && $result_select->num_rows > 0) {
                                    while ($opt = $result_select->fetch_assoc()) {
                                        echo '<option value="' . intval($opt['sel_id']) . '">'
                                             . htmlspecialchars($opt['sel_intitule'])
                                             . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Aucune sélection</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des sélections -->
    <?php if ($result && $result->num_rows > 0) { ?>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Auteur</th>
                                <th><i class="fas fa-heading"></i> Intitulé sélection</th>
                                <th><i class="fas fa-align-left"></i> Texte introductif</th>
                                <th><i class="fas fa-cubes"></i> Éléments</th>
                                <th><i class="fas fa-toggle-on"></i> État</th>
                                <th><i class="fas fa-calendar-alt"></i> Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result->data_seek(0);
                        while ($res = $result->fetch_assoc()) {
                            $id_sel  = intval($res['sel_id']);
                            $sql_elt = "SELECT elt_intitule 
                                        FROM t_element_elt 
                                        JOIN t_rassemblement_ras USING (elt_id) 
                                        WHERE sel_id = " . $id_sel;
                            $res_elt = $mysqli->query($sql_elt);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($res['sel_id']) ?></td>
                                <td><strong><?= htmlspecialchars($res['cpt_mail']) ?></strong></td>
                                <td><?= htmlspecialchars($res['sel_intitule']) ?></td>
                                <td><?= htmlspecialchars($res['sel_texte_introductif']) ?></td>
                                <td>
                                    <?php
                                    if ($res_elt && $res_elt->num_rows > 0) {
                                        while ($elt = $res_elt->fetch_assoc()) {
                                            echo '<span class="element-badge">' . htmlspecialchars($elt['elt_intitule']) . '</span>';
                                        }
                                    } else {
                                        echo "<em class='text-muted'>Aucun élément</em>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($res['sel_etat'] == 'A') { ?>
                                        <span class="badge-valide actif"><i class="fas fa-check-circle"></i> Actif</span>
                                    <?php } else { ?>
                                        <span class="badge-valide inactif"><i class="fas fa-times-circle"></i> Inactif</span>
                                    <?php } ?>
                                </td>
                                <td><?= htmlspecialchars($res['sel_date_ajoute']) ?></td>
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
                    Aucune sélection disponible pour le moment.
                </div>
            </div>
        </div>
        
       

    
</div>
</div>
    <?php } ?>
    <<div class="row mt-4">
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
<?php $mysqli->close(); ?>