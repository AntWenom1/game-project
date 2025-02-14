<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require '../includes/chat_functions.php';

$storyId = 1; // Default story (change later)
$firstMessage = getFirstMessage($storyId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../assets/css/chat.css">
</head>
<body>
    <div class="chat-container">
        <div id="chat-box">
            <div class="bot-message"><?php echo $firstMessage['message']; ?></div>
        </div>
        <div id="choices"></div>
    </div>

    <script>
        function fetchChoices(conversationId) {
            fetch(`get_choices.php?conversation_id=${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    let choicesContainer = document.getElementById('choices');
                    choicesContainer.innerHTML = '';
                    data.forEach(choice => {
                        let button = document.createElement('button');
                        button.innerText = choice.text;
                        button.onclick = () => sendMessage(choice.id, choice.text);
                        choicesContainer.appendChild(button);
                    });
                });
        }

        function sendMessage(choiceId, userMessage) {
            let chatBox = document.getElementById('chat-box');
            
            // Add user message
            let userDiv = document.createElement('div');
            userDiv.classList.add('user-message');
            userDiv.innerText = userMessage;
            chatBox.appendChild(userDiv);

            // Show typing indicator
            let typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.innerText = "Simulated Person is typing...";
            chatBox.appendChild(typingDiv);

            // Fetch bot response
            fetch(`get_next_message.php?choice_id=${choiceId}`)
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        document.getElementById('typing-indicator').remove();
                        let botDiv = document.createElement('div');
                        botDiv.classList.add('bot-message');
                        botDiv.innerText = data.message;
                        chatBox.appendChild(botDiv);
                        fetchChoices(data.id);
                    }, data.typing_delay * 1000);
                });
        }

        fetchChoices(<?php echo $firstMessage['id']; ?>);
    </script>
</body>
</html>
