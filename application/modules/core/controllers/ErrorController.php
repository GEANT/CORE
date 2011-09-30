<?php
/**
 * CORE Conference Manager
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.terena.org/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to webmaster@terena.org so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2011 TERENA (http://www.terena.org)
 * @license    http://www.terena.org/license/new-bsd     New BSD License
 * @revision   $Id: ErrorController.php 619 2011-09-29 11:20:22Z gijtenbeek $
 */
 
/**
 * ErrorController
 *
 * @package Core_Controllers
 */ 
class Core_ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch (get_class($errors->exception)) {

            case 'Zend_Controller_Dispatcher_Exception':
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            case 'Zend_Controller_Dispatcher_Exception':
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = '404 page not found.';
                break;
            case 'TA_Model_Acl_Exception':
                $this->view->message = $errors->exception->getMessage();
                break;
            case 'TA_Model_Exception':
                $this->view->message = $errors->exception->getMessage();
                break;
            default:
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application Error';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;

    }

	public function getLog()
	{
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }



    /**
     * This is called when a user visits a restricted page that is not
     * protected by ACL from the navigation object.
     *
     */
    public function noaccessAction() {}


}