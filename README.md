# Projeto GSICredi

Gestão de Sistemas de Informação de Crédito e Consignados.

## Tecnologias

A aplicação é desenvolvida em **Laravel**.

Para mais informações, consultar a documentação no site oficial do framework.

-   php: >= ^7.2
-   Composer: 1.8.4
-   Laravel: 7.0.\*
-   npm: >= 6.9.0

## Implantação

-   Realizar o _clone_ do projeto.

-   Executar o **composer** para a instalação das dependências do php:

    ```
    $ composer install --optimize-autoloader --no-dev
    ```

-   Criar o arquivo de definição das variáveis do sistema, o arquivo _.env_ (o arquivo _.env.example_ possui as variáveis e pode ser copiado):

    ```
    $ cp .env.example .env
    ```

-   Antes de realizar a migração da base de dados deve-se configurar o arquivo **.env** com as variáveis apontadas pela tabela do arquivo **readme**.
-   Criar a base de dados conforme os arquivos de migração do projeto. **A base de dados já deve ter sido criada antes da execução do comando**:

    ```
    $ php artisan migrate --seed
    ```

-   Atribuir a chave da aplicação:

    ```
    $ php artisan key:generate
    ```

-   Configurar o serviço de disponibilização de aplicações.

-   Fim.

### Variáveis principais do arquivo [.env]

| Variável        | Responsabilidade                                                        | Valor                                                   |
| --------------- | :---------------------------------------------------------------------- | :------------------------------------------------------ |
| `APP_DEBUG`     | Permitir ou não reportar o erro encontrado na interface web.            | **Em produção, recomenda-se atribuir o valor [false]**. |
| `APP_URL`       | Endereço eletrônico pela qual a aplicação irá responder.                | -                                                       |
| `APP_VERSION`   | Incrementar mais 1 ao valor a cada atualização.                         | -                                                       |
| `DB_CONNECTION` | Identificação do SGBD: **psql** para _POSTGRES_; **mysql** para _MySQL_ | **mysql**                                                   |
| `DB_HOST`       | Endereço eletrônico do servidor da base de dados.                       | -                                                       |
| `DB_PORT`       | Porta do servidor da base de dados (caso não esteja na porta padrão).   | -                                                       |
| `DB_DATABASE`   | Nome da base de dados.                                                  | **gsicredi**                                                       |
| `DB_USERNAME`   | Usuário do serviço da base de dados.                                    | **root**                                                       |
| `DB_PASSWORD`   | Senha do usuário do serviço da base de dados.                           | -                                                       |

## Observações

-   
