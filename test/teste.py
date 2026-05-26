import json
import unittest
import urllib.request
import urllib.error
import urllib.parse

BASE_URL = "http://localhost:3000/api/v1/clima"


class TestWeatherAPI(unittest.TestCase):

    def _get(self, cidade: str):
        """Faz GET em /api/v1/clima/<cidade> e devolve (status_code, body_dict)."""
        url = f"{BASE_URL}/{urllib.parse.quote(cidade)}"
        try:
            with urllib.request.urlopen(url, timeout=10) as r:
                return r.status, json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            return e.code, json.loads(e.read().decode())

    # ──────────────────────────────────────────────────────────────────────────
    # Teste 1 – Cidade válida → retorna previsão (HTTP 200)
    # ──────────────────────────────────────────────────────────────────────────
    def test_cidade_valida_retorna_previsao(self):
        status, body = self._get("maracanau")

        self.assertEqual(200, status)
        self.assertIn("nome",   body)
        self.assertIn("estado", body)
        self.assertIn("clima",  body)
        self.assertIn("temperatura_min",  body["clima"])
        self.assertIn("temperatura_max",  body["clima"])
        self.assertIn("condicao",         body["clima"])
        self.assertEqual("°C", body["clima"]["unidades"]["temperatura"])
        self.assertIn("consultado em: ",  body)

    # ──────────────────────────────────────────────────────────────────────────
    # Teste 2 – Nome com menos de 2 caracteres → erro 400
    # ──────────────────────────────────────────────────────────────────────────
    def test_nome_curto_retorna_erro_400(self):
        status, body = self._get("X")

        self.assertEqual(400, status)
        self.assertTrue(body["erro"])
        self.assertEqual("NOME_INVALIDO", body["codigo"])
        self.assertIn("2 caracteres",     body["mensagem"])


if __name__ == "__main__":
    unittest.main(verbosity=2)
