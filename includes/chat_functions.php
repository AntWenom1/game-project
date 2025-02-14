<?php
require 'config.php';

/**
 * Get the first message of a conversation
 */
function getFirstMessage($storyId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM conversations WHERE story_id = ? ORDER BY id ASC LIMIT 1");
    $stmt->execute([$storyId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get choices for a conversation message
 */
function getChoices($conversationId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM choices WHERE conversation_id = ?");
    $stmt->execute([$conversationId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get the next message based on user choice
 */
function getNextMessage($choiceId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT conversations.* FROM choices 
                          JOIN conversations ON choices.leads_to = conversations.id 
                          WHERE choices.id = ?");
    $stmt->execute([$choiceId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
