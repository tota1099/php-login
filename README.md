# Login PHP

[![Build Status](https://travis-ci.com/tota1099/php-login.svg?branch=main)](https://travis-ci.com/tota1099/php-login)
[![Coverage Status](https://coveralls.io/repos/github/tota1099/php-login/badge.svg?branch=main)](https://coveralls.io/github/tota1099/php-login?branch=main)

Esqueleto de um sistema baseado em módulos e permissões de acesso, construido com clean architecture e testes.

## Arquitetura e Stack

A arquitetura do projeto está dividida em camadas, sendo elas: 

- **Domain:** Entidades do sistema e contratos de casos de usos
- **Use Cases:** Essa camada é a que contém as regras de negócios mais específicas do sistema. Aqui que todos os casos de uso do sistema são implementados. Apenas mudanças de requisitos afetem essa camada
- **Infra:** Implementações de adapters para frameworks e bibliotecas externas
- **Presentation:** A porta de entrada da nossa aplicação. No nosso caso, disponibilizamos os nossos recursos em endpoints HTTP, mas nada impede no futuro de disponibilizarmos via terminal por exemplo.
- **Main:** Aqui é feito a montagem de todo o quebra cabeça. Esta camada possui um auto grau de acoplamento.
- **Utils:** Implementações de recursos genéricos úteis no sistema (Exemplo: Email Validator, Encrypter)

A stack do projeto é:

- PHP 8.0.0
- MYSQL 5.6
- PHP SQLite3 (utilizado nos testes unitários)

Utilizamos as seguintes bibliotecas no projeto:

* DEV
  * "phpunit/phpunit": Framework de teste
  * "fakerphp/faker": Biblioteca para gerar dados
  * "php-coveralls/php-coveralls": Biblioteca para gerar relatórios de cobertura de teste
  * "php-mock/php-mock-phpunit": Utilizado para fazer mock de funções nativas do PHP

* PRODUÇÃO
  * "robmorgan/phinx": Gerenciador de migrations de banco de dados
  * "vlucas/phpdotenv": Gerenciar variaveis de ambiente
- - - -

## Execução do código

Para execução do serviço é necessário configurar as variáveis de ambiente:

* DATABASE_URI: endereço de conexão com o banco de dados MYSQL (Exemplo: "mysql:host=database;dbname=project")
* DATABASE_USER: usuário do banco de dados
* DATABASE_PASSWORD: senha do banco de dados 

O projeto está configurado para execução com o docker-compose. Para rodar o projeto, execute:

```bash
$ docker-compose up -d
```

Após subir o ambiente, devemos rodar o gerenciador de migration para criar as tabelas do banco de dados:

```bash
$ docker exec -it php-apache ./vendor/bin/phinx migrate
```

Agora basta entrar no `http://localhost/` no browser!

- - - -
## Testes

Os testes automatizados são executados através da ferramenta PHPUNIT. Para rodar basta executar:

```bash
$ docker exec -it php-apache ./vendor/bin/phinx migrate -e testing
$ docker exec -it php-apache ./vendor/bin/phpunit --testdox
```
