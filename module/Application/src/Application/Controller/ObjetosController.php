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
        $actividad_id = (int)$this->params()->fromRoute('id', 0);

        $form = new ObjetosForm("objetosform");
        $form->setAttribute('class', 'form-inline');
        $form->get('guardar')->setAttribute('value', 'Crear');
        $tipos = array(
                'T' => 'Transacción',
                'WP' => 'Web Panel',
                'PR' => 'Procedimiento',
                'ATR' => 'Atributo',
                'TBL' => 'Tabla',
        );
        $form->get('objetos_tipo')->setValueOptions($tipos);
        $form->get('objetos_actividad_id')->setValue($actividad_id);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/objetos/guardar');
        $datos = array(
            'objetos' => $this->getObjetosBO()->obtenerTodosPorActividad($actividad_id) ,            
            'actividad_id' => $actividad_id ,
            'form' => $form    
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
        $form->get('guardar')->setAttribute('value', 'Editar');

        $tipos = array(
                'T' => 'Transacción',
                'WP' => 'Web Panel',
                'PR' => 'Procedimiento',
                'ATR' => 'Atributo',
                'TBL' => 'Tabla',
        );
        $form->get('objetos_tipo')->setValueOptions($tipos);

        
        $modelView = new ViewModel(array('form' => $form));

        $modelView->setTemplate('application/objetos/crear');
        return $modelView;
    }

    public function etiquetasAction()
    {
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
        $form->get('guardar')->setAttribute('value', 'Agregar');
        $form->get('objetos_id')->setAttribute('value', $id);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/etiquetas/vincular');

        $modelView = new ViewModel(
            array(
                'objeto' => $objeto,
                'etiquetas' => $etiquetas,
                'form' => $form,
            )
        );
        return $modelView;
    }

    public function crearAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        $form = new ObjetosForm();
        $form->get('guardar')->setValue('Agregar');
        $tipos = array(
                'T' => 'Transacción',
                'WP' => 'Web Panel',
                'PR' => 'Procedimiento',
                'ATR' => 'Atributo',
                'TBL' => 'Tabla',
        );
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

            $tipos = array(
                'T' => 'Transacción',
                'WP' => 'Web Panel',
                'PR' => 'Procedimiento',
                'ATR' => 'Atributo',
                'TBL' => 'Tabla',
            );            
            $form->get('objetos_tipo')->setValueOptions($tipos);

            $modelView = new ViewModel(array('form' => $form));
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
}
