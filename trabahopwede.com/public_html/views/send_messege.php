<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));
    $subject = htmlspecialchars(trim($_POST["subject"] ?? 'Contact Message'));

    if (empty($name) || empty($email) || empty($message)) {
        header("Location: home (12).php?status=error#contact");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: home (12).php?status=error#contact");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'trabahopwede@gmail.com';
        $mail->Password   = 'evtfdycdecvxyydb'; // Use env vars in production
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('trabahopwede@gmail.com', 'Trabaho PWeDe Website');
        $mail->addAddress('trabahopwede@gmail.com');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);
        $mail->Subject = "Contact Form: $subject";
        $mail->Body    = "You received a new message from your website:\n\n"
                       . "Name: $name\n"
                       . "Email: $email\n\n"
                       . "Message:\n$message";

        $mail->send();

        // ✅ Redirect to contact section with success message
        header("Location: home (12).php?status=success#contact");
        exit;
    } catch (Exception $e) {
        // ❌ Redirect to contact section with error message
        header("Location: home (12).php?status=error#contact");
        exit;
    }
} else {
    // Invalid access to the script
    header("Location: home (12).php?status=error#contact");
    exit;
}
?>
