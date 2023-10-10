# Repository-tools

É uma API de um repositório feito para quem não quer perder ferramentas muito úteis, para desenvolvimento, ou para o dia a dia, uma bela dica para quem se perde em meio de tantas abas no navegador.

### End-points

- /api/tools (GET)

### Requisitos

A api é feita em symfony, portanto os principais requisitos são:
- Symfony instalado em sua máquina
> Documentação para instalação do symfony e todos os seu requisitos, [Symfony Docs](https://symfony.com/)

### Iniciando projeto em sua máquina

- Instalação de todas as depêndencias
  
`$ composer install`

- Gerando keys JWT privadas e públicas da api (garanta que o php está no PATH do seu SO)

`$ php bin/console lexik:jwt:generate-keypair`
