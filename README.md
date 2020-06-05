# API BitCoin

API feita para aprender e aperfeiçoar conhecimento em determinadas tecnologias. 

Fiquem a vontade para usar como desejar ou tomar como base de projetos.

Tecnologias usadas: 
- [JWT](https://jwt.io/)
- [Lumen]( https://lumen.laravel.com/ )
- [Docker]( https://www.docker.com/ )
- [Composer]( https://getcomposer.org/ )
- [ORM Laravel]( https://laravel.com/docs/7.x/eloquent )
- [SendGrid API]( https://sendgrid.com/docs/API_Reference/index.html )



### Objetivo
Desenvolver uma API de investimento em bitcoins

# Documentação
Acesse a documentação do Postman
[Documentação API Bitcoin](https://documenter.getpostman.com/view/1190868/SztHYkp7?version=latest)

# Instalação

### Clone
```shell
git clone https://github.com/glauberborges/Api-BitCoin.git
```
### Dependência
Instale as dependência com composer 

```shell
composer install
```
### Docker

Construa e suba o servidores host e banco de dados com Docker

```shell
docker-compose build 
```

```shell
docker-compose up 
```

### Migration
Acesse o container do Docker

Copie o arquivo .env.example > .env

```shell
cp .env.example .env
```

Faça a configuração do .env

```shell
docker exec -it <ID_CONTAINER> /bin/sh
```

Navegue até a pasta do projeto e execute:

```shell
php artisan migrate
```

### Cron*
FakeCron, foi criado um loop em bash para imitar o CRON, para executar acesse ` cd www` e execute, deixe em uma aba executado o loop 

```shell
 sh fakecron.sh 
 ```

Acesse a aplicação em seu browser

```shell
http://localhost:8888/
```

