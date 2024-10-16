# MTN MoMo API (Collection, Disbursement, Remittance) pour Laravel

### Info: Ce package vous permet d'intégrer directement l'API MTN MoMo pour Laravel sans utiliser un quelconque agrégateur de paiement et d'avoir le contrôle sur vos transactions

### NB: Mode Sandbox utilisé. Pour passer en mode production veuillez me contactez à [ana@roazagba.me](mailto:ana@roazagba.me)

### Une fois les exigences KYC remplies, les identifiants nécessaires en production vous sont fournis sur le tableau de bord du portail des partenaires MTN. Cependant, dans un environnement de test, vous devrez créer un utilisateur sandbox via l'API, ce que ce package peut réaliser pour vous.

### Pré-requis

Avant l'intégration, assurez-vous d'avoir faire les étapes suivantes

1. Créer un compte développer sur [https://momodeveloper.mtn.com](https://momodeveloper.mtn.com)
2. Souscrire aux produits que vous vous voulez utiliser à [https://momodeveloper.mtn.com/products](https://momodeveloper.mtn.com/products) Exemple: **collection**, **disbursement**, **remittance**

   - **Collection**: L'encaissement est un service qui permet aux partenaires Mobile Money de recevoir des paiements pour des biens et des services en utilisant MTN Mobile Money. Les services peuvent être offerts en face à face, comme MomoPay, ou à distance, à la fois hors ligne et en ligne. Les paiements peuvent être initiés par le client sur USSD/App/Web ou par le marchand, qui reçoit une demande de débit pour approbation.

     Une fois le service activé, un compte d'encaissement est créé pour le partenaire, sur lequel les fonds sont déposés/reçus. Le partenaire est en mesure d'effectuer des paiements ultérieurs à ses fournisseurs/partenaires/employés (B2B ou B2C) et/ou de liquider les fonds collectés sur leurs comptes bancaires respectifs.

     Le service offre la possibilité de collecter des paiements en ligne, des factures, des remboursements de prêts, des contributions à des activités et des versements pour des remboursements de services et de produits convenus d'un commun accord. Umeme, National Water, URA, NSSF, DSTV, pour n'en citer que quelques-uns, sont des exemples de partenaires qui collectent des factures.

   - **Disbursement**: Les décaissements sont un service qui permet aux partenaires de Mobile Money d'envoyer de l'argent en masse à différents destinataires en un seul clic. Cette configuration peut être exécutée manuellement (se connecter au système, télécharger la liste des bénéficiaires et déclencher les paiements) ou automatisée (nécessite une configuration unique des listes de bénéficiaires et des commandes pour effectuer le paiement).

     Voici quelques exemples de partenaires qui utilisent ce service : Les sociétés de paris pour payer les gagnants, les versements de fonds aux réfugiés/bénéficiaires, entre autres.

     Il est prévu que le partenaire ouvre un compte de décaissement auprès de MTN et que ce compte soit préfinancé pour permettre les paiements une fois que les demandes émanent du partenaire.

   - **Remittance**: Les transferts de fonds sont une solution qui permet à un client de transférer ou de recevoir des fonds de la diaspora vers le compte du destinataire de Mobile Money en monnaie locale. Il s'agit d'une solution automatisée dans laquelle l'argent est transféré en temps réel lorsque la demande arrive dans le système (elle fonctionne de la même manière que la solution automatisée de décaissement).

     L'expéditeur se connecte sur le Web/l'application/l'USSD (USSD uniquement pour les transferts sortants) ou se rend au point de vente de l'expéditeur pour envoyer de l'argent. Une demande est alors envoyée au système du partenaire local qui déclenche un paiement dans le portefeuille du destinataire.

     Le partenaire est tenu d'ouvrir un compte auprès de MTN et d'y charger des fonds pour faciliter le transfert de fonds (entrant et sortant).

3. Allez dans le profil developper [https://momodeveloper.mtn.com/profile](https://momodeveloper.mtn.com/profile), vous trouverez les clés primaire et secondaire de chaque produit.

   ```bash
   Primary Key => Subscription Key (Clé abonnement)
   ```

4. Cas d'utilisation du mode sandbox à [https://momodeveloper.mtn.com/api-documentation/testing](https://momodeveloper.mtn.com/api-documentation/testing)

### Installation

```bash
composer require roazagba/apimtnmomo
```

### Configuration

```bash
php artisan vendor:publish --provider="Roazagba\ApiMTNMomo\MTNMoMoServiceProvider" --tag="config"
```

### Créer un environnement sandbox utilisateur API ainsi que la clé secrète API.

```bash
php artisan momo:create-api-user {baseURL} {primaryKey} {providerCallbackHost}
```

- **baseURL** : L'URL de base pour l'API. Dans cet exemple, elle pointe vers l'environnement de test (sandbox) de MTN MoMo Developer qui est **https://sandbox.momodeveloper.mtn.com/**. Cela permet de définir l'URL de l'API à utiliser, que ce soit en mode test ou production.

- **primaryKey** : La clé primaire de l'API du produit (collection, disbursement, remittance), nécessaire pour effectuer des transactions avec le service de collecte. Elle est utilisée pour identifier l'application qui effectue les transactions.

- **providerCallbackHost** : L'URL vers laquelle les notifications ou retours d'informations seront envoyés.

Exemple:

```bash
php artisan momo:create-api-user https://sandbox.momodeveloper.mtn.com/ your_product_primary_key https://your-callback-url.com
```

Après exéxution de la commande vous aurez une réponse en console :

```text
[baseURL] => https://sandbox.momodeveloper.mtn.com/
[userID] => 6eb8a48f-f113-444c-97df-a3b7f088a6f2
[primaryKey] => 85a5f074bf59202441688d781dks65
[apiKeySecret] => 6eb444c8a48fea6ec3a39985a5f074bf5
[targetEnvironment] => sandbox
[providerCallbackHost] => https://your-callback-url.com
```

- **userID** : ID pour l'utilisateur API créé. Cet ID est utilisé pour authentifier l'utilisateur qui effectue les requêtes via l'API de produit avec la clé secrète.
- **apiKeySecret** : La clé secrète pour l'API du produit. C'est une clé sensible qui permet d'authentifier les requêtes de produit avec l'ID Utilisateur. Elle doit être protégée et ne jamais être exposée publiquement.
- **targetEnvironment** : Le type d'environnement à utiliser. Ici, il est défini sur "sandbox" pour indiquer qu'il s'agit d'un environnement de test. En production, cela serait par exemple pour le Bénin "mtnbenin".

### Variables d'environnement dans le fichier .env

```env
RA_BASE_URL="https://sandbox.momodeveloper.mtn.com/"
RA_CURRENCY="EUR" # EUR pour le sandbox, en production pour Bénin c'est XOF
RA_TARGET_ENVIRONNEMENT="sandbox"
RA_CALLBACK_URL="http://localhost:8000"
RA_COLLECTION_API_KEY_SECRET="apiKeySecret"
RA_COLLECTION_PRIMARY_KEY="primaryKey"
RA_COLLECTION_USER_ID="userID"
```

### Collection

```php
use Roazagba\ApiMTNMomo\MTNMoMoConfig;
use Roazagba\ApiMTNMomo\Products\MTNMoMoCollection;

$config = new MTNMoMoConfig();

$collection = new MTNMoMoCollection($config);
```

**createTransaction()** opération permettant de demander un paiement à un consommateur (payeur). Le payeur est invité à autoriser le paiement. La transaction sera exécutée une fois que le payeur aura autorisé le paiement. La demande de paiement est en attente jusqu'à ce que la transaction soit autorisée ou refusée par le payeur ou qu'elle soit interrompue par le système.

```php

$params = [
    'amount' => '2',
    'referenceExternalID' => rand(1000000000000000, 9999999999999999) . '',
    'numberMoMo' => '22969411836',
    'description' => 'transaction',
    'note' => 'newtransaction'
];

$transactionId = $collection->createTransaction($params);
```

**getTransaction()** opération permettant de récupérer les détails de la transaction et du statut

```php
$transaction = $collection->getTransaction($transactionId);
```

**getAccountBalance()** opération permettant d'obtenir le solde de son propre compte.

```php
$balance = $collection->getAccountBalance();
```

**getBasicUserInfo()** opération permettant de renvoyer les informations personnelles du titulaire du compte. L'opération ne nécessite aucun consentement de la part du titulaire du compte.

```php
$numberMoMo = '46733123460';
$user_info = $collection->getBasicUserInfo($numberMoMo);
```

---

### Disbursement : Coming Soon

---

### Remittance : Coming Soon

**NB : J'ai décidé de laisser l'implémentation de l'API de disbursement et de remittance pour l'environnement sandbox afin de permettre à d'autres développeurs de contribuer. Cela ouvre la voie à une collaboration plus large et à l'apport de nouvelles perspectives pour compléter cette partie du projet.**

**Ceux qui souhaitent l'avoir en production, peuvent me contacter à [ana@roazagba.me](mailto:ana@roazagba.me)**

**Merci de votre compréhension.**
