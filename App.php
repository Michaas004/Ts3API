<?php

system('clear');

const CLASSES = "src/Classes/";
const AUTHOR = "Michaas004";
const START = "Ts3Info » ";
const CONFIG = "src/Config/";
const END = PHP_EOL;

print START . 'Welcome to the application console Ts3Info!' . END;
print START . 'Author: '. AUTHOR . END;
print START . 'Launching the application...' . END. END;

$name_class = ['ts3admin.class.php'];

if(is_dir(CLASSES)) {
    foreach($name_class as $class) {
        if(file_exists(CLASSES. $class)) {
            require_once CLASSES. $class;
        }
        else {
            exit(START.'Not found a class: ' . $class. END);
        }
    }
}
else {
    exit(START.'Classes folder not found!'.END);
}

if(is_dir(CONFIG)) {
    if(file_exists(CONFIG . 'config.php')) {
        require_once CONFIG . 'config.php';
    }
    else {
        exit(START.'Not found config file: config.php'. END);
    }
}

$tsQuery = new ts3admin($config['teamspeak']['Host'], $config['teamspeak']['QueryPort']);

$connect = $tsQuery->connect();

if($connect['success']) {
    echo START. 'The connection to the server was successfully made!'. END;
}
else {
    exit (START. 'Connection problem detected: '. implode(", ", $connect['errors']). END):
}

$login = $tsQuery->login($config['teamspeak']['Login'], $config['teamspeak']['Password']);

if($login['success']) {
    echo START. 'Login successfully completed!'.END;
}
else {
    exit (START. 'Login problem detected: '. implode(", ", $login['errors']) . END);
}

$selectServer = $tsQuery->selectServer($config['teamspeak']['VoicePort']);

if($selectServer['success']) {
    echo START. 'You have successfully selected the voice port!'. END;
}
else {
    exit (START. 'Problem with voice port selection detected: '. implode(", ", $selectServer['errors']). END);
}

$setName = $tsQuery->setName($config['teamspeak']['Nickname']);

if($setName['success']) {
    echo START. 'Name changed successfully!'. END;
}
else {
    exit(START. 'Bot rename problem detected: '. implode(", ", $setName['errors']). END);
}

while(true) {

    $SERVER_INFORMATION = $tsQuery->serverInfo()['data'];
    $FILE_LOCATION = $config['cache_settings']['save_to_directory'];
    $serverInfo = [
        'SERVER_NAME' => $SERVER_INFORMATION['virtualserver_name'],
        'SERVER_PLATFORM' => $SERVER_INFORMATION['virtualserver_platform'],
        'MAX_SLOTS' => $SERVER_INFORMATION['virtualserver_maxclients'],
        'CREATED_DATE' => date('d/m/Y H:i:s', $SERVER_INFORMATION['virtualserver_created']),
        'VOICE_PORT' => $SERVER_INFORMATION['virtualserver_port'],
        'ONLINE_CHANNELS' => $SERVER_INFORMATION['virtualserver_channelsonline'],
        'ONLINE_CLIENTS' => $SERVER_INFORMATION['virtualserver_clientsonline'],
    ];

    file_put_contents($FILE_LOCATION, json_encode($serverInfo));
    sleep($config['cache_settings']['sleep']);
}