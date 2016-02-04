<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Areas\AreasForm;
use Application\Form\Areas\AreasFormValidator;

class AreasController extends AbstractActionController
{

	private $areasBO;

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
        $script = $renderer->render('application/areas/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'areas' => $this->getAreasBO()->obtenerTodos() ,
            'title' => 'Áreas'
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('areas');
        }

        $form = new AreasForm();
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/areas/guardar');

        $area = $this->getAreasBO()->obtenerPorId($id);

        if (!is_object($area)) {
            return $this->redirect()->toRoute('areas');
        }

        $form->bind($area);
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
                'I' => 'Inactivo',
        );
        $form->get('areas_estado')->setValueOptions($estados);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Área'
            )
        );

        $modelView->setTemplate('application/areas/crear');
        return $modelView;
    }

    public function crearAction()
    {
        $form = new AreasForm();
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
                'I' => 'Inactivo',
        );
        $form->get('areas_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/areas/guardar');

        $datos = array(
            'form' => $form,
            'title' => 'Nueva Área'
        );

        return new ViewModel($datos);
    }

    public function guardarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'areas',
                array('controller' => 'areas')
            );
        }

        $form = new AreasForm("Areasform");
        $form->setInputFilter(new AreasFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        if (!$form->isValid()) {            

            $estados = array(
                'A' => 'Activo',
                'I' => 'Inactivo',
            );
            $form->get('areas_estado')->setValueOptions($estados);
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
                    'title' => 'Guardar Área'
                )
            );
            $modelView->setTemplate('application/areas/crear');
            return $modelView;
        }

        $id = $this->getAreasBO()->guardar($form->getData());
        return $this->redirect()->toRoute(
            'areas',
            array(
                'controller' => 'areas',
                'action' => 'index'                
            )
        );
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('areas');
        }

        $this->getAreasBO()->eliminar($id);

        return $this->redirect()->toRoute('areas');
    }
}
