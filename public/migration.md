php bin/console doctrine:database:drop --force

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate 'DoctrineMigrations\Version1'

php bin/console doctrine:migrations:migrate 'DoctrineMigrations\Version2'

php bin/console doctrine:migrations:migrate 'DoctrineMigrations\Version3'

php bin/console doctrine:fixtures:load --append
