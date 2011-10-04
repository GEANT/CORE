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
 * @revision   $Id: LastRequest.php 598 2011-09-15 20:55:32Z visser $
 */

/**
 * Redirect Helper
 *
 * @author Christian Gijtenbeek
 * @package TA_Controller
 * @subpackage Helper
 */ 
class TA_Controller_Action_Helper_LastRequest extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Enter description here...
     *
     * @var string
     */
    protected $_namespace = __CLASS__;

    /**
     * Enter description here...
     *
     * @var Zend_Session_Namespace
     */
    protected $_session = null;

    /**
     * Enter description here...
     *
     * @param string $namespace
     * @return TA_Controller_Action_Helper_LastRequest
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Enter description here...
     *
     * @param Zend_Session_Namespace $session
     * @return TA_Controller_Action_Helper_LastRequest
     */
    public function setSession($session)
    {
        $this->_session = $session;
        return $this;
    }

    /**
     * Enter description here...
     *
     * @return Zend_Session_Namespace
     */
    public function getSession()
    {
        if (null === $this->_session) {
            $this->_session = new Zend_Session_Namespace($this->getNamespace());
        }
        return $this->_session;
    }

    /**
     * Enter description here...
     *
     * @return Zend_Controller_Action_Helper_Redirector
     */
    protected function _getRedirector()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
    }

    /**
     * Enter description here...
     *
     * @param string $requestUri
     * @return TA_Controller_Action_Helper_LastRequest
     */
    public function saveRequestUri($requestUri = '')
    {
	    if ('' === $requestUri) {
            $requestUri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        }
        $this->getSession()->lastRequestUri = $requestUri;

        return $this;
    }

    /**
     *
     * @return bool
     */
    public function hasRequestUri()
    {
        $session = $this->getSession();
        return isset($session->lastRequestUri);
    }

    /**
     * Enter description here...
     *
     * @return string|null
     */
    public function getRequestUri()
    {
        $session = $this->getSession();
        if ($this->hasRequestUri()) {
            $lastRequestUri = $session->lastRequestUri;
            unset($session->lastRequestUri);
            return $lastRequestUri;
        } else {
            return null;
        }
    }

    public function redirect()
    {
        if (null === ($lastRequestUri = $this->getRequestUri())) {
            $this->_getRedirector()->gotoUrl('/');
        } else {
            $this->_getRedirector()->gotoUrl($lastRequestUri);
        }
    }

    public function direct()
    {
        $this->redirect();
    }
}