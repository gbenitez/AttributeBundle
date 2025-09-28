<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__).'/vendor/autoload.php';

// Aplicación simple para testing
$response = new Response();
$response->setContent('<h1>Attribute Bundle Test</h1><p>El bundle está funcionando correctamente.</p><p><a href="/admin/attributes/list">Ver lista de attributes</a></p>');
$response->send();
