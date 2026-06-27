<?php

require_once 'controller.php';

do {
    echo "\n** Menu Distributeur **\n";
    echo "1 - Créer Wallet\n";
    echo "2 - Faire Dépôt\n";
    echo "3 - Faire Retrait\n";
    echo "4 - Lister les Transactions\n";
    echo "0 - Quitter\n";

    $choix = readline("Votre choix : ");

    if ($choix === '0') {
        echo "Au revoir !\n";
    } elseif ($choix === '1' || $choix === '2' || $choix === '3' || $choix === '4') {
        \Controller\handle($choix);
    } else {
        echo "Choix invalide, veuillez réessayer\n";
    }

} while ($choix !== '0');