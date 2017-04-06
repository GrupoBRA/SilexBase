<?php

$configPath = __DIR__ . \DIRECTORY_SEPARATOR .'config' . \DIRECTORY_SEPARATOR;

if(\is_dir($configPath) === false){
    \mkdir($configPath);
}

$template = <<<EOF
<?php
        
if (\defined('URL_AUTH_API') === false) {
    \define('URL_AUTH_API', 'http://api.auth.alpha.onyxapis.com/v1/');
}
if (\defined('URL_ACCOUNT_API') === false) {
    \define('URL_ACCOUNT_API', 'http://api.account.alpha.onyxapis.com/v1/');
}
if (\defined('URL_JWT_API') === false) {
    \define('URL_JWT_API', 'http://jwt-api.alpha.onyxapis.com/v1/');
}
if (\defined('URL_APP_API') === false) {
    \define('URL_APP_API', 'http://app-api.alpha.onyxapis.com/v1/');
}
        
EOF;

\file_put_contents($configPath . 'urls_apis.php', $template);

print("\n API Config OK.\n");

exit(0);
