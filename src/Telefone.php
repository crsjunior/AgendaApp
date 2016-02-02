<?php

/**
 * @Entity
 * $Table(name="telefone")
 */
class Telefone
{
	/**
	 * @Id
	 * @GeneratedValue(strategy="AUTO")
	 * @Column(type="integer", name="id_telefone", nullable=false)
	 */
	protected $id;

	/**
	 * @Column(type="string", name="numero")
	 */
	protected $numero;

	/**
	 * @ManyToOne(targetEntity="Contato", inversedBy="telefones")
	 * @JoinColumn(name="contato_id", referencedColumnName="id_contato")
	 */
	protected $contato;

	public function __construct($id = null, $numero = null, $contato = null)
	{
		if (!is_null($id) && is_numeric($id)) {
			$this->id = (int)$id;
		}
		if (!is_null($numero) && is_string($numero)) {
			$this->numero = $numero;
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

	public function getNumero()
	{
		return $this->numero;
	}

	public function setNumero($numero)
	{
		$this->numero = $numero;
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
		if (is_null($this->numero)) {
			$this->numero = '';
		}
		if (is_null($this->contato)) {
			$this->contato = new Contato;
		}
	}
}