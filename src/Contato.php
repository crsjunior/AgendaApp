<?php

/**
 * @Entity
 * $Table(name="contato")
 */
class Contato
{
	/**
	 * @Id
	 * @GeneratedValue(strategy="AUTO")
	 * @Column(type="integer", name="id_contato", nullable=false)
	 */
	protected $id;

	/**
	 * @Column(type="string", name="nome")
	 */
	protected $nome;

	/**
	 * @Column(type="string", name="sobrenome")
	 */
	protected $sobrenome;

	/**
	 * @OneToMany(targetEntity="Telefone", mappedBy="contato")
	 */
	protected $telefones;

	/**
	 * @OneToMany(targetEntity="Email", mappedBy="contato")
	 */
	protected $emails;

	public function __construct($id = null, $nome = null, $sobrenome = null, $telefones = null, $emails = null)
	{
		if (!is_null($id) && is_numeric($id)) {
			$this->id = (int)$id;
		}
		if (!is_null($nome) && is_string($nome)) {
			$this->nome = $nome;
		}
		if (!is_null($sobrenome) && is_string($sobrenome)) {
			$this->sobrenome = $sobrenome;
		}
		if (!is_null($telefones) && is_array($telefones)) {
			$this->telefones = $telefones;
		}
		if (!is_null($emails) && is_array($emails)) {
			$this->emails = $emails;
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($nome)
	{
		$this->nome = $nome;
	}

	public function getSobrenome()
	{
		return $this->sobrenome;
	}

	public function setSobrenome($sobrenome)
	{
		$this->sobrenome = $sobrenome;
	}

	public function getTelefones()
	{
		return $this->telefones;
	}

	public function setTelefones($telefones)
	{
		$this->telefones = $telefones;
	}

	public function getEmails()
	{
		return $this->emails;
	}

	public function setEmails($emails)
	{
		$this->emails = $emails;
	}

	public function addTelefone($telefone)
	{
		array_push($this->telefones, $telefone);
	}

	public function addEmail($email)
	{
		array_push($this->emails, $email);
	}

	public function correctNullFields()
	{
		if (is_null($this->nome)) {
			$this->nome = '';
		}
		if (is_null($this->sobrenome)) {
			$this->sobrenome = '';
		}
	}
}