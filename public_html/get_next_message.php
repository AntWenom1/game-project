<?php
require '../includes/chat_functions.php';

if (isset($_GET['choice_id'])) {
    $nextMessage = getNextMessage($_GET['choice_id']);
    echo json_encode($nextMessage);
}
?>
