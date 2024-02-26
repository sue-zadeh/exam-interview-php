<?php
// Error reporting for development (should be disabled or redirected to logs in production)
//ini_set('error_log', '/path/to/your/php-error.log'); // Specify where to log errors
//error_reporting(0);  disable error reporting:
//ini_set('display_errors', 0);   // Set to 0 to prevent errors from being shown to the user

ini_set('display_errors', 1);
error_reporting(E_ALL);

// API configuration based on the challenge provided
$apiServer = "https://api.demo.sitehost.co.nz";
$apiVersion = "1.0";
$apiKey = "d17261d51ff7046b760bd95b4ce781ac"; //In production, a secure method should be used to store and retrieve API keys.

$clientID = "293785";
$method = "srs/list_domains";
$format = "json";

 //API call that lists all registered domains
 $apiUrl = "{$apiServer}/{$apiVersion}/{$method}.{$format}?client_id={$clientID}&apikey={$apiKey}";

try {
    // Make the API request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception("Failed to fetch data from the API.");
    }
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to decode JSON response.");
    }

    // Output the HTML for the domain list
    echo "<h1>Domains for Customer #$clientID</h1>";
    if (!empty($data['domains'])) {
        echo "<ul>";
        foreach ($data['domains'] as $domain) {
            echo "<li>" . htmlspecialchars($domain['domain'], ENT_QUOTES, 'UTF-8') . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No domains found for this customer.</p>";
    }
} catch (Exception $e) {
    // Handle any errors gracefully
    error_log($e->getMessage());
    echo "An error occurred. Please try again later.";
}
?>
