# Integração de Sistemas

Olá, o seguinte trabalho tem o objetivo de integrar APIs públicas, as quais trazem informações de cidades do estado e informações sobre o clima de determinada cidade.

---

## 🧰 Stack utilizada

- PHP 8.2.12  
- XAMPP compatível com PHP 8.2.12  
- Composer 2.9.8  
- Python 3.14.4  

---

## ⚙️ Configuração

1. Extrair os arquivos da pasta `src` para a pasta `htdocs` do XAMPP  
2. Iniciar o servidor Apache pelo XAMPP  
3. Configurar a porta do servidor, se necessário, no arquivo `httpd.conf`:

```conf
ServerName localhost:3000
#Listen 12.34.56.78:3000
Listen 3000
