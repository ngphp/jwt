<?php

// without composer: require 'src\ngphp.php';

// with composer
require 'vendor/autoload.php';

use ngphp\JWT;

// Initialize the JWT handler with a secret key
$secret = 'your_secret_key';

$jwt = new JWT($secret);

// Example payload
$payload = [
    'user_id' => 1,
    'username' => 'john.doe',
    'email' => 'john.doe@example.com',
    'role' => 'admin',
    'iat' => time(),
    'exp' => time() + 3600 // 1 hour expiration
];

// Generate a JWT
$token = $jwt->generate($payload);
echo "Generated Token: " . $token . "<hr>";

// Simulate storing the token in a database
echo "Storing the token in the database...\n<br>\n<br>";
// Example of database storage logic (not implemented here)
// storeTokenInDatabase($user_id, $token);

// Example headers containing the JWT
$headers = [
    'Authorization' => 'Bearer ' . $token
];

// Authenticate the user using the token from the headers
echo "Authenticating User...\n<br>";
try {
    if ($jwt->authenticate($headers)) {
        $user = JWT::getUser();
        echo "Authenticated User: \n<br>";
        print_r($user);
        echo "\n<br>";
    }
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n<br>";
}

// Example of decoding the token directly
echo "Decoding the Token...\n<br>";
try {
    $decoded = $jwt->decode($token);
    echo "Decoded Token: \n<br>";
    print_r($decoded);
    echo "\n<br>";
} catch (\Exception $e) {
    echo "Decoding failed: " . $e->getMessage() . "\n<br>";
}

// Example output separation
echo "<hr><b>Example 1: Token Generation</b>\n<br>";
echo "Generated Token: " . $token . "\n";

echo "<hr><b>Example 2: Token Authentication</b>\n<br>";
try {
    if ($jwt->authenticate($headers)) {
        $user = JWT::getUser();
        echo "Authenticated User: \n<br>";
        print_r($user);
        echo "\n<br>";
    }
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n<br>";
}

echo "<hr><b>Example 3: Token Decoding</b>\n<br>";
try {
    $decoded = $jwt->decode($token);
    echo "Decoded Token: \n<br>";
    print_r($decoded);
    echo "\n<br>";
} catch (\Exception $e) {
    echo "Decoding failed: " . $e->getMessage() . "\n<br>";
}
