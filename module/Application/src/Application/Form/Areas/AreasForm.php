<?php
namespace Application\Form\Areas;

use Zend\Form\Form;
use Zend\Form\Element;

class AreasForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('areasform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'areasform'));

        $areas_id = new Element('areas_id');
        $areas_id->setLabel('ID');
        $areas_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'areas_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );
    
        $areas_nombre = new Element('areas_nombre');
        $areas_nombre->setLabel('Nombre');
        $areas_nombre->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'ej: Procedimiento',
                'id' => 'areas_nombre',
                'class' => 'form-control',
            )
        );

        $areas_estado = new Element\Select('areas_estado');
        $areas_estado->setLabel('Estado');
        //$areas_estado->setEmptyOption('ELige un Estado..');

        $areas_estado->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $areas_estado->setAttributes(
            array(
                'id' => 'areas_estado',
                'class' => "form-control",
                'data-rule-required' => "true",
                'data-msg-required' => "Debe seleccionar el Estado",
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
        $guardar->setOptions(
            array(
            'label' => '<i class="glyphicon glyphicon-floppy-disk"></i>',
            'label_options' => array(
                'disable_html_escape' => true,
            )
            )
        );

        $this->add($areas_id);
        $this->add($areas_nombre);
        $this->add($areas_estado);

        $this->add($guardar);
    }
}
