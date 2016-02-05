<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\Login\LoginForm;
use Application\Form\Login\LoginFormValidator;

use Application\Model\Login as LoginService;

use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class LoginController extends AbstractActionController
{
    private $login;
    private $logger;

    public function setLogin(LoginService $login)
    {
        $this->login = $login;
    }

    // public function setLogger($logger)
    // {
    //     $this->logger = $logger;
    // }

    public function indexAction()
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/layout_visitas');       

        // $renderer = $this->getServiceLocator()->get('ViewManager')->getRenderer();
        // $script = $renderer->render('application/login/js/index');
        // $renderer->headScript()->appendScript($script, 'text/javascript');

        $loginForm = new LoginForm();
        $loginForm->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/login/autenticar');
        $datos = array(
            'form' => $loginForm,
            'title' => 'Inicio de Sesión'
        );
        return new ViewModel($datos);
    }

    public function autenticarAction()
    {
        // si no hay POST se redirecciona al login
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(
                'login',
                array(
                    'controller' => 'login'
                )
            );
        }

        $form = new LoginForm();
        $form->setInputFilter(new LoginFormValidator());

        // Obtenemos los datos desde el Formulario con POST data:
        $data = $this->request->getPost();

        $form->setData($data);

        // Validando el form
        if (!$form->isValid()) {
            $modelView = new ViewModel(array('form' => $form));
            $modelView->setTemplate('application/login/index');
            return $modelView;
        }

        $values = $form->getData();

        $username = $values['username'];
        $password = $values['password'];
        $remember = $values['remember'];

        try {
            $this->login->setMessage(
                'El nombre de Usuario y Contraseña no coinciden.',
                LoginService::NOT_IDENTITY
            );

            $this->login->setMessage(
                'La contraseña ingresada es incorrecta. Inténtelo de nuevo.',
                LoginService::INVALID_CREDENTIAL
            );

            $this->login->setMessage(
                'Los campos de Usuario y Contraseña no pueden dejarse en blanco.',
                LoginService::INVALID_LOGIN
            );

            $this->login->login($username, $password);
            
            if ($remember == 1) {
                $arrayConf = array(
                    'name' => 'reminderSesion',
                    'cookie_lifetime' => 2419200,
                    'remember_me_seconds' => 2419200,
                    'use_cookies' => true,
                    'cookie_httponly' => true,

                );
            } else {
                $arrayConf = array(
                    'remember_me_seconds' => 1800,
                    'name' => 'reminderSesion',
                );
            }

            $config = new StandardConfig();
            $config->setOptions($arrayConf);

            $manager = new SessionManager($config);

            //guardamos las variables de sesion
            $sesion = new Container('reminderSesion');
            
            $sesion->user_username = $this->login->getIdentity()->usuarios_username;
            $sesion->user_user_id = $this->login->getIdentity()->usuarios_id;
            // $sesion->admRolesID = $this->login->getIdentity()->admRolesID;            

            //$this->flashMessengerPlus()->addSuccess('Login Correcto!', $this->login->message, false, null);
            //$this->logger->info("Login Correcto! Usuario: " . $username);

            $this->redirect()->toRoute('actividades');
        }
        catch(\Exception $e) {

            //$this->logger->err($e->getMessage());
            //$this->flashMessengerPlus()->addError('Login Incorrecto!', $e->getMessage(), false, null);

            return $this->redirect()->toRoute(
                'login',
                array(
                    'controller' => 'login',
                    'action' => 'index'
                )
            );
        }
    }

    public function logoutAction()
    {
        $this->login->logout();
        $sesion = new Container('reminderSesion');
        $sesion->getManager()->getStorage()->clear('reminderSesion');
        //$this->logger->info("Usuario desconectado: " . $this->login->getIdentity());

        //$this->flashMessengerPlus()->addSuccess('Logout Correcto!', 'Logout Correcto', false, null);
        return $this->redirect()->toRoute(
            'login',
            array(
                'controller' => 'login',
                'action' => 'index'
            )
        );
    }
}
