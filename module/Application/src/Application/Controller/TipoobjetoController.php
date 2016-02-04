<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Tipoobjetos\TipoobjetosForm;
use Application\Form\Tipoobjetos\TipoobjetosFormValidator;

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
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/tipoobjeto/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'tipos' => $this->getTipoObjetosBO()->obtenerTodos() ,
            'title' => 'Tipos de Objeto'
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('tipoobjetos');
        }

        $form = new TipoobjetosForm();
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/tipoobjeto/guardar');

        $tipo = $this->getTipoObjetosBO()->obtenerPorId($id);

        if (!is_object($tipo)) {
            return $this->redirect()->toRoute('tipoobjetos');
        }

        $form->bind($tipo);
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
        $form->get('tipo_objeto_estado')->setValueOptions($estados);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Tipo de Objeto'
            )
        );

        $modelView->setTemplate('application/tipoobjeto/crear');
        return $modelView;
    }

    public function crearAction()
    {
        $form = new TipoobjetosForm();
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
        $form->get('tipo_objeto_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/tipoobjeto/guardar');

        $datos = array(
            'form' => $form,
            'title' => 'Nuevo Tipo de Objeto'
        );

        return new ViewModel($datos);
    }

    public function guardarAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'tipoobjetos',
                array('controller' => 'tipoobjeto')
            );
        }

        $form = new TipoobjetosForm("tipoobjetosform");
        $form->setInputFilter(new TipoobjetosFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        if (!$form->isValid()) {            

            $estados = array(
                'A' => 'Activo',
                'I' => 'Inactivo',
            );
            $form->get('tipo_objeto_estado')->setValueOptions($estados);
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
                    'title' => 'Guardar Tipo de Objeto'
                )
            );
            $modelView->setTemplate('application/tipoobjeto/crear');
            return $modelView;
        }

        $id = $this->getTipoObjetosBO()->guardar($form->getData());
        return $this->redirect()->toRoute(
            'tipoobjetos',
            array(
                'controller' => 'tipoobjeto',
                'action' => 'index'                
            )
        );
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tipoobjetos');
        }

        $this->getTipoObjetosBO()->eliminar($id);

        return $this->redirect()->toRoute('tipoobjetos');
    }
}
