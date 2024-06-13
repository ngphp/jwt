# NGJWT

NGJWT is a simple library to encode and decode JSON Web Tokens (JWT) in PHP. This library is part of the LeanPHP framework and was developed by Vedat Yıldırım. It provides methods to generate, validate, and decode JWT tokens, conforming to the current specifications. 

## Installation

You can install this package via Composer: https://packagist.org/packages/leanphpio/jwt

```bash
composer install leanphpio/jwt
```

## Usage

### Generating a JWT

```
// without composer: require 'NGJWT.php';

// with composer
require 'vendor/autoload.php';

use LeanPHP\JWT\NGJWT;

// Initialize the JWT handler with a secret key
$secret = 'your_secret_key';
$jwt = new NGJWT($secret);

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
echo "Generated Token: " . $token . "\n";
```

### Authenticating a JWT

```
// Example headers containing the JWT
$headers = [
    'Authorization' => 'Bearer ' . $token
];

// Authenticate the user using the token from the headers
try {
    if ($jwt->authenticate($headers)) {
        $user = NGJWT::getUser();
        echo "Authenticated User: \n";
        print_r($user);
    }
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n";
}

```

### Decoding a JWT

```
// Decode the token directly
try {
    $decoded = $jwt->decode($token);
    echo "Decoded Token: \n";
    print_r($decoded);
} catch (\Exception $e) {
    echo "Decoding failed: " . $e->getMessage() . "\n";
}
```

### Example

Here's a more detailed example that demonstrates generating, storing, authenticating, and decoding a JWT.

```
require 'vendor/autoload.php';

use leanphp\jwt\NGJWT;

// Initialize the JWT handler with a secret key
$secret = 'your_secret_key';
$jwt = new NGJWT($secret);

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
echo "Generated Token: " . $token . "\n\n";

// Simulate storing the token in a database
echo "Storing the token in the database...\n\n";
// Example of database storage logic (not implemented here)
// storeTokenInDatabase($user_id, $token);

// Example headers containing the JWT
$headers = [
    'Authorization' => 'Bearer ' . $token
];

// Authenticate the user using the token from the headers
echo "Authenticating User...\n";
try {
    if ($jwt->authenticate($headers)) {
        $user = NGJWT::getUser();
        echo "Authenticated User: \n";
        print_r($user);
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n";
}

// Decode the token directly
echo "Decoding the Token...\n";
try {
    $decoded = $jwt->decode($token);
    echo "Decoded Token: \n";
    print_r($decoded);
    echo "\n";
} catch (\Exception $e) {
    echo "Decoding failed: " . $e->getMessage() . "\n";
}

// Example output separation
echo "\n--------------------------\n";
echo "Example 1: Token Generation\n";
echo "Generated Token: " . $token . "\n";

echo "\n--------------------------\n";
echo "Example 2: Token Authentication\n";
try {
    if ($jwt->authenticate($headers)) {
        $user = NGJWT::getUser();
        echo "Authenticated User: \n";
        print_r($user);
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n";
}

echo "\n--------------------------\n";
echo "Example 3: Token Decoding\n";
try {
    $decoded = $jwt->decode($token);
    echo "Decoded Token: \n";
    print_r($decoded);
    echo "\n";
} catch (\Exception $e) {
    echo "Decoding failed: " . $e->getMessage() . "\n";
}

```

License
This project is licensed under the MIT License. See the LICENSE file for more details.

Acknowledgements
This library is part of the LeanPHP framework and was developed by Vedat Yıldırım.

Contributing
If you find any issues or have suggestions for improvements, feel free to open an issue or submit a pull request on GitHub.


This `README.md` provides an overview of the NGJWT library, including installation instructions, usage examples, and detailed explanations of each method. It also includes a complete example demonstrating how to generate, authenticate, and decode JWT tokens. The repository name `php-jwt` is concise and SEO-friendly, making it easier for users to find your project when searching for JWT solutions in PHP.
