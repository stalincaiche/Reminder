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
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

use Zend\Session\Container;


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

        $sesion = new Container('reminderSesion');
        $user_user_id = $sesion->user_user_id;

        $datos = array(
            'title' =>  "Actividades",

            'actividades' => $this->getActividadesBO()->obtenerActivasPorUsuario($user_user_id) ,
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
        $renderer->inlineScript()->appendFile($script, 'text/javascript');

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
        // $form->get('actividades_responsable')->setValueOptions($usuarios);

        $sesion = new Container('reminderSesion');
        $user_username = $sesion->user_username;
        $user_user_id = $sesion->user_user_id;

        // $usuarios = $this->getUsuariosBO()->obtenerCombo();
        $form->get('actividades_responsable')->setValueOptions(array( $user_user_id => $user_username ));


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
        $renderer->inlineScript()->appendScript($script, 'text/javascript');

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
        // agregando scripts necesarios
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();

        $script = $renderer->render('application/actividades/js/crear');
        $renderer->inlineScript()->prependFile($script, 'text/javascript');


        $form = new ActividadesForm();
        // $form->get('guardar')->setOptions(
        //     array(
        //         'label' => '<i class="glyphicon glyphicon-floppy-disk"></i> Guardar',
        //         'label_options' => array(
        //             'disable_html_escape' => true,
        //         )
        //     )
        // );
        
        // $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/actividades/guardar2');

        $areas = $this->getAreasBO()->obtenerCombo();
        $form->get('actividades_area')->setValueOptions($areas);

        $usuarios = $this->getUsuariosBO()->obtenerCombo();
        $form->get('actividades_responsable')->setValueOptions($usuarios);

        $datos = array(
            'form' => $form,
            'title' =>  "BITACORA",
        );
        $modelView = new ViewModel($datos);
        $modelView->setTemplate('application/actividades/bitacora');
        return $modelView;
    }

    public function bitacora2Action()
    {
        //php composer.phar require phpoffice/phpexcel
        //'PHPExcel' => array($vendorDir . '/phpoffice/phpexcel/Classes'),
        $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();

        $form = new ActividadesForm();


        $objPHPExcel = new \PHPExcel();
        $i           = 3;
        $secuencial  = 1;
        $fecha_fin   = $form->getActividadesFechaFin();
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

        #estilos
        $styleArray = array(
                            'font' => array(
                                'bold' => true,
                            ),
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                            ),
                            'borders' => array(
                                'top' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                ),
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startcolor' => array(
                                    'argb' => 'FFA0A0A0',
                                ),
                                'endcolor' => array(
                                    'argb' => 'FFFFFFFF',
                                ),
                            ),
                        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(50);


        // cabecera
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No.')
                ->setCellValue('B2', 'COMPAÑIA')
                ->setCellValue('C2', 'RESPONSABLE')
                ->setCellValue('D2', 'AREA')
                ->setCellValue('E2', 'REPORTADO POR')
                ->setCellValue('F2', 'FECHA REPORTADA')
                ->setCellValue('G2', 'DESCRIPCIÓN');

        foreach ($datos as $key => $actividad) {
            if( $actividad->getActividadesFechaFin() != NULL OR $actividad->getActividadesFechaFin() != ''){
                $periodo = $actividad->getActividadesFecha(). ' - '.$actividad->getActividadesFechaFin();
            }else{
                $periodo = $actividad->getActividadesFecha(). ' - '.$fecha_fin;
            }


            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $secuencial)
                ->setCellValue('C'.$i, $actividad->getActividadesResponsable())
                ->setCellValue('D'.$i, $actividad->getActividadesArea())
                ->setCellValue('E'.$i, $actividad->getActividadesReporta())
                ->setCellValue('F'.$i, $periodo);

            $i += 1;
            $secuencial += 1;
        }



        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // If you want to output e.g. a PDF file, simply do:
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        $nomFile  = 'Bitacora_CAICHE_'.date('Y-m-d').'.xlsx';
        $objWriter->save($nomFile);

        // $objPHPExcel = PHPExcel_IOFactory::load("C:\Users\Stalin Caiche\Documents\TRABAJO\MyExcel.xlsx");
        $layout = $this->layout();


        $modelView = new ViewModel(array('form' => $form));
        //$modelView = new ViewModel(array());
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
