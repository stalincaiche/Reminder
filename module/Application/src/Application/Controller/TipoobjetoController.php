<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Actividades\ActividadesForm;
use Application\Form\Actividades\ActividadesFormValidator;

class TipoobjetoController extends AbstractActionController
{

	private $tipoObjetosBO;

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
        $datos = array(
            'tipos' => $this->getTipoObjetosBO()->obtenerActivas() ,            
        );
        return new ViewModel($datos);
    }

    // public function editarAction()
    // {
    //     $id = (int)$this->params()->fromRoute('id', 0);

    //     if (!$id) {
    //         return $this->redirect()->toRoute('actividades');
    //     }

    //     $form = new ActividadesForm("actividadesform");
    //     $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');

    //     $actividad = $this->getTipoObjetosBO()->obtenerPorId($id);

    //     if (!is_object($actividad)) {
    //         return $this->redirect()->toRoute('actividades');
    //     }

    //     $form->bind($actividad);
    //     $form->get('guardar')->setAttribute('value', 'Editar');

    //     $estados = array(
    //             'A' => 'Activo',
    //             'F' => 'Finalizado',
    //     );
    //     $form->get('actividades_estado')->setValueOptions($estados);

        
    //     $modelView = new ViewModel(array('form' => $form));

    //     $modelView->setTemplate('application/actividades/crear');
    //     return $modelView;
    // }

    // public function crearAction()
    // {
    //     $form = new ActividadesForm();
    //     $form->get('guardar')->setValue('Agregar');
    //     $estados = array(
    //             'A' => 'Activo',
    //             'F' => 'Finalizado',
    //     );
    //     $form->get('actividades_estado')->setValueOptions($estados);
    //     $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');

    //     $datos = array(
    //         'form' => $form
    //     );

    //     return new ViewModel($datos);
    // }

    // public function guardarAction()
    // {
    //     if (!$this->request->isPost()) {
    //         return $this->redirect()->toRoute(
    //             'actividades',
    //             array('controller' => 'actividades')
    //         );
    //     }

    //     $form = new ActividadesForm("actividadesform");
    //     $form->setInputFilter(new ActividadesFormValidator());
    //     // Obtenemos los datos desde el Formulario con POST data:
    //     $data = $this->request->getPost();

    //     $form->setData($data);

    //     if (!$form->isValid()) {            

    //         $estados = array(
    //             'A' => 'Activo',
    //             'F' => 'Finalizado',
    //         );
    //         $form->get('actividades_estado')->setValueOptions($estados);

    //         $modelView = new ViewModel(array('form' => $form));
    //         $modelView->setTemplate('application/actividades/crear');
    //         return $modelView;
    //     }

    //     $id = $this->getTipoObjetosBO()->guardar($form->getData());
    //     return $this->redirect()->toRoute(
    //         'actividades',
    //         array(
    //             'controller' => 'actividades',
    //             'action' => 'index'                
    //         )
    //     );
    // }

    // public function eliminarAction()
    // {
    //     $id = (int)$this->params()->fromRoute('id', 0);
    //     if (!$id) {
    //         return $this->redirect()->toRoute('actividades');
    //     }

    //     $this->getTipoObjetosBO()->eliminar($id);

    //     return $this->redirect()->toRoute('actividades');
    // }    
}
