<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Objetos\ObjetosForm;
use Application\Form\Objetos\ObjetosFormValidator;

use Application\Form\Etiquetas\EtiquetasForm;
use Application\Form\Etiquetas\EtiquetasFormValidator;

class ObjetosController extends AbstractActionController
{

    private $objetosBO;
    private $etiquetasBO;
    private $tipoObjetosBO;

    public function getObjetosBO()
    {
        if (!$this->objetosBO) {
            $sm = $this->getServiceLocator();
            $this->objetosBO = $sm->get('Application\Model\ObjetosBO');
        }
        return $this->objetosBO;
    }

    public function getEtiquetasBO()
    {
        if (!$this->etiquetasBO) {
            $sm = $this->getServiceLocator();
            $this->etiquetasBO = $sm->get('Application\Model\EtiquetasBO');
        }
        return $this->etiquetasBO;
    }


    public function getTipoObjetosBO()
    {
        if (!$this->tipoObjetosBO) {
            $sm = $this->getServiceLocator();
            $this->tipoObjetosBO = $sm->get('Application\Model\TipoObjetosBO');
        }
        return $this->tipoObjetosBO;
    }

    public function indexAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/objetos/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $actividad_id = (int)$this->params()->fromRoute('id', 0);

        $form = new ObjetosForm("objetosform");
        $form->setAttribute('class', 'form-inline');
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $tipos = $this->getTipoObjetosBO()->obtenerCombo();

        $form->get('objetos_tipo')->setValueOptions($tipos);
        $form->get('objetos_actividad_id')->setValue($actividad_id);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/guardar');
        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodosPorActividad($actividad_id) ,            
            'actividad_id' => $actividad_id ,
            'form' => $form,
            'title' =>  "Objetos"
        );
        return new ViewModel($datos);
    }

    public function verAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/objetos/js/ver');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $actividad_id = (int)$this->params()->fromRoute('id', 0);        

        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodosPorActividad($actividad_id) ,            
            'actividad_id' => $actividad_id ,            
            'title' =>  "Objetos y sus Etiquetas"
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $form = new ObjetosForm("objetosform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/guardar');

        $objeto = $this->getObjetosBO()->obtenerPorId($id);

        if (!is_object($objeto)) {
            return $this->redirect()->toRoute('objetos');
        }

        $form->bind($objeto);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $tipos = $this->getTipoObjetosBO()->obtenerCombo();
        $form->get('objetos_tipo')->setValueOptions($tipos);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Objeto'
            )
        );

        $modelView->setTemplate('application/objetos/crear');
        return $modelView;
    }

    public function editar2Action()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $etiqueta_id = (int)$this->params()->fromRoute('id2', 0);

        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $form = new ObjetosForm("objetosform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/guardar2');

        $objeto = $this->getObjetosBO()->obtenerPorId($id);

        if (!is_object($objeto)) {
            return $this->redirect()->toRoute('objetos');
        }

        $form->bind($objeto);
        $form->get('etiquetas_id')->setAttribute('value', $etiqueta_id);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $tipos = $this->getTipoObjetosBO()->obtenerCombo();
        $form->get('objetos_tipo')->setValueOptions($tipos);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Objeto'
            )
        );

        $modelView->setTemplate('application/objetos/crear');
        return $modelView;
    }

    public function updateAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $form = new ObjetosForm("objetosform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/ingresar');

        $objeto = $this->getObjetosBO()->obtenerPorId($id);

        if (!is_object($objeto)) {
            return $this->redirect()->toRoute('objetos');
        }

        $form->bind($objeto);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $tipos = $this->getTipoObjetosBO()->obtenerCombo();
        $form->get('objetos_tipo')->setValueOptions($tipos);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Objeto'
            )
        );

        $modelView->setTemplate('application/objetos/crear');
        return $modelView;
    }

    public function etiquetasAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/objetos/js/etiquetas');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $objeto = $this->getObjetosBO()->obtenerPorId($id);
        $etiquetas = $this->getEtiquetasBO()->obtenerPorObjeto($id);

        if (!is_object($objeto)) {
            return $this->redirect()->toRoute('objetos');
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('class', 'form-inline');
        
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );

        $form->get('objetos_id')->setAttribute('value', $id);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/vincular');

        $modelView = new ViewModel(
            array(
                'objeto' => $objeto,
                'etiquetas' => $etiquetas,
                'form' => $form,
                'title' => 'Etiquetas'
            )
        );
        return $modelView;
    }

    public function crearAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        $form = new ObjetosForm();
        $form->get('guardar')->setValue('Agregar');
        $tipos = $this->getTipoObjetosBO()->obtenerCombo();
        $form->get('objetos_actividad_id')->setValue($id);
        $form->get('objetos_tipo')->setValueOptions($tipos);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/guardar');

        $datos = array(
            'form' => $form
        );

        return new ViewModel($datos);
    }

    public function guardarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'objetos',
                array('controller' => 'objetos')
            );
        }

        $form = new ObjetosForm("objetosform");
        $form->setInputFilter(new ObjetosFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {                        
            $tipos = $this->getTipoObjetosBO()->obtenerCombo();           
            $form->get('objetos_tipo')->setValueOptions($tipos);
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
                    'title' => 'Editar Objeto'
                )
            );
            $modelView->setTemplate('application/objetos/crear');
            return $modelView;
        }
        
        $data = $form->getData();

        $id = $this->getObjetosBO()->guardar($data);

        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'index',
                'id' => $data["objetos_actividad_id"]
            )
        );
    }

    public function guardar2Action()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $form = new ObjetosForm("objetosform");
        $form->setInputFilter(new ObjetosFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);        
        
        if (!$form->isValid()) {                        
            $tipos = $this->getTipoObjetosBO()->obtenerCombo();           
            $form->get('objetos_tipo')->setValueOptions($tipos);
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
                    'title' => 'Editar Objeto'
                )
            );
            $modelView->setTemplate('application/objetos/crear');
            return $modelView;
        }
        
        $data = $form->getData();

        $id = $this->getObjetosBO()->guardar($data);

        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'objetos',
                'id' => $data["etiquetas_id"]
            )
        );
    }

    public function ingresarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'objetos',
                array(
                    'controller' => 'objetos',
                    'action' => 'listado',
                )
            );
        }

        $form = new ObjetosForm("objetosform");
        $form->setInputFilter(new ObjetosFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {            

            $tipos = $this->getTipoObjetosBO()->obtenerCombo();       
            $form->get('objetos_tipo')->setValueOptions($tipos);
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
                    'title' => 'Guardar Objeto'
                )
            );
            $modelView->setTemplate('application/objetos/crear');
            return $modelView;
        }
        
        $data = $form->getData();

        $id = $this->getObjetosBO()->guardar($data);
        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'listado',
            )
        );
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $id2 = (int)$this->params()->fromRoute('id2', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $this->getObjetosBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'index',
                'id' => $id2
            )
        );
    }

    public function eliminar2Action()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $id2 = (int)$this->params()->fromRoute('id2', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $this->getObjetosBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'objetos',
                'id' => $id2                
            )
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('objetos');
        }

        $this->getObjetosBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'listado'                
            )
        );
    }

    public function listadoAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/objetos/js/listado');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodos() ,
            'title' => 'Administrar Objetos'           
        );
        return new ViewModel($datos);
    }
}
