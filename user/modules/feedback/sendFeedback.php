<?php
require_once '../class/feedback.php';
if (!isset($_SESSION['tokenlogin'])) {
    header("Location: ../user/?module=authen&action=login");
    exit;
}
$feedback = new Feedback($conn);
$feedback->sendFeedback($dataSend);

