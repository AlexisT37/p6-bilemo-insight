# start.ps1

docker-compose -f docker-compose.test.yml up --build -d

docker exec p6-bilemo-php-1 php bin/console --env=test doctrine:schema:create
docker exec p6-bilemo-php-1 php bin/console doctrine:schema:update --force
docker exec p6-bilemo-php-1 php bin/console doctrine:fixtures:load --no-interaction
docker-compose -f docker-compose.test.yml exec php ./vendor/bin/phpunit --colors --testdox