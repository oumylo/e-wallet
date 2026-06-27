<?php

$wallets = [];
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

function trouverIndexParTelephone($wallets, $telephone) {
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['telephone'] === $telephone) {
            return $i;
        }
    }
    return -1;
}