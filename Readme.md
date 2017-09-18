# French Departments

## Français

Un import à usage unique des départements français, qui seront gérés comme les états d'un pays.

### Installation

#### Manuelle

* Copiez le module dans le dossier ```<thelia_root>/local/modules/``` et assurez-vous que le nom du module soit bien FrenchDepartments.
* Activez-le depuis l'écran d'administration de votre Thelia.

#### Composer

```
composer require thelia/french-departments-module:~1.0
```

### Usage

Activez simplement le module une fois et vérifiez que les départements ont bien été importés dans le menu de Configuration `États / Provinces`.

Une fois l'import effectué, le module n'est plus utile et peut être désactivé ou supprimé.

#### Utiliser les départements

Si vous souhaitez créer une nouvelle zone de livraison basée sur les départements français, suivez cette démarche :

- dans la configuration du pays France, cochez "Ce pays possède des états / provinces" et enregistrez
- dans les "Zones de livraison", créez autants de zones avec autant de départements que vous le souhaitez
- dans la "Configuration du transport", sélectionnez le module de transport duquel vous voulez configurer les zones de transport
- ajoutez les zones de livraison précédemment créées pour les rendre disponibles pour le module de livraison choisi
- dans la configuration du module de livraison, configurez les prix pour chaque zone que vous avez créées
- assurez-vous de ne pas avoir de conflits entre vos zones de départements et la zone France

### Autre

Si vous réactivez le module alors que les départements sont déjà importés, rien ne se passera (en réalité, si le pays France a un état associé, rien ne se passera).

Les codes ISO des départements français suivent ce format : "FR-XX", mais la base de données ne sauvegarde que 4 caractères. Seul la partie "XX" est donc enregistrée pour gérer cette spécificité.


## English

A one shot import of french departments, which will be handled like country's states.

### Installation

#### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is FrenchDepartments.
* Activate it in your thelia administration panel.

#### Composer

Add it in your main thelia composer.json file

```
composer require thelia/french-departments-module:~1.0
```

### Usage

Just activate it once and check that departments have been well imported in the `States / Provinces` Configuration menu.

Once the import is done, the plugin is not useful anymore: you can deactivate or delete it.

#### Using departments

If you want to create shipping zones based on french departments, do the following:

- in the France country configuration, check "This country has states / provinces " and save
- in the "Shipping zones management", create as many zones with as many departments as you want
- in the "Shipping configuration", select the delivery module you want to configure shipping zones for
- add the new shipping zones previously created to be available for the selected delivery module
- in the delivery module configuration, configure your prices for each departments zone you've just created
- be sure not to have conflicts between global France zone (maybe disable it) and departments zones

### Other

If you reactivate the plugin while departments are already imported, nothing will happen (in fact, if any `state` is associated to France country, nothing will happen).

French departments' ISO codes follow this format: 'FR-XX', but the database only saves 4 characters. Only 'XX' is saved to handle this specifity.
