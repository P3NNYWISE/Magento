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

class Dropfin_Fbconnect_Helper_Data extends Mage_Core_Helper_Abstract {

	const XML_PATH_ENABLED = 'dropfin_fbconnect/general/enable';
	
	const XML_PATH_APP_ID = 'dropfin_fbconnect/general/app_id';
	const XML_PATH_APP_SECRET = 'dropfin_fbconnect/general/app_secret';
	const XML_PATH_LANGUAGE = 'dropfin_fbconnect/general/language';

	const XML_PATH_LOGIN = 'dropfin_fbconnect/general/login';
	const XML_PATH_LOGIN_BLOCK_TITLE = 'dropfin_fbconnect/general/login_block_title';
	const XML_PATH_LOGIN_BLOCK_CONTENT = 'dropfin_fbconnect/general/login_block_content';
	const XML_PATH_LOGIN_BUTTON_TEXT = 'dropfin_fbconnect/general/login_button_text';
	
	const XML_PATH_SHOW_FANBOX = 'dropfin_fbconnect/general/show_fanbox';
	const XML_PATH_PAGE_ID = 'dropfin_fbconnect/general/fb_page_id';
	const XML_PATH_FANBOX_WIDTH = 'dropfin_fbconnect/general/fanbox_width';
	const XML_PATH_FANBOX_HEIGHT = 'dropfin_fbconnect/general/fanbox_height';
	const XML_PATH_SHOW_FACES = 'dropfin_fbconnect/general/show_faces';
	const XML_PATH_SHOW_STREAM = 'dropfin_fbconnect/general/show_stream';	
	const XML_PATH_SHOW_COVER = 'dropfin_fbconnect/general/show_cover';	

	const XML_PATH_SHOW_COMMENTBOX = 'dropfin_fbconnect/general/show_comment';	
	const XML_PATH_COMMENTBOX_COLOR = 'dropfin_fbconnect/general/commentbox_color';	
	const XML_PATH_COMMENTBOX_WIDTH = 'dropfin_fbconnect/general/commentbox_width';	
	const XML_PATH_COMMENTBOX_NO = 'dropfin_fbconnect/general/commentbox_no';

	public function _getStoreConfig($xml) {
		return Mage::getStoreConfig($xml);
	}
     
	public function isEnable() {
		return $this->_getStoreConfig(self::XML_PATH_ENABLED);
	}
	
	public function getAppId() {
		return $this->_getStoreConfig(self::XML_PATH_APP_ID);
	}

	public function getAppSecret() {
		return $this->_getStoreConfig(self::XML_PATH_APP_SECRET);
	}

	public function getLanguage() {
		return $this->_getStoreConfig(self::XML_PATH_LANGUAGE);
	}

	public function isLoginEnable() {
		return $this->_getStoreConfig(self::XML_PATH_LOGIN);
	}

	public function getLoginBlockTitle() {
		return $this->_getStoreConfig(self::XML_PATH_LOGIN_BLOCK_TITLE);
	}

	public function getLoginBlockContent() {
		return $this->_getStoreConfig(self::XML_PATH_LOGIN_BLOCK_CONTENT);
	}

	public function getLoginButtonText() {
		return $this->_getStoreConfig(self::XML_PATH_LOGIN_BUTTON_TEXT);
	}

	public function showFanBox() {
		return $this->_getStoreConfig(self::XML_PATH_SHOW_FANBOX);
	}

	public function getFanboxPageId() {
		return $this->_getStoreConfig(self::XML_PATH_PAGE_ID);
	}

	public function getFanboxWidth() {
		return $this->_getStoreConfig(self::XML_PATH_FANBOX_WIDTH);
	}

	public function getFanboxHeight() {
		return $this->_getStoreConfig(self::XML_PATH_FANBOX_HEIGHT);
	}

	public function showFaces() {
		if($this->_getStoreConfig(self::XML_PATH_SHOW_FACES)){
			return 'true';
		}
		return 'false';
	}

	public function showStream() {
		if($this->_getStoreConfig(self::XML_PATH_SHOW_STREAM)){
			return 'true';
		}
		return 'false';
	}

	public function showCover() {
		if($this->_getStoreConfig(self::XML_PATH_SHOW_COVER)){
			return 'false';
		}
		return 'true';
	}
	
	public function showCommmentBox() {
		return $this->_getStoreConfig(self::XML_PATH_SHOW_COMMENTBOX);
	}

	public function getCommmentBoxColor() {
		return $this->_getStoreConfig(self::XML_PATH_COMMENTBOX_COLOR);
	}
	
	public function getCommmentBoxWidth() {
		return $this->_getStoreConfig(self::XML_PATH_COMMENTBOX_WIDTH);
	}
	
	public function getCommentBoxNo() {
		return $this->_getStoreConfig(self::XML_PATH_COMMENTBOX_NO);
	}

}