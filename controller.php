<?php
namespace Controller;

require_once "services.php";

function handle($choix) : void {
    switch ($choix) {
        case '1':
            \Services\creerWalletService();
            break;
        case '2':
            \Services\depotService();
            break;
        case '3':
            \Services\retraitService();
            break;
        case '4':
            \Services\listerTransactionsService();
            break;
    }
}