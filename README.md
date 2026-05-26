# integra-o-sistemas
Olá, o seguinte trabalho tem o objetivo de integrar API'S públicas, as quais trazem informações de cidades do estado e informações sobre o clima de determinada cidade.

Stack utilizada:
PHP 8.2.12
XAMPP compatível PHP 8.2.12
Composer 2.9.8
Python 3.14.4 

Configuração: 
Extrair arquivos da pasta src para a pasta htdocs do Xampp e startar servidor na porta adequada. (Alterar porta no httpd.conf 
-> linha: ServerName localhost:3000 
          #Listen 12.34.56.78:3000
          Listen 3000)

segue exemplo de execução:
I) Startar servidor Xampp
II) acessar navegador no link desejado: http://localhost/api/v1/cidades (seguir url padrão do trabalho) 

Para teste automatizado utilizar python 3.14.4 e rodar código.
