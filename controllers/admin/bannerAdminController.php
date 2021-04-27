<?php

class BannerAdminController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'multi_banner';
        $this->identifier = 'id_multi_banner';
        $this->className = 'Banner_Db';
        $this->lang = false;

        $this->bootstrap = true;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_defaultOrderWay = 'DESC';

        parent::__construct();

        $this->displayInformations = $this->l('Some option may be available after saving post');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ],
            'enableSelection' => ['text' => $this->l('Enable selection')],
            'disableSelection' => ['text' => $this->l('Disable selection')],
        ];

        $this->fields_list = [
            'id_multi_banner' => [
                'title' => $this->l('ID'),
                'align' => 'center',
            ],
            'color' => [
                'title' => $this->l('Text Color'),
                'align' => 'center',
                'callback_object' => $this
            ],
            'background_color' => [
                'title' => $this->l('Background Color'),
                'align' => 'center',
                'callback_object' => $this
            ],
            'content' => [
                'title' => $this->l('Banner Content'),
                'align' => 'left'
            ],
            'start_date' => [
                'title' => $this->l('Start Date'),
                'align' => 'center'
            ],
            'end_date' => [
                'title' => $this->l('Start Date'),
                'align' => 'center'
            ],
            'active' => [
                'title' => $this->trans('Displayed', array(), 'Admin.Global'),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false
            ],
            'priority' => [
                'title' => $this->l('Priority'),
                'align' => 'center'
            ],
            
        ];
    }

    public function init()
    {
        parent::init();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
    }

    public function renderList()
    {
        $this->initToolbar();

        return parent::renderList();
    }

    public function initFormToolbar()
    {
        unset($this->toolbar_btn['back']);
        $this->toolbar_btn['save-and-stay'] = array(
            'short' => 'SaveAndStay',
            'href' => '#',
            'desc' => $this->l('Save and stay'),
        );
        $this->toolbar_btn['back'] = array(
            'href' => self::$currentIndex.'&token='.Tools::getValue('token'),
            'desc' => $this->l('Back to list'),
        );
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = $this->l('BannerAdmin');

        if ($this->display == 'add' || $this->display == 'edit') {
            $this->page_header_toolbar_btn['back_to_list'] = array(
                'href' => Context::getContext()->link->getAdminLink('Admin'),
                'desc' => $this->l('Back to list', null, null, false),
                'icon' => 'process-icon-back',
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function renderForm()
    {
        $this->initFormToolbar();
        if(!$this->loadObject(true)) {
            return;
        }

        $this->fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('New header'),
                'icon' => 'icon-folder-close'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('BANNER_TEXT_COLOUR'),
                    'desc' => 'Hex Colour Code',
                    'placeholder' => '#ffffff',
                    'name' => 'TEXT COLOUR',
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('BANNER_BACKGROUND_COLOUR'),
                    'desc' => 'Hex Colour Code (es. #FFFFFF)',
                    'name' => 'BACKGROUND COLOUR',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Content'),
                    'desc' => 'This is a discount',
                    'name' => 'BANNER CONTENT',
                    'required' => true
                ],
                [
                    'type' => 'date',
                    'name' => 'BANNER_START_DATE',
                    'label' => $this->l('Start Date'),
                    'required' => true
                ],
                [
                    'type' => 'date',
                    'name' => 'BANNER_END_DATE',
                    'label' => $this->l('End Date'),
                    'required' => false
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Live Mode (Always)'),
                    'name' => 'BANNER_LIVE_MODE',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save and stay'),
                'stay' => true
            ]
        ];

        $this->multiple_fieldsets = true;

        return AdminController::renderForm();
    }

}