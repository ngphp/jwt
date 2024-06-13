<?php

namespace ngphp;

/**
 * NovaJWT class for handling JWT operations.
 * 
 * This class is part of the LeanPHP framework and was developed by Vedat Yıldırım.
 * It provides methods to generate, validate, and decode JSON Web Tokens (JWT).
 */
class JWT
{
    private string $secret;

    /**
     * Constructor to initialize the JWT secret key.
     * 
     * @param string|null $secret The secret key for signing the JWT. If not provided, it will use the environment variable 'JWT_SECRET_KEY' or a default value.
     */
    public function __construct(string $secret = null) 
    {
        $this->secret = $secret ?: (getenv('JWT_SECRET_KEY') ?: "supersecretkey"); // Not secure, example only!
    }

    private static ?array $currentUser = null;

    /**
     * Get the current authenticated user from the JWT payload.
     * 
     * @return array|null The payload of the authenticated user.
     */
    public static function getUser(): ?array
    {
        return self::$currentUser;
    }

    /**
     * Authenticate the user using the JWT token from the headers.
     * 
     * @param array $headers The HTTP headers containing the JWT token.
     * @return bool True if authentication is successful, false otherwise.
     * @throws \Exception if the Authorization header is missing or the token is invalid.
     */
    public function authenticate(array $headers): bool
    {
        if (empty($headers['Authorization'])) {
            throw new \Exception('Authorization header is missing');
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        if (!$this->validate($token)) {
            throw new \Exception('Invalid or expired token');
        }

        // Set the current user
        $user = $this->decode($token);
        self::$currentUser = $user;

        return true;
    }

    /**
     * Generate a JWT token.
     * 
     * @param array $payload The payload data to include in the JWT.
     * @param int $expiration The expiration time in seconds.
     * @return string The generated JWT token.
     */
    public function generate(array $payload, int $expiration = 3600): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $payload['iat'] = time(); // Issued at
        $payload['exp'] = time() + $expiration; // Expiration Time
        $payload['jti'] = bin2hex(random_bytes(16)); // Unique JWT ID (jti)

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    /**
     * Validate a JWT token.
     * 
     * @param string $token The JWT token to validate.
     * @return bool True if the token is valid, false otherwise.
     */
    public function validate(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $signature = $this->base64UrlDecode($signatureEncoded);
        $calculatedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        return hash_equals($signature, $calculatedSignature);
    }

    /**
     * Decode a JWT token.
     * 
     * @param string $token The JWT token to decode.
     * @return array The decoded payload.
     * @throws \Exception if the JWT structure is invalid or the payload encoding is invalid.
     */
    public function decode(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \Exception("Invalid JWT structure");
        }

        $payloadEncoded = $parts[1];
        $payload = json_decode($this->base64UrlDecode($payloadEncoded), true);
        if (is_null($payload)) {
            throw new \Exception("Invalid payload encoding");
        }

        return $payload;
    }

    /**
     * Base64 URL encode.
     * 
     * @param string $data The data to encode.
     * @return string The base64 URL encoded string.
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL decode.
     * 
     * @param string $data The data to decode.
     * @return string|false The decoded data or false on failure.
     */
    private function base64UrlDecode(string $data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
