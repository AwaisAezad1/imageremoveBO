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
