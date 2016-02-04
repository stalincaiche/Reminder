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
        $datos = array(
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
        $form->get('guardar')->setAttribute('value', 'Editar');

        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);


        $modelView = new ViewModel(array('form' => $form));

        $modelView->setTemplate('application/actividades/crear');
        return $modelView;
    }

    public function crearAction()
    {
        $form = new ActividadesForm();
        $form->get('guardar')->setValue('Agregar');
        $estados = array(
                'A' => 'Activo',
                'F' => 'Finalizado',
        );
        $form->get('actividades_estado')->setValueOptions($estados);
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar');

        $datos = array(
            'form' => $form
        );

        return new ViewModel($datos);
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

            $modelView = new ViewModel(array('form' => $form));
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

    public function eliminarAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('actividades');
        }

        $this->getActividadesBO()->eliminar($id);

        return $this->redirect()->toRoute('actividades');
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
        $datos = array(
            'actividades' => $this->getActividadesBO()->obtenerTodos() ,
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
