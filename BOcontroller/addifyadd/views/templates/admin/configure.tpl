{*
* 2007-2024 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2024 PrestaShop SA
* @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<style>
    .list-group {
        display: flex;
        flex-direction: column;
    }

    .list-group button {
        padding: 10px;
    }

    .tab-content {
        display: none;
    }

    .active {
        background-color: black;
        color: white;
    }
</style>

<div class="col-lg-12">
    <div class="col-md-2">
        <nav class="list-group">
            <button id="Addimage" onclick="displayContent('feature', this)" {if $showrules == '0'}class="active"{/if}>{l s='Add Image' mod='Addifyadd'}</button>
            <button id="Rules" onclick="displayContent('rules', this)" {if $showrules == '1'}class="active"{/if}>{l s='General Settings' mod='Addifyadd'}</button>
        </nav>
    </div>
    <div id="feature" class="tab-content col-md-10" {if $showrules == '0'}style="display: block;"{/if}>
        <div class="form-group">
            {$featurelist}
        </div>
    </div>
    <div id="rules" class="tab-content col-md-10" {if $showrules == '1'}style="display: block;"{/if}>
        <div class="form-group">
            {$rules}
        </div>
    </div>
</div>

<script type="text/javascript">

    function displayContent(contentId, button) {
        var feature = document.getElementById('feature');
        var rules = document.getElementById('rules');

        if (contentId === 'feature') {
            feature.style.display = 'block';
            rules.style.display = 'none';
        } else if (contentId === 'rules') {
            feature.style.display = 'none';
            rules.style.display = 'block';
        }
        
        document.querySelectorAll('.list-group button').forEach(function(btn) {
            btn.classList.remove('active');
        });

        button.classList.add('active');
    }
    
</script>
