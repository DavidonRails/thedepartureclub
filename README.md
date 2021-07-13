## HoboJet

### Production build

- git clone

- cp .env.staging .env (only first time)
 
- php artisan migrate --seed (only first time)

- bash build

#### Sync exclude

- .bowerrc
- .env
- .env.staging
- .eslintrc
- .gitatributes
- .gitignore
- .phpstorm.meta.php
- _ide_helper.php
- bower.json
- build
- composer.json
- composer.lock
- gulpfile.js
- package.json
- phpspec.yml
- phpunit.xml
- readme.md
- server.php

- node_modules/
- resources/assets/
- tests/


### Development build

- composer install
- npm install
- bower install
- chmod 777 storage

- cp .env.exsample .env

- gulp devel:admin
- gulp devel:app

### Staging deployment procedure
- cd /var/www/html
- git pull origin develop
- gulp build (in case you updated css or javascript files)
- sudo service apache2 restart