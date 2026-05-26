import json
import unittest
import urllib.request
import urllib.error
import urllib.parse

BASE_URL = "http://localhost:3000/api/v1/cidades"


class TestCidadesAPI(unittest.TestCase):

    def _get(self, uf: str):
        """Faz GET em /api/v1/cidades/<uf> e devolve (status_code, body_dict)."""
        url = f"{BASE_URL}/{urllib.parse.quote(uf)}"
        try:
            with urllib.request.urlopen(url, timeout=10) as r:
                return r.status, json.loads(r.read().decode())
        except urllib.error.HTTPError as e:
            return e.code, json.loads(e.read().decode())

    # ──────────────────────────────────────────────────────────────────────────
    # Teste 1 – UF válida → retorna lista de cidades (HTTP 200)
    # ──────────────────────────────────────────────────────────────────────────
    def test_uf_valida_retorna_cidades(self):
        status, body = self._get("ce")

        self.assertEqual(200, status)
        self.assertEqual("ce",          body["uf"])
        self.assertIn("cidades",        body)
        self.assertIn("consultado em",  body)

        # Cada item da lista deve ter o campo "nome"
        self.assertTrue(len(body["cidades"]) > 0, "Lista de cidades não pode ser vazia")
        for cidade in body["cidades"]:
            self.assertIn("nome", cidade)

    # ──────────────────────────────────────────────────────────────────────────
    # Teste 2 – UF inexistente → erro 404
    # ──────────────────────────────────────────────────────────────────────────
    def test_uf_inexistente_retorna_erro_404(self):
        status, body = self._get("xx")  # sigla que não existe

        self.assertEqual(404, status)
        self.assertTrue(body["erro"])
        self.assertEqual("UF_NAO_ENCONTRADA",                      body["codigo"])
        self.assertEqual("Estado com a sigla informada não foi encontrado", body["mensagem"])
        self.assertEqual("xx",                                     body["sigla_uf_informada"])


if __name__ == "__main__":
    unittest.main(verbosity=2)
