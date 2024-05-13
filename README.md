##
## Client Maneger - Gerenciador de Clientes
##
## Testar no ambiente local:

Faça o clone do repositório na sua máquina

```bash
git clone git@github.com:guibmolina/client-maneger.git
```

### Configuração do ambiente
***

**Para configuração do ambiente é necessário ter o [Docker](https://docs.docker.com/desktop/) instalado em sua máquina.**

Dentro da pasta do projeto, rode o seguinte comando: `docker-compose up -d`.

Copie o arquivo `.env.example` e renomeie para `.env` dentro da pasta raíz da aplicação.

```bash
cp .env.example .env
```

Após criar o arquivo `.env`, será necessário acessar o container da aplicação para rodar alguns comandos de configuração do Laravel.

Para acessar o container use o comando `docker exec -it client-maneger-api bash`.

Digite os seguintes comandos dentro do container:

```bash
composer install
php artisan key:generate
php artisan migrate
```
Feito os passos, basta acessar: http://localhost

## Endpoints

###  Criação de cliente

Requisição
```
POST  http://localhost/api/v1/clients

{
	"name":"Guilherme Molina",
	"cpf":"90106870076",
	"birth_date":"1999/12/13",
	"phone":"11989917556"
}

```
Resposta
```
{
	"id": 1,
	"name": "Guilherme Molina",
	"cpf": "90106870076",
	"birth_date": "1999-12-13",
	"phone": "11989917556"
}
```
Campo       | Tipo      | Obrigatório   
----------- | :------:  | :------:        
name        | string    	 | true                 
cpf	        |string          | true      
birth_date  | string    	 | true 
phone		| integer    	 | false          



###  Listar Clientes

Requisição
```bash
GET  http://localhost/api/v1/clients
```
Resposta
```
[
	{
		"id": 1,
		"name": "José",
		"cpf": "14843659096",
		"birth_date": "2006-03-09",
		"phone": "1132321672"
	},
	{
		"id": 2,
		"name": "Larissa",
		"cpf": "39725980093",
		"birth_date": "2000-02-24",
		"phone": "11983445641"
	}
]
```
#### Filtros
É possível combinar filtros na listagem  de clientes

##### Filtar por nome
```bash
GET  http://localhost/api/v1/clients?name=Jose
```
##### Filtrar por CPF
```bash
GET  http://localhost/api/v1/clients?cpf=14843659096
```


###  Exclusão de Cliente

Requisição
```bash
DELETE  http://localhost/api/v1/clients/{client_id}
```

***
### Insomnia
Segue no repositório o arquivo .json da collection do Insomnia com todas as requisições (client-maneger-requests-insomnia.json)
