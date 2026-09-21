<?php
/*

// Script sans PHPMailer

// =====================================================
// CONFIGURATION
// =====================================================

$destinataire = "contact@batimaj.fr";

// Adresse utilisée par le serveur pour envoyer le mail
$expediteur = "contact@batimaj.fr";


// =====================================================
// VÉRIFICATION DE LA MÉTHODE
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}


// =====================================================
// RÉCUPÉRATION DES DONNÉES
// =====================================================

$nom = trim($_POST["name"] ?? "");
$prenom = trim($_POST["firstname"] ?? "");
$email = trim($_POST["email"] ?? "");
$telephone = trim($_POST["phone"] ?? "");
$projet = trim($_POST["project"] ?? "");
$message = trim($_POST["message"] ?? "");


// =====================================================
// VALIDATION
// =====================================================

if (
    empty($nom) ||
    empty($prenom) ||
    empty($email) ||
    empty($message)
) {
    die("Veuillez remplir tous les champs obligatoires.");
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("L'adresse email renseignée n'est pas valide.");
}


// =====================================================
// SÉCURISATION
// =====================================================

$nom = htmlspecialchars($nom, ENT_QUOTES, "UTF-8");
$prenom = htmlspecialchars($prenom, ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
$telephone = htmlspecialchars($telephone, ENT_QUOTES, "UTF-8");
$projet = htmlspecialchars($projet, ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");


// =====================================================
// CONTENU DU MAIL
// =====================================================

$sujet = "Nouvelle demande de devis - BATIMAJ";

$contenu = "

Nouvelle demande de devis depuis le site BATIMAJ

--------------------------------------------

CLIENT

Nom : $nom
Prénom : $prenom
Email : $email
Téléphone : $telephone

--------------------------------------------

PROJET

Type de projet : $projet

--------------------------------------------

MESSAGE

$message

--------------------------------------------

Message envoyé depuis le site BATIMAJ.
";


// =====================================================
// EN-TÊTES
// =====================================================

$headers = [];

$headers[] = "From: BATIMAJ <$expediteur>";
$headers[] = "Reply-To: $email";
$headers[] = "Content-Type: text/plain; charset=UTF-8";


// =====================================================
// ENVOI
// =====================================================

$envoye = mail(
    $destinataire,
    $sujet,
    $contenu,
    implode("\r\n", $headers)
);


// =====================================================
// RÉSULTAT
// =====================================================

if ($envoye) {

    header("Location: index.html?devis=envoye");
    exit;

} else {

    header("Location: index.html?devis=erreur");
    exit;
}
*/

// Script avec PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';


// =====================================================
// CONFIGURATION
// =====================================================

// Adresse BATIMAJ qui recevra les demandes
$destinataire = 'contact@batimaj.fr';

// Adresse utilisée pour envoyer les mails
$adresseEnvoi = 'contact@batimaj.fr';

// Serveur SMTP fourni par o2switch
// À remplacer par le serveur indiqué dans ton espace o2switch
$serveurSMTP = 'xxxxxxxx.o2switch.net';

// Identifiant de la boîte mail
$identifiantSMTP = 'contact@batimaj.fr';

// MOT DE PASSE SMTP
// Ne mets pas ton vrai mot de passe ici avant la mise en ligne.
$motDePasseSMTP = 'MOT_DE_PASSE_ICI';


// =====================================================
// VÉRIFICATION
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}


// =====================================================
// RÉCUPÉRATION DU FORMULAIRE
// =====================================================

$nom = trim($_POST['name'] ?? '');
$prenom = trim($_POST['firstname'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['phone'] ?? '');
$projet = trim($_POST['project'] ?? '');
$message = trim($_POST['message'] ?? '');


// =====================================================
// VALIDATION
// =====================================================

if (
    empty($nom) ||
    empty($prenom) ||
    empty($email) ||
    empty($message)
) {
    die('Veuillez remplir tous les champs obligatoires.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('L’adresse email renseignée est invalide.');
}


// =====================================================
// NETTOYAGE
// =====================================================

$nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
$prenom = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$telephone = htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8');
$projet = htmlspecialchars($projet, ENT_QUOTES, 'UTF-8');
$message = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));


// =====================================================
// PHPMailer
// =====================================================

$mail = new PHPMailer(true);

try {

    // -------------------------------------------------
    // SMTP O2SWITCH
    // -------------------------------------------------

    $mail->isSMTP();

    $mail->Host = $serveurSMTP;

    $mail->SMTPAuth = true;

    $mail->Username = $identifiantSMTP;

    $mail->Password = $motDePasseSMTP;

    // SMTP sécurisé o2switch
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->Port = 465;


    // -------------------------------------------------
    // EXPÉDITEUR
    // -------------------------------------------------

    $mail->setFrom(
        $adresseEnvoi,
        'BATIMAJ - Site internet'
    );


    // -------------------------------------------------
    // DESTINATAIRE
    // -------------------------------------------------

    $mail->addAddress(
        $destinataire,
        'BATIMAJ'
    );


    // -------------------------------------------------
    // RÉPONSE AU CLIENT
    // -------------------------------------------------

    $mail->addReplyTo(
        $email,
        $prenom . ' ' . $nom
    );


    // -------------------------------------------------
    // EMAIL
    // -------------------------------------------------

    $mail->CharSet = 'UTF-8';

    $mail->isHTML(true);

    $mail->Subject = 'Nouvelle demande de devis - BATIMAJ';


    $mail->Body = "

        <h2>Nouvelle demande de devis</h2>

        <hr>

        <h3>Informations du client</h3>

        <p>
            <strong>Nom :</strong> $nom<br>
            <strong>Prénom :</strong> $prenom<br>
            <strong>Email :</strong> $email<br>
            <strong>Téléphone :</strong> $telephone
        </p>

        <h3>Projet</h3>

        <p>
            <strong>Type :</strong> $projet
        </p>

        <h3>Message</h3>

        <p>
            $message
        </p>

        <hr>

        <p>
            Demande envoyée depuis le site internet BATIMAJ.
        </p>

    ";


    // Version texte pour les clients mail qui n'affichent pas HTML
    $mail->AltBody =

        "Nouvelle demande de devis BATIMAJ\n\n" .

        "Nom : $nom\n" .
        "Prénom : $prenom\n" .
        "Email : $email\n" .
        "Téléphone : $telephone\n\n" .

        "Projet : $projet\n\n" .

        "Message :\n$message";


    // -------------------------------------------------
    // ENVOI
    // -------------------------------------------------

    $mail->send();


    // -------------------------------------------------
    // SUCCÈS
    // -------------------------------------------------

    header('Location: index.html?devis=envoye');

    exit;


} catch (Exception $e) {

    // -------------------------------------------------
    // ERREUR
    // -------------------------------------------------

    echo "Une erreur est survenue lors de l'envoi du message.";

    // À utiliser uniquement pendant les tests :
    // echo "<br><br>Erreur : " . $mail->ErrorInfo;

}
?>