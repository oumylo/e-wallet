<?php

namespace Repository;

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
    //$transactions[] = $transaction;
    array_push($transactions, $transaction);
}

function trouveIndexTelephone(array $wallets, string $telephone) : int {

    $index = array_search( $telephone, array_column($wallets, 'telephone'));
    if ($index !== false) {
        return $index;
    } else {
        return -1;
    }

    // foreach ($wallets as $index => $wallet) {
    //     if ($wallet["telephone"] === $telephone) {
    //         return $index;
    //     }
    // }
    // return -1;
}