<?php

/**
MIT License

Copyright (c) 2018-2019 Stepan Fedotov <stepan@crident.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 **/

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use Illuminate\Database\Capsule\Manager as Capsule;

// Default API/URL settings if not stored in WHMCS
global $pterodactyl_default_api_url, $pterodactyl_default_application_api_key, $pterodactyl_default_client_api_key;
$pterodactyl_default_api_url = "";
$pterodactyl_default_application_api_key = "";
$pterodactyl_default_client_api_key = "";

function pterodactyl_config() {
    $_ADDONLANG = pterodactyl_GetLang([]);
    return [
        "name" => "Pterodactyl Addon",
        "description" => $_ADDONLANG['config_description'],
        "version" => "2.0",
        "author" => "Ruben",
        "fields" => [
            "api_url" => [
                "FriendlyName" => $_ADDONLANG['api_url'],
                "Type" => "text",
                "Size" => "50",
                "Description" => $_ADDONLANG['api_url_description'],
            ],
            "application_api_key" => [
                "FriendlyName" => $_ADDONLANG['application_api_key'],
                "Type" => "password",
                "Size" => "50",
                "Description" => $_ADDONLANG['application_api_key_description'],
            ],
            "client_api_key" => [
                "FriendlyName" => $_ADDONLANG['client_api_key'],
                "Type" => "password",
                "Size" => "50",
                "Description" => $_ADDONLANG['client_api_key_description'],
            ],
        ]
    ];
}

function pterodactyl_output($vars) {
    global $pterodactyl_default_api_url, $pterodactyl_default_application_api_key, $pterodactyl_default_client_api_key;
    $_ADDONLANG = pterodactyl_GetLang($vars);
    $config = pterodactyl_GetConfig();
    $modulelink = $vars['modulelink'];
    $saveFeedback = '';
    $saveFeedbackClass = 'success';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_pterodactyl_settings'])) {
        try {
            if (empty($_SESSION['adminid'])) {
                throw new Exception('Admin session required.');
            }
            if (function_exists('check_token')) {
                check_token('WHMCS.admin.default');
            }

            $apiUrlInput = trim((string) ($_POST['api_url'] ?? ''));
            $appKeyInput = trim((string) ($_POST['application_api_key'] ?? ''));
            $clientKeyInput = trim((string) ($_POST['client_api_key'] ?? ''));

            if ($apiUrlInput === '' && !empty($config['api_url'])) {
                $apiUrlInput = $config['api_url'];
            }
            if ($appKeyInput === '' && !empty($config['application_api_key'])) {
                $appKeyInput = $config['application_api_key'];
            }
            if ($clientKeyInput === '' && !empty($config['client_api_key'])) {
                $clientKeyInput = $config['client_api_key'];
            }

            pterodactyl_SaveConfigValue('api_url', rtrim($apiUrlInput, '/'));
            pterodactyl_SaveConfigValue('application_api_key', $appKeyInput);
            pterodactyl_SaveConfigValue('client_api_key', $clientKeyInput);

            $config = pterodactyl_GetConfig();
            $saveFeedback = $_ADDONLANG['admin_settings_saved'] ?? 'Settings saved successfully.';
            $saveFeedbackClass = 'success';
        } catch (Exception $e) {
            $saveFeedback = ($_ADDONLANG['admin_settings_save_failed'] ?? 'Failed to save settings:') . ' ' . $e->getMessage();
            $saveFeedbackClass = 'danger';
        }
    }

    $apiUrl = !empty($config['api_url']) ? $config['api_url'] : $pterodactyl_default_api_url;
    $appKey = !empty($config['application_api_key']) ? $config['application_api_key'] : $pterodactyl_default_application_api_key;
    $clientKey = !empty($config['client_api_key']) ? $config['client_api_key'] : $pterodactyl_default_client_api_key;
    $apiUrlEscaped = htmlspecialchars((string) $apiUrl, ENT_QUOTES, 'UTF-8');

    echo '<div class="addon-admin-container">';
    echo '<h3>' . $_ADDONLANG['admin_management_title'] . '</h3>';

    if ($saveFeedback !== '') {
        echo '<div class="alert alert-' . $saveFeedbackClass . '"><i class="fas fa-info-circle"></i> ' . $saveFeedback . '</div>';
    }

    echo '<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">' . ($_ADDONLANG['admin_api_settings'] ?? 'API Settings') . '</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="' . htmlspecialchars((string) $modulelink, ENT_QUOTES, 'UTF-8') . '">
                    <input type="hidden" name="save_pterodactyl_settings" value="1" />
                    ' . (function_exists('generate_token') ? generate_token('plain') : '') . '
                    <div class="form-group">
                        <label for="ptero_api_url"><strong>' . ($_ADDONLANG['api_url'] ?: 'Pterodactyl URL') . '</strong></label>
                        <input type="text" class="form-control" id="ptero_api_url" name="api_url" value="' . $apiUrlEscaped . '" placeholder="https://panel.example.com" />
                        <p class="help-block">' . ($_ADDONLANG['api_url_description'] ?: '') . '</p>
                    </div>
                    <div class="form-group">
                        <label for="ptero_application_api_key"><strong>' . ($_ADDONLANG['application_api_key'] ?: 'Application API Key') . '</strong></label>
                        <input type="password" class="form-control" id="ptero_application_api_key" name="application_api_key" value="" autocomplete="new-password" placeholder="' . ($_ADDONLANG['admin_keep_existing_secret'] ?? 'Leave empty to keep current key.') . '" />
                        <p class="help-block">' . ($_ADDONLANG['application_api_key_description'] ?: '') . '</p>
                    </div>
                    <div class="form-group">
                        <label for="ptero_client_api_key"><strong>' . ($_ADDONLANG['client_api_key'] ?: 'Admin Client API Key') . '</strong></label>
                        <input type="password" class="form-control" id="ptero_client_api_key" name="client_api_key" value="" autocomplete="new-password" placeholder="' . ($_ADDONLANG['admin_keep_existing_secret'] ?? 'Leave empty to keep current key.') . '" />
                        <p class="help-block">' . ($_ADDONLANG['client_api_key_description'] ?: '') . '</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> ' . ($_ADDONLANG['save'] ?: 'Save') . '</button>
                </form>
            </div>
          </div>';

    if (empty($apiUrl) || empty($appKey) || empty($clientKey)) {
        echo '<div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>' . $_ADDONLANG['config_incomplete'] . '</strong><br>
                ' . $_ADDONLANG['config_incomplete_desc'] . '
                <br><br>
                ' . $_ADDONLANG['admin_note'] . '
              </div>';
    } else {
        $source = (!empty($config['api_url'])) ? $_ADDONLANG['admin_whmcs_settings'] : $_ADDONLANG['admin_code_variables'];
        echo '<div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                ' . $_ADDONLANG['admin_config_loaded_from'] . ' <strong>' . $source . '</strong>
              </div>';
    }
    
    echo '<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">' . $_ADDONLANG['admin_manual'] . '</h3>
            </div>
            <div class="panel-body">
                <p>' . $_ADDONLANG['admin_manual_help'] . '</p>
                <ul>
                    <li><strong>' . $_ADDONLANG['admin_app_key_label'] . ':</strong> ' . $_ADDONLANG['admin_app_key_help'] . '</li>
                    <li><strong>' . $_ADDONLANG['admin_client_key_label'] . ':</strong> ' . $_ADDONLANG['admin_client_key_help'] . '</li>
                </ul>
                <p>' . $_ADDONLANG['admin_location_note'] . '</p>
            </div>
          </div>';
    echo '</div>';
}

