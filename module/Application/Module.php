<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Application\Model\BO\ActividadesBO;
use Application\Model\Entity\Actividades;

use Application\Model\BO\ObjetosBO;
use Application\Model\Entity\Objetos;

use Application\Model\BO\EtiquetasBO;
use Application\Model\Entity\Etiquetas;
use Application\Model\Entity\ObjetosEtiquetas;

use Application\Model\BO\TipoObjetosBO;
use Application\Model\Entity\TipoObjetos;

use Application\Model\BO\UsuariosBO;
use Application\Model\Entity\Usuarios;

use Application\Model\BO\AreasBO;
use Application\Model\Entity\Areas;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\ActividadesBO' => function ($sm)
                {
                    $tableGateway = $sm->get("ActividadesTableGateway");
                    $actividadesBO = new ActividadesBO($tableGateway);
                    return $actividadesBO;
                },
                'ActividadesTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New Actividades());
                    return new TableGateway("actividades", $dbAdapter, NULL, $resultSetPrototype);
                },
                'Application\Model\ObjetosBO' => function ($sm)
                {
                    $tableGateway = $sm->get("ObjetosTableGateway");
                    $ObjetosBO = new ObjetosBO($tableGateway);
                    return $ObjetosBO;
                },
                'ObjetosTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New Objetos());
                    return new TableGateway("objetos", $dbAdapter, NULL, $resultSetPrototype);
                },
                'Application\Model\EtiquetasBO' => function ($sm)
                {
                    $etiquetasTableGateway = $sm->get("EtiquetasTableGateway");
                    $objetosEtiquetasTableGateway = $sm->get("ObjetosEtiquetasTableGateway");
                    $EtiquetasBO = new EtiquetasBO($etiquetasTableGateway,$objetosEtiquetasTableGateway);
                    return $EtiquetasBO;
                },
                'EtiquetasTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New Etiquetas());
                    return new TableGateway("etiquetas", $dbAdapter, NULL, $resultSetPrototype);
                },
                'ObjetosEtiquetasTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New ObjetosEtiquetas());
                    return new TableGateway("objetos_etiquetas", $dbAdapter, NULL, $resultSetPrototype);
                },
                'Application\Model\TipoObjetosBO' => function ($sm)
                {
                    $tableGateway = $sm->get("TipoObjetosTableGateway");
                    $tipoObjetosBO = new TipoObjetosBO($tableGateway);
                    return $tipoObjetosBO;
                },
                'TipoObjetosTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New TipoObjetos());
                    return new TableGateway("tipo_objeto", $dbAdapter, NULL, $resultSetPrototype);
                },
                'Application\Model\UsuariosBO' => function ($sm)
                {
                    $tableGateway = $sm->get("UsuariosTableGateway");
                    $usuariosBO = new UsuariosBO($tableGateway);
                    return $usuariosBO;
                },
                'UsuariosTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New Usuarios());
                    return new TableGateway("usuarios", $dbAdapter, NULL, $resultSetPrototype);
                },
                'Application\Model\AreasBO' => function ($sm)
                {
                    $tableGateway = $sm->get("AreasTableGateway");
                    $areasBO = new AreasBO($tableGateway);
                    return $areasBO;
                },
                'AreasTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(New Areas());
                    return new TableGateway("areas", $dbAdapter, NULL, $resultSetPrototype);
                },
            ) ,
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'RetornaEtiquetas' => function ($sm)
                {
                    $locator = $sm->getServiceLocator();
                    $EtiquetasBO = $locator->get('Application\Model\EtiquetasBO');                    
                    return new View\Helper\RetornaEtiquetas($EtiquetasBO);
                }
                ,
            ) ,
        );
    }
}
