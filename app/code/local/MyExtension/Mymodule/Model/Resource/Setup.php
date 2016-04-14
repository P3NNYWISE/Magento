<?php
/**
 * MyExtension_Mymodule extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       MyExtension
 * @package        MyExtension_Mymodule
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Mymodule setup
 *
 * @category    MyExtension
 * @package     MyExtension_Mymodule
 * @author      Ultimate Module Creator
 */
class MyExtension_Mymodule_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{

    /**
     * get the default entities for mymodule module - used at installation
     *
     * @access public
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getDefaultEntities()
    {
        $entities = array();
        $entities['myextension_mymodule_articulo'] = array(
            'entity_model'                  => 'myextension_mymodule/articulo',
            'attribute_model'               => 'myextension_mymodule/resource_eav_attribute',
            'table'                         => 'myextension_mymodule/articulo',
            'additional_attribute_table'    => 'myextension_mymodule/eav_attribute',
            'entity_attribute_collection'   => 'myextension_mymodule/articulo_attribute_collection',
            'attributes'                    => array(
                    'titulo' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Titulo',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '1',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '10',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'status' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Enabled',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '1',
                        'unique'         => false,
                        'position'       => '20',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'url_key' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => 'myextension_mymodule/articulo_attribute_backend_urlkey',
                        'frontend'       => '',
                        'label'          => 'URL key',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '30',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'in_rss' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'In RSS',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '1',
                        'unique'         => false,
                        'position'       => '40',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_title' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta title',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '50',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_keywords' => array(
                        'group'          => 'General',
                        'type'           => 'text',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta keywords',
                        'input'          => 'textarea',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '60',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_description' => array(
                        'group'          => 'General',
                        'type'           => 'text',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta description',
                        'input'          => 'textarea',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '70',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'allow_comment' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Allow Comment',
                        'input'          => 'select',
                        'source'         => 'myextension_mymodule/adminhtml_source_yesnodefault',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '2',
                        'unique'         => false,
                        'position'       => '80',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'parent_id' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Parent id',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'path' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Path',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'position' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Position',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'level' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Level',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'children_count' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Children count',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),

                )
         );
        return $entities;
    }
}
