<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Usuarios\UsuariosForm;
use Application\Form\Usuarios\UsuariosFormValidator;

class UsuariosController extends AbstractActionController
{

    private $usuariosBO;

    public function getUsuariosBO()
    {
        if (!$this->usuariosBO) {
            $sm = $this->getServiceLocator();
            $this->usuariosBO = $sm->get('Application\Model\UsuariosBO');
        }
        return $this->usuariosBO;
    }

    public function indexAction()
    {
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        $script = $renderer->render('application/usuarios/js/index');
        $renderer->headScript()->appendScript($script, 'text/javascript');

        $datos = array(
            'usuarios' => $this->getUsuariosBO()->obtenerTodos() ,
            'title' => 'Usuarios'
        );
        return new ViewModel($datos);
    }

    public function editarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('usuarios');
        }

        $form = new UsuariosForm();
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/usuarios/guardar');

        $usuario = $this->getUsuariosBO()->obtenerPorId($id);

        if (!is_object($usuario)) {
            return $this->redirect()->toRoute('usuarios');
        }

        $form->bind($usuario);
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
        $form->get('usuarios_estado')->setValueOptions($estados);

        
        $modelView = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Editar Usuario'
            )
        );

        $modelView->setTemplate('application/usuarios/crear');
        return $modelView;
    }

    public function crearAction()
    {
        $form = new UsuariosForm();
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
        $form->get('usuarios_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/usuarios/guardar');

        $datos = array(
            'form' => $form,
            'title' => 'Nuevo Usuario'
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

        $form = new UsuariosForm("Usuariosform");
        $form->setInputFilter(new UsuariosFormValidator());
        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        if (!$form->isValid()) {            

            $estados = array(
                'A' => 'Activo',
                'I' => 'Inactivo',
            );
            $form->get('usuarios_estado')->setValueOptions($estados);
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
            $modelView->setTemplate('application/usuarios/crear');
            return $modelView;
        }

        $id = $this->getUsuariosBO()->guardar($form->getData());
        return $this->redirect()->toRoute(
            'usuarios',
            array(
                'controller' => 'usuarios',
                'action' => 'index'                
            )
        );
    }

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('usuarios');
        }

        $this->getUsuariosBO()->eliminar($id);

        return $this->redirect()->toRoute('usuarios');
    }
}
