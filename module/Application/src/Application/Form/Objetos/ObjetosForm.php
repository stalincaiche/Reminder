<?php
namespace Application\Form\Objetos;

use Zend\Form\Form;
use Zend\Form\Element;

class ObjetosForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('objetosform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'objetosform'));

        $objetos_actividad_id = new Element('objetos_actividad_id');
        $objetos_actividad_id->setLabel('Actividad');
        $objetos_actividad_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'objetos_actividad_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );

        $objetos_id = new Element('objetos_id');
        $objetos_id->setLabel('ID');
        $objetos_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'objetos_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );
    
        $objetos_nombre = new Element('objetos_nombre');
        $objetos_nombre->setLabel('Nombre');
        $objetos_nombre->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'ej: wKER001',
                'id' => 'objetos_nombre',
                'class' => 'form-control',                
            )
        );

        $objetos_tipo = new Element\Select('objetos_tipo');
        $objetos_tipo->setLabel('Tipo');
        $objetos_tipo->setEmptyOption('Elige un Tipo..');

        $objetos_tipo->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $objetos_tipo->setAttributes(
            array(
                'id' => 'objetos_tipo',
                'class' => "form-control",
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe seleccionar el Tipo",
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

        $etiquetas_id = new Element\Hidden('etiquetas_id');        
        $etiquetas_id->setAttributes(
            array(                
                'id' => 'etiquetas_id',
            )
        );

        $this->add($objetos_actividad_id);
        $this->add($objetos_id);
        $this->add($objetos_nombre);
        $this->add($objetos_tipo);
        $this->add($etiquetas_id);

        $this->add($guardar);
    }
}