function pterodactyl_GetConfig() {
    $config = [];
    $results = Capsule::table('tbladdonmodules')->where('module', 'pterodactyl')->get();
    foreach ($results as $result) {
        $config[$result->setting] = $result->value;
    }
    return $config;
}

function pterodactyl_SaveConfigValue($setting, $value) {
    $query = Capsule::table('tbladdonmodules')
        ->where('module', 'pterodactyl')
        ->where('setting', $setting);

    if ($query->exists()) {
        $query->update(['value' => (string) $value]);
    } else {
        Capsule::table('tbladdonmodules')->insert([
            'module' => 'pterodactyl',
            'setting' => $setting,
            'value' => (string) $value,
        ]);
    }
}

function pterodactyl_GetHostname(array $params) {
    global $pterodactyl_default_api_url;
    $config = pterodactyl_GetConfig();
    $hostname = !empty($config['api_url']) ? $config['api_url'] : $params['serverhostname'];
    
    if (empty($hostname)) {
        $hostname = $pterodactyl_default_api_url;
    }

    if ($hostname === '') throw new Exception('Could not find the panel\'s hostname - did you configure it in the addon settings, server group or code?');

    // For whatever reason, WHMCS converts some characters of the hostname to their literal meanings (- => dash, etc) in some cases
    foreach([
                'DOT' => '.',
                'DASH' => '-',
            ] as $from => $to) {
        $hostname = str_replace($from, $to, $hostname);
    }

    if (!preg_match('#^https?://#i', $hostname)) {
        if (ip2long($hostname) !== false) $hostname = 'http://' . $hostname;
        else $hostname = ($params['serversecure'] ? 'https://' : 'http://') . $hostname;
    }

    return rtrim($hostname, '/');
}

function pterodactyl_GetLang(array $params) {
    $lang = '';
    if (isset($params['language']) && is_string($params['language'])) {
        $lang = $params['language'];
    } elseif (isset($params['lang']) && is_string($params['lang'])) {
        $lang = $params['lang'];
    } elseif (isset($_SESSION['Language']) && is_string($_SESSION['Language'])) {
        $lang = $_SESSION['Language'];
    } elseif (isset($GLOBALS['CONFIG']['Language']) && is_string($GLOBALS['CONFIG']['Language'])) {
        $lang = $GLOBALS['CONFIG']['Language'];
    }
    
    if (!$lang) {
        $lang = 'english';
    }
    
    $lang = strtolower(trim($lang));
    
    // Static cache per language to avoid redundant includes and mapping
    static $langCache = [];
    if (isset($langCache[$lang])) return $langCache[$lang];
    
    // 1. Try direct match
    if (file_exists(__DIR__ . '/lang/' . $lang . '.php')) {
        $match = $lang;
    } else {
        // 2. Try common mappings
        $mapping = [
            'russian' => 'russian', 'ru' => 'russian',
            'french' => 'french', 'fr' => 'french',
            'italian' => 'italian', 'it' => 'italian',
            'danish' => 'danish', 'da' => 'danish',
            'dutch' => 'dutch', 'nl' => 'dutch', 'nederlands' => 'dutch',
            'german' => 'german', 'de' => 'german', 'deutsch' => 'german',
            'english' => 'english', 'en' => 'english'
        ];
        
        $match = 'english';
        if (isset($mapping[$lang])) {
            $match = $mapping[$lang];
        } else {
            $short = substr($lang, 0, 2);
            if (isset($mapping[$short])) {
                $match = $mapping[$short];
            }
        }
    }

    $langFile = __DIR__ . '/lang/' . $match . '.php';
    if (!file_exists($langFile)) {
        $langFile = __DIR__ . '/lang/english.php';
    }
    
    $_LANG = [];
    $_ADDONLANG = [];
    if (file_exists($langFile)) {
        include $langFile;
    }
    
    $langCache[$lang] = !empty($_ADDONLANG) ? $_ADDONLANG : $_LANG;
    
    // Convert to entities for WHMCS compatibility if not already done
    foreach ($langCache[$lang] as $key => $value) {
        if (is_string($value)) {
            $langCache[$lang][$key] = mb_encode_numericentity($value, [0x80, 0x10ffff, 0, 0xffffff], 'UTF-8');
        }
    }

    return $langCache[$lang];
}

