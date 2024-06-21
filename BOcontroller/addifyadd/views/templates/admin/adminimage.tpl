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
{if isset($get_image)}
    <div class="col-lg-3">
        <img src="{$baseUrl}modules/addifyadd/views/img/{$get_image}" id="image" class="img-thumbnail">
    </div>
    <button id="removeBtn" onclick="del()" type="button" class="btn btn-danger">Remove</button>
    <script>
            function del() {
                event.preventDefault();
                var image = document.getElementById('image');
                var removeBtn = document.getElementById('removeBtn');
                if (image && removeBtn) {
                    image.style.display = 'none';
                    removeBtn.style.display = 'none';
                    
                    // var link = '{$link}';
                    // var imageid = {$idimage};
                    // var jsonData = {
                    //     imageid: imageid
                    // };
                    // var jsonString = JSON.stringify(jsonData);
                    
                    // $.ajax({
                    //     type: 'post',
                    //     url: link,
                    //     data: {
                    //         ajax: true,
                    //         Data: jsonString
                    //     },
                    //     dataType: 'json'
                    // });
                }
            }
    </script>
{/if}
