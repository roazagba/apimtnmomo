# Guide de Contribution

Merci de vouloir contribuer à ce package Laravel ! Ce guide vous expliquera comment participer en signalant des bugs, en proposant des fonctionnalités ou en soumettant du code. Suivez les étapes ci-dessous pour rendre le processus fluide et efficace.

## Table des Matières

- [Comment Contribuer](#comment-contribuer)
- [Signalement de Bugs](#signalement-de-bugs)
- [Propositions de Fonctionnalités](#propositions-de-fonctionnalités)
- [Configuration de l'Environnement de Développement](#configuration-de-lenvironnement-de-développement)
- [Processus de Soumission](#processus-de-soumission)
- [Normes de Code](#normes-de-code)
- [Tests](#tests)

---

## Comment Contribuer

1. **Forkez** ce dépôt.
2. **Clonez** le fork sur votre machine locale :
   ```bash
   git clone https://github.com/votre-utilisateur/apimtnmomo.git
   ```
3. Créez une **branche** dédiée pour votre fonctionnalité ou correction de bug :
   ```bash
   git checkout -b feature/newadd
   ```
4. Effectuez vos modifications dans la branche.
5. **Commitez** vos changements avec un message descriptif :
   ```bash
   git add .
   git commit -m "Ajout d'une nouvelle fonctionnalité"
   ```
6. **Pushez** la branche vers votre fork :
   ```bash
   git push origin feature/newadd
   ```
7. Ouvrez une **Pull Request** (PR) vers le dépôt original.

## Signalement de Bugs

Si vous trouvez un bug, merci de créer une **issue** et de fournir les informations suivantes :

1. Version de Laravel et de PHP que vous utilisez.
2. Description du problème.
3. Étapes pour reproduire le bug.
4. Comportement attendu et comportement observé.
5. Si possible, des logs ou messages d'erreur.

## Propositions de Fonctionnalités

Pour suggérer une nouvelle fonctionnalité, veuillez :

1. Ouvrir une **issue** avec un titre clair.
2. Expliquer clairement la fonctionnalité proposée.
3. Décrire pourquoi cette fonctionnalité est importante et utile.
4. Donner des exemples d’utilisation ou des scénarios d’application.

## Configuration de l'Environnement de Développement

Sera très bientôt très bientôt

## Processus de Soumission

Pour soumettre une Pull Request :

1. Assurez-vous que votre code est propre et respecte les **normes de code**.
2. Ajoutez des **tests** pour vos changements si nécessaire.
3. Vérifiez que les tests existants passent.
4. Fournissez une description claire de vos changements dans la Pull Request.

## Normes de Code

Nous suivons les conventions de codage Laravel. Voici quelques points clés :

- **PSR-12** pour les normes de code PHP.
- Utilisez **camelCase** pour les noms de méthodes et de variables et **snake_case** pour les variables uniquement.
- Respectez l’indentation et la lisibilité du code.
- Utilisez **DocBlocks** pour documenter les classes, méthodes et propriétés complexes.

## Tests

Les tests sont essentiels pour assurer la qualité du code. Assurez-vous que tout nouveau code est accompagné de tests unitaires ou fonctionnels. Pour exécuter les tests :

1. Créez vos Tests Unitaires dans le répertoire `tests/Unit` et vos Tests Fonctionnels `test/Feature`.
2. Exécutez les tests avec PHPUnit :
   ```bash
   ./vendor/bin/phpunit
   ```

Assurez-vous que tous les tests réussissent avant de soumettre une Pull Request.
