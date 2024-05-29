<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
require_once '../class/feedback.php';

$feedback = new Feedback($conn);
$feedback->delete();
