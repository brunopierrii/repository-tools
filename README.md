# Repository-tools

É uma API de um repositório feito para quem não quer perder ferramentas muito úteis, para desenvolvimento, ou para o dia a dia, uma bela dica para quem se perde em meio de tantas abas no navegador.

### Requisitos

A api é feita em symfony, portanto os principais requisitos são:
- Symfony instalado em sua máquina
> Documentação para instalação do symfony e todos os seu requisitos, [Symfony Docs](https://symfony.com/)

### Iniciando projeto em sua máquina

- Instalação de todas as depêndencias
  
`$ composer install`

- Gerando keys JWT privadas e públicas da api (garanta que o php está no PATH do seu SO)

`$ php bin/console lexik:jwt:generate-keypair`

### End-points
- Listar registros: `/api/tools` (GET)
  ```json
  [
    {
      "id": 1,
      "title": "fastify",
      "link": "https://www.fastify.io/",
      "description": "Extremely fast and simple, low-overhead web framework for NodeJS. Supports HTTP2.",
      "tags": [
        "web",
        "framework",
        "node",
        "http2",
        "https",
        "localhost"
      ]
    },
    {
      "id": 2,
      "title": "GitHub",
      "link": "https://www.github.com/",
      "description": "Extremely fast and simple, low-overhead web framework for NodeJS. Supports HTTP2.",
      "tags": [
        "web",
        "framework",
        "node",
        "http2",
        "https",
        "localhost"
      ]
    },
    {
        "id": 3,
        "title": "json-server",
        "link": "https://github.com/typicode/json-server",
        "description": "Fake REST API based on a json schema. Useful for mocking and creating APIs for front-end devs to consume in coding challenges.",
        "tags": [
            "api",
            "json",
            "schema",
            "node",
            "github",
            "rest"
        ]
      }
    ]
  
- Pesquisar registros por tag: `/api/tag/{tagName}` (GET) `/api/tag/framework` (framework é a tag sendo buscada neste exemplo)
  ```json
  [
    {
      "id": 1,
      "title": "fastify",
      "link": "https://www.fastify.io/",
      "description": "Extremely fast and simple, low-overhead web framework for NodeJS. Supports HTTP2.",
      "tags": [
        "web",
        "framework",
        "node",
        "http2",
        "https",
        "localhost"
      ]
    },
    {
      "id": 2,
      "title": "GitHub",
      "link": "https://www.github.com/",
      "description": "Extremely fast and simple, low-overhead web framework for NodeJS. Supports HTTP2.",
      "tags": [
        "web",
        "framework",
        "node",
        "http2",
        "https",
        "localhost"
      ]
    }
  ]
   
- Novo registro: `/api/tools/new` (POST)
  ```json
  {
    "title": "Linkedin",
    "link": "https://www.linkedin.com/",
    "description": "Midia Social",
    "tags": [
        "web"
    ]
  }

- Editar registro: `/api/tools/edit/{id}` (PUT)
  ```json
  {
    "title": "Linkedin",
    "link": "https://www.linkedin.com/",
    "description": "Midia Social",
    "tags": [
        "web"
    ]
  }

- Deletar registro: `/api/tools/delete/{id}` (DELETE)
  ```json
  {}
  
#### End-points (Autenticação/autorização)

- Novo usuário: `/api/app/login` (POST)
  ```json
  {
    "name": "Custumer",
    "email": "custumer@tech.com",
    "password": "password@exemple"
  }

- Login jwt autenticate: `/api/login` (POST) `Response: { "token": "token_jwt" }`
  ```json
  {
    "username": "teste@teste.com",
    "password": "123123"
  }
