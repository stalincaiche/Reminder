<?php
namespace ApplicationTest\Controller;

use Application\Model\BO\UsuariosBO;
use Application\Model\Entity\Usuarios;
require (dirname(__FILE__).'/../../../src/Application/Model/BO/UsuariosBO.php');
require (dirname(__FILE__).'/../../../src/Application/Model/Entity\Usuarios.php');

class UsuariosBoTest extends \PHPUnit_Framework_TestCase
{
    public function testObtenerPorId()
    {
        // $consumer = new \Application\Model\BO\UsuariosBO();        
        // $consumer = new UsuariosBO();
        // $result = $consumer->obtenerPorId(1);
        $usuario = new Usuarios();
        $usuario->setUsuariosId(1);

        $result = $usuario->getUsuariosId();
        $this->assertEquals(1, $result);
    }   
}
