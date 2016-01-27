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
        $form = new EtiquetasForm("etiquetasform");
        $form->setAttribute('class', 'form-inline');
        $form->get('guardar')->setAttribute('value', 'Agregar');
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/guardar');

        $formBusqueda = new EtiquetasForm("etiquetasBusquedaform");
        $formBusqueda->setAttribute('class', 'form-inline');
        
        $formBusqueda->get('guardar')->setAttribute('value', 'Buscar');
        $formBusqueda->get('guardar')->setAttribute('class', 'btn btn-primary');

        $formBusqueda->get('etiquetas_nombre')->setAttribute('placeholder', 'Busca una Etiqueta');

        $formBusqueda->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/buscar');

        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodos() ,
            'form' => $form,
            'formBusqueda' => $formBusqueda
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

            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $data = $form->getData();

        $id = $this->getEtiquetasBO()->guardar($data);
        return $this->redirect()->toRoute(
            'etiquetas',
            array(
                'controller' => 'etiquetas',
                'action' => 'index',                
            )
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

            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
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
        $form->setAttribute('class', 'form-inline');
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/actualizar');

        $etiqueta = $this->getEtiquetasBO()->obtenerPorId($id);

        if (!is_object($etiqueta)) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $form->bind($etiqueta);
        $form->get('guardar')->setAttribute('value', 'Editar');
        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'id' => $id
            )
        );

        $modelView->setTemplate('application/etiquetas/crear');
        return $modelView;
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $this->getEtiquetasBO()->eliminar($id);

        return $this->redirect()->toRoute('etiquetas');
    }

    public function buscarAction()
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

            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
        }

        $data = $form->getData();

        $datos = array(
            'etiquetas' => $this->getEtiquetasBO()->buscar($data['etiquetas_nombre'])
        );
        return new ViewModel($datos);
    }

    public function objetosAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('etiquetas');
        }

        $objetos = $this->getEtiquetasBO()->obtenerObjetosPorEtiquetas($id);

        $modelView = new ViewModel(
            array(
                'objetos' => $objetos,                
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

            return $this->redirect()->toRoute(
                'etiquetas',
                array('controller' => 'etiquetas')
            );
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
}
