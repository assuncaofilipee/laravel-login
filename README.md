# API Laravel

## Sobre

API Backend responsável por controle de acesso (Login) e troca de senha.

## Tecnologias

 - [Laravel](https://laravel.com)
 - [Mysql](https://www.mysql.com)

## Clonar Repositório

```bash
    $ git clone https://github.com/filipeassuncao/laravel-login.git
```
## Configuração

### Dependências:

Ao clonar o repositório, certifique-se de possuir o docker e docker-compose instalados e configurados em sua máquina local.

### Configuração de variáveis de ambientes (.env)

Execute o comando abaixo para criar o arquivo de configuração .env:

```bash
    $ cp .env.example .env
```

* No arquivo .env, defina as senhas do usuário padrão mysql e usuário root:
    * DB_PASSWORD= senha usuário mysql
    * DB_PASSWORD_ROOT= senha usuário root

### Executando:

No arquivo Makefile, na pasta raiz do projeto, se encontram diversos comandos reduzidos para facilitar a execução dos mesmos.

Na primeira vez que for iniciar o container, rode o comando que irá realizar a configuração do ambiente onde será executado a aplicação:


```bash
    $ make buildd
```

Em sua máquina, a url default será http://localhost:8080/

Para iniciar o contaier novamente, caso esteja inativo, basta rodar o comando make upd


