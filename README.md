# univa
To make project working locally : 

1. Clone the repository;
2. Rename docker-compose.yml.dist to docker-compose.yml;
3. Rename symfony/.env.dist to symfony/.env;
4. Run mkdir -p config/jwt
5. Run openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
6. Run openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
7. Copy the value obtained at step 5 and paste it in JWT_PASSPHRASE in symfony/.env
8. Update schema by connecting to docker container and execute : bin/console doctrine:schema:update --force
9. Create an admin user by connecting to docker container and execute : bin/console app:add-user admin@admin.com admin ROLE_ADMIN
10. See api doc : http://univa.localhost/api/doc