function pterodactyl_API(array $params, $endpoint, array $data = [], $method = "GET", $dontLog = false) {
    global $pterodactyl_default_application_api_key;
    $config = pterodactyl_GetConfig();
    $url = pterodactyl_GetHostname($params) . '/api/application/' . $endpoint;
    
    $apiKey = !empty($config['application_api_key']) ? $config['application_api_key'] : $params['serverpassword'];
    if (empty($apiKey)) {
        $apiKey = $pterodactyl_default_application_api_key;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($curl, CURLOPT_USERAGENT, "Pterodactyl-WHMCS");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_POSTREDIR, CURL_REDIR_POST_301);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $headers = [
        "Authorization: Bearer " . $apiKey,
        "Accept: Application/vnd.pterodactyl.v1+json",
    ];

    if($method === 'POST' || $method === 'PATCH') {
        $jsonData = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        array_push($headers, "Content-Type: application/json");
        array_push($headers, "Content-Length: " . strlen($jsonData));
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    $responseData = json_decode($response, true);
    $responseData['status_code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if($responseData['status_code'] === 0 && !$dontLog) logModuleCall("Pterodactyl-WHMCS", "CURL ERROR", curl_error($curl), "");

    curl_close($curl);

    if(!$dontLog) logModuleCall("Pterodactyl-WHMCS", $method . " - " . $url,
        isset($data) ? json_encode($data) : "",
        print_r($responseData, true));

    return $responseData;
}

function pterodactyl_ClientAPI(array $params, $endpoint, array $data = [], $method = "GET", $dontLog = false) {
    global $pterodactyl_default_client_api_key;
    $config = pterodactyl_GetConfig();
    $url = pterodactyl_GetHostname($params) . '/api/client/' . $endpoint;
    
    // Priority: 1. Product Option, 2. Server Access Hash, 3. Global Addon Setting, 4. Code Variable
    $apiKey = pterodactyl_GetOption($params, 'client_api_key');
    if (empty($apiKey)) {
        $apiKey = !empty($params['serveraccesshash']) ? $params['serveraccesshash'] : $config['client_api_key'];
    }

    if (empty($apiKey)) {
        $apiKey = $pterodactyl_default_client_api_key;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($curl, CURLOPT_USERAGENT, "Pterodactyl-WHMCS");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_POSTREDIR, CURL_REDIR_POST_301);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $headers = [
        "Authorization: Bearer " . $apiKey,
        "Accept: Application/vnd.pterodactyl.v1+json",
    ];

    if($method === 'POST' || $method === 'PATCH' || $method === 'PUT') {
        $jsonData = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        array_push($headers, "Content-Type: application/json");
        array_push($headers, "Content-Length: " . strlen($jsonData));
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    $responseData = json_decode($response, true);
    $responseData['status_code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if($responseData['status_code'] === 0 && !$dontLog) logModuleCall("Pterodactyl-WHMCS-Client", "CURL ERROR", curl_error($curl), "");

    curl_close($curl);

    if(!$dontLog) logModuleCall("Pterodactyl-WHMCS-Client", $method . " - " . $url,
        isset($data) ? json_encode($data) : "",
        print_r($responseData, true));

    return $responseData;
}

function pterodactyl_Error($func, $params, Exception $err) {
    logModuleCall("Pterodactyl-WHMCS", $func, $params, $err->getMessage(), $err->getTraceAsString());
}

function pterodactyl_MetaData() {
    return [
        "DisplayName" => "Pterodactyl",
        "APIVersion" => "1.1",
        "RequiresServer" => true,
    ];
}

function pterodactyl_ConfigOptions() {
    return [
        "cpu" => [
            "FriendlyName" => "CPU Limit (%)",
            "Description" => "Amount of CPU to assign to the created server.",
            "Type" => "text",
            "Size" => 10,
        ],
        "disk" => [
            "FriendlyName" => "Disk Space (MB)",
            "Description" => "Amount of Disk Space to assign to the created server.",
            "Type" => "text",
            "Size" => 10,
        ],
        "memory" => [
            "FriendlyName" => "Memory (MB)",
            "Description" => "Amount of Memory to assign to the created server.",
            "Type" => "text",
            "Size" => 10,
        ],
        "swap" => [
            "FriendlyName" => "Swap (MB)",
            "Description" => "Amount of Swap to assign to the created server.",
            "Type" => "text",
            "Size" => 10,
        ],
        "location_id" => [
            "FriendlyName" => "Location ID",
            "Description" => "ID of the Location to automatically deploy to.",
            "Type" => "text",
            "Size" => 10,
        ],
        "dedicated_ip" => [
            "FriendlyName" => "Dedicated IP",
            "Description" => "Assign dedicated ip to the server (optional)",
            "Type" => "yesno",
        ],
        "nest_id" => [
            "FriendlyName" => "Nest ID",
            "Description" => "ID of the Nest for the server to use.",
            "Type" => "text",
            "Size" => 10,
        ],
        "egg_id" => [
            "FriendlyName" => "Egg ID",
            "Description" => "ID of the Egg for the server to use.",
            "Type" => "text",
            "Size" => 10,
        ],
        "io" => [
            "FriendlyName" => "Block IO Weight",
            "Description" => "Block IO Adjustment number (10-1000)",
            "Type" => "text",
            "Size" => 10,
            "Default" => "500",
        ],
        "pack_id" => [
            "FriendlyName" => "Pack ID",
            "Description" => "ID of the Pack to install the server with (optional) [UNUSED, LEFT FOR COMPATIBILITY REASONS]",
            "Type" => "text",
            "Size" => 10,
        ],
        "port_range" => [
            "FriendlyName" => "Port Range",
            "Description" => "Port ranges seperated by comma to assign to the server (Example: 25565-25570,25580-25590) (optional)",
            "Type" => "text",
            "Size" => 25,
        ],
        "startup" => [
            "FriendlyName" => "Startup",
            "Description" => "Custom startup command to assign to the created server (optional)",
            "Type" => "text",
            "Size" => 25,
        ],
        "image" => [
            "FriendlyName" => "Image",
            "Description" => "Custom Docker image to assign to the created server (optional)",
            "Type" => "text",
            "Size" => 25,
        ],
        "databases" => [
            "FriendlyName" => "Databases",
            "Description" => "Client will be able to create this amount of databases for their server (optional)",
            "Type" => "text",
            "Size" => 10,
        ],
        "server_name" => [
            "FriendlyName" => "Server Name",
            "Description" => "The name of the server as shown on the panel (optional)",
            "Type" => "text",
            "Size" => 25,
        ],
        "oom_disabled" => [
            "FriendlyName" => "Disable OOM Killer",
            "Description" => "Should the Out Of Memory Killer be disabled (optional)",
            "Type" => "yesno",
        ],
        "backups" => [
            "FriendlyName" => "Backups",
            "Description" => "Client will be able to create this amount of backups for their server (optional)",
            "Type" => "text",
            "Size" => 10,
        ],
        "allocations" => [
            "FriendlyName" => "Allocations",
            "Description" => "Client will be able to create this amount of allocations for their server (optional)",
            "Type" => "text",
            "Size" => 10,
        ],
        "client_api_key" => [
            "FriendlyName" => "Client API Key",
            "Description" => "Client API Key for WebSocket/Console (optional, if not set globally)",
            "Type" => "password",
            "Size" => 50,
        ],
    ];
}

function pterodactyl_TestConnection(array $params) {
    $solutions = [
        0 => "Check module debug log for more detailed error.",
        401 => "Authorization header either missing or not provided.",
        403 => "Double check the password (which should be the Application Key).",
        404 => "Result not found.",
        422 => "Validation error.",
        500 => "Panel errored, check panel logs.",
    ];

    $err = "";
    try {
        $response = pterodactyl_API($params, 'nodes');

        if($response['status_code'] !== 200) {
            $status_code = $response['status_code'];
            $err = "Invalid status_code received: " . $status_code . ". Possible solutions: "
                . (isset($solutions[$status_code]) ? $solutions[$status_code] : "None.");
        } else {
            if($response['meta']['pagination']['count'] === 0) {
                $err = "Authentication successful, but no nodes are available.";
            }
        }
    } catch(Exception $e) {
        pterodactyl_Error(__FUNCTION__, $params, $e);
        $err = $e->getMessage();
    }

    return [
        "success" => $err === "",
        "error" => $err,
    ];
}

function random($length) {
    if (class_exists("\Illuminate\Support\Str")) {
        return \Illuminate\Support\Str::random($length);
    } else if (function_exists("str_random")) {
        return str_random($length);
    } else {
        throw new \Exception("Unable to find a valid function for generating random strings");
    }
}

function pterodactyl_GenerateUsername($length = 8) {
    $returnable = false;
    while (!$returnable) {
        $generated = random($length);
        if (preg_match('/[A-Z]+[a-z]+[0-9]+/', $generated)) {
            $returnable = true;
        }
    }
    return $generated;
}

function pterodactyl_GetOption(array $params, $id, $default = NULL) {
    $options = pterodactyl_ConfigOptions();

    $friendlyName = $options[$id]['FriendlyName'];
    if(isset($params['configoptions'][$friendlyName]) && $params['configoptions'][$friendlyName] !== '') {
        return $params['configoptions'][$friendlyName];
    } else if(isset($params['configoptions'][$id]) && $params['configoptions'][$id] !== '') {
        return $params['configoptions'][$id];
    } else if(isset($params['customfields'][$friendlyName]) && $params['customfields'][$friendlyName] !== '') {
        return $params['customfields'][$friendlyName];
    } else if(isset($params['customfields'][$id]) && $params['customfields'][$id] !== '') {
        return $params['customfields'][$id];
    }

    $found = false;
    $i = 0;
    foreach(pterodactyl_ConfigOptions() as $key => $value) {
        $i++;
        if($key === $id) {
            $found = true;
            break;
        }
    }

    if($found && isset($params['configoption' . $i]) && $params['configoption' . $i] !== '') {
        return $params['configoption' . $i];
    }

    return $default;
}

function pterodactyl_CreateAccount(array $params) {
    try {
        $serverId = pterodactyl_GetServerID($params);
        if(isset($serverId)) throw new Exception('Failed to create server because it is already created.');

        $userResult = pterodactyl_API($params, 'users/external/' . $params['clientsdetails']['id']);
        if($userResult['status_code'] === 404) {
            $userResult = pterodactyl_API($params, 'users?filter[email]=' . urlencode($params['clientsdetails']['email']));
            if($userResult['meta']['pagination']['total'] === 0) {
                $userResult = pterodactyl_API($params, 'users', [
                    'username' => pterodactyl_GetOption($params, 'username', pterodactyl_GenerateUsername()),
                    'email' => $params['clientsdetails']['email'],
                    'first_name' => $params['clientsdetails']['firstname'],
                    'last_name' => $params['clientsdetails']['lastname'],
                    'external_id' => (string) $params['clientsdetails']['id'],
                ], 'POST');
            } else {
                $found = false;
                foreach($userResult['data'] as $key => $value) {
                    if($value['attributes']['email'] === $params['clientsdetails']['email']) {
                        $userResult = array_merge($userResult, (array) $value);
                        $found = true;
                        break;
                    }
                }
                if (!$found && isset($userResult['data'][0])) {
                    $userResult = array_merge($userResult, (array) $userResult['data'][0]);
                }
            }
        }

        if($userResult['status_code'] === 200 || $userResult['status_code'] === 201) {
            $userId = $userResult['attributes']['id'];
        } else {
            throw new Exception('Failed to create user, received error code: ' . $userResult['status_code'] . '. Enable module debug log for more info.');
        }

        $nestId = pterodactyl_GetOption($params, 'nest_id');
        $eggId = pterodactyl_GetOption($params, 'egg_id');

        $eggData = pterodactyl_API($params, 'nests/' . $nestId . '/eggs/' . $eggId . '?include=variables');
        if($eggData['status_code'] !== 200) throw new Exception('Failed to get egg data, received error code: ' . $eggData['status_code'] . '. Enable module debug log for more info.');

        $environment = [];
        foreach($eggData['attributes']['relationships']['variables']['data'] as $key => $val) {
            $attr = $val['attributes'];
            $var = $attr['env_variable'];
            $default = $attr['default_value'];
            $friendlyName = pterodactyl_GetOption($params, $attr['name']);
            $envName = pterodactyl_GetOption($params, $attr['env_variable']);

            if(isset($friendlyName)) $environment[$var] = $friendlyName;
            elseif(isset($envName)) $environment[$var] = $envName;
            else $environment[$var] = $default;
        }

        $name = pterodactyl_GetOption($params, 'server_name', pterodactyl_GenerateUsername() . '_' . $params['serviceid']);
        $memory = pterodactyl_GetOption($params, 'memory');
        $swap = pterodactyl_GetOption($params, 'swap');
        $io = pterodactyl_GetOption($params, 'io');
        $cpu = pterodactyl_GetOption($params, 'cpu');
        $disk = pterodactyl_GetOption($params, 'disk');
        $location_id = pterodactyl_GetOption($params, 'location_id');
        $dedicated_ip = pterodactyl_GetOption($params, 'dedicated_ip') ? true : false;
        $port_range = pterodactyl_GetOption($params, 'port_range');
        $port_range = isset($port_range) ? explode(',', $port_range) : [];
        $image = pterodactyl_GetOption($params, 'image', $eggData['attributes']['docker_image']);
        $startup = pterodactyl_GetOption($params, 'startup', $eggData['attributes']['startup']);
        $databases = pterodactyl_GetOption($params, 'databases');
        $allocations = pterodactyl_GetOption($params, 'allocations');
        $backups = pterodactyl_GetOption($params, 'backups');
        $oom_disabled = pterodactyl_GetOption($params, 'oom_disabled') ? true : false;
        $serverData = [
            'name' => $name,
            'user' => (int) $userId,
            'nest' => (int) $nestId,
            'egg' => (int) $eggId,
            'docker_image' => $image,
            'startup' => $startup,
            'oom_disabled' => $oom_disabled,
            'limits' => [
                'memory' => (int) $memory,
                'swap' => (int) $swap,
                'io' => (int) $io,
                'cpu' => (int) $cpu,
                'disk' => (int) $disk,
            ],
            'feature_limits' => [
                'databases' => $databases ? (int) $databases : null,
                'allocations' => (int) $allocations,
                'backups' => (int) $backups,
            ],
            'deploy' => [
                'locations' => [(int) $location_id],
                'dedicated_ip' => $dedicated_ip,
                'port_range' => $port_range,
            ],
            'environment' => $environment,
            'start_on_completion' => true,
            'external_id' => (string) $params['serviceid'],
        ];

        $server = pterodactyl_API($params, 'servers?include=allocations', $serverData, 'POST');

        if($server['status_code'] === 400) throw new Exception('Couldn\'t find any nodes satisfying the request.');
        if($server['status_code'] !== 201) throw new Exception('Failed to create the server, received the error code: ' . $server['status_code'] . '. Enable module debug log for more info.');

        unset($params['password']);

        // Get IP & Port and set on WHMCS "Dedicated IP" field
        $_IP = $server['attributes']['relationships']['allocations']['data'][0]['attributes']['ip'];
        $_Port = $server['attributes']['relationships']['allocations']['data'][0]['attributes']['port'];

        // Check if IP & Port field have value. Prevents ":" being added if API error
        if (isset($_IP) && isset($_Port)) {
            try {
                $query = Capsule::table('tblhosting')->where('id', $params['serviceid'])->where('userid', $params['userid'])->update(array('dedicatedip' => $_IP . ":" . $_Port));
            } catch (Exception $e) { return $e->getMessage() . "<br />" . $e->getTraceAsString(); }
        }

        Capsule::table('tblhosting')->where('id', $params['serviceid'])->update([
            'username' => '',
            'password' => '',
        ]);
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

// Function to allow backwards compatibility with death-droid's module
function pterodactyl_GetServerID(array $params, $raw = false) {
    $serverResult = pterodactyl_API($params, 'servers/external/' . $params['serviceid'], [], 'GET', true);
    if($serverResult['status_code'] === 200) {
        if($raw) return $serverResult;
        else return $serverResult['attributes']['id'];
    } else if($serverResult['status_code'] === 500) {
        throw new Exception('Failed to get server, panel errored. Check panel logs for more info.');
    }

    if(Capsule::schema()->hasTable('tbl_pterodactylproduct')) {
        $oldData = Capsule::table('tbl_pterodactylproduct')
            ->select('user_id', 'server_id')
            ->where('service_id', '=', $params['serviceid'])
            ->first();

        if(isset($oldData) && isset($oldData->server_id)) {
            if($raw) {
                $serverResult = pterodactyl_API($params, 'servers/' . $oldData->server_id);
                if($serverResult['status_code'] === 200) return $serverResult;
                else throw new Exception('Failed to get server, received the error code: ' . $serverResult['status_code'] . '. Enable module debug log for more info.');
            } else {
                return $oldData->server_id;
            }
        }
    }
}

function pterodactyl_SuspendAccount(array $params) {
    try {
        $serverId = pterodactyl_GetServerID($params);
        if(!isset($serverId)) throw new Exception('Failed to suspend server because it doesn\'t exist.');

        $suspendResult = pterodactyl_API($params, 'servers/' . $serverId . '/suspend', [], 'POST');
        if($suspendResult['status_code'] !== 204) throw new Exception('Failed to suspend the server, received error code: ' . $suspendResult['status_code'] . '. Enable module debug log for more info.');
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function pterodactyl_UnsuspendAccount(array $params) {
    try {
        $serverId = pterodactyl_GetServerID($params);
        if(!isset($serverId)) throw new Exception('Failed to unsuspend server because it doesn\'t exist.');

        $suspendResult = pterodactyl_API($params, 'servers/' . $serverId . '/unsuspend', [], 'POST');
        if($suspendResult['status_code'] !== 204) throw new Exception('Failed to unsuspend the server, received error code: ' . $suspendResult['status_code'] . '. Enable module debug log for more info.');
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function pterodactyl_TerminateAccount(array $params) {
    try {
        $serverId = pterodactyl_GetServerID($params);
        if(!isset($serverId)) throw new Exception('Failed to terminate server because it doesn\'t exist.');

        $deleteResult = pterodactyl_API($params, 'servers/' . $serverId, [], 'DELETE');
        if($deleteResult['status_code'] !== 204) throw new Exception('Failed to terminate the server, received error code: ' . $deleteResult['status_code'] . '. Enable module debug log for more info.');
    } catch(Exception $err) {
        return $err->getMessage();
    }

    // Remove the "Dedicated IP" Field on Termination
    try {
        $query = Capsule::table('tblhosting')->where('id', $params['serviceid'])->where('userid', $params['userid'])->update(array('dedicatedip' => ""));
    } catch (Exception $e) { return $e->getMessage() . "<br />" . $e->getTraceAsString(); }

    return 'success';
}

function pterodactyl_ChangePassword(array $params) {
    try {
        if($params['password'] === '') throw new Exception('The password cannot be empty.');

        $serverData = pterodactyl_GetServerID($params, true);
        if(!isset($serverData)) throw new Exception('Failed to change password because linked server doesn\'t exist.');

        $userId = $serverData['attributes']['user'];
        $userResult = pterodactyl_API($params, 'users/' . $userId);
        if($userResult['status_code'] !== 200) throw new Exception('Failed to retrieve user, received error code: ' . $userResult['status_code'] . '.');

        $updateResult = pterodactyl_API($params, 'users/' . $serverData['attributes']['user'], [
            'username' => $userResult['attributes']['username'],
            'email' => $userResult['attributes']['email'],
            'first_name' => $userResult['attributes']['first_name'],
            'last_name' => $userResult['attributes']['last_name'],

            'password' => $params['password'],
        ], 'PATCH');
        if($updateResult['status_code'] !== 200) throw new Exception('Failed to change password, received error code: ' . $updateResult['status_code'] . '.');

        unset($params['password']);
        Capsule::table('tblhosting')->where('id', $params['serviceid'])->update([
            'username' => '',
            'password' => '',
        ]);
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function pterodactyl_ChangePackage(array $params) {
    try {
        $serverData = pterodactyl_GetServerID($params, true);
        if($serverData['status_code'] === 404 || !isset($serverData['attributes']['id'])) throw new Exception('Failed to change package of server because it doesn\'t exist.');
        $serverId = $serverData['attributes']['id'];

        $memory = pterodactyl_GetOption($params, 'memory');
        $swap = pterodactyl_GetOption($params, 'swap');
        $io = pterodactyl_GetOption($params, 'io');
        $cpu = pterodactyl_GetOption($params, 'cpu');
        $disk = pterodactyl_GetOption($params, 'disk');
        $databases = pterodactyl_GetOption($params, 'databases');
        $allocations = pterodactyl_GetOption($params, 'allocations');
        $backups = pterodactyl_GetOption($params, 'backups');
        $oom_disabled = pterodactyl_GetOption($params, 'oom_disabled') ? true : false;
        $updateData = [
            'allocation' => $serverData['attributes']['allocation'],
            'memory' => (int) $memory,
            'swap' => (int) $swap,
            'io' => (int) $io,
            'cpu' => (int) $cpu,
            'disk' => (int) $disk,
            'oom_disabled' => $oom_disabled,
            'feature_limits' => [
                'databases' => (int) $databases,
                'allocations' => (int) $allocations,
                'backups' => (int) $backups,
            ],
        ];

        $updateResult = pterodactyl_API($params, 'servers/' . $serverId . '/build', $updateData, 'PATCH');
        if($updateResult['status_code'] !== 200) throw new Exception('Failed to update build of the server, received error code: ' . $updateResult['status_code'] . '. Enable module debug log for more info.');

        $nestId = pterodactyl_GetOption($params, 'nest_id');
        $eggId = pterodactyl_GetOption($params, 'egg_id');
        $eggData = pterodactyl_API($params, 'nests/' . $nestId . '/eggs/' . $eggId . '?include=variables');
        if($eggData['status_code'] !== 200) throw new Exception('Failed to get egg data, received error code: ' . $eggData['status_code'] . '. Enable module debug log for more info.');

        $environment = [];
        foreach($eggData['attributes']['relationships']['variables']['data'] as $key => $val) {
            $attr = $val['attributes'];
            $var = $attr['env_variable'];
            $friendlyName = pterodactyl_GetOption($params, $attr['name']);
            $envName = pterodactyl_GetOption($params, $attr['env_variable']);

            if(isset($friendlyName)) $environment[$var] = $friendlyName;
            elseif(isset($envName)) $environment[$var] = $envName;
            elseif(isset($serverData['attributes']['container']['environment'][$var])) $environment[$var] = $serverData['attributes']['container']['environment'][$var];
            elseif(isset($attr['default_value'])) $environment[$var] = $attr['default_value'];
        }

        $image = pterodactyl_GetOption($params, 'image', $serverData['attributes']['container']['image']);
        $startup = pterodactyl_GetOption($params, 'startup', $serverData['attributes']['container']['startup_command']);
        $updateData = [
            'environment' => $environment,
            'startup' => $startup,
            'egg' => (int) $eggId,
            'image' => $image,
            'skip_scripts' => false,
        ];

        $updateResult = pterodactyl_API($params, 'servers/' . $serverId . '/startup', $updateData, 'PATCH');
        if($updateResult['status_code'] !== 200) throw new Exception('Failed to update startup of the server, received error code: ' . $updateResult['status_code'] . '. Enable module debug log for more info.');
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function pterodactyl_UsageUpdate(array $params) {
    try {
        $services = Capsule::table('tblhosting')
            ->join('tblproducts', 'tblproducts.id', '=', 'tblhosting.packageid')
            ->where('tblhosting.server', (int) $params['serverid'])
            ->where('tblproducts.servertype', 'pterodactyl')
            ->whereIn('tblhosting.domainstatus', ['Active', 'Suspended'])
            ->select('tblhosting.id as serviceid', 'tblhosting.userid')
            ->get();

        foreach ($services as $service) {
            $serviceParams = $params;
            $serviceParams['serviceid'] = (int) $service->serviceid;
            $serviceParams['userid'] = (int) $service->userid;

            try {
                $serverData = pterodactyl_GetServerID($serviceParams, true);
                if (
                    !isset($serverData['attributes']['identifier']) ||
                    !isset($serverData['attributes']['limits'])
                ) {
                    continue;
                }

                $identifier = $serverData['attributes']['identifier'];
                $usageResult = pterodactyl_ClientAPI($serviceParams, 'servers/' . $identifier . '/resources', [], 'GET', true);
                if (
                    !isset($usageResult['status_code']) ||
                    (int) $usageResult['status_code'] !== 200 ||
                    !isset($usageResult['attributes']['resources'])
                ) {
                    continue;
                }

                $resources = $usageResult['attributes']['resources'];
                $diskBytes = isset($resources['disk_bytes']) ? (float) $resources['disk_bytes'] : 0;
                $networkRx = isset($resources['network_rx_bytes']) ? (float) $resources['network_rx_bytes'] : 0;
                $networkTx = isset($resources['network_tx_bytes']) ? (float) $resources['network_tx_bytes'] : 0;

                $diskUsageMb = round($diskBytes / 1048576, 2);
                $bandwidthUsageMb = round(($networkRx + $networkTx) / 1048576, 2);
                $diskLimitMb = isset($serverData['attributes']['limits']['disk']) ? (float) $serverData['attributes']['limits']['disk'] : 0;

                Capsule::table('tblhosting')
                    ->where('id', (int) $service->serviceid)
                    ->update([
                        'diskusage' => $diskUsageMb,
                        'disklimit' => $diskLimitMb > 0 ? $diskLimitMb : 0,
                        'bwusage' => $bandwidthUsageMb,
                        'bwlimit' => 0,
                        'lastupdate' => date('Y-m-d H:i:s'),
                    ]);
            } catch (Exception $innerErr) {
                logModuleCall(
                    'Pterodactyl-WHMCS-UsageUpdate',
                    'Service ' . (int) $service->serviceid,
                    '',
                    $innerErr->getMessage()
                );
            }
        }
    } catch (Exception $err) {
        logModuleCall("Pterodactyl-WHMCS-UsageUpdate", __FUNCTION__, $params, $err->getMessage(), $err->getTraceAsString());
    }
}

function pterodactyl_LoginLink(array $params) {
    if($params['moduletype'] !== 'pterodactyl') return;
    $_ADDONLANG = pterodactyl_GetLang($params);

    try {
        $serverId = pterodactyl_GetServerID($params);
        if(!isset($serverId)) return;

        $hostname = pterodactyl_GetHostname($params);
        echo '<div style="margin-bottom: 10px;">';
        echo '<a class="btn btn-primary" href="'.$hostname.'/admin/servers/view/' . $serverId . '" target="_blank"><i class="fas fa-external-link-alt"></i> ' . ($_ADDONLANG['admin_to_server_panel'] ?: 'Zum Server-Panel') . '</a>';
        echo '</div>';
    } catch(Exception $err) {
        // Ignore
    }
}

function pterodactyl_AdminServicesTabFields(array $params) {
    return [];
}

function pterodactyl_ClientArea(array $params) {
    global $_ADDONLANG;
    if($params['moduletype'] !== 'pterodactyl') return;
    
    // Ensure that the console is NOT displayed in the admin area
    if (defined('ADMIN_AREA') && ADMIN_AREA === true) {
        return '';
    }
    
    // Additional protection: Check for admin directory in the path
    if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
        return '';
    }

    try {
        $hostname = pterodactyl_GetHostname($params);
        $serverData = pterodactyl_GetServerID($params, true);
        if($serverData['status_code'] === 404 || !isset($serverData['attributes']['id'])) {
            $_LANG = pterodactyl_GetLang($params);
            return [
                'templatefile' => 'clientarea',
                'vars' => [
                    'serviceurl' => $hostname,
                    'lang' => $_LANG,
                    'token' => $_SESSION['tkval'],
                    'error' => $_LANG['server_not_found'] ?: 'Server not found or API access denied.',
                ],
            ];
        }

        $internalId = $serverData['attributes']['id'];
        $identifier = $serverData['attributes']['identifier'];

        // AJAX Handlers
        if (isset($_REQUEST['modaction'])) {
            // CSRF Protection
            $token = $_REQUEST['token'];
            if (!$token || $token !== $_SESSION['tkval']) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['errors' => [['detail' => 'CSRF Token Validation Failed']]]);
                die();
            }

            $action = $_REQUEST['modaction'];
            if ($action == 'get_websocket') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/websocket');
                echo json_encode($result);
                die();
            }
            if ($action == 'send_command') {
                $command = $_POST['command'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/command', ['command' => $command], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'power_action') {
                $signal = $_POST['signal'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/power', ['signal' => $signal], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'get_resources') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/resources');
                echo json_encode($result);
                die();
            }
            if ($action == 'get_server') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier);
                echo json_encode($result);
                die();
            }
            if ($action == 'get_startup') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/startup');
                echo json_encode($result);
                die();
            }
            if ($action == 'update_startup') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/startup/variable', [
                    'key' => $_POST['key'],
                    'value' => $_POST['value']
                ], 'PUT');
                echo json_encode($result);
                die();
            }
            if ($action == 'get_databases') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/databases?include=password', [], 'GET', true);
                echo json_encode($result);
                die();
            }
            if ($action == 'create_database') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/databases', [
                    'database' => $_POST['database'],
                    'remote' => $_POST['remote']
                ], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'delete_database') {
                $dbId = $_POST['db_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/databases/' . $dbId, [], 'DELETE');
                echo json_encode($result);
                die();
            }
            if ($action == 'rotate_db_password') {
                $dbId = $_POST['db_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/databases/' . $dbId . '/rotate-password', [], 'POST', true);
                echo json_encode($result);
                die();
            }
            if ($action == 'get_network') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/network/allocations');
                echo json_encode($result);
                die();
            }
            if ($action == 'set_primary_allocation') {
                $allocId = $_POST['allocation_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/network/allocations/' . $allocId . '/primary', [], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'create_allocation') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/network/allocations', [], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'delete_allocation') {
                $allocId = $_POST['allocation_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/network/allocations/' . $allocId, [], 'DELETE');
                echo json_encode($result);
                die();
            }
            if ($action == 'get_backups') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups');
                echo json_encode($result);
                die();
            }
            if ($action == 'create_backup') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups', [
                    'name' => $_POST['name']
                ], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'delete_backup') {
                $backupId = $_POST['backup_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups/' . $backupId, [], 'DELETE');
                echo json_encode($result);
                die();
            }
            if ($action == 'download_backup') {
                $backupId = $_GET['backup_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups/' . $backupId . '/download');
                echo json_encode($result);
                die();
            }
            if ($action == 'restore_backup') {
                $backupId = $_POST['backup_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups/' . $backupId . '/restore', [
                    'truncate' => true
                ], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'lock_backup') {
                $backupId = $_POST['backup_id'];
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/backups/' . $backupId . '/lock', [], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'update_settings') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/settings/rename', [
                    'name' => $_POST['name']
                ], 'POST');
                echo json_encode($result);
                die();
            }
            if ($action == 'reinstall_server') {
                $result = pterodactyl_ClientAPI($params, 'servers/' . $identifier . '/settings/reinstall', [], 'POST');
                echo json_encode($result);
                die();
            }
        }

        return [
            'templatefile' => 'clientarea',
            'vars' => [
                'serviceurl' => $hostname . '/server/' . $identifier,
                'identifier' => $identifier,
                'hostname' => $hostname,
                'servername' => $serverData['attributes']['name'],
                'lang' => pterodactyl_GetLang($params),
                'token' => $_SESSION['tkval'],
            ],
        ];
    } catch (Exception $err) {
        return [
            'templatefile' => 'clientarea',
            'vars' => [
                'error' => $err->getMessage(),
                'lang' => pterodactyl_GetLang($params),
                'token' => $_SESSION['tkval'],
            ],
        ];
    }
}
