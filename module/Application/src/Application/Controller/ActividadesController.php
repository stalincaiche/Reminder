<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Actividades\ActividadesForm;
use Application\Form\Actividades\ActividadesFormValidator;

class ActividadesController extends AbstractActionController
{

	private $actividadesBO;

	public function getActividadesBO()
    {
        if (!$this->actividadesBO) {
            $sm = $this->getServiceLocator();
            $this->actividadesBO = $sm->get('Application\Model\ActividadesBO');
        }
        return $this->actividadesBO;
    }

    public function indexAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/actividades/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'title' =>  "Actividades",
            'actividades' => $this->getActividadesBO()->obtenerActivas() ,            
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $form = new ActividadesForm("actividadesform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');

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
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $form = new ActividadesForm("actividadesform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar2');

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

        $datos = array(
            'form' => $form,
            'title' =>  "Nueva Actividad",
        );

        return new ViewModel($datos);
    }

    public function crear2Action()
    {
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
