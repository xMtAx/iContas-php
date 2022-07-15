<p align="center"><img src="https://i.imgur.com/KQkLiAT.png" width="50"></p>
<h1 align="center">iConta$ Laravel</h1>

## Versões

As versões dos frameworks e pacotes:

- Laravel Framework 8.60.0
- PHP ^7.4
- Bootstrap 5.1
- jQuery 3.6.0
- Sweetalert 2
- Bootstrap Icons 1.7.2

## Começando

As instruções a seguir vão adicionar uma cópia do projeto na sua máquina local para testes e desenvolvimento.

### Pré-requisitos

- Você precisa dos seguintes serviços instalados no seu computador:

```
GIT
PHP ^7.4
Servidor de banco de dados (MySQL, Postgress, etc...)
Composer (https://getcomposer.org/)
```

### Instalando

- Primeiramente é necessária uma base de dados, para isso é preciso criar uma:

```
CREATE DATABASE icontas;
GRANT ALL PRIVILEGES ON icontas . * TO 'seu_usuario'@'localhost';
```

- Clone o projeto para sua máquina (coloque na pasta do seu servidor WEB):

```
git clone https://github.com/eduardoplnascimento/iContas-php.git
```

- Entre no diretório **iContas-php**.
- Copie o arquivo .env.example e nomeie .env:

```
cp .env.example .env
```

- Configurar o arquivo .env com as suas informações:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=icontas
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

- Rodar o comando para instalação (pode demorar alguns minutos):

```
composer install
```

- Rodar o comando para gerar a chave do Laravel:

```
php artisan key:generate
```

- Rodar os comandos para migrar o banco de dados:

```
php artisan migrate
```

### Abrir o servidor backend

- Para rodar o servidor backend utilize o comando:

```
php artisan serve
```

## Utilização

- Acessando a URL do projeto, você será direcionado para a página inicial:

![](https://i.imgur.com/VuE1ufC.png)

- A partir dessa página é possível acessar a página de *login*:

![](https://i.imgur.com/3FB2ZwL.png)

- A partir da página de *login*, você pode acessar a página de cadastro:

![](https://i.imgur.com/9te92gX.png)

- Após fazer o *login* ou cadastro, o usuário é redirecionado para a rota ```/dashboard``` onde é possível utilizar o sistema

![](https://i.imgur.com/lRNr1eD.png)
