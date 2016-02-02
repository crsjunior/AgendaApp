<?php
// bootstrap.php

// autoload eh responsavel por carregar todas as classes sem necessidade d inclui-las previamente
require_once "vendor/autoload.php";

// o Doctrine usa namespaces em sua estrutura
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// local onde irao ficar as entidades do projeto
$entidades = array(__DIR__ . "/src");
$isDevMode = true;

// configuracoes da conexao
$dbParams = array(
	'driver' => 'pdo_mysql',
	'host' => 'localhost',
	'port' => '3307',
	'user' => 'root',
	'password' => 'usbw',
	'dbname' => 'agendaapp',
);

// setando as configuracoes definidas anteriormente
$config = Setup::createAnnotationMetadataConfiguration($entidades, $isDevMode);

// criando o Entity Manager com base nas configuracoes de dev e banco de dados
$entityManager = EntityManager::create($dbParams, $config);
