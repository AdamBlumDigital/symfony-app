# Installation

This documentation ssumes one has installed both 
[Composer](https://getcomposer.org/) and the 
[Symfony CLI](https://symfony.com/download).

Install the necessary Composer packages and 
Symfony Flex recipes:

```bash
composer require annotations twig logger symfony/orm-pack symfony/translation twig/intl-extra knplabs/knp-menu-bundle symfony/messenger symfony/redis-messenger symfony/security-bundle symfony/security-csrf symfony/form symfony/filesystem symfony/string symfony/uid symfony/validator symfony/workflow stof/doctrine-extensions-bundle symfony/asset symfony/lock symfony/notifier
composer require --dev symfony/maker-bundle symfony/test-pack orm-fixtures symfony/profiler-pack 
```

```
sudo su - postgres
```

```
createuser username -P
```

Then go into a Postgres command line and assign `CREATEDB`
priviledges to the newly created user:

```
ALTER ROLE username CREATEDB
```
