<?php
namespace Application\Form\Actividades;

use Zend\Form\Form;
use Zend\Form\Element;

class ActividadesForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('actividadesform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'actividadesform'));

        $actividades_id = new Element('actividades_id');
        $actividades_id->setLabel('ID');
        $actividades_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'actividades_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );
    
        $actividades_nombre = new Element('actividades_nombre');
        $actividades_nombre->setLabel('Nombre');
        $actividades_nombre->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'ej: Mi Primera Actividad',
                'id' => 'actividades_nombre',
                'class' => 'form-control',
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe ingresar el nombre del usuario",
                //'data-rule-minlength' => "4",
                //'data-msg-minlength' => "El nombre del Usuario debe tener mínimo 4 caracteres",
                //'data-rule-maxlength' => "50",
                //'data-msg-maxlength' => "El nombre del Usuario debe tener máximo 50 caracteres",
            )
        );

        $actividades_estado = new Element\Select('actividades_estado');
        $actividades_estado->setLabel('Estado');
        //$actividades_estado->setEmptyOption('ELige un Estado..');

        $actividades_estado->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $actividades_estado->setAttributes(
            array(
                'id' => 'actividades_estado',
                'class' => "form-control",
                'data-rule-required' => "true",
                'data-msg-required' => "Debe seleccionar el Estado",
            )
        );

        $actividades_responsable = new Element\Select('actividades_responsable');
        $actividades_responsable->setLabel('Responsable');
        // $actividades_responsable->setEmptyOption('Elige un Responsable..');

        $actividades_responsable->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $actividades_responsable->setAttributes(
            array(
                'id' => 'actividades_responsable',
                'class' => "form-control",
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe seleccionar el Tipo",
            )
        );

        $actividades_area = new Element\Select('actividades_area');
        $actividades_area->setLabel('Área');
        $actividades_area->setEmptyOption('Elige una Área..');

        $actividades_area->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $actividades_area->setAttributes(
            array(
                'id' => 'actividades_area',
                'class' => "form-control",
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe seleccionar el Tipo",
            )
        );

        $actividades_reporta = new Element('actividades_reporta');
        $actividades_reporta->setLabel('Reportada Por');
        $actividades_reporta->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Persona que reporta',
                'id' => 'actividades_reporta',
                'class' => 'form-control',
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe ingresar el nombre del usuario",
                //'data-rule-minlength' => "4",
                //'data-msg-minlength' => "El nombre del Usuario debe tener mínimo 4 caracteres",
                //'data-rule-maxlength' => "50",
                //'data-msg-maxlength' => "El nombre del Usuario debe tener máximo 50 caracteres",
            )
        );

        $actividades_fecha = new Element('actividades_fecha');
        $actividades_fecha->setLabel('Fecha de Inicio');
        $actividades_fecha->setAttributes(
            array(
                'placeholder' => 'Fecha de Inicio',
                'id' => 'actividades_fecha',
                'class' => 'form-control',
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe ingresar una fecha de Inicio",
            )
        );

        $actividades_fecha_fin = new Element('actividades_fecha_fin');
        $actividades_fecha_fin->setLabel('Fecha de Finalización');
        $actividades_fecha_fin->setAttributes(
            array(
                'placeholder' => 'Fecha de Finalización',
                'id' => 'actividades_fecha_fin',
                'class' => 'form-control',
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe ingresar una fecha de Finalización",
            )
        );

        $guardar = new Element\Button('guardar');
        $guardar->setAttributes(
            array(
                'class' => 'btn btn-success mr5',
                'type' => 'submit',
                'id' => 'guardar',
            )
        );
        $guardar->setOptions(array(
            'label' => '<i class="glyphicon glyphicon-floppy-disk"></i>',
            'label_options' => array(
                'disable_html_escape' => true,
            )
        ));

        $this->add($actividades_id);
        $this->add($actividades_nombre);
        $this->add($actividades_fecha);
        $this->add($actividades_estado);
        $this->add($actividades_responsable);
        $this->add($actividades_area);
        $this->add($actividades_reporta);
        $this->add($actividades_fecha_fin);

        
        $this->add($guardar);    
    }
}
