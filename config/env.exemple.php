<?php 

// Constante du mode de l'application
// dev : variables utilisées en local
// prod : pour le déploiement de l'api en production
define("MODE", "dev");

switch (MODE) {
    case "dev":
        $_ENV['host'] = '';
        $_ENV['username'] = '';
        $_ENV['database'] = '';
        $_ENV['password'] = '';
        break;
    
    case "prod":
        $_ENV['host'] = '';
        $_ENV['username'] = '';
        $_ENV['database'] = '';
        $_ENV['password'] = '';
        break;

};