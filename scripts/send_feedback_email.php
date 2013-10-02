<?php
$name = $_POST["contact_name"];
$email = $_POST["contact_email"];
$message = $_POST["contact_message"];
$subject = "Website Feedback";

$headers = 'From: '. $email . "\r\n" .
           'Reply-To: '. $email . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

mail('jdherman8@gmail.com', $subject, $message, $headers);

header("Location: http://google.com");
?>
