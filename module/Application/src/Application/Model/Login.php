<?php
namespace Application\Model;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;

class Login
{

    private $auth;
    private $authAdapter;

    public $message;
    public $tituloMessage;

    const NOT_IDENTITY = 'notIdentity';
    const INVALID_CREDENTIAL = 'invalidCredential';
    const INVALID_USER = 'invalidUser';
    const INVALID_LOGIN = 'invalidLogin';

    protected $messages = array(
        self::NOT_IDENTITY => "El nombre de Usuario y la Contraseña no coinciden.",
        self::INVALID_CREDENTIAL => "La contraseña ingresada es incorrecta. Inténtelo de nuevo.",
        self::INVALID_USER => "El Usuario ingresado es incorrecto. Inténtelo de nuevo.",
        self::INVALID_LOGIN => "Los campos de Usuario y Contraseña no pueden dejarse en blanco."
    );

    public function __construct($dbAdapter)
    {
        $this->authAdapter = new AuthAdapter($dbAdapter, 'usuarios', 'usuarios_username', 'usuarios_password', 'usuarios_id');

        $this->auth = new AuthenticationService();
    }

    public function login($username, $password)
    {
        if (!empty($username) && !empty($password)) {
            $this->authAdapter->setIdentity($username);
            // $this->authAdapter->setCredential(crypt($password, 'N0M3H@ck335'));
            $this->authAdapter->setCredential($password);

            $result = $this->auth->authenticate($this->authAdapter);

            switch ($result->getCode()) {
            case Result::FAILURE_IDENTITY_NOT_FOUND:

                $this->message = $this->messages[self::NOT_IDENTITY];
                $this->tituloMessage = "Login Invalido";

                throw new \Exception($this->messages[self::NOT_IDENTITY]);
                    break;

            case Result::FAILURE_CREDENTIAL_INVALID:

                $this->message = $this->messages[self::INVALID_CREDENTIAL];
                $this->tituloMessage = "Login Invalido";

                throw new \Exception($this->messages[self::INVALID_CREDENTIAL]);
                    break;

            case Result::SUCCESS:
                if ($result->isValid()) {
                    $data = $this->authAdapter->getResultRowObject();
                    $this->auth->getStorage()->write($data);
                    $this->message = 'Bienvenido ' . $username;
                    $this->tituloMessage = "Login Correcto";
                } else {
                    $this->message = $this->messages[self::INVALID_USER];
                    throw new \Exception($this->messages[self::INVALID_USER]);
                }
                break;

            default:
                $this->message = $this->messages[self::INVALID_LOGIN];
                $this->tituloMessage = "Login Invalido";

                throw new \Exception($this->messages[self::INVALID_LOGIN]);
                    break;
            }
        } else {
            $this->message = $this->messages[self::INVALID_LOGIN];
            $this->tituloMessage = "Login Invalido";

            throw new \Exception($this->messages[self::INVALID_LOGIN]);
        }
        return $this;
    }

    public function logout()
    {
        $this->auth->clearIdentity();
        return $this;
    }

    public function getIdentity()
    {
        if ($this->auth->hasIdentity()) {
            return $this->auth->getIdentity();
        }
        return null;
    }

    public function isLoggedIn()
    {
        return $this->auth->hasIdentity();
    }

    /**
     * @param string $messageString
     * @param string $messageKey    OPTIONAL
     * @return UserModel
     * @throws Exception
     */
    public function setMessage($messageString, $messageKey = null)
    {
        if ($messageKey === null) {
            $keys = array_keys($this->messages);
            $messageKey = current($keys);
        }
        if (!isset($this->messages[$messageKey])) {
            throw new \Exception("No message exists for key '$messageKey'");
        }
        $this->messages[$messageKey] = $messageString;
        return $this;
    }

    /**
     * @param array $messages
     * @return UserModel
     */
    public function setMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            $this->setMessage($message, $key);
        }
        return $this;
    }
}
