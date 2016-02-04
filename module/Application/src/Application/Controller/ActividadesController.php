<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Actividades\ActividadesForm;
use Application\Form\Actividades\ActividadesFormValidator;
use PHPExcel;
use PHPExcel_IOFactory;
use IOException;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;

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

        $datos = array(
            'title' =>  "Actividades",
            'actividades' => $this->getActividadesBO()->obtenerActivas() ,            
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
        
        $usuarios = $this->getUsuariosBO()->obtenerCombo();        
        $form->get('actividades_responsable')->setValueOptions($usuarios);

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

    public function bitacoraAction()
    {
        //php composer.phar require phpoffice/phpexcel
        //'PHPExcel' => array($vendorDir . '/phpoffice/phpexcel/Classes'),
        $objPHPExcel = new \PHPExcel();
        $i = 3;
        // Set properties
        $objPHPExcel->getProperties()->setCreator("ThinkPHP")
                ->setLastModifiedBy("Stalin Caiche")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getActiveSheet()->setTitle('Bitacora');

        $datos = $this->getActividadesBO()->obtenerActivas();

        // cabecera
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2', 'ID')
                ->setCellValue('B2', 'Actividad')
                ->setCellValue('C2', 'Fecha');

        foreach ($datos as $key => $actividad) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $actividad->getActividadesId())
                ->setCellValue('B'.$i, $actividad->getActividadesNombre())
                ->setCellValue('C'.$i, $actividad->getActividadesFecha());

            $i += 1;
        }



        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // If you want to output e.g. a PDF file, simply do:
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        $nomFile  = 'Bitacora_CAICHE_'.date('yyyy-mm-dd').'.xlsx';
        $objWriter->save($nomFile);

        // $objPHPExcel = PHPExcel_IOFactory::load("C:\Users\Stalin Caiche\Documents\TRABAJO\MyExcel.xlsx");
        $layout = $this->layout();


        // $modelView = new ViewModel(array('form' => $form));
        $modelView = new ViewModel(array());
        $modelView->setTemplate('application/actividades/bitacora');
        return $modelView;

    }

    public function generarexcelAction()
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load("MyExcel.xlsx"); 

        $modelView = new ViewModel(array());
        $modelView->setTemplate('application/actividades/bitacora');
        return $modelView;
    }
}
