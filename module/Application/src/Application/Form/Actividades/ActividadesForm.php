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

        $guardar = new Element\Submit('guardar');
        $guardar->setAttributes(
            array(
                'class' => 'btn btn-primary mr5',
                'type' => 'submit',
                'id' => 'guardar',
            )
        );

        $this->add($actividades_id);
        $this->add($actividades_nombre);
        $this->add($actividades_estado);

        $this->add($guardar);
    }
}
