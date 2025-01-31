<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Aw_TruncateHtml extends Module
{
    public function __construct()
    {
        $this->name = 'aw_truncatehtml';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Axel Paillaud';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('Truncate HTML Properly', [], 'Modules.Awtruncatehtml.Admin');
        $this->description = $this->trans('Allows you to truncate HTML without cutting tags.', [], 'Modules.Awtruncatehtml.Admin');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayHeader');
    }

    public function hookDisplayHeader()
    {
        $this->context->smarty->registerPlugin('modifier', 'truncate_html', ['Aw_TruncateHtml', 'truncateHtml']);
    }

    public static function truncateHtml($text, $length = 100, $ellipsis = '...')
    {
        require_once(__DIR__ . '/truncatehtml.php');
        return TruncateHtml::truncate($text, $length, $ellipsis);
    }
}

