<?php
// Initialisation
session_start();
$formulaire_actif = isset($_GET['formulaire']) ? $_GET['formulaire'] : 'inscription';
$texte_reponses = '';

// Traitement des données POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texte_reponses = afficher_donnees($_POST);
    // --- CODE D'ENVOI D'EMAIL ---
    if (isset($_POST['date_debut'])) {
        $destinataire = "squookyetest@gmail.com";
        $sujet = "Formulaire de Demande de Surveillance";
        
        $message = "Nouvelle demande reçue :\n\n";
        foreach ($_POST as $cle => $valeur) {
            $message .= $cle . " : " . $valeur . "\n";
        }
        
        $envoi_reussi = mail($destinataire, $sujet, $message);

        if ($envoi_reussi) {
            $texte_reponses .= '<div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin-top: 20px; border-radius: 4px;">';
            $texte_reponses .= '<strong>✅ Succès !</strong> Votre demande de surveillance a bien été envoyée à nos services.';
            $texte_reponses .= '</div>';
        } else {
            $texte_reponses .= '<div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-top: 20px; border-radius: 4px;">';
            $texte_reponses .= '<strong>❌ Erreur :</strong> Un problème est survenu lors de l\'envoi de votre demande. Veuillez réessayer plus tard.';
            $texte_reponses .= '</div>';
        }
    }
}

function afficher_donnees($donnees) {
    $html = '<div style="border: 2px solid green; padding: 15px; margin: 20px 0;">';
    $html .= '<h2>✓ Données reçues :</h2>';
    $html .= '<ul>';
    foreach ($donnees as $cle => $valeur) {
        $cle_affichage = htmlspecialchars($cle);
        $valeur_affichage = htmlspecialchars($valeur);
        $html .= "<li><strong>$cle_affichage :</strong> $valeur_affichage</li>";
    }
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

function formulaire_inscription() {
    return '
    <form method="POST">
        <fieldset>
            <legend>Création d\'un compte</legend>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br><br>
            
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required><br><br>
            
            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" required><br><br>
            
            <label for="tel">Téléphone :</label>
            <input type="tel" id="tel" name="tel" pattern="[0-9]{10}" placeholder="10 chiffres" required><br><br>
            
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" minlength="8" required><br><br>
            
            <label for="mdp_conf">Confirmation du mot de passe :</label>
            <input type="password" id="mdp_conf" name="mdp_conf" minlength="8" required><br><br>
            
            <button type="submit">Créer le compte</button>
            <button type="reset">Annuler</button>
        </fieldset>
    </form>
    ';
}

function formulaire_connexion() {
    return '
    <form method="POST">
        <fieldset>
            <legend>Connexion</legend>
            <label for="email_co">Email :</label>
            <input type="email" id="email_co" name="email" required><br><br>
            
            <label for="mdp_co">Mot de passe :</label>
            <input type="password" id="mdp_co" name="mdp" required><br><br>
            
            <button type="submit">Se connecter</button>
            <button type="reset">Annuler</button>
        </fieldset>
    </form>
    ';
}

function formulaire_demande() {
    return '
    <form method="POST">
        <fieldset>
            <legend>Demande de surveillance (Opération Tranquillité - Vacances)</legend>
            
            <label for="date_debut">Date de début d\'absence :</label>
            <input type="date" id="date_debut" name="date_debut" required><br><br>
            
            <label for="date_fin">Date de fin d\'absence :</label>
            <input type="date" id="date_fin" name="date_fin" required><br><br>
            
            <label for="contact">Personne à contacter :</label>
            <input type="text" id="contact" name="contact" required><br><br>
            
            <label for="tel_contact">Téléphone du contact :</label>
            <input type="tel" id="tel_contact" name="tel_contact" pattern="[0-9]{10}" required><br><br>
            
            <label for="type_logement">Type de logement :</label>
            <select id="type_logement" name="type_logement" required>
                <option value="">-- Choisir --</option>
                <option value="maison">Maison</option>
                <option value="appartement">Appartement</option>
                <option value="commerce">Commerce</option>
            </select><br><br>
            
            <label for="alarme">Existe-t-il un dispositif d\'alarme ?</label>
            <select id="alarme" name="alarme" required>
                <option value="">-- Choisir --</option>
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select><br><br>
            
            <label for="renseignements">Renseignements particuliers :</label>
            <textarea id="renseignements" name="renseignements" rows="4" cols="40"></textarea><br><br>
            
            <button type="submit">Envoyer la demande</button>
            <button type="reset">Annuler</button>
        </fieldset>
    </form>
    ';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opération Tranquillité - Vacances</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #003399;
            margin-bottom: 30px;
            text-align: center;
        }
        .menu {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .menu a {
            padding: 10px 15px;
            background-color: #003399;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .menu a:hover, .menu a.active {
            background-color: #cc0000;
        }
        fieldset {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        legend {
            padding: 0 10px;
            font-weight: bold;
            color: #003399;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #003399;
            box-shadow: 0 0 5px #003399;
        }
        button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        button[type="submit"] {
            background-color: #003399;
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #cc0000;
        }
        button[type="reset"] {
            background-color: #ccc;
            color: black;
        }
        button[type="reset"]:hover {
            background-color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Opération Tranquillité - Vacances</h1>
        
        <div class="menu">
            <a href="tp9_a.php?formulaire=inscription" class="<?php echo ($formulaire_actif === 'inscription') ? 'active' : ''; ?>">
                Créer un compte
            </a>
            <a href="tp9_a.php?formulaire=connexion" class="<?php echo ($formulaire_actif === 'connexion') ? 'active' : ''; ?>">
                Se connecter
            </a>
            <a href="tp9_a.php?formulaire=demande" class="<?php echo ($formulaire_actif === 'demande') ? 'active' : ''; ?>">
                Demande de surveillance
            </a>
        </div>

        <?php
        // Affichage du formulaire approprié
        switch ($formulaire_actif) {
            case 'inscription':
                echo formulaire_inscription();
                break;
            case 'connexion':
                echo formulaire_connexion();
                break;
            case 'demande':
                echo formulaire_demande();
                break;
            default:
                echo formulaire_inscription();
        }
        ?>

        <?php
        // Affichage des données si le formulaire a été soumis
        if (!empty($texte_reponses)) {
            echo $texte_reponses;
        }
        ?>
    </div>
</body>
</html>
