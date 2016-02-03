<?php
namespace Application\Form\Usuarios;

use Zend\Form\Form;
use Zend\Form\Element;

class UsuariosForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('usuariosform');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setAttributes(array('id' => 'usuariosform'));

        $usuarios_id = new Element('usuarios_id');
        $usuarios_id->setLabel('ID');
        $usuarios_id->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Este campo se genera automáticamente',
                'id' => 'usuarios_id',
                'readonly' => 'readonly',
                'class' => 'form-control',
            )
        );
    
        $usuarios_username = new Element('usuarios_username');
        $usuarios_username->setLabel('Usuario');
        $usuarios_username->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'dvader',
                'id' => 'usuarios_username',
                'class' => 'form-control',
            )
        );

        $usuarios_nombres = new Element('usuarios_nombres');
        $usuarios_nombres->setLabel('Nombre');
        $usuarios_nombres->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Darth Vader',
                'id' => 'usuarios_nombres',
                'class' => 'form-control',
            )
        );

        $usuarios_estado = new Element\Select('usuarios_estado');
        $usuarios_estado->setLabel('Estado');
        //$usuarios_estado->setEmptyOption('ELige un Estado..');

        $usuarios_estado->setOptions(
            array(
                'disable_inarray_validator' => true
            )
        );
        $usuarios_estado->setAttributes(
            array(
                'id' => 'usuarios_estado',
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
        $guardar->setOptions(array(
            'label' => '<i class="glyphicon glyphicon-floppy-disk"></i>',
            'label_options' => array(
                'disable_html_escape' => true,
            )
        ));

        $this->add($usuarios_id);
        $this->add($usuarios_username);
        $this->add($usuarios_nombres);
        $this->add($usuarios_estado);

        $this->add($guardar);
    }
}
