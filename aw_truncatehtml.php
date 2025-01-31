<?php
/**
 * Copyright since 2024 Axel Paillaud - Axelweb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to [ton email] so we can send you a copy immediately.
 *
 * @author    Axel Paillaud - Axelweb <contact@axelweb.fr>
 * @copyright Since 2024 Axel Paillaud - Axelweb
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

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
        return TruncateHtml::truncate($text, $length, $ellipsis, true);
    }
}

