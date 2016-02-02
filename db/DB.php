<?php

use Doctrine\ORM\UnitOfWork;

class DBHelper
{
	const TABLE_CONTATO = 'Contato';
	const TABLE_TELEFONE = 'Telefone';
	const TABLE_EMAIL = 'Email';

	protected static function getEntityManager()
	{
		return $GLOBALS['entityManager'];
	}

	protected static function getRepository($name)
	{
		return $GLOBALS['entityManager']->getRepository($name);
	}

	protected static function checkEntityState($obj, $unitOfWork)
	{
		return (DBHelper::getEntityManager()->getUnitOfWork()->getEntityState($obj) == $unitOfWork);
	}

	protected static function imprime($funcao = '', $variavel = '', $valor = '')
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
}


class DBObject extends DBHelper
{
	public static function insertContato($objeto)
	{
		parent::getEntityManager()->persist($objeto);
		parent::getEntityManager()->flush();
		return ($objeto->getId() > 0) ? $objeto : null;
	}

	public static function insertTelefone($objeto)
	{
		$contato = DBManual::getContatoByID($objeto->getContato()->getId());
		if (!is_null($contato)) {
			$objeto->setContato($contato);
			parent::getEntityManager()->persist($objeto);
			parent::getEntityManager()->flush();
		}
		return ($objeto->getId() > 0) ? $objeto : null;
	}

	public static function insertEmail($objeto)
	{
		$contato = DBManual::getContatoByID($objeto->getContato()->getId());
		if (!is_null($contato)) {
			$objeto->setContato($contato);
			parent::getEntityManager()->persist($objeto);
			parent::getEntityManager()->flush();
		}
		return ($objeto->getId() > 0) ? $objeto : null;
	}

	public static function updateContato($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_CONTATO, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			if (!is_null($objeto->getNome())) {
				$objetoTrabalho->setNome($objeto->getNome());
			}
			if (!is_null($objeto->getSobrenome())) {
				$objetoTrabalho->setSobrenome($objeto->getSobrenome());
			}
			parent::getEntityManager()->persist($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_MANAGED)) {
				parent::getEntityManager()->flush();
				return $objetoTrabalho;
			}
		}
		return null;
	}

	public static function updateTelefone($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_TELEFONE, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			if (!is_null($objeto->getNumero())) {
				$objetoTrabalho->setNumero($objeto->getNumero());
			}
			if (!is_null($objeto->getContato())) {
				$contato = DBManual::getContatoByID($objeto->getContato()->getId());
				$objetoTrabalho->setContato($contato);
			}
			parent::getEntityManager()->persist($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_MANAGED)) {
				parent::getEntityManager()->flush();
				return $objetoTrabalho;
			}
		}
		return null;
	}

	public static function updateEmail($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_EMAIL, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			if (!is_null($objeto->getEndereco())) {
				$objetoTrabalho->setEndereco($objeto->getEndereco());
			}
			if (!is_null($objeto->getContato()->getId())) {
				$contato = DBManual::getContatoByID($objeto->getContato()->getId());
				$objetoTrabalho->setContato($contato);
			}
			parent::getEntityManager()->persist($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_MANAGED)) {
				parent::getEntityManager()->flush();
				return $objetoTrabalho;
			}
		}
		return null;
	}

	public static function deleteContato($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_CONTATO, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			parent::getEntityManager()->remove($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_REMOVED)) {
				parent::getEntityManager()->flush();
				return true;
			}
		}
		return false;
	}

	public static function deleteTelefone($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_TELEFONE, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			parent::getEntityManager()->remove($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_REMOVED)) {
				parent::getEntityManager()->flush();
				return true;
			}
		}
		return false;
	}

	public static function deleteEmail($objeto)
	{
		$objetoTrabalho = parent::getEntityManager()->find(DBObject::TABLE_EMAIL, $objeto->getId());
		if (!is_null($objetoTrabalho)) {
			parent::getEntityManager()->remove($objetoTrabalho);
			if (parent::checkEntityState($objetoTrabalho, UnitOfWork::STATE_REMOVED)) {
				parent::getEntityManager()->flush();
				return true;
			}
		}
		return false;
	}
}


class DBManual extends DBHelper
{
	public static function getContatoByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBManual::TABLE_CONTATO, $id);
		return $objeto;
	}

	public static function getTelefoneByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBManual::TABLE_TELEFONE, $id);
		return $objeto;
	}

	public static function getEmailByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBManual::TABLE_EMAIL, $id);
		return $objeto;
	}

	public static function searchContatos($id = null, $nome = null)
	{
		$params = array();
		if (!is_null($id) && is_null($id)) {
			$params['id'] = (int)$id;
		}
		if (!is_null($nome) && is_string($nome)) {
			$params['nome'] = $nome;
		}
		$objetos = parent::getRepository(DBManual::TABLE_CONTATO)->findBy($params);
		return $objetos;
	}

	public static function searchTelefones($id = null, $numero = null, $contato = null)
	{
		$params = array();
		if (!is_null($id) && is_null($id)) {
			$params['id'] = (int)$id;
		}
		if (!is_null($numero) && is_string($numero)) {
			$params['numero'] = $numero;
		}
		if (!is_null($contato)) {
			$params['contato'] = $contato;
		}
		$objetos = parent::getRepository(DBManual::TABLE_TELEFONE)->findBy($params);
		return $objetos;
	}

	public static function searchEmails($id = null, $endereco = null, $contato = null)
	{
		$params = array();
		if (!is_null($id) && is_null($id)) {
			$params['id'] = (int)$id;
		}
		if (!is_null($endereco) && is_string($endereco)) {
			$params['endereco'] = $endereco;
		}
		if (!is_null($contato)) {
			$params['contato'] = $contato;
		}
		$objetos = parent::getRepository(DBManual::TABLE_EMAIL)->findBy($params);
		return $objetos;
	}
}


