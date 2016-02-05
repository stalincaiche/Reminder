<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Actividades\ActividadesForm;
use Application\Form\Actividades\ActividadesFormValidator;
use Zend\Session\Container;

class ActividadesController extends AbstractActionController
{

    private $actividadesBO;
    private $usuariosBO;
	private $areasBO;

	public function getActividadesBO()
    {
        if (!$this->actividadesBO) {
            $sm = $this->getServiceLocator();
            $this->actividadesBO = $sm->get('Application\Model\ActividadesBO');
        }
        return $this->actividadesBO;
    }

    public function getUsuariosBO()
    {
        if (!$this->usuariosBO) {
            $sm = $this->getServiceLocator();
            $this->usuariosBO = $sm->get('Application\Model\UsuariosBO');
        }
        return $this->usuariosBO;
    }

    
    public function getAreasBO()
    {
        if (!$this->areasBO) {
            $sm = $this->getServiceLocator();
            $this->areasBO = $sm->get('Application\Model\AreasBO');
        }
        return $this->areasBO;
    }

    public function indexAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');
        
        $sesion = new Container('reminderSesion');
        $user_user_id = $sesion->user_user_id;

        $datos = array(
            'title' =>  "Actividades",
            'actividades' => $this->getActividadesBO()->obtenerActivasPorUsuario($user_user_id) ,            
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $form = new ActividadesForm("actividadesform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');
        
        $areas = $this->getAreasBO()->obtenerCombo();        
        $form->get('actividades_area')->setValueOptions($areas);

        $usuarios = $this->getUsuariosBO()->obtenerCombo();        
        $form->get('actividades_responsable')->setValueOptions($usuarios);

        $actividad = $this->getActividadesBO()->obtenerPorId($id);

        if (!is_object($actividad)) {
            return $this->redirect()->toRoute('actividades');
        }

        $form->bind($actividad);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' =>  "Editar Actividad",
            )
        );

        $modelView->setTemplate('application/actividades/crear');
        return $modelView;
    }

    public function editar2Action()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $form = new ActividadesForm("actividadesform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar2');

        $areas = $this->getAreasBO()->obtenerCombo();        
        $form->get('actividades_area')->setValueOptions($areas);

        $usuarios = $this->getUsuariosBO()->obtenerCombo();        
        $form->get('actividades_responsable')->setValueOptions($usuarios);

        $actividad = $this->getActividadesBO()->obtenerPorId($id);

        if (!is_object($actividad)) {
            return $this->redirect()->toRoute('actividades');
        }

        $form->bind($actividad);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' =>  "Editar Actividad",
            )
        );

        $modelView->setTemplate('application/actividades/crear');
        return $modelView;
    }

    public function crearAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new ActividadesForm();
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );
        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');

        $sesion = new Container('reminderSesion');
        $user_username = $sesion->user_username;
        $user_user_id = $sesion->user_user_id;
        
        

        
        // $usuarios = $this->getUsuariosBO()->obtenerCombo();
        $form->get('actividades_responsable')->setValueOptions(array( $user_user_id => $user_username ));

        $areas = $this->getAreasBO()->obtenerCombo();        
        $form->get('actividades_area')->setValueOptions($areas);

        $datos = array(
            'form' => $form,
            'title' =>  "Nueva Actividad",
        );

        return new ViewModel($datos);
    }

    public function crear2Action()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new ActividadesForm();
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );
        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar2');
        
        $areas = $this->getAreasBO()->obtenerCombo();        
        $form->get('actividades_area')->setValueOptions($areas);
        
        $usuarios = $this->getUsuariosBO()->obtenerCombo();        
        $form->get('actividades_responsable')->setValueOptions($usuarios);

        $datos = array(
            'form' => $form,
            'title' =>  "Nueva Actividad",
        );
        $modelView = new ViewModel($datos);
        $modelView->setTemplate('application/actividades/crear');
        return $modelView;
    }

    public function guardarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'actividades',
                array('controller' => 'actividades')
            );
        }

        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new ActividadesForm("actividadesform");
        $form->setInputFilter(new ActividadesFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        if (!$form->isValid()) {            

            $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
            );
            $form->get('actividades_estado')->setValueOptions($estados);
            $form->get('guardar')->setOptions(
                array(
                    'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                    'label_options' => array(
                        'disable_html_escape' => true,
                    )
                )
            );

            $areas = $this->getAreasBO()->obtenerCombo();        
            $form->get('actividades_area')->setValueOptions($areas);

            $usuarios = $this->getUsuariosBO()->obtenerCombo();        
            $form->get('actividades_responsable')->setValueOptions($usuarios);

            $modelView = new ViewModel(
                array(
                    'form' => $form,
                    'title' =>  "Guardar Actividad",
                )
            );
            $modelView->setTemplate('application/actividades/crear');
            return $modelView;
        }

        $id = $this->getActividadesBO()->guardar($form->getData());
        return $this->redirect()->toRoute(
            'actividades',
            array(
                'controller' => 'actividades',
                'action' => 'index'                
            )
        );
    }

    public function guardar2Action()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'actividades',
                array(
                    'controller' => 'actividades',
                    'action' => 'listado'                
                )
            );
        }

        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/crear');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new ActividadesForm("actividadesform");
        $form->setInputFilter(new ActividadesFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        if (!$form->isValid()) {            

            $areas = $this->getAreasBO()->obtenerCombo();        
            $form->get('actividades_area')->setValueOptions($areas);

            $usuarios = $this->getUsuariosBO()->obtenerCombo();        
            $form->get('actividades_responsable')->setValueOptions($usuarios);

            $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
            );
            $form->get('actividades_estado')->setValueOptions($estados);
            $form->get('guardar')->setOptions(
                array(
                    'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                    'label_options' => array(
                        'disable_html_escape' => true,
                    )
                )
            );

            $modelView = new ViewModel(
                array(
                    'form' => $form,
                    'title' =>  "Guardar Actividad",
                )
            );
            $modelView->setTemplate('application/actividades/crear');
            return $modelView;
        }

        $id = $this->getActividadesBO()->guardar($form->getData());
        return $this->redirect()->toRoute(
            'actividades',
            array(
                'controller' => 'actividades',
                'action' => 'listado'                
            )
        );
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $this->getActividadesBO()->eliminar($id);

        return $this->redirect()->toRoute('actividades');
    }

    public function eliminar2Action()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute(
                'actividades',
                array(
                    'controller' => 'actividades',
                    'action' => 'listado'                
                )
            );
        }

        $this->getActividadesBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'actividades',
            array(
                'controller' => 'actividades',
                'action' => 'listado'                
            )
        );
    }

    public function finalizarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $this->getActividadesBO()->finalizar($id);

        return $this->redirect()->toRoute('actividades');
    }
    
    public function finalizardosAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('actividades', 
                array(
                    'controller' => 'actividades',
                    'action' => 'listado'
                )
            );
        }

        $this->getActividadesBO()->finalizar($id);

        return $this->redirect()->toRoute('actividades', 
            array(
                'controller' => 'actividades',
                'action' => 'listado'
            )
        );
    }

    public function activarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('actividades', 
                array(
                    'controller' => 'actividades',
                    'action' => 'listado'
                )
            );
        }

        $this->getActividadesBO()->activar($id);

        return $this->redirect()->toRoute('actividades', 
            array(
                'controller' => 'actividades',
                'action' => 'listado'
            )
        );
    }

    public function listadoAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/listado');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'actividades' => $this->getActividadesBO()->obtenerTodos() ,
            'title' => 'Administrar Actividades'
        );
        return new ViewModel($datos);
    }
}
