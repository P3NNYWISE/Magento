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

class Dropfin_Fbconnect_Model_System_Config_Language
{
    public function toOptionArray()
    {
        return array(
					array('value'=>'ca_ES','label'=> Mage::helper('adminhtml')->__('Catalan')),
					array('value'=>'cs_CZ','label'=> Mage::helper('adminhtml')->__('Czech')),
					array('value'=>'cy_GB','label'=> Mage::helper('adminhtml')->__('Welsh')),
					array('value'=>'da_DK','label'=> Mage::helper('adminhtml')->__('Danish')),
					array('value'=>'de_DE','label'=> Mage::helper('adminhtml')->__('German')),
					array('value'=>'eu_ES','label'=> Mage::helper('adminhtml')->__('Basque')),
					array('value'=>'en_PI','label'=> Mage::helper('adminhtml')->__('English (Pirate)')),
					array('value'=>'en_UD','label'=> Mage::helper('adminhtml')->__('English (Upside Down)')),
					array('value'=>'ck_US','label'=> Mage::helper('adminhtml')->__('Cherokee')),
					array('value'=>'en_US','label'=> Mage::helper('adminhtml')->__('English (US)')),
					array('value'=>'es_LA','label'=> Mage::helper('adminhtml')->__('Spanish')),
					array('value'=>'es_CL','label'=> Mage::helper('adminhtml')->__('Spanish (Chile)')),
					array('value'=>'es_CO','label'=> Mage::helper('adminhtml')->__('Spanish (Colombia)')),
					array('value'=>'es_ES','label'=> Mage::helper('adminhtml')->__('Spanish (Spain)')),
					array('value'=>'es_MX','label'=> Mage::helper('adminhtml')->__('Spanish (Mexico)')),
					array('value'=>'es_VE','label'=> Mage::helper('adminhtml')->__('Spanish (Venezuela)')),
					array('value'=>'fb_FI','label'=> Mage::helper('adminhtml')->__('Finnish (test)')),
					array('value'=>'fi_FI','label'=> Mage::helper('adminhtml')->__('Finnish')),
					array('value'=>'fr_FR','label'=> Mage::helper('adminhtml')->__('French (France)')),
					array('value'=>'gl_ES','label'=> Mage::helper('adminhtml')->__('Galician')),
					array('value'=>'hu_HU','label'=> Mage::helper('adminhtml')->__('Hungarian')),
					array('value'=>'it_IT','label'=> Mage::helper('adminhtml')->__('Italian')),
					array('value'=>'ja_JP','label'=> Mage::helper('adminhtml')->__('Japanese')),
					array('value'=>'ko_KR','label'=> Mage::helper('adminhtml')->__('Korean')),
					array('value'=>'nb_NO','label'=> Mage::helper('adminhtml')->__('Norwegian (bokmal)')),
					array('value'=>'nn_NO','label'=> Mage::helper('adminhtml')->__('Norwegian (nynorsk)')),
					array('value'=>'nl_NL','label'=> Mage::helper('adminhtml')->__('Dutch')),
					array('value'=>'pl_PL','label'=> Mage::helper('adminhtml')->__('Polish')),
					array('value'=>'pt_BR','label'=> Mage::helper('adminhtml')->__('Portuguese (Brazil)')),
					array('value'=>'pt_PT','label'=> Mage::helper('adminhtml')->__('Portuguese (Portugal)')),
					array('value'=>'ro_RO','label'=> Mage::helper('adminhtml')->__('Romanian')),
					array('value'=>'ru_RU','label'=> Mage::helper('adminhtml')->__('Russian')),
					array('value'=>'sk_SK','label'=> Mage::helper('adminhtml')->__('Slovak')),
					array('value'=>'sl_SI','label'=> Mage::helper('adminhtml')->__('Slovenian')),
					array('value'=>'sv_SE','label'=> Mage::helper('adminhtml')->__('Swedish')),
					array('value'=>'th_TH','label'=> Mage::helper('adminhtml')->__('Thai')),
					array('value'=>'ku_TR','label'=> Mage::helper('adminhtml')->__('Kurdish')),
					array('value'=>'zh_CN','label'=> Mage::helper('adminhtml')->__('Simplified Chinese (China)')),
					array('value'=>'zh_HK','label'=> Mage::helper('adminhtml')->__('Traditional Chinese (Hong Kong)')),
					array('value'=>'zh_TW','label'=> Mage::helper('adminhtml')->__('Traditional Chinese (Taiwan)')),
					array('value'=>'fb_LT','label'=> Mage::helper('adminhtml')->__('Leet Speak')),
					array('value'=>'af_ZA','label'=> Mage::helper('adminhtml')->__('Afrikaans')),
					array('value'=>'sq_AL','label'=> Mage::helper('adminhtml')->__('Albanian')),
					array('value'=>'hy_AM','label'=> Mage::helper('adminhtml')->__('Armenian')),
					array('value'=>'az_AZ','label'=> Mage::helper('adminhtml')->__('Azeri')),
					array('value'=>'be_BY','label'=> Mage::helper('adminhtml')->__('Belarusian')),
					array('value'=>'bn_IN','label'=> Mage::helper('adminhtml')->__('Bengali')),
					array('value'=>'bs_BA','label'=> Mage::helper('adminhtml')->__('Bosnian')),
					array('value'=>'bg_BG','label'=> Mage::helper('adminhtml')->__('Bulgarian')),
					array('value'=>'hr_HR','label'=> Mage::helper('adminhtml')->__('Croatian')),
					array('value'=>'nl_BE','label'=> Mage::helper('adminhtml')->__('Dutch (Belgie)')),
					array('value'=>'en_GB','label'=> Mage::helper('adminhtml')->__('English (UK)')),
					array('value'=>'eo_EO','label'=> Mage::helper('adminhtml')->__('Esperanto')),
					array('value'=>'et_EE','label'=> Mage::helper('adminhtml')->__('Estonian')),
					array('value'=>'fo_FO','label'=> Mage::helper('adminhtml')->__('Faroese')),
					array('value'=>'fr_CA','label'=> Mage::helper('adminhtml')->__('French (Canada)')),
					array('value'=>'ka_GE','label'=> Mage::helper('adminhtml')->__('Georgian')),
					array('value'=>'el_GR','label'=> Mage::helper('adminhtml')->__('Greek')),
					array('value'=>'gu_IN','label'=> Mage::helper('adminhtml')->__('Gujarati')),
					array('value'=>'hi_IN','label'=> Mage::helper('adminhtml')->__('Hindi')),
					array('value'=>'is_IS','label'=> Mage::helper('adminhtml')->__('Icelandic')),
					array('value'=>'id_ID','label'=> Mage::helper('adminhtml')->__('Indonesian')),
					array('value'=>'ga_IE','label'=> Mage::helper('adminhtml')->__('Irish')),
					array('value'=>'jv_ID','label'=> Mage::helper('adminhtml')->__('Javanese')),
					array('value'=>'kn_IN','label'=> Mage::helper('adminhtml')->__('Kannada')),
					array('value'=>'kk_KZ','label'=> Mage::helper('adminhtml')->__('Kazakh')),
					array('value'=>'la_VA','label'=> Mage::helper('adminhtml')->__('Latin')),
					array('value'=>'lv_LV','label'=> Mage::helper('adminhtml')->__('Latvian')),
					array('value'=>'li_NL','label'=> Mage::helper('adminhtml')->__('Limburgish')),
					array('value'=>'lt_LT','label'=> Mage::helper('adminhtml')->__('Lithuanian')),
					array('value'=>'mk_MK','label'=> Mage::helper('adminhtml')->__('Macedonian')),
					array('value'=>'mg_MG','label'=> Mage::helper('adminhtml')->__('Malagasy')),
					array('value'=>'ms_MY','label'=> Mage::helper('adminhtml')->__('Malay')),
					array('value'=>'mt_MT','label'=> Mage::helper('adminhtml')->__('Maltese')),
					array('value'=>'mr_IN','label'=> Mage::helper('adminhtml')->__('Marathi')),
					array('value'=>'mn_MN','label'=> Mage::helper('adminhtml')->__('Mongolian')),
					array('value'=>'ne_NP','label'=> Mage::helper('adminhtml')->__('Nepali')),
					array('value'=>'pa_IN','label'=> Mage::helper('adminhtml')->__('Punjabi')),
					array('value'=>'rm_CH','label'=> Mage::helper('adminhtml')->__('Romansh')),
					array('value'=>'sa_IN','label'=> Mage::helper('adminhtml')->__('Sanskrit')),
					array('value'=>'sr_RS','label'=> Mage::helper('adminhtml')->__('Serbian')),
					array('value'=>'so_SO','label'=> Mage::helper('adminhtml')->__('Somali')),
					array('value'=>'sw_KE','label'=> Mage::helper('adminhtml')->__('Swahili')),
					array('value'=>'tl_PH','label'=> Mage::helper('adminhtml')->__('Filipino')),
					array('value'=>'ta_IN','label'=> Mage::helper('adminhtml')->__('Tamil')),
					array('value'=>'tt_RU','label'=> Mage::helper('adminhtml')->__('Tatar')),
					array('value'=>'te_IN','label'=> Mage::helper('adminhtml')->__('Telugu')),
					array('value'=>'ml_IN','label'=> Mage::helper('adminhtml')->__('Malayalam')),
					array('value'=>'uk_UA','label'=> Mage::helper('adminhtml')->__('Ukrainian')),
					array('value'=>'uz_UZ','label'=> Mage::helper('adminhtml')->__('Uzbek')),
					array('value'=>'vi_VN','label'=> Mage::helper('adminhtml')->__('Vietnamese')),
					array('value'=>'xh_ZA','label'=> Mage::helper('adminhtml')->__('Xhosa')),
					array('value'=>'zu_ZA','label'=> Mage::helper('adminhtml')->__('Zulu')),
					array('value'=>'km_KH','label'=> Mage::helper('adminhtml')->__('Khmer')),
					array('value'=>'tg_TJ','label'=> Mage::helper('adminhtml')->__('Tajik')),
					array('value'=>'ar_AR','label'=> Mage::helper('adminhtml')->__('Arabic')),
					array('value'=>'he_IL','label'=> Mage::helper('adminhtml')->__('Hebrew')),
					array('value'=>'ur_PK','label'=> Mage::helper('adminhtml')->__('Urdu')),
					array('value'=>'fa_IR','label'=> Mage::helper('adminhtml')->__('Persian')),
					array('value'=>'sy_SY','label'=> Mage::helper('adminhtml')->__('Syriac')),
					array('value'=>'yi_DE','label'=> Mage::helper('adminhtml')->__('Yiddish')),
					array('value'=>'gn_PY','label'=> Mage::helper('adminhtml')->__('Guarani')),
					array('value'=>'qu_PE','label'=> Mage::helper('adminhtml')->__('Quechua')),
					array('value'=>'ay_BO','label'=> Mage::helper('adminhtml')->__('Aymara')),
					array('value'=>'se_NO','label'=> Mage::helper('adminhtml')->__('Northern Sami')),
					array('value'=>'ps_AF','label'=> Mage::helper('adminhtml')->__('Pashto')),
					array('value'=>'tl_ST','label'=> Mage::helper('adminhtml')->__('Klingon')),
				);
    }
}
