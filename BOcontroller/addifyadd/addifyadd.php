<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

use Symfony\Component\Form\Button;

if (!defined('_PS_VERSION_')) {
    exit;
}
class Addifyadd extends Module
{
    public function __construct()
    {
        $this->name = 'addifyadd';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'awais aezad';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->trans('addifyadd');
        $this->description = $this->trans('Description of addifyadd');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?');
        if (!Configuration::get('addifyadd')) {
            $this->warning = $this->trans('No name provided');
        }
    }
    public function install()
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "adddata (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_feature INT(11),
            featurename VARCHAR(255),
            image VARCHAR(255),
            value VARCHAR(255),
            id_feature_value INT(11)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";
        $this->registerHook('displayProductAdditionalInfo');
        Db::getInstance()->execute($sql);
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        return parent::install() && $this->installTab();
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'adddata`';
        $this->unregisterHook('displayProductAdditionalInfo');

        Db::getInstance()->execute($sql);

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }
        return (
            parent::uninstall()
            && Configuration::deleteByName('addifyadd') && $this->uninstallTab()
        );
    }
    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminCatalog');
        $tab->class_name = 'AdminProduct';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Product Icons');
        }
        $tab->module = $this->name;
        return $tab->add();
    }

    public function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminProduct');
        if (!$tabId) {
            return true;
        }
        $tab = new Tab($tabId);
        return $tab->delete();
    }
    public function getContent()
    {
        // $this->registerHook('displayProductAdditionalInfo');

        $submitName = Tools::getValue('submit_name');
        $submitrule = Tools::getValue('submit_rule');
        $showrules = ($submitrule == 'Rules') ? '1' : '0';
        if ($submitName == 'productIconRedirect') {
            $this->context->smarty->assign(array(
                'featurelist' => $this->renderFeatureList(),
                'rules' => $this->addNewRulesForm(),
                'showrules' => $showrules,
            ));
            return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        } elseif ($submitName == 'valueRedirect') {
            return $this->renderFeatureValues();
        }
        if (Tools::isSubmit('addrules')) {
            $this->addFormRules();
            $output = $this->displayConfirmation($this->l('Data Added'));
            $this->context->smarty->assign(array(
                'featurelist' => $this->renderFeatureList(),
                'rules' => $this->addNewRulesForm(),
                'showrules' => $showrules,
            ));
            return  $output . $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        }
        if (Tools::isSubmit('updateValueData')) {
            $this->updateValuesInDatabase();
            $output = $this->displayConfirmation($this->l('Data Updated'));
            $this->context->smarty->assign(array(
                'featurelist' => $this->renderFeatureList(),
                'rules' => $this->addNewRulesForm(),
                'showrules' => $showrules,
            ));
            return $output . $this->renderFeatureValues();
        }
        if (Tools::isSubmit('saveValueData')) {
            $this->addValuesToDatabase();
            $output = $this->displayConfirmation($this->l('Data Added'));
            $this->context->smarty->assign(array(
                'featurelist' => $this->renderFeatureList(),
                'rules' => $this->addNewRulesForm(),
                'showrules' => $showrules,
            ));
            return $output . $this->renderFeatureValues();
        }
        if ((Tools::isSubmit('viewadddatafeatures')) == true) {
            return $this->renderFeatureValues();
        }
        if ((Tools::isSubmit('updateFeatureValues')) == true) {
            return $this->addNewFeatureForm();
        }
        $this->context->smarty->assign(array(
            'featurelist' => $this->renderFeatureList(),
            'rules' => $this->addNewRulesForm(),
            'showrules' => $showrules,
        ));
        return  $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
    public function getformvalues()
    {
        $configval = array();
        if (Tools::isSubmit('addrules')) {
            $configval['active_product_page'] = Tools::getValue('active_product_page');
            $configval['active_quickview_page'] = Tools::getValue('active_quickview_page');
            $configval['featurename_active'] = Tools::getValue('featurename_active');
            $configval['featurevalue_active'] = Tools::getValue('featurevalue_active');
            $configval['group_active'] = Tools::getValue('group_active');
            $configval['product_active'] = Tools::getValue('product_active');
            $configval['category_active'] = Tools::getValue('category_active');
            if (!Tools::getValue('groupBox')) {
                $configval['groupBox'] = Tools::getValue('groupBox');
            } else {
                $configval['groupBox'] = implode(',', Tools::getValue('groupBox'));
            }
            if (Tools::getValue('category')) {
                $configval['category'] = implode(',', Tools::getValue('category'));
            } else {
                $configval['category'] = '';
            }
            if (!Tools::getValue('product')) {
                $configval['product'] = Tools::getValue('product');
            } else {
                $configval['product'] = implode(',', Tools::getValue('product'));
            }
            return $configval;
        }

        if (Tools::isSubmit('saveValueData')) {
            $configval['value'] = Tools::getValue('value');
            $configval['featurename'] = Tools::getValue('featurename');
            $configval['image'] = Tools::getValue('image');
            $configval['id_feature'] = Tools::getValue('id_feature');
            $configval['id_feature_value'] = Tools::getValue('id_feature_value');
            if (isset($_FILES['image']['name'])) {
                $target_dir = _PS_MODULE_DIR_ . 'addifyadd/views/img/';
                $without_extension = Tools::substr(basename($_FILES['image']["name"]), 0, strrpos(basename($_FILES['image']["name"]), "."));
                $img_path_ext = Tools::substr(basename($_FILES['image']["name"]), strrpos(basename($_FILES['image']["name"]), '.'));
                $target_file = $target_dir . $without_extension . $img_path_ext;
                move_uploaded_file($_FILES['image']["tmp_name"], $target_file);
            }
            return $configval;
        }

        if (Configuration::get('active_product_page') || Configuration::get('active_quickview_page')) {
            $configval = $this->getconfigurationValues();
            return $configval;
        }
    }
    public function getconfigurationValues()
    {
        $configval = array();

        $configval['active_product_page'] = Configuration::get('active_product_page');
        $configval['active_quickview_page'] = Configuration::get('active_quickview_page');
        $configval['featurename_active'] = Configuration::get('featurename_active');
        $configval['featurevalue_active'] = Configuration::get('featurevalue_active');
        $configval['group_active'] = Configuration::get('group_active');
        $configval['product_active'] = Configuration::get('product_active');
        $configval['category_active'] = Configuration::get('category_active');
        $configval['groupBox'] = explode(',', Configuration::get('groupBox'));
        $configval['category'] = explode(',', Configuration::get('category'));
        $configval['product'] = explode(',', Configuration::get('product'));


        return $configval;
    }
    public function getValuesFromDatabase($name)
    {

        $sql = "SELECT * FROM " . _DB_PREFIX_ . $name;
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
    public function hookDisplayProductAdditionalInfo()
    {
        $var = Tools::getAllValues(); // get all values of the page
        $getdataimage = $this->getValuesFromDatabase('adddata'); // get values from database
        $getdata = $this->getconfigurationValues(); // get rule data from configuration table
        $product = new Product($var['id_product']); //get product of the page
        $categoryIds = $product->getCategories(); // get the category of that product
        $features = $product->getFeatures(); // get the feature of that product
        //condition to check if the page is of quickview page 
        if ($var['action'] == 'quickview' && $var['controller'] == 'product') {
            //condition to check if the quickview page status is active
            if ($getdata['active_quickview_page'] == 1) {
                //condition to check if the product status is active
                if ($getdata['product_active'] == 1) {
                    //condition to check if the product is in the rule product array and if the product category matches the rule category
                    if (
                        in_array($var['id_product'], $getdata['product'])
                    ) {
                        $this->context->smarty->assign(array(
                            'configdata' => $getdata,
                            'data_show' => $getdataimage,
                            'productfeature' => $features,
                        ));
                        return $this->context->smarty->fetch($this->local_path . 'views/templates/front/custom_link.tpl');
                    }
                }
            }
        }
        //condition to check if the page is of product page 
        elseif ($var['controller'] == 'product') {
            //condition to check if the product page status is active
            if ($getdata['active_product_page'] == 1) {
                //condition to check if the product status is active
                if ($getdata['product_active'] == 1) {
                    //condition to check if the product is in the rule product array
                    if (
                        in_array($var['id_product'], $getdata['product'])
                    ) {
                        $this->context->smarty->assign(array(
                            'configdata' => $getdata,
                            'data_show' => $getdataimage,
                            'productfeature' => $features,
                        ));
                        return $this->context->smarty->fetch($this->local_path . 'views/templates/front/custom_link.tpl');
                    }
                }
            }
        }
    }
    public function addFormRules()
    {
        $update = $this->getformvalues();
        Configuration::updateValue('active_product_page', $update['active_product_page']);
        Configuration::updateValue('active_quickview_page', $update['active_quickview_page']);
        Configuration::updateValue('featurename_active', $update['featurename_active']);
        Configuration::updateValue('featurevalue_active', $update['featurevalue_active']);
        Configuration::updateValue('group_active', $update['group_active']);
        Configuration::updateValue('product_active', $update['product_active']);
        Configuration::updateValue('category_active', $update['category_active']);
        Configuration::updateValue('groupBox', $update['groupBox']);
        Configuration::updateValue('category', $update['category']);
        Configuration::updateValue('product', $update['product']);
    }
    public function addValuesToDatabase()
    {
        $update = $this->getformvalues();
        if (!empty($update)) {
            Db::getInstance()->insert('adddata', $update);
        }
    }
    public function getValuesFromDatabaseById($name, $id)
    {
        $sql = "SELECT * FROM " . _DB_PREFIX_ . "$name WHERE id = '$id'";
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
    public function updateValuesInDatabase()
    {
        $update_id = (int) Tools::getValue('id');
        $image = Tools::getValue('image');
        $id_feature = Tools::getValue('id_feature');
        $value = Tools::getValue('value');
        $id_feature_value = Tools::getValue('id_feature_value');
        $featurename = Tools::getValue('featurename');


        $save_update = array(
            'image' => $image,
            'id_feature' => $id_feature,
            'value' => $value,
            'id_feature_value' => $id_feature_value,
            'featurename' => $featurename,
        );
        if (isset($_FILES['image']['name'])) {
            $target_dir = _PS_MODULE_DIR_ . 'addifyadd/views/img/';
            $without_extension = Tools::substr(basename($_FILES['image']["name"]), 0, strrpos(basename($_FILES['image']["name"]), "."));
            $img_path_ext = Tools::substr(basename($_FILES['image']["name"]), strrpos(basename($_FILES['image']["name"]), '.'));
            $target_file = $target_dir . $without_extension . $img_path_ext;
            move_uploaded_file($_FILES['image']["tmp_name"], $target_file);
        }
        Db::getInstance()->update('adddata', $save_update, 'id = ' . $update_id);
    }
    public function renderFeatureList()
    {

        $data = Feature::getFeatures($this->context->language->id);
        $featuresWithValues = [];

        foreach ($data as $feature) {
            $featureId = $feature['id_feature'];
            $featureValues = FeatureValue::getFeatureValuesWithLang($this->context->language->id, $featureId);
            $count = count($featureValues);
            $feature['values'] = $count;
            $featuresWithValues[] = $feature;
        }

        $fields_list = array(
            'id_feature' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
                'search' => false,
            ),
            'values' => array(
                'title' => $this->l('Values'),
                'type' => 'text',
                'search' => false,
            ),
        );

        // Prepare options for HelperList
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->show_toolbar = true;
        $helper->actions = array('view');
        $helper->module = $this;
        $helper->title = $this->l('Feature List');
        $helper->table = 'adddatafeatures';
        $helper->identifier = 'id_feature';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        // Load list values
        $helper->listTotal = count($featuresWithValues);

        // Assign data to template variables
        $helper->tpl_vars['fields_list'] = $fields_list;
        $helper->tpl_vars['list'] = $featuresWithValues;

        // Load list values
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        return $helper->generateList($featuresWithValues, $fields_list);
    }
    public function renderFeatureValues()
    {
        $id = Tools::getValue('id_feature');
        $data = Feature::getFeatures($this->context->language->id);

        foreach ($data as $feature) {
            if ($feature['id_feature'] == $id) {
                $featurename = $feature['name'];
            }
        }

        $featureValues = FeatureValue::getFeatureValuesWithLang($this->context->language->id, $id);
        $imagedata = $this->getValuesFromDatabase('adddata');

        $mergedData = [];
        foreach ($featureValues as $feature) {
            $image = null;
            foreach ($imagedata as $img) {
                if ($feature['value'] == $img['value']) {
                    $image = $img['image'];
                    break;
                }
            }
            $mergedData[] = array(
                'id_feature_value' => $feature['id_feature_value'],
                'value' => $feature['value'],
                'image' => $image,
            );
        }
        usort($mergedData, function ($a, $b) {
            return $a['id_feature_value'] - $b['id_feature_value'];
        });

        $fields_list = array(
            'id_feature_value' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
                'search' => false,
            ),
            'value' => array(
                'title' => $this->l('Value'),
                'type' => 'text',
                'search' => false,
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'text',
                'search' => false,
            ),
        );

        // Prepare options for HelperList
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->show_toolbar = true;
        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&add' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&submit_name=' . 'productIconRedirect',
            'desc' => $this->l('Features'),
            'icon' => 'process-icon-back',
        );
        $helper->actions = array('edit');
        $helper->module = $this;
        $helper->title = $this->l('Feature ' . $featurename . ' List');
        $helper->table = 'FeatureValues';
        $helper->identifier =  'id_feature_value';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_feature=' . $id;

        // Load list values
        $helper->listTotal = count($mergedData);
        $helper->identifier = 'id_feature_value';
        $helper->tpl_vars['fields_list'] = $fields_list;
        $helper->tpl_vars['base_url'] = $this->_path;
        // Load list values
        $helper->listTotal = count($mergedData);
        $helper->listTotal = count($mergedData);
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_feature=' . $id;

        return $helper->generateList($mergedData, $fields_list);
    }
    public function addNewFeatureForm()
    {
        $featurename = "";
        $id_feature = Tools::getValue('id_feature');
        $id_feature_value = Tools::getValue('id_feature_value');
        $features = Feature::getFeatures($this->context->language->id);

        foreach ($features as $feature) {
            $featureNames[] = $feature['name'];
            if ($feature['id_feature'] == $id_feature) {
                $featureNames['name'] == $feature['name'];
            }
        }
        foreach ($features as $feature) {
            if ($feature['id_feature'] == $id_feature) {
                $featurename = $feature['name'];
            }
        }

        $featureValues = FeatureValue::getFeatureValuesWithLang($this->context->language->id, $id_feature);
        $featurevalue = '';
        foreach ($featureValues as $feature) {
            if ($feature['id_feature_value'] == $id_feature_value) {
                $featurevalue = $feature['value'];
            }
        }
        $id = '';
        $getimage="";
        $imagedata = $this->getValuesFromDatabase('adddata');
        foreach ($imagedata as $image) {
            if ($featurevalue == $image['value']) {
                $id_image = $image['id'];
                $getimage = $image['image'];
            }
        }
        $imagefile = "";
        $get_image = $getimage;
        $baseUrl = $this->context->shop->getBaseURL(true);
        $link = $this->context->link->getAdminLink('ImageRemove');
        if ($get_image) {
            $this->context->smarty->assign(array(
                'link' => $link,
                'baseUrl' => $baseUrl,
                'get_image' => $get_image,
                'idimage' => $id_image,
            ));
            $imagefile = $this->context->smarty->fetch(_PS_MODULE_DIR_ . '/addifyadd/views/templates/admin/adminimage.tpl');
        }

        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Edit ' . $featurevalue . ' Value'),
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'value',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_feature',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_feature_value',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'featurename',
                    ),
                    array(
                        'type' => 'file',
                        'display_image' => true,
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'required' => true,
                        'desc' => $this->l(''),
                        'image' => $imagefile,
                    ),


                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ),
                'buttons' => array(
                    '0' => array(
                        'type' => 'submit',
                        'title' => $this->l('Go Back'),
                        'icon' => 'process-icon-back',
                        'class' => 'pull-left',
                        'href' =>  Context::getContext()->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&id_feature=' . $id_feature . '&submit_name=' . 'valueRedirect',
                    )
                ),
            ),
        );
        $helper = new HelperForm();
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        if (Tools::isSubmit('updateFeatureValues')) {
            if ($get_image) {
                $data = $this->getValuesFromDatabaseById('adddata', $id);
                $helper->submit_action = 'updateValueData';
                $helper->tpl_vars = array(
                    'fields_value' => array(
                        'id' => $id,
                        'value' => $data[0]['value'],
                        'id_feature' => $data[0]['id_feature'],
                        'id_feature_value' => $id_feature_value,
                        'featurename' => $featurename,
                    ),
                );
            } else {
                $helper->submit_action = 'saveValueData';
                $helper->tpl_vars = array(
                    'fields_value' => array(
                        'value' => $featurevalue,
                        'id_feature' => $id_feature,
                        'id_feature_value' => $id_feature_value,
                        'featurename' => $featurename,
                    ),
                );
            }
            $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
            return $helper->generateForm(array($form));
        }
    }
    public function addNewRulesForm()
    {
        $this->context->controller->addJS($this->_path . 'views/js/rules.js');
        $id = Tools::getValue('id');
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add Rules'),
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show icons at the product page'),
                        'name' => 'active_product_page',
                        'id' => 'active_product_page',
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),

                            array(
                                'id' => 'off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show icons at the quickview page'),
                        'name' => 'active_quickview_page',
                        'id' => 'active_quickview_page',
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),

                            array(
                                'id' => 'off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Feature Name'),
                        'name' => 'featurename_active',
                        'id' => 'featurename_active',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Feature Value Name'),
                        'name' => 'featurevalue_active',
                        'id' => 'featurevalue_active',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Users'),
                        'name' => 'group_active',
                        'id' => 'group_active',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'group',
                        'label' => $this->l('Group'),
                        'name' => 'groupBox',
                        'id' => 'groupBox',
                        'required' => true,
                        'values' => Group::getGroups(Context::getContext()->language->id),
                        'info_introduction' => $this->l('You now have three default customer groups.'),
                        'desc' => $this->l('Mark any/all of the customer group(s) on who you would like to apply the rule.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Product'),
                        'name' => 'product_active',
                        'id' => 'product_active',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'hideprocat_product',
                        'label' => $this->l('Search Product'),
                        'name' => 'product',
                        'id' => 'product',
                        'col' => 6,
                        'prefix' => '<i class="icon-search"></i>',
                        'hint' => $this->l('Product(s) will be restricted for selected product.')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Choose Categories'),
                        'name' => 'category_active',
                        'id' => 'category_active',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->l('Categories'),
                        'name' => 'category',
                        'id' => 'category',
                        'col' => 6,
                        'tree' => array(
                            'id' => 'type_category',
                            'use_checkbox' => true,
                            'disabled_categories' => null,
                            'root_category' => Context::getContext()->shop->getCategory()
                        ),

                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ),

            ),
        );
        $helper = new HelperForm();
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'addrules';
        $helper->tpl_vars = array(
            'context_link' => Context::getContext()->link,
            'fields_value' => $this->getformvalues(),
        );
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        return $helper->generateForm(array($form));
    }
}
