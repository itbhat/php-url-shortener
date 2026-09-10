<?php

/**
 * Generate a cryptographically secure random short code.
 */
function generateShortCode(int $length = 8): string
{
    $characters =
        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $maxIndex = strlen($characters) - 1;

    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, $maxIndex)];
    }

    return $code;
}

/**
 * Validate a destination URL.
 */
function isValidUrl(string $url): bool
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    $scheme = strtolower(
        parse_url($url, PHP_URL_SCHEME) ?? ''
    );

    return in_array($scheme, ['http', 'https'], true);
}

/**
 * Validate a custom alias.
 */
function isValidAlias(string $alias): bool
{
    return preg_match(
        '/^[A-Za-z0-9_-]{3,32}$/',
        $alias
    ) === 1;
}

/**
 * Check whether an alias is reserved.
 */
function isReservedAlias(string $alias): bool
{
    $reserved = [
        'index',
        'redirect',
        'api',
        'admin',
        'login',
        'dashboard',
        'about',
        'help'
    ];

    return in_array(
        strtolower($alias),
        $reserved,
        true
    );
}

/**
 * Create a shortened URL.
 */
function createShortUrl(
    PDO $pdo,
    string $url,
    ?string $customAlias = null,
    ?int $expiresAt = null
): string
{
    if ($customAlias !== null) {

        $customAlias = trim($customAlias);

        if (!isValidAlias($customAlias)) {
            throw new InvalidArgumentException(
                'Invalid custom alias.'
            );
        }

        if (isReservedAlias($customAlias)) {
            throw new InvalidArgumentException(
                'That alias is reserved and cannot be used.'
            );
        }

        $stmt = $pdo->prepare(
            'SELECT id
             FROM urls
             WHERE short_code = ?'
        );

        $stmt->execute([$customAlias]);

        if ($stmt->fetch()) {
            throw new RuntimeException(
                'That custom alias is already in use.'
            );
        }

        $shortCode = $customAlias;

    } else {

        do {

            $shortCode = generateShortCode();

            $stmt = $pdo->prepare(
                'SELECT id
                 FROM urls
                 WHERE short_code = ?'
            );

            $stmt->execute([$shortCode]);

        } while ($stmt->fetch());
    }

    $stmt = $pdo->prepare(
        'INSERT INTO urls
            (original_url, short_code, expires_at)
         VALUES (?, ?, ?)'
    );

    $stmt->execute([
        $url,
        $shortCode,
        $expiresAt !== null
            ? date('Y-m-d H:i:s', $expiresAt)
            : null
    ]);

    return $shortCode;
}