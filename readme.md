Rozjetí projektu
----------------
Stažení závislostí projektu

    composer install

Stažení knihoven a balíčku do node_modules

    npm install

Transformace stylů a překlad SASS na CSS

    npx webpack

Nastavení Databáze
------------------

Ve složce `config/local.neon`

    database:
	dsn: 'mysql:host=127.0.0.1;dbname=*nazev databaze*'
	user: *jméno uživatele*
	password: *heslo k databázi*

