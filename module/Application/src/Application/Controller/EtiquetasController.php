<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Etiquetas\EtiquetasForm;
use Application\Form\Etiquetas\EtiquetasFormValidator;

class EtiquetasController extends AbstractActionController
{

    private $objetosBO;
	private $etiquetasBO;

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

    public function indexAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/etiquetas/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('class', 'form-inline');
        $form->get('guardar')->setAttribute('title', 'Guardar');
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/guardaretiqueta');

        $formBusqueda = new EtiquetasForm("etiquetasBusquedaform");
        $formBusqueda->setAttribute('class', 'form-inline');
        
        $formBusqueda->get('guardar')->setOptions(
                array(
                    'label' => '<i class="glyphicon glyphicon-search"></i> Buscar',
                    'label_options' => array(
                        'disable_html_escape' => true,
                    )
                )
            );
        $formBusqueda->get('guardar')->setAttribute('class', 'btn btn-primary');

        $formBusqueda->get('etiquetas_nombre')->setAttribute('placeholder', 'Busca una Etiqueta');

        $formBusqueda->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/buscar');

        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodos() ,
            'form' => $form,
            'formBusqueda' => $formBusqueda,
            'title' => 'Etiquetas'
        );
        return new ViewModel($datos);
    }

    public function guardarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {

            $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/guardar');
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
                    'title' => 'Etiquetar'                
                )
            );
            $modelView->setTemplate('application/etiquetas/vincular');
            return $modelView;
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->guardar($data);
        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'etiquetas',
                'id' => $data['objetos_id'],
            )
        );
    }

    public function guardaretiquetaAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {

            $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/guardaretiqueta');
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
                    'title' => 'Guardar Etiqueta'                
                )
            );
            $modelView->setTemplate('application/etiquetas/vincular');
            return $modelView;
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->guardar($data);
        return $this->redirect()->toRoute(
            'etiquetas',
            array('controller' => 'etiquetas')
        );
    }

    public function actualizarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {

            $form->get('guardar')->setOptions(
                array(
                    'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                    'label_options' => array(
                        'disable_html_escape' => true,
                    )
                )
            );
            
            $modelView = new ViewModel(
                array(
                    'form' => $form,
                    'id' => $data['etiquetas_id'],
                    'title' => 'Editar Etiqueta'
                )
            );
            $modelView->setTemplate('application/etiquetas/editar');
            return $modelView;
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->actualizar($data);
        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'index',                
            )
        );
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('class', '');
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/actualizar');

        $etiqueta = $this->getEtiquetasBO()->obtenerPorId($id);

        if (!is_object($etiqueta)) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $form->bind($etiqueta);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );
        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'id' => $id,
                'title' => 'Editar Etiqueta'
            )
        );

        return $modelView;
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $objetos_id = (int)$this->params()->fromRoute('id2', 0);
        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $this->getEtiquetasBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'etiquetas',
                'id' => $objetos_id,
            )
        );
    }

    public function eliminar2Action()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $this->getEtiquetasBO()->eliminar($id);

        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'index',                
            )
        );
    }

    public function eliminaradminAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $this->getEtiquetasBO()->eliminar($id);

        return $this->redirect()->toRoute('etiquetas', array('controller' => 'etiquetas', 'action' => 'listado'));
    }

    public function buscarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/etiquetas/js/buscar');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {

            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $data = $form->getData();

        $datos = array(
            'etiquetas' => $this->getEtiquetasBO()->buscar($data['etiquetas_nombre']),
            'title' => 'Resultados'
        );
        return new ViewModel($datos);
    }

    public function objetosAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/etiquetas/js/objetos');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $objetos = $this->getEtiquetasBO()->obtenerObjetosPorEtiquetas($id);

        $modelView = new ViewModel(
            array(
                'objetos' => $objetos,
                'title' => 'Objetos asociados',
                'etiqueta_id' => $id
            )
        );

        return $modelView;
    }

    public function desvincularAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $objeto = (int)$this->params()->fromRoute('id2', 0);
        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $this->getEtiquetasBO()->desvincular($id);

        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'etiquetas',                
                'id' => $objeto
            )
        );
    }

    public function vincularAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {

            $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/guardar');
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
                    'title' => 'Etiquetar',                
                )
            );
            return $modelView;
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->guardar($data);
        return $this->redirect()->toRoute(
            'objetos',
            array(
                'controller' => 'objetos',
                'action' => 'etiquetas',                
                'id' => $data['objetos_id']
            )
        );
    }

    public function listadoAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/etiquetas/js/listado');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'etiquetas' => $this->getEtiquetasBO()->obtenerTodosCount() ,
            'title' => 'Administrar Etiquetas'            
        );
        return new ViewModel($datos);
    }

    public function crearAction()
    {
        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/ingresar');

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
                'title' => 'Nueva Etiqueta'
            )
        );

        return $modelView;
    }

    public function ingresarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'etiquetas',
                array(
                    'controller' => 'etiquetas',
                    'action' => 'listado'
                )
            );
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setInputFilter(new EtiquetasFormValidator());
        
        $data = $this->request->getPost();

        $form->setData($data);
        
        if (!$form->isValid()) {
            
            $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/ingresar');
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
                    'title' => 'Guardar Etiqueta'
                )
            );
            $modelView->setTemplate('application/etiquetas/crear');
            return $modelView;
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->ingresar($data);
        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'listado'
            )
        );
    }

    public function updateAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('class', '');
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/ingresar');

        $etiqueta = $this->getEtiquetasBO()->obtenerPorId($id);

        if (!is_object($etiqueta)) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $form->bind($etiqueta);
        $form->get('guardar')->setOptions(
            array(
                'label' => '<i class="glyphicon glyphicon-floppy-saved"></i> Editar',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            )
        );
        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'id' => $id,
                'title' => 'Editar Etiqueta'
            )
        );
        $modelView->setTemplate('application/etiquetas/crear');
        return $modelView;
    }
}
