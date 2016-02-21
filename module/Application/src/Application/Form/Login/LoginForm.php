<?php
namespace Application\Form\Login;

use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('loginForm');
        $this->setAttributes(
            array(
                'id' => 'loginForm',
                'role' => 'form',
                'method' => 'post',
            )
        );

        $username = new Element('username');
        $username->setLabel('Nombre de Usuario');
        $username->setAttributes(
            array(
                'type' => 'text',
                'placeholder' => 'Escribe tu Nombre de Usuario',
                'id' => 'username',
                'class' => 'form-control',
                // 'data-rule-required' => "true",
                // 'data-msg-required' => "Este campo debe estar lleno",
                // 'data-rule-minlength' => "6",
                // 'data-msg-minlength' => "Debe tener al menos 6 caracteres",
            )
        );

        $password = new Element\Password('password');
        $password->setLabel('Contraseña');
        $password->setAttributes(
            array(
                'placeholder' => 'Escribe tu Contraseña',
                'id' => 'password',
                'class' => 'form-control',
                // 'data-rule-required' => "true",
                // 'data-msg-required' => "Este campo debe estar lleno",
            )
        );

        $remember = new Element\Checkbox('remember');
        $remember->setLabel('Mantener mi sesión activa');
        $remember->setUseHiddenElement(true);
        $remember->setCheckedValue("1");
        $remember->setUncheckedValue("0");
        $remember->setAttributes(array('id' => 'remember',));

        $loginButton = new Element\Button('loginButton');
        $loginButton->setLabel('Inicia Sesión');
        $loginButton->setAttributes(
            array(
                'class' => 'btn btn-primary',
                'type' => 'submit',
                'id' => 'loginButton',
            )
        );

        $this->add($username);
        $this->add($password);
        $this->add($remember);
        $this->add($loginButton);
    }
}
