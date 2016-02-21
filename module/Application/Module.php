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

use Zend\Session\Container;

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
        $app = $e->getParam("application");

        $app->getEventManager()->attach(
            'dispatch',
            array(
                $this,
                'initAuth'
            ),
            200
        );
        

        $moduleRouteListener = new ModuleRouteListener();
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener->attach($eventManager);
    }

    public function initAuth(MvcEvent $e)
    {
        $application = $e->getApplication();
        $matches = $e->getRouteMatch();

        $controller = $matches->getParam('controller');
        $action = $matches->getParam('action');

        $services = $application->getServiceManager();

        $sesion = new Container('reminderSesion');

        // ¿Es el controlador de errores?
        if ($controller === "Application\Controller\Error") {

            // No valida permisos
            return;
        }

        // No valida permisos
        if ($action == "error") {
            return;
        }

        // ¿es la página de Marketing?
        if ($controller === "Application\Controller\Index" && in_array($action, array('index'))) {

            // ¿Tiene una sesión activa ?
            if ($sesion->offsetExists('user_username')) {

                // Si se encuentra en la página de marketing y tiene una sesion activa
                // se lo envia de regreso a la página principal
                $matches->setParam("controller", "Application\Controller\Inicio");
                $matches->setParam("action", "index");
                // return;
            } else {

                // Si no tiene una sesion activa se lo deja permanecer en la página de marketing
                return;
            }
        }

        if ($controller === "Application\Controller\Admin" && in_array($action, array('colaborar'))) {

            return;
            
        }

        // ¿es el login o se está autenticando ?
        if ($controller === "Application\Controller\Login" && in_array($action, array('index','autenticar','logout'))) {

            if($action =="logout")
            {
                return;
            }
            if ($sesion->offsetExists('user_username')) {

                // Si se encuentra en la página de marketing y tiene una sesion activa
                // se lo envia de regreso a la página principal
                $matches->setParam("controller", "Application\Controller\Inicio");
                $matches->setParam("action", "index");
                // return;
            } else {

                // Si no tiene una sesion activa se lo deja permanecer en la página de marketing
                return;
            }
        }
        // var_dump($sesion->offsetExists('user_username'));exit();
        //Si no hay acl con esto se valida que inicie sesión
        if (!$sesion->offsetExists('user_username')) {
            $matches->setParam("controller", "Application\Controller\Login");
            $matches->setParam("action", "index");            
            return;
        }
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

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'Application\Controller\Login' => function ($sm)
                {
                    $locator = $sm->getServiceLocator();
                    //$logger = $locator->get('Zend\Log');
                    $controller = new \Application\Controller\LoginController();
                    $controller->setLogin($locator->get('Application\Model\Login'));
                    //$controller->setLogger($logger);
                    return $controller;
                }
                ,
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\Login' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new \Application\Model\Login($dbAdapter);
                },
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
