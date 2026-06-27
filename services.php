<?php

require_once "repository.php";
require_once "validator.php";

function saisieWallets() : array {
    $wallet = ["nom" => "", "telephone" => "", "code" => "", "solde" => 0];
    $wallet["nom"]       = readline("Veuillez saisir le nom : ");
    $wallet["telephone"] = readline("Veuillez saisir le téléphone : ");
    $wallet["code"]      = readline("Veuillez saisir le code : ");
    $wallet["solde"]     = (int) readline("Veuillez saisir le solde : ");
    return $wallet;
}

function creerWalletService() : void {
    $wallets  = getWallets();
    $wallet   = saisieWallets();
    $erreur   = validerWallet($wallets, $wallet);

    if ($erreur !== null) {
        echo "Erreur : " . $erreur . "\n";
        return;
    }

    // $wallets[] = $wallet;
    // setWallets($wallets);

    array_push($wallets, $wallet);
    setWallets($wallets);
    echo "Wallet créé avec succès !\n";
}

function saisieDepot() : array {
    $telephone = readline("Entrer le numéro du dépôt : ");
    $montant   = (int) readline("Entrer le montant : ");
    return ["telephone" => $telephone, "montant" => $montant];
}

function depotService() : void {
    $wallets   = getWallets();
    $saisie    = saisieDepot();
    $telephone = $saisie["telephone"];
    $montant   = $saisie["montant"];

    if (!validerMontant($montant)) {
        echo "Erreur : Le montant doit être strictement positif.\n";
        return;
    }

    $index = trouveIndexTelephone($wallets, $telephone);
    if ($index === -1) {
        echo "Erreur : Ce numéro n'existe pas.\n";
        return;
    }

    $wallets[$index]["solde"] += $montant;
    setWallets($wallets);

    ajouterTransaction([
        "type"      => "DEPOT",
        "telephone" => $telephone,
        "montant"   => $montant,
        "frais"     => 0
    ]);

    echo "Dépôt de " . $montant . " CFA effectué avec succès !\n";
}

function fraisRetrait(int $montant) : int {
    if ($montant <= 10000) {
        return 200;
    }
    if ($montant <= 100000) {
        return 500;
    }
    $frais = $montant * 0.01;
    if ($frais > 5000) {
        return 5000;
    }
    return (int) $frais;
}

function saisieRetrait() : array {
    $telephone = readline("Entrer le numéro du retrait : ");
    $montant   = (int) readline("Entrer le montant : ");
    return ["telephone" => $telephone, "montant" => $montant];
}

function retraitService() : void {
    $wallets   = getWallets();
    $saisie    = saisieRetrait();
    $telephone = $saisie["telephone"];
    $montant   = $saisie["montant"];

    if (!validerMontant($montant)) {
        echo "Erreur : Le montant doit être strictement positif.\n";
        return;
    }

    $index = trouveIndexTelephone($wallets, $telephone);
    if ($index === -1) {
        echo "Erreur : Ce numéro n'existe pas.\n";
        return;
    }

    $frais = fraisRetrait($montant);

    if (!validerSoldeSuffisant($wallets[$index]["solde"], $montant, $frais)) {
        echo "Erreur : Solde insuffisant. Solde disponible : " . $wallets[$index]["solde"] . " CFA.\n";
        return;
    }

    $wallets[$index]["solde"] -= ($montant + $frais);
    setWallets($wallets);

    ajouterTransaction([
        "type"      => "RETRAIT",
        "telephone" => $telephone,
        "montant"   => $montant,
        "frais"     => $frais
    ]);

    echo "Retrait effectué avec succès !\n";
    echo "Montant : " . $montant . " CFA | Frais : " . $frais . " CFA | Total débité : " . ($montant + $frais) . " CFA\n";
}

function listerTransactionsService() : void {
    $transactions = getTransactions();

    if (count($transactions) === 0) {
        echo "Aucune transaction pour le moment.\n";
        return;
    }

    echo "\n--- Historique des transactions ---\n";

    $index = 1;
    array_walk($transactions, function($transaction) use (&$index) {
        echo $index . ". [" . $transaction["type"] . "] "
            . "Tel : " . $transaction["telephone"] . " | "
            . "Montant : " . $transaction["montant"] . " CFA | "
            . "Frais : " . $transaction["frais"] . " CFA\n";
        $index++;
    });


    // for ($index = 0; $index < count($transactions); $index++) {

    //     echo ($index + 1) . ". [" . $transactions[$index]["type"] . "] "
    //         . "Tel : " . $transactions[$index]["telephone"] . " | "
    //         . "Montant : " . $transactions[$index]["montant"] . " CFA | "
    //         . "Frais : " . $transactions[$index]["frais"] . " CFA\n";
    // }
}