{*
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
* @author PrestaShop SA and Contributors <contact@prestashop.com>
    * @copyright Since 2007 PrestaShop SA and Contributors
    * @license https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
    */
    *}

    <style>
        
        .pfi-newdatasheet{
            display: none;
        }
        .displayed-data dt {
            font-weight: 500;
            text-transform: capitalize;
            word-break: normal;
            text-align: center;
            width: 100%;
        }

        .displayed-data dd {
            margin: 2px 0;
        }

        .displayed-data dd img {
            width: auto;
            height: 70px;
        }

        dl.displayed-data {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        dl.displayed-data img {
            width: auto;
            height: 70px;
        }

        .innercontent {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .additional-info-container dl.pfi-newdatasheet{
            display: block;
        }
    </style>
    <dl class="pfi-newdatasheet">
        {foreach $productfeature as $feature}
        {foreach $data_show as $index => $item}
        {if $feature.id_feature == $item.id_feature && $feature.id_feature_value == $item.id_feature_value}
        <div class="innercontent">
            {if $configdata.featurename_active == 1}
            <dt id="dt{$index}">{$item.featurename}</dt>
            {/if}
            <dd id="dd{$index}"><img src="{_PS_BASE_URL_}{__PS_BASE_URI__}modules/addifyadd/views/img/{$item.image}" alt="Wrapper image" id="image"></dd>
            {if $configdata.featurevalue_active == 1}
            <dt id="dt{$index}">{$item.value}</dt>
            {/if}
        </div>
        {/if}
        {/foreach}
        {/foreach}
    </dl>
    <div id="additional-info-container" class="additional-info-container"></div>

<script>
    var targetShort1 = document.querySelectorAll('.product-variants');
    var data = document.querySelector('.pfi-newdatasheet');
    
    targetShort1.forEach(function(productVariant) {
        var additionalInfoContainer = document.createElement('div');
        additionalInfoContainer.classList.add('additional-info-container'); 
        additionalInfoContainer.id = 'additional-info-container';
        
        productVariant.parentNode.insertBefore(additionalInfoContainer, productVariant);
        
        var clonedData = data.cloneNode(true);
        clonedData.classList.remove('pfi-newdatasheet');
        clonedData.classList.add('displayed-data'); 
        additionalInfoContainer.appendChild(clonedData);
    });
</script>

<style>
    .displayed-data {
        display: block;
    }
</style>



