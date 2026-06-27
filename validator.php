<?php

namespace Validator;

function champsObligatoires(array $wallet) : bool {
    // if ($wallet["nom"] === "" || $wallet["telephone"] === "" || $wallet["code"] === "") {
    //     return false;
    // }
    // return true;

    if (in_array("", [$wallet["nom"], $wallet["telephone"], $wallet["code"]])) {
        return false;
    }
    return true;
}

function valideTelephone(string $telephone) : bool {
    if (strlen($telephone) != 9 || !ctype_digit($telephone)) {
        return false;
    }

    $prefixesValides = ["77", "78", "70", "75", "76"];
    $debut = substr($telephone, 0, 2);
    return in_array($debut, $prefixesValides);

    // if ($debut === "77" || $debut === "78" || $debut === "70" || $debut === "75" || $debut === "76") {
    //     return true;
    // }
    // return false;
}

function uniqueTelephone(array $wallets, string $telephone) : bool {
    // foreach ($wallets as $wallet) {
    //     if ($wallet["telephone"] === $telephone) {
    //         return false;
    //     }
    // }
    // return true;

    $telephones = array_column($wallets, "telephone");
    return !in_array($telephone, $telephones);
}

function valideCode(array $wallets, string $code) : bool {
    if (strlen($code) != 4 || !ctype_digit($code)) {
        return false;
    }

    $codes = array_column($wallets, "code");
    return !in_array($code, $codes);

    
    // foreach ($wallets as $wallet) {
    //     if ($wallet["code"] === $code) {
    //         return false;
    //     }
    // }
    // return true;
}

function verifieSoldeInitial(int $solde) : bool {
    return $solde >= 0;
}

function validerWallet(array $wallets, array $wallet) : ?string {
    if (!champsObligatoires($wallet)) {
        return "Veuillez remplir tous les champs obligatoires.";
    }
    if (!valideTelephone($wallet["telephone"])) {
        return "Le numéro doit commencer par 77, 78, 76, 75 ou 70 et contenir 9 chiffres.";
    }
    if (!uniqueTelephone($wallets, $wallet["telephone"])) {
        return "Ce numéro de téléphone existe déjà.";
    }
    if (!valideCode($wallets, $wallet["code"])) {
        return "Le code doit contenir exactement 4 chiffres et être unique.";
    }
    if (!verifieSoldeInitial($wallet["solde"])) {
        return "Le solde initial doit être positif ou nul.";
    }
    return null;
}

function validerMontant(int $montant) : bool {
    return $montant > 0;
}

function validerSoldeSuffisant(int $solde, int $montant, int $frais) : bool {
    return $solde >= ($montant + $frais);
}