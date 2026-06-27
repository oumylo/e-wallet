<?php

$wallets = [
    ["nom" => "Oumy LO", "telephone" => "778122604", "code" => "1234", "solde" => 10000]
];

$transactions = [];

function getWallets() {
    global $wallets;
    return $wallets;
}

function setWallets($data) {
    global $wallets;
    $wallets = $data;
}

function getTransactions() {
    global $transactions;
    return $transactions;
}

function ajouterTransaction($transaction) {
    global $transactions;
    $transactions[] = $transaction;
}

function trouveIndexTelephone(array $wallets, string $telephone) : int {
    foreach ($wallets as $index => $wallet) {
        if ($wallet["telephone"] === $telephone) {
            return $index;
        }
    }
    return -1;
}