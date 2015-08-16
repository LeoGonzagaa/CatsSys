<?php

/**
 * TODO
 * O modulo Auth é responsável por autenticação e autorização
 * rotas deverão ser ajustadas para: 
 * auth --> página de login
 * auth/login --> página de login
 * auth/logout --> página de logout (retorna para página de login)
 * 
 */

namespace Authentication\Controller;

/*
 * traits
 */

use Authentication\Form\LoginFilter;
use Authentication\Form\LoginForm;
use Database\Service\EntityManagerService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;

/**
 * Description of LoginController
 *
 * @author marcio
 */
class LoginController extends AbstractActionController
{

    use EntityManagerService;

    /**
     * Faz a autenticação de usuários
     * @return ViewModel
     */
    public function loginAction()
    {
        $loginForm = new LoginForm();
        $message = null;
        $request = $this->getRequest();

        if ($request->isPost()) {
            $loginForm->setInputFilter(new LoginFilter(
                    $this->getServiceLocator()
            ));
            $loginForm->setData($request->getPost());

            if ($loginForm->isValid()) {
                $data = $loginForm->getData();
                $this->userAuthentication($data) ?
                                $this->redirect()->toRoute('dashboard/default') :
                                $message = 'Credenciais inválidas.';
            }
        }

        return new ViewModel(array(
            'error' => 'Your authentication credentials are not valid!',
            'form' => $loginForm,
            'message' => $message,
        ));
    }

    protected function userAuthentication($data)
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $adapter = $auth->getAdapter();
        $adapter->setIdentityValue($data['username']);
        $adapter->setCredentialValue($data['password']);
        $authResult = $auth->authenticate();

        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $auth->getStorage()->write($identity);

            if ($data['rememberme']) {
                $sessionManager = new SessionManager();
                $sessionManager->rememberMe();
            }

            // store user roles in a session container
            $userContainer = new Container('User');
            $userContainer->offsetSet('id', $identity->getUserId());

            $userRoles = $identity->getRole()->toArray();

            $roleNames = array();

            foreach ($userRoles as $userRole) {
                $roleNames[] = $userRole->getRoleName();
            }

            $userContainer->offsetSet('activeRole', $roleNames[0]);
            $userContainer->offsetSet('allRoles', $roleNames);


            return true;
        }
        return false;
    }

    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();

            // remove user data
            $userContainer = new Container('User');
            $userContainer->getManager()->getStorage()->clear('User');

            // forget-me
            $sessionManager = new SessionManager();
            $sessionManager->forgetMe();
        }

        return $this->redirect()->toRoute('authentication/default');
    }

}
