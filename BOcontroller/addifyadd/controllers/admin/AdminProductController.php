<?php
/**
* 2007-2024
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License 3.0 (AFL-3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author Addify
* @copyright 2007-2024 PrestaShop AS
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
* International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminProductController extends ModuleAdminController
{
    // Display content and redirect with submit name
    public function initContent()
    {
        parent::initContent();

        // Get the submit name from the request if available
        $submit = 'productIconRedirect';

        // Redirect to the specified page in AdminModules with the submit name
        Tools::redirectAdmin(
            Context::getContext()->link->getAdminLink('AdminModules') . '&configure=' . $this->module->name . '&submit_name=' . $submit
        );
    }
}
