# French Departments

A one shoot import of French departments, which will be handled like country's states.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is FrenchDepartments.
* Activate it in your thelia administration panel.

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/french-departments-module:~1.0
```

## Usage

Just activate it once and check that departments have been well imported in the `States / Provinces` Configuration menu.

Once the import is done, the plugin is not useful anymore: you can deactivate or delete it.

## Other

If you reactivate the plugin while departments are already imported, nothing will happen (in fact, if any `state` is associated to France country, nothing will happen).

French departments' ISO codes follow this format: 'FR-XX', but the database only saves 4 characters. Only 'XX' is saved to handle this specifity.