class DBJson extends DBHelper
{
	public static function getContatoByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBJson::TABLE_CONTATO, $id);
		return (!is_null($objeto)) ? DBJson::contatoToJson($objeto) : array();
	}

	public static function getTelefoneByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBJson::TABLE_TELEFONE, $id);
		return (!is_null($objeto)) ? DBJson::telefoneToJson($objeto) : array();
	}

	public static function getEmailByID($id)
	{
		$objeto = parent::getEntityManager()->find(DBJson::TABLE_EMAIL, $id);
		return (!is_null($objeto)) ? DBJson::emailToJson($objeto) : array();
	}

	public static function searchContatos($id = null, $nome = null)
	{
		$objetos = DBManual::searchContatos($id, $nome);
		$json = array();
		foreach ($objetos as $objeto) {
			$json[] = DBJson::contatoToJson($objeto);
		}
		return $json;
	}

	public static function searchTelefones($id = null, $numero = null, $contato = null)
	{
		$objetos = DBManual::searchTelefones($id, $numero, $contato);
		$json = array();
		foreach ($objetos as $objeto) {
			$json[] = DBJson::telefoneToJson($objeto);
		}
		return $json;
	}

	public static function searchEmails($id = null, $endereco = null, $contato = null)
	{
		$objetos = DBManual::searchEmails($id, $endereco, $contato);
		$json = array();
		foreach ($objetos as $objeto) {
			$json[] = DBJson::emailToJson($objeto);
		}
		return $json;
	}

	/**
	 * @param $objeto O contato.
	 * @param bool|false $noTelefones Se a lista de telefones do contato nao deve ser incluida no json.
	 * @param bool|false $noEmails Se a lista de emails do contato nao deve ser incluida no json.
	 * @return array O contato no formato json.
	 */
	public static function contatoToJson($objeto, $noTelefones = false, $noEmails = false)
	{
		$json = array(
			'id' => $objeto->getId(),
			'nome' => $objeto->getNome(),
			'sobrenome' => $objeto->getSobrenome()
		);
		if (!$noTelefones) {
			$jsonTelefones = array();
			foreach ($objeto->getTelefones() as $telefone) {
				$jsonTelefones[] = DBJson::telefoneToJson($telefone, true);
			}
			$json['telefones'] = $jsonTelefones;
		}
		if (!$noEmails) {
			$jsonEmails = array();
			foreach ($objeto->getEmails() as $email) {
				$jsonEmails[] = DBJson::emailToJson($email, true);
			}
			$json['emails'] = $jsonEmails;
		}
		return $json;
	}

	public static function jsonToContato($json)
	{
		$objeto = new Contato(
			isset($json['id']) ? $json['id'] : null,
			isset($json['nome']) ? $json['nome'] : null,
			isset($json['sobrenome']) ? $json['sobrenome'] : null
		);
		return $objeto;
	}

	/**
	 * @param $objeto O telefone.
	 * @param bool|false $noContato Se a lista de contatos do telefone nao deve ser incluida no json.
	 * @return array O telefone no formato json.
	 */
	public static function telefoneToJson($objeto, $noContato = false)
	{
		$json = array(
			'id' => $objeto->getId(),
			'numero' => $objeto->getNumero()
		);
		if (!$noContato) {
			$json['contato'] = DBJson::contatoToJson($objeto->getContato(), true);
		}
		return $json;
	}

	public static function jsonToTelefone($json)
	{
		$objeto = new Telefone(
			isset($json['id']) ? $json['id'] : null,
			isset($json['numero']) ? $json['numero'] : null,
			(isset($json['contato']) && isset($json['contato']['id'])) ? new Contato($json['contato']['id']) : null
		);
		return $objeto;
	}

	/**
	 * @param $objeto O email.
	 * @param bool|false $noContato Se a lista de contatos do email nao deve ser incluida no json.
	 * @return array O email no formato json.
	 */
	public static function emailToJson($objeto, $noContato = false)
	{
		$json = array(
			'id' => $objeto->getId(),
			'endereco' => $objeto->getEndereco()
		);
		if (!$noContato) {
			$json['contato'] = DBJson::contatoToJson($objeto->getContato(), false, true);
		}
		return $json;
	}

	public static function jsonToEmail($json)
	{
		$objeto = new Email(
			isset($json['id']) ? $json['id'] : null,
			isset($json['endereco']) ? $json['endereco'] : null,
			(isset($json['contato']) && isset($json['contato']['id'])) ? new Contato($json['contato']['id']) : null
		);
		return $objeto;
	}
}
