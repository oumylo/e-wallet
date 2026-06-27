<?php

require_once "services.php";

function handle($choix) : void {
    switch ($choix) {
        case '1':
            creerWalletService();
            break;
        case '2':
            depotService();
            break;
        case '3':
            retraitService();
            break;
        case '4':
            listerTransactionsService();
            break;
    }
}