<?php
require '../includes/chat_functions.php';

if (isset($_GET['conversation_id'])) {
    $choices = getChoices($_GET['conversation_id']);
    echo json_encode($choices);
}
?>
