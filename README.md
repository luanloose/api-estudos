# Projeto Api Pagamentos

Api de pagamentos 

### Para rodar o projeto será necessário:

1. Clonar o projeto.

2. Rodar o comando `make docker-start` dentro do diretorio raiz do projeto para startar os containers e criar as imagens.

3. Rodar o comando `make docker-stop` dentro do diretorio raiz do projeto para parar os containers.

4. Acesse a CLI do container do php para rodar as migrations com `php artisan migrate`

4. Pronto para brincar no projeto. 

### Testes

1. Suba os containers

2. Rode o comando `docker container exec infra-api_php_1 ../vendor/bin/phpunit ../tests`

### Documentação

1. [Link](https://documenter.getpostman.com/view/14149795/TzRa8Q7q)