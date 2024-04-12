<?php

class IndexController extends Zend_Controller_Action
{

    private $path;
    private $varSession;

    public function init()
    {
        $this->_helper->layout->setLayout("administracion");
        //$this->_helper->layout()->disableLayout();
        $this->varSession = new Zend_Session_Namespace("users");
        $this->path = Zend_Layout::getMvcInstance()->getView()->baseUrl();
        $this->view->path = $this->path;
    }

    public function indexAction()
    {
        if (!isset($this->varSession->usuarios_id))
            $this->redirect("/Login");
        $this->view->usuario = $this->varSession->usuario;
    }

    public function denyAction()
    {
        if (!isset($this->varSession->usuarios_id))
            $this->redirect("/Login");
        $this->_helper->layout->setLayout("administracion");
    }

    public function testAction()
    {
        if (!isset($this->varSession->usuarios_id))
            $this->redirect("/Login");
        //Prepare email

        $mail = new Zend_Mail();
        $mail->addTo("lylyal@hotmail.com");
        $mail->setSubject(utf8_decode("Recuperación de contraseña"));
        $mail->setBodyHtml(utf8_decode("<center><h1>Recuperación de Contraseña</h1>Estimado Usuario se hace llegar su contraseña correspondiente: <b>2024</b></center>"));
        $mail->setFrom('administracion@ecomasclean.com.mx', 'EcoClean, tu proveedor de limpieza');

        //Send it!
        $sent = true;
        try {
            $mail->send();
        } catch (Exception $e) {
            $sent = false;
            die($e);
        }

        //Do stuff (display error message, log it, redirect user, etc)
        if ($sent) {
            //Mail was sent successfully.
        } else {
            //Mail failed to send.
        }
    }

    public function sendpasswordAction()
    {
        $this->_helper->layout->setLayout("login");
        if ($this->getRequest()->isPost()) {
            $email = strtolower($this->getRequest()->getParam("email"));
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if ($email == "") {
                $this->view->error = "Ingrese un correo electrónico.";
                $this->view->old = $email;
            } elseif (!preg_match($regex, $email)) {
                $this->view->error = "El valor ingresado no es un correo electrónico válido.";
                $this->view->old = $email;
            } else {
                //buscamos en la base de datos si existe el correo asociado a algun empleado
                $Musuario = new Model_Usuario();
                $usuario = $Musuario->getusuariobyemail($email);
                if ($usuario != null) {

                    $mail = new Zend_Mail();
                    $mail->addTo($email);
                    $mail->setSubject(utf8_decode("Recuperación de contraseña"));
                    $mail->setBodyHtml(utf8_decode("<center><h1>Recuperación de Contraseña</h1>Estimado Usuario <b>".$usuario->cuenta."</b> se hace llegar su contraseña correspondiente: <b>".$usuario->enc."</b></center>"));
                    $mail->setFrom('administracion@ecomasclean.com.mx', 'EcoClean, tu proveedor de limpieza');

                    //Send it!                
                    //$sent = true;
                    $this->view->sent = "Se ha enviado al correo: <b>" . $email . "</b> la contraseña correspondiente!";
                    try {
                        $mail->send();
                    } catch (Exception $e) {
                        $this->view->error = "El email no pudo enviarse, intentar más tarde!";
                        $this->view->old = $email;
                    }
                    
                } else {
                    $this->view->error = "Email no localizado";
                    $this->view->old = $email;
                }
            }
        }
    }
}
