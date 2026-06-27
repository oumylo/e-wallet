<?php

require_once 'repository.php';
require_once 'validator.php';

function creerWalletService() {
    $wallets = getWallets();

    $wallet = [];
    $wallet['nom']       = readline("Nom du client : ");
    $wallet['telephone'] = readline("Numéro de téléphone : ");
    $wallet['code']      = readline("Code secret : ");
    $wallet['solde']     = (int) readline("Solde initial : ");

    $erreur = validerChampsObligatoires($wallet);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $erreur = validerTelephone($wallet['telephone']);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $erreur = validerUniciteTelephone($wallets, $wallet['telephone']);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $erreur = validerCode($wallets, $wallet['code']);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $erreur = validerSoldeInitial($wallet['solde']);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $wallets[] = $wallet;
    setWallets($wallets);
    echo "Wallet créé avec succès !\n";
}

function depotService() {
    $wallets   = getWallets();
    $telephone = readline("Numéro de téléphone : ");
    $montant   = (int) readline("Montant à déposer : ");

    $erreur = validerMontantPositif($montant);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $index = trouverIndexParTelephone($wallets, $telephone);
    if ($index === -1) { echo "Erreur : Numéro introuvable.\n"; return; }

    $wallets[$index]['solde'] += $montant;
    setWallets($wallets);

    ajouterTransaction([
        'type'      => 'DEPOT',
        'telephone' => $telephone,
        'montant'   => $montant,
        'frais'     => 0
    ]);

    echo "Dépôt de " . $montant . " CFA effectué avec succès !\n";
}

function calculerFrais($montant) {
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

function retraitService() {
    $wallets   = getWallets();
    $telephone = readline("Numéro de téléphone : ");
    $montant   = (int) readline("Montant à retirer : ");

    $erreur = validerMontantPositif($montant);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $index = trouverIndexParTelephone($wallets, $telephone);
    if ($index === -1) { echo "Erreur : Numéro introuvable.\n"; return; }

    $frais  = calculerFrais($montant);
    $erreur = validerSoldeSuffisant($wallets[$index]['solde'], $montant, $frais);
    if ($erreur !== null) { echo "Erreur : " . $erreur . "\n"; return; }

    $wallets[$index]['solde'] -= ($montant + $frais);
    setWallets($wallets);

    ajouterTransaction([
        'type'      => 'RETRAIT',
        'telephone' => $telephone,
        'montant'   => $montant,
        'frais'     => $frais
    ]);

    echo "Retrait effectué !\n";
    echo "Montant : " . $montant . " CFA | Frais : " . $frais . " CFA | Total débité : " . ($montant + $frais) . " CFA\n";
}

function listerTransactionsService() {
    $transactions = getTransactions();

    if (count($transactions) === 0) {
        echo "Aucune transaction pour le moment.\n";
        return;
    }

    echo "\n--- Historique des transactions ---\n";
    for ($i = 0; $i < count($transactions); $i++) {
        echo ($i + 1) . ". [" . $transactions[$i]['type'] . "] "
            . "Tel : " . $transactions[$i]['telephone'] . " | "
            . "Montant : " . $transactions[$i]['montant'] . " CFA | "
            . "Frais : " . $transactions[$i]['frais'] . " CFA\n";
    }
}