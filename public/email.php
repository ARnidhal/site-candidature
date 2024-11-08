<?php
$to = 'arfaouinidhal77@gmail.com'; // Replace with the recipient's email
$subject = 'Test Email';
$message = 'This is a test email from a PHP script!';
$headers = 'From: ghazouani4444@gmail.com' . "\r\n" .
           'Reply-To: ghazouani4444@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully';
} else {
    echo 'Email not sent';
}
?>
