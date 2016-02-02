<?php

require_once('Rest.inc.php');
require_once('../config.inc');

class API extends REST
{
	public $data = "";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Dynmically call the method based on the query string.
	 */
	public function processApi()
	{
		$func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
		if ((int)method_exists($this, $func) > 0) {
			$this->$func();
		} else {
			$this->response('', 404); // If the method not exist with in this class "Page not found".
		}
	}

	/**
	 * Encode array into Json.
	 */
	private function json($data)
	{
		if (is_array($data)) {
			return json_encode($data);
		}
	}


	private function imprime($funcao = '', $variavel = '', $valor = '')
	{
		$saida = '';
		if (strlen($funcao) > 0) {
			$saida = $saida . 'Function: ' . $funcao . '  |  ';
		}
		if (strlen($variavel) > 0) {
			$saida = $saida . 'Var: ' . $variavel . '  |  ';
		}
		$saida = $saida . 'Value: ' . $valor . '<br>';
		echo $saida;
	}


	private function contatos() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$objetos = DBJson::searchContatos();
		if (!empty($objetos)) {
			$this->response($this->json($objetos), API::STATUS_OK);
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	private function telefones() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$objetos = DBJson::searchTelefones();
		if (!empty($objetos)) {
			$this->response($this->json($objetos), API::STATUS_OK);
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	private function emails() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$objetos = DBJson::searchEmails();
		if (!empty($objetos)) {
			$this->response($this->json($objetos), API::STATUS_OK);
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	/**
	 * Recebe um parametro no formato:
	 * contato?id=valor
	 */
	private function contato() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idContato = (int)$this->_request['id'];
		if ($idContato > 0) {
			$objeto = DBJson::getContatoByID($idContato);
			if (!empty($objeto)) {
				$this->response($this->json($objeto), API::STATUS_OK);
			}
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	private function telefone() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idTelefone = (int)$this->_request['id'];
		if ($idTelefone > 0) {
			$objeto = DBJson::getTelefoneByID($idTelefone);
			if (!empty($objeto)) {
				$this->response($this->json($objeto), API::STATUS_OK);
			}
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	private function email() // OK
	{
		if ($this->get_request_method() !== 'GET') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idEmail = (int)$this->_request['id'];
		if ($idEmail > 0) {
			$objeto = DBJson::getEmailByID($idEmail);
			if (!empty($objeto)) {
				$this->response($this->json($objeto), API::STATUS_OK);
			}
		}
		$this->response('', API::STATUS_NO_CONTENT);
	}

	/**
	 * Recebe um JSON no formato:
	 * {
	 *     "nome":"valor",
	 *     "sobrenome":"valor"
	 * }
	 */
	private function insertContato() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		if (!empty($json)) {
			$objeto = DBJson::jsonToContato($json);
			$objeto->correctNullFields();
			$objeto = DBObject::insertContato($objeto);
			if (!is_null($objeto)) {
				$success = array(
					'status' => 'Sucesso',
					'msg' => 'Contato criado com sucesso.',
					'data' => DBJson::contatoToJson($objeto)
				);
				$this->response($this->json($success), API::STATUS_OK);
			} else {
				$this->response('', API::STATUS_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}

	private function insertTelefone() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		if (!empty($json)) {
			$objeto = DBJson::jsonToTelefone($json);
			$objeto->correctNullFields();
			$objeto = DBObject::insertTelefone($objeto);
			if (!is_null($objeto)) {
				$success = array(
					'status' => 'Sucesso',
					'msg' => 'Telefone criado com sucesso.',
					'data' => DBJson::telefoneToJson($objeto)
				);
				$this->response($this->json($success), API::STATUS_OK);
			} else {
				$this->response('', API::STATUS_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}

	private function insertEmail() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		if (!empty($json)) {
			$objeto = DBJson::jsonToEmail($json);
			$objeto->correctNullFields();
			$objeto = DBObject::insertEmail($objeto);
			if (!is_null($objeto)) {
				$success = array(
					'status' => 'Sucesso',
					'msg' => 'Email criado com sucesso.',
					'data' => DBJson::emailToJson($objeto)
				);
				$this->response($this->json($success), API::STATUS_OK);
			} else {
				$this->response('', API::STATUS_INTERNAL_SERVER_ERROR);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}

	/**
	 * Recebe um JSON no formato:
	 * {
	 *     "id":valor,
	 *     "contato":{
	 *         "nome":"valor",
	 *         "sobrenome":"valor"
	 *     }
	 * }
	 */
	private function updateContato() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		// obtendo o id do objeto:
		$idContato = (int)$json['id'];
		// obtendo o json do objeto com os novos valores:
		$json_objeto = $json['contato'];
		// convertendo o json do objeto para um objeto:
		$objeto = DBJson::jsonToContato($json_objeto);
		// garantindo que nao haja tentativa de alteracao do id do objeto:
		$objeto->setId($idContato);
		// corrigindo os campos do objeto:
		// comentado: ao corrigir os campos null, estava alterando campos no banco de dados que nao havia sido
		//            passados pelo JSON, fazendo com que eles ficassem em branco no banco de dados.
		//$objeto->correctNullFields();
		// AQUI JA TEMOS O 'ID' E O 'OBJETO' COM OS NOVOS VALORES!

		if (DBObject::updateContato($objeto)) {
			$success = array(
				'status' => 'Sucesso',
				'msg' => 'Contato (' . $idContato . ') atualizado com sucesso.'
			);
			$this->response($this->json($success), API::STATUS_OK);
		} else {
			$this->response('', API::STATUS_NOT_FOUND);
		}
	}

	private function updateTelefone() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		// obtendo o id do objeto:
		$idTelefone = (int)$json['id'];
		// obtendo o json do objeto com os novos valores:
		$json_objeto = $json['telefone'];
		// convertendo o json do objeto para um objeto:
		$objeto = DBJson::jsonToTelefone($json_objeto);
		// garantindo que nao haja tentativa de alteracao do id do objeto:
		$objeto->setId($idTelefone);
		// corrigindo os campos do objeto:
		// comentado: ao corrigir os campos null, estava alterando campos no banco de dados que nao havia sido
		//            passados pelo JSON, fazendo com que eles ficassem em branco no banco de dados.
		//$objeto->correctNullFields();
		// AQUI JA TEMOS O 'ID' E O 'OBJETO' COM OS NOVOS VALORES!

		if (DBObject::updateTelefone($objeto)) {
			$success = array(
				'status' => 'Sucesso',
				'msg' => 'Telefone (' . $idTelefone . ') atualizado com sucesso.'
			);
			$this->response($this->json($success), API::STATUS_OK);
		} else {
			$this->response('', API::STATUS_NOT_FOUND);
		}
	}

	private function updateEmail() // OK
	{
		if ($this->get_request_method() !== 'POST') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$json = json_decode(file_get_contents("php://input"), true);
		// obtendo o id do objeto:
		$idEmail = (int)$json['id'];
		// obtendo o json do objeto com os novos valores:
		$json_objeto = $json['email'];
		// convertendo o json do objeto para um objeto:
		$objeto = DBJson::jsonToEmail($json_objeto);
		// garantindo que nao haja tentativa de alteracao do id do objeto:
		$objeto->setId($idEmail);
		// corrigindo os campos do objeto:
		// comentado: ao corrigir os campos null, estava alterando campos no banco de dados que nao havia sido
		//            passados pelo JSON, fazendo com que eles ficassem em branco no banco de dados.
		//$objeto->correctNullFields();
		// AQUI JA TEMOS O 'ID' E O 'OBJETO' COM OS NOVOS VALORES!

		if (DBObject::updateEmail($objeto)) {
			$success = array(
				'status' => 'Sucesso',
				'msg' => 'Email (' . $idEmail . ') atualizado com sucesso.'
			);
			$this->response($this->json($success), API::STATUS_OK);
		} else {
			$this->response('', API::STATUS_NOT_FOUND);
		}
	}

	/**
	 * Recebe um parametro no formato:
	 * deleteContato?id=valor
	 */
	private function deleteContato() // OK
	{
		if ($this->get_request_method() !== 'DELETE') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idContato = (int)$this->_request['id'];
		if ($idContato > 0) {
			$objeto = DBManual::getContatoByID($idContato);
			if (!is_null($objeto)) {
				if (DBObject::deleteContato($objeto)) {
					$success = array(
						'status' => 'Sucesso',
						'msg' => 'Contato deletado com sucesso.'
					);
					$this->response($this->json($success), API::STATUS_OK);
				} else {
					$this->response('', API::STATUS_NO_CONTENT);
				}
			} else {
				$this->response('', API::STATUS_NOT_FOUND);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}

	private function deleteTelefone() // OK
	{
		if ($this->get_request_method() !== 'DELETE') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idTelefone = (int)$this->_request['id'];
		if ($idTelefone > 0) {
			$objeto = DBManual::getTelefoneByID($idTelefone);
			if (!is_null($objeto)) {
				if (DBObject::deleteTelefone($objeto)) {
					$success = array(
						'status' => 'Sucesso',
						'msg' => 'Telefone deletado com sucesso.'
					);
					$this->response($this->json($success), API::STATUS_OK);
				} else {
					$this->response('', API::STATUS_NO_CONTENT);
				}
			} else {
				$this->response('', API::STATUS_NOT_FOUND);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}

	private function deleteEmail() // OK
	{
		if ($this->get_request_method() !== 'DELETE') {
			$this->response('', API::STATUS_NOT_ACCEPTABLE);
		}
		$idEmail = (int)$this->_request['id'];
		if ($idEmail > 0) {
			$objeto = DBManual::getEmailByID($idEmail);
			if (!is_null($objeto)) {
				if (DBObject::deleteEmail($objeto)) {
					$success = array(
						'status' => 'Sucesso',
						'msg' => 'Email deletado com sucesso.'
					);
					$this->response($this->json($success), API::STATUS_OK);
				} else {
					$this->response('', API::STATUS_NO_CONTENT);
				}
			} else {
				$this->response('', API::STATUS_NOT_FOUND);
			}
		} else {
			$this->response('', API::STATUS_NO_CONTENT);
		}
	}
}


// Initialize library
$api = new API;
$api->processApi();
