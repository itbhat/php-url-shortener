<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

$error = '';
$shortUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $url = trim($_POST['url'] ?? '');
    $customAlias = trim($_POST['alias'] ?? '');
    $expirationHours = trim($_POST['expires_at'] ?? '');

    $allowedExpirations = [
        '',
        '1',
        '24',
        '168',
        '720'
    ];

    if ($url === '') {

        $error = 'Please enter a URL.';

    } elseif (!isValidUrl($url)) {

        $error = 'Please enter a valid HTTP or HTTPS URL.';

    } elseif (!in_array($expirationHours, $allowedExpirations, true)) {

        $error = 'Invalid expiration option.';

    } else {

        $expiresAt = null;

        if ($expirationHours !== '') {
            $expiresAt =
                time() + ((int)$expirationHours * 3600);
        }

        try {

            $shortCode = createShortUrl(
                $pdo,
                $url,
                $customAlias !== ''
                    ? $customAlias
                    : null,
                $expiresAt
            );

            $shortUrl =
                'https://urlshortener.42web.io/' .
                $shortCode;

        } catch (InvalidArgumentException $e) {

            $error = $e->getMessage();

        } catch (RuntimeException $e) {

            $error = $e->getMessage();

        } catch (PDOException $e) {

            $error = 'Unable to create the short URL.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>URL Shortener</title>

    <link
        rel="stylesheet"
        href="styles.css"
    >

</head>

<body>

<div class="container">

    <h1>URL Shortener</h1>

    <p class="subtitle">
        Turn long URLs into short, shareable links.
    </p>

    <?php if ($error !== ''): ?>

        <div class="error">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>

    <form
        method="post"
        class="url-form"
    >

        <input
            type="url"
            name="url"
            class="url-input"
            placeholder="https://example.com/your-long-url"
            required
        >

        <label for="alias">
            Custom alias
        </label>

        <input
            type="text"
            id="alias"
            name="alias"
            class="url-input"
            placeholder="e.g. github"
            maxlength="32"
        >

        <label for="expires_at">
            Expiration
        </label>

        <select
            id="expires_at"
            name="expires_at"
            class="url-input"
        >

            <option value="">Never</option>
            <option value="1">1 hour</option>
            <option value="24">24 hours</option>
            <option value="168">7 days</option>
            <option value="720">30 days</option>

        </select>

        <p>
            Optional. Use 3–32 letters, numbers,
            hyphens or underscores.
        </p>

        <button
            type="submit"
            class="url-button"
        >
            Shorten URL
        </button>

    </form>

    <?php if ($shortUrl !== ''): ?>

        <div class="result">

            <strong>
                Your shortened URL
            </strong>

            <p class="result-url">

                <a
                    id="shortUrl"
                    href="<?= htmlspecialchars(
                        $shortUrl,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    <?= htmlspecialchars(
                        $shortUrl,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </a>

            </p>

            <button
                type="button"
                class="copy-button"
                onclick="copyShortUrl()"
            >
                Copy
            </button>

        </div>

    <?php endif; ?>

</div>

<script>

function copyShortUrl()
{
    const url =
        document.getElementById('shortUrl').href;

    navigator.clipboard.writeText(url)
        .then(function () {
            alert('Short URL copied!');
        })
        .catch(function () {
            alert('Unable to copy URL.');
        });
}

</script>

</body>
</html>