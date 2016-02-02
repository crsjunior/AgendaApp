<?php

/**
 * @Entity
 * $Table(name="email")
 */
class Email
{
	/**
	 * @Id
	 * @GeneratedValue(strategy="AUTO")
	 * @Column(type="integer", name="id_email", nullable=false)
	 */
	protected $id;

	/**
	 * @Column(type="string", name="endereco")
	 */
	protected $endereco;

	/**
	 * @ManyToOne(targetEntity="Contato", inversedBy="emails")
	 * @JoinColumn(name="contato_id", referencedColumnName="id_contato")
	 */
	protected $contato;

	public function __construct($id = null, $endereco = null, $contato = null)
	{
		if (!is_null($id) && is_numeric($id)) {
			$this->id = (int)$id;
		}
		if (!is_null($endereco) && is_string($endereco)) {
			$this->endereco = $endereco;
		}
		if (!is_null($contato)) {
			$this->contato = $contato;
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

	public function getEndereco()
	{
		return $this->endereco;
	}

	public function setEndereco($endereco)
	{
		$this->endereco = $endereco;
	}

	public function getContato()
	{
		return $this->contato;
	}

	public function setContato($contato)
	{
		$this->contato = $contato;
	}

	public function correctNullFields()
	{
		if (is_null($this->endereco)) {
			$this->endereco = '';
		}
		if (is_null($this->contato)) {
			$this->contato = new Contato;
		}
	}
}
