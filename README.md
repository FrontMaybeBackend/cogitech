Aby uruchomić projekt

git clone https://github.com/FrontMaybeBackend/cogitech.git

cd rekrutacja

composer install

następnie:
skonfigurować połączenie z swoją lokalną bazą danych
stworzyć baze danych
php bin/console doctrine:database:create

po stworzeniu trzeba  wykonać migracje:
php bin/console doctrine:migrations:migrate

i pokolei wywołać dwie komendy aby pobrać dane z rest api.

php bin/console app:get-users

php bin/console app:get-posts

symfony server:start

localhost/lista przekierowuje na localhost/login gdzie musimy wpisac dane usera pobranego z api, hasło to dodane słowo 'test' do username:
np:
username:Bret
password:Brettest
localhost/api/posts wyświetla posty pobrane z bazy
