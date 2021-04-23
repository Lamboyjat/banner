
<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

// Require Models
require_once(dirname(__FILE__) . '/classes/bannerDb.php');

class Banner extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'banner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'LAMIN JATTA';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Banner Lamin');
        $this->description = $this->l('This is banner for ... try it out');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall Banner Lamin');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    
    public function install()
    {
        Configuration::updateValue('BANNER_LIVE_MODE', false);
        
        if (parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader')
        ) {
            $this->installTabs();
            
            // Create db tables
            include(dirname(__FILE__).'/sql/install.php');

            return  true;
        }

        return false;
        
    }

    public function uninstall()
    {
        Configuration::deleteByName('BANNER_TEXT_COLOUR');
        Configuration::deleteByName('BANNER_BACKGROUND_COLOUR');
        Configuration::deleteByName('BANNER_CONTENT');
        Configuration::deleteByName('BANNER_START_DATE');
        Configuration::deleteByName('BANNER_END_DATE');
        Configuration::deleteByName('BANNER_LIVE_MODE');
        Configuration::deleteByName('BANNER_PRIORITY');

        return parent::uninstall(); 
        // Remove tabs
            $this->uninstallTabs(); 

        // Delete db tables
        include(dirname(__FILE__).'/sql/uninstall.php');

        
    }

    public function installTabs()
    {
        $tab  =  new Tab();
        $tab->class_name = 'BannerAdmin';
        $tab->module = $this->name;
        $tab->id_parent = Tab::getIdFromClassName('DEFAULT');
        $tab->icon = 'settings_applications';
        $languages = Language::getLanguages();
        foreach($languages as $lang){
            $tab->name [$lang['id_lang']] = $this->l('BannerAdmin');
        } 
        try { 
            $tab->save();
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
 
        return true;
        
    }

    public function uninstallTabs()
    {
        $idTab = Tab::getIdFromClassName('BannerAdmin');
        if($idTab){
            $tab = new Tab($idTab);
            try{
                $tab->delete();
            }catch(Exception $e){
                echo $e->getMessage(); 
                return false;
            } 
        } 
        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitBannerModule')) == true) {
            $this->postProcess();
        }

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBannerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Banner Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 5,
                        'type' => 'text',
                        'desc' => $this->l('Hex Colour Code'),
                        'name' => 'BANNER_TEXT_COLOUR',
                        'label' => $this->l('Text Colour'),
                    ),
                    array(
                        'col' => 5,
                        'type' => 'text',
                        'desc' => $this->l('Hex Colour Code'),
                        'name' => 'BANNER_BACKGROUND_COLOUR',
                        'label' => $this->l('Background Colour'),
                    ),
                    array(
                        'col' => 5,
                        'type' => 'textarea',
                        'desc' => $this->l('Content'),
                        'name' => 'BANNER_CONTENT',
                        'label' => $this->l('Content'),
                    ),
                    array(
                        'type' => 'date',
                        'name' => 'BANNER_START_DATE',
                        'label' => $this->l('Start Date'),
                    ),
                    array(
                        'type' => 'date',
                        'min' => '2021-01-01',
                        'name' => 'BANNER_END_DATE',
                        'label' => $this->l('End Date'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live Mode (Always)'),
                        'name' => 'BANNER_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 5,
                        'type' => 'text',
                        //'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Priority'),
                        'name' => 'BANNER_PRIORITY',
                        'label' => $this->l('Header Priority'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }


    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
			'BANNER_TEXT_COLOUR' => Configuration::get('BANNER_TEXT_COLOUR', true),
			'BANNER_BACKGROUND_COLOUR' => Configuration::get('BANNER_BACKGROUND_COLOUR', true),
			'BANNER_CONTENT' => Configuration::get('BANNER_CONTENT', true),
			'BANNER_START_DATE' => Configuration::get('BANNER_START_DATE', true),
			'BANNER_END_DATE' => Configuration::get('BANNER_END_DATE', true),
            'BANNER_LIVE_MODE' => Configuration::get('BANNER_LIVE_MODE', true),
            'BANNER_PRIORITY' => Configuration::get('BANNER_PRIORITY', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('banner') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayHeader($params)
    {

		$this->context->smarty->assign([
			'text_colour' => Configuration::get('BANNER_TEXT_COLOUR', '#000000'),
			'background' => Configuration::get('BANNER_BACKGROUND_COLOUR', '#FFFFFF'),
			'content' => Configuration::get('BANNER_CONTENT'),
			'start_date' => Configuration::get('BANNER_START_DATE')
		]);

		if (!Configuration::get('BANNER_LIVE_MODE', false)) {
			return;
		}

        return $this->display(__FILE__, 'views/templates/hooks/display_header_banner.tpl');

    }


}

