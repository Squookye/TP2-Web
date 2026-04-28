<?php
session_start();

$host = 'localhost';
$dbname = 'tranquillite_vacances';
$user = 'root';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
 
if (isset($_POST['login_btn'])) {
    if ($_POST['user'] == "admin" && $_POST['pwd'] == "1234") {
        $_SESSION['admin'] = true;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: tp9_b.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Tranquillité Vacances</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        .login-box { background: white; padding: 20px; border-radius: 5px; width: 300px; margin: auto; }
    </style>
</head>
<body>

    <h1>Opération Tranquillité Vacances</h1>

    <?php if (!isset($_SESSION['admin'])): ?>
        <div class="login-box">
            <h3>Accès Administrateur</h3>
            <?php if(isset($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>
            <form method="POST">
                <p>Login : <input type="text" name="user" required></p>
                <p>Mdp : <input type="password" name="pwd" required></p>
                <button type="submit" name="login_btn">Se connecter</button>
            </form>
        </div>

    <?php else: ?>
        <p>Bienvenue, Admin | <a href="?logout">Déconnexion</a></p>
        <h2>Liste des demandes de surveillance</h2>

        <table>
            <thead>
                <tr>
                    <th>N° Demande</th>
                    <th>Client (Nom Prénom)</th>
                    <th>Dates (Début - Fin)</th>
                    <th>Contact d'urgence</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = $db->query("SELECT d.*, u.nom, u.prenom 
                                     FROM DEMANDE d 
                                     JOIN UTILISATEUR u ON d.id_utilisateur = u.id_utilisateur");
                
                while ($row = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_demande'] . "</td>";
                    echo "<td>" . $row['nom'] . " " . $row['prenom'] . "</td>";
                    echo "<td>Du " . $row['date_debut'] . " au " . $row['date_fin'] . "</td>";
                    echo "<td>" . $row['contact_nom'] . " (" . $row['contact_telephone'] . ")</td>";
                    echo "<td><button>Affecter Agent</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>