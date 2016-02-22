<?php
namespace Application\Form\Etiquetas;

use Zend\Form\Form;
use Zend\Form\Element;

class EtiquetasForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('objetosform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'objetosform'));

        $etiquetas_id = new Element('etiquetas_id');
        $etiquetas_id->setLabel('ID');
        $etiquetas_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'etiquetas_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );

        $objetos_id = new Element\Hidden('objetos_id');        
        $objetos_id->setAttributes(
            array(                
                'id' => 'objetos_id',
            )
        );

        $etiquetas_nombre = new Element('etiquetas_nombre');
        $etiquetas_nombre->setLabel('Nombre');
        $etiquetas_nombre->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Nueva Etiqueta',
                'id' => 'etiquetas_nombre',
                'class' => 'form-control',                
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

        $this->add($etiquetas_id);
        $this->add($objetos_id);
        $this->add($etiquetas_nombre);
        $this->add($guardar);
    }
}
