<?php

function validerChampsObligatoires($wallet) {
    if ($wallet['nom'] === '' || $wallet['telephone'] === '' || $wallet['code'] === '') {
        return "Tous les champs sont obligatoires.";
    }
    return null;
}

function validerTelephone($telephone) {
    if (strlen($telephone) != 9 || !ctype_digit($telephone)) {
        return "Le numéro doit contenir exactement 9 chiffres.";
    }
    $debut = substr($telephone, 0, 2);
    if ($debut !== '77' && $debut !== '78' && $debut !== '76' && $debut !== '75' && $debut !== '70') {
        return "Le numéro doit commencer par 77, 78, 76, 75 ou 70.";
    }
    return null;
}

function validerUniciteTelephone($wallets, $telephone) {
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['telephone'] === $telephone) {
            return "Ce numéro de téléphone existe déjà.";
        }
    }
    return null;
}

function validerCode($wallets, $code) {
    if (strlen($code) != 4 || !ctype_digit($code)) {
        return "Le code doit contenir exactement 4 chiffres.";
    }
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i]['code'] === $code) {
            return "Ce code secret existe déjà.";
        }
    }
    return null;
}

function validerSoldeInitial($solde) {
    if ($solde < 0) {
        return "Le solde initial doit être positif ou nul.";
    }
    return null;
}

function validerMontantPositif($montant) {
    if ($montant <= 0) {
        return "Le montant doit être strictement positif.";
    }
    return null;
}

function validerSoldeSuffisant($solde, $montant, $frais) {
    if ($solde < ($montant + $frais)) {
        return "Solde insuffisant. Solde disponible : " . $solde . " CFA.";
    }
    return null;
}