# UniPatas

UniPatas é uma plataforma solidária de castração animal desenvolvida como Trabalho de Graduação (TG) do curso de Análise e Desenvolvimento de Sistemas.

O sistema tem como objetivo auxiliar tutores de animais em situação de vulnerabilidade, permitindo o cadastro de animais que necessitam de castração e promovendo a conexão entre tutores, doadores e iniciativas de apoio à causa animal.



## Funcionalidades

### Usuários
- Cadastro de usuários
- Login e logout
- Visualização de perfil

### Animais
- Cadastro de animais
- Upload de foto do animal
- Listagem de animais cadastrados
- Visualização de detalhes do animal
- Edição e exclusão de registros

### Doações
- Registro de doações
- Visualização das doações realizadas pelo usuário



## Tecnologias Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- XAMPP
- phpMyAdmin


## Estrutura do Projeto

```text
unipatas/
│
├── animais/
├── usuarios/
├── doacoes/
├── config/
├── css/
├── imagens/
├── includes/
│
├── index.php
└── README.md
```



## Como Executar o Projeto

### 1. Clonar ou baixar o projeto

Copie a pasta do projeto para:

```text
xampp/htdocs/
```

### 2. Iniciar o XAMPP

Inicie os serviços:

- Apache
- MySQL

### 3. Criar o banco de dados

Abra o phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Crie o banco de dados:

```sql
unipatas
```

### 4. Importar o banco

Importe o arquivo:

```text
unipatas.sql
```

### 5. Executar o sistema

Acesse:

```text
http://localhost/unipatas
```


## Projeto Acadêmico

Projeto desenvolvido para fins acadêmicos como Trabalho de Graduação do curso de Análise e Desenvolvimento de Sistemas.



## Desenvolvedoras

- Maria Eduarda Lucinda
- Verônica Souto Ferreira
