# Présentation Technique — Projet E-Wallet PHP

---

## Sommaire

1. Les Closures en PHP
2. Les Fonctions Natives de Tableaux utilisées dans le projet
3. Les Namespaces utilisés dans le projet

---

## 1. Les Closures en PHP

Une closure est une fonction anonyme qui **capture des variables extérieures** grâce au mot-clé `use`.

Dans notre projet, on a utilisé une closure dans `services.php` pour afficher l'historique des transactions :

```php
$index = 1;
array_walk($transactions, function($transaction) use (&$index) {
    echo $index . ". [" . $transaction["type"] . "] "
        . "Tel : " . $transaction["telephone"] . " | "
        . "Montant : " . $transaction["montant"] . " CFA | "
        . "Frais : " . $transaction["frais"] . " CFA\n";
    $index++;
});
```

**Explications :**
- `function($transaction)` : fonction anonyme passée à `array_walk`
- `use (&$index)` : on capture la variable `$index` depuis l'extérieur
- `&` : le `&` signifie qu'on modifie la vraie valeur de `$index` à chaque tour

---

## 2. Fonctions Natives de Tableaux Utilisées dans le Projet

### `array_push()`

Ajoute un élément à la fin d'un tableau.

```php
// Partie A — sans fonction native
$wallets[] = $wallet;

// Partie B — avec array_push
array_push($wallets, $wallet);
```

Utilisé dans `services.php` pour ajouter un nouveau wallet et une nouvelle transaction.

---

### `array_column()`

Extrait une colonne d'un tableau multidimensionnel.

```php
// Extraire tous les numéros de téléphone
$telephones = array_column($wallets, "telephone");
// Résultat : ["778122604", "778122605", ...]

// Extraire tous les codes secrets
$codes = array_column($wallets, "code");
// Résultat : ["1234", "1235", ...]
```

Utilisé dans `validator.php` pour vérifier l'unicité du téléphone et du code, et dans `repository.php` pour trouver l'index d'un wallet.

---

### `in_array()`

Vérifie si une valeur existe dans un tableau.

```php
// Vérifier si le préfixe du téléphone est valide
$prefixesValides = ["77", "78", "70", "75", "76"];
$debut = substr($telephone, 0, 2);
return in_array($debut, $prefixesValides);

// Vérifier que le téléphone n'existe pas déjà
$telephones = array_column($wallets, "telephone");
return !in_array($telephone, $telephones);

// Vérifier que le code n'existe pas déjà
$codes = array_column($wallets, "code");
return !in_array($code, $codes);

// Vérifier que les champs ne sont pas vides
if (in_array("", [$wallet["nom"], $wallet["telephone"], $wallet["code"]])) {
    return false;
}
```

Utilisé dans `validator.php` pour toutes les validations.

---

### `array_search()`

Retourne l'index de la première valeur trouvée dans un tableau.

```php
// Partie A — boucle manuelle
foreach ($wallets as $index => $wallet) {
    if ($wallet["telephone"] === $telephone) {
        return $index;
    }
}
return -1;

// Partie B — avec array_search
$index = array_search($telephone, array_column($wallets, "telephone"));
return $index !== false ? $index : -1;
```

Utilisé dans `repository.php` pour trouver l'index d'un wallet par son numéro de téléphone.

---

### `array_walk()`

Applique une fonction sur chaque élément d'un tableau.

```php
// Partie A — boucle for
for ($i = 0; $i < count($transactions); $i++) {
    echo ($i + 1) . ". [" . $transactions[$i]["type"] . "] "
        . "Tel : " . $transactions[$i]["telephone"] . "\n";
}

// Partie B — avec array_walk
$index = 1;
array_walk($transactions, function($transaction) use (&$index) {
    echo $index . ". [" . $transaction["type"] . "] "
        . "Tel : " . $transaction["telephone"] . "\n";
    $index++;
});
```

Utilisé dans `services.php` pour afficher l'historique des transactions.

---

## 3. Les Namespaces

Un namespace est un **espace de noms** qui permet d'organiser et de cloisonner les fonctions dans des fichiers différents.

Dans notre projet, chaque fichier a son propre namespace :

```php
// repository.php
namespace Repository;

// validator.php
namespace Validator;

// services.php
namespace Services;

// controller.php
namespace Controller;
```

Pour appeler une fonction d'un autre namespace, on utilise `\NomDuNamespace\nomFonction()` :

```php
// Dans services.php, appeler une fonction de repository.php
$wallets = \Repository\getWallets();
\Repository\setWallets($wallets);
\Repository\ajouterTransaction([...]);
\Repository\trouveIndexTelephone($wallets, $telephone);

// Dans services.php, appeler une fonction de validator.php
\Validator\validerWallet($wallets, $wallet);
\Validator\validerMontant($montant);
\Validator\validerSoldeSuffisant($solde, $montant, $frais);

// Dans index.php, appeler une fonction de controller.php
\Controller\handle($choix);
```

---

## Conclusion

| Concept               | Fichier |                 Utilité |
|---|---|---|
| **Closure + `use`** | `services.php` | Afficher les transactions avec `array_walk` |
| **`array_push`** | `services.php` | Ajouter un wallet ou une transaction |
| **`array_column`** | `validator.php`, `repository.php` | Extraire téléphones et codes |
| **`in_array`** | `validator.php` | Vérifier unicité et préfixes valides |
| **`array_search`** | `repository.php` | Trouver l'index d'un wallet |
| **`array_walk`** | `services.php` | Parcourir et afficher les transactions |
| **Namespaces** | Tous les fichiers | Organiser et cloisonner les fonctions |

---

*Projet réalisé par Oumy LO — Formation PHP Procédural*