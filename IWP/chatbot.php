<?php
// Get the user input
if (isset($_POST['user_input'])) {
    $user_input = trim($_POST['user_input']);
    
    // Simple responses based on user input
    $responses = [
        'hi' => 'Hello! How can I assist you today?',
        'hello' => 'Hi there! What can I help you with?',
        'price' => 'You can check the prices of artworks on our collection page.',
        'artist' => 'We have several featured artists. Would you like to learn more about them?',
        'artwork' => 'You can explore all available artworks in our gallery.',
        'artworks' => 'You can explore all available artworks in our gallery.'

    ];

    // Default response if the input is not recognized
    $default_response = 'Sorry, I didn\'t understand that. Can you please rephrase?';

    // Check if the user input matches any of the predefined responses
    $response = isset($responses[strtolower($user_input)]) ? $responses[strtolower($user_input)] : $default_response;

    echo $response; // Return the response to the frontend
}
?>
