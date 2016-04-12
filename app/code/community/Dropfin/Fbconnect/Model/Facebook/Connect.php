<?php

/**
 * Dropfin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade 
 * this extension to newer versions in the future. 
 *
 * @category    Dropfin
 * @package     Facebook Connect
 * @copyright   Copyright (c) Dropfin (http://www.dropfin.com)
 */

class Dropfin_Fbconnect_Model_Facebook_Connect
{
    const REDIRECT_URI_ROUTE = 'fbconnect/account/connect';

    const OAUTH2_SERVICE_URI = 'https://graph.facebook.com';
    const OAUTH2_AUTH_URI = 'https://graph.facebook.com/oauth/authorize';
    const OAUTH2_TOKEN_URI = 'https://graph.facebook.com/oauth/access_token';

    protected $appId = null;
    protected $appSecret = null;
    protected $redirectUri = null;
    protected $state = '';
    protected $scope = array('email');

    protected $token = null;

    public function __construct($params = array())
    {
        if(($this->isEnabled = $this->_isEnabled())) {
            $this->appId = $this->_getAppId();
            $this->appSecret = $this->_getAppSecret();
            $this->redirectUri = Mage::getModel('core/url')->sessionUrlVar(
                Mage::getUrl(self::REDIRECT_URI_ROUTE)
            );

            if(!empty($params['scope'])) {
                $this->scope = $params['scope'];
            }

            if(!empty($params['state'])) {
                $this->state = $params['state'];
            }
        }
    }

    public function setState($state)
    {
        $this->state = $state;
    }
    
    public function getAccessToken()
    {
        if(empty($this->token)) {
            $this->fetchAccessToken();
        }

        return json_encode($this->token);
    }

    public function createAuthUrl()
    {
        $url =
        self::OAUTH2_AUTH_URI.'?'.
            http_build_query(
                array(
                    'client_id' => $this->appId,
                    'redirect_uri' => $this->redirectUri,
                    'state' => $this->state,
                    'scope' => implode(',', $this->scope)
                    )
            );
        return $url;
    }

    public function api($endpoint, $method = 'GET', $params = array())
    {
        if(empty($this->token)) {
            $this->fetchAccessToken();
        }

        $url = self::OAUTH2_SERVICE_URI.$endpoint;

        $method = strtoupper($method);

        $params = array_merge(array(
            'access_token' => $this->token->access_token
        ), $params);

        $response = $this->_httpRequest($url, $method, $params);

        return $response;
    }

    protected function fetchAccessToken()
    {
        if(empty($_REQUEST['code'])) {
            throw new Exception(
                Mage::helper('dropfin_fbconnect')->__('Unable to retrieve access code.')
            );
        }

        $response = $this->_httpRequest(
            self::OAUTH2_TOKEN_URI,
            'POST',
            array(
                'code' => $_REQUEST['code'],
                'redirect_uri' => $this->redirectUri,
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'grant_type' => 'authorization_code'
            )
        );

        $this->token = $response;
    }

    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        $connect = new Zend_Http_Client($url, array('timeout' => 60));

        switch ($method) {
            case 'GET':
                $connect->setParameterGet($params);
                break;
            case 'POST':
                $connect->setParameterPost($params);
                break;
            case 'DELETE':
                $connect->setParameterGet($params);
                break;
            default:
                throw new Exception(
                    Mage::helper('dropfin_fbconnect')->__('Required HTTP method is not supported.')
                );
        }

        $response = $connect->request($method);
        $decoded_response = json_decode($response->getBody());
        
        if(empty($decoded_response)) {
            $parsed_response = array();
            parse_str($response->getBody(), $parsed_response);

            $decoded_response = json_decode(json_encode($parsed_response));
        }

        if($response->isError()) {
            $status = $response->getStatus();
            if(($status == 400 || $status == 401)) {
                if(isset($decoded_response->error->message)) {
                    $message = $decoded_response->error->message;
                } else {
                    $message = Mage::helper('dropfin_fbconnect')->__('Unspecified OAuth error occurred.');
                }

                throw new Dropfin_Fbconnect_FacebookOAuthException($message);
            } else {
                $message = sprintf(
                    Mage::helper('dropfin_fbconnect')->__('HTTP error %d occurred while issuing request.'),
                    $status
                );
                throw new Exception($message);
            }
        }

        return $decoded_response;
    }

    protected function _isEnabled() {
        return Mage::helper('dropfin_fbconnect')->isLoginEnable();
    }

    protected function _getAppId()
    {
        return Mage::helper('dropfin_fbconnect')->getAppId();
    }

    protected function _getAppSecret()
    {
        return Mage::helper('dropfin_fbconnect')->getAppSecret();
    }
}

class Dropfin_Fbconnect_FacebookOAuthException extends Exception {}