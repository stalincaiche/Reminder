<?php
namespace Application\Form\Tipoobjetos;

use Zend\Form\Form;
use Zend\Form\Element;

class TipoobjetosForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('actividadesform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'tipoobjetosform'));

        $tipo_objeto_id = new Element('tipo_objeto_id');
        $tipo_objeto_id->setLabel('ID');
        $tipo_objeto_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'tipo_objeto_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );
    
        $tipo_objeto_nombre = new Element('tipo_objeto_nombre');
        $tipo_objeto_nombre->setLabel('Nombre');
        $tipo_objeto_nombre->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'ej: Procedimiento',
                'id' => 'tipo_objeto_nombre',
                'class' => 'form-control',
                //'data-rule-required' => "true",
                //'data-msg-required' => "Debe ingresar el nombre del usuario",
                //'data-rule-minlength' => "4",
                //'data-msg-minlength' => "El nombre del Usuario debe tener mínimo 4 caracteres",
                //'data-rule-maxlength' => "50",
                //'data-msg-maxlength' => "El nombre del Usuario debe tener máximo 50 caracteres",
            )
        );

        $tipo_objeto_estado = new Element\Select('tipo_objeto_estado');
        $tipo_objeto_estado->setLabel('Estado');
        //$tipo_objeto_estado->setEmptyOption('ELige un Estado..');

        $tipo_objeto_estado->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $tipo_objeto_estado->setAttributes(
            array(
                'id' => 'tipo_objeto_estado',
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

        $this->add($tipo_objeto_id);
        $this->add($tipo_objeto_nombre);
        $this->add($tipo_objeto_estado);

        $this->add($guardar);
    }
}
