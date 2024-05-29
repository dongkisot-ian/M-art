<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT p.*, c.name as category FROM `product_list` p inner join category_list c on p.category_id =c.id where p.id = '{$_GET['id']}'");
    if($qry->num_rows > 0 ){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
    .product-thumbnail{
        width:20rem;
        height:20rem;
        object-fit: cover;
        object-position:center center
    }
</style>
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center mb-4">
                <img class="img-thumbnails border product-thumbnail" src="<?= validate_image((isset($id) && is_file(base_app."uploads/thumbnails/$id.png")) ? "uploads/thumbnails/$id.png?v=".(strtotime($date_updated)): "") ?>" alt="<?= isset($title) ? $title : "" ?>">
            </div>
        </div>
        <h3 class="text-center fw-bolder"><?= isset($title) ? $title : '' ?></h3>
        <center>
            <hr class="bg-primary opacity-100 w-25 mb-0" style="height:3px">
        </center>
        <div class="text-center"><small><?= isset($category) ? $category : '' ?></small></div>
        <p><?= isset($short_description) ? $short_description : "" ?></p>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                <div class="d-flex w-100 align-items-center">
                    <div class="col-auto d-flex align-items-center pe-3">
                        <i class="material-icons me-3">inventory_2</i>
                        Stock/s:
                    </div>
                    <div class="col-auto flex-shrink-1 flex-grow-1"><?= isset($stock) ? number_format($stock) : 0 ?></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                <div class="d-flex w-100 align-items-center">
                    <div class="col-auto d-flex align-items-center pe-3">
                        <i class="material-icons me-3">sell</i>
                        Price:
                    </div>
                    <div class="col-auto flex-shrink-1 flex-grow-1"><?= isset($selling_price) ? format_num($selling_price,2) : 0 ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= (isset($id) && is_file(base_app."contents/$id.html")) ? file_get_contents(base_app."contents/$id.html") : "" ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-md-12 col-sm-12">Status:</div>
            <div class="col-lg-10 col-md-8 col-md-12 col-sm-12">
            <?php 
                $status = isset($status)? $status : '';
                switch($status){
                    case 0:
                        echo '<span class="badge bg-default border text-muted bg-gradient px-3 rounded-pill">Sold</span>' ;
                        break;
                    case 1:
                        echo '<span class="badge bg-primary bg-gradient px-3 rounded-pill">Available</span>' ;
                        break;
                }
            ?>   
            </div>
        </div>
        <div class="text-end pt-3">
            <a href="./?page=products/manage_product&id=<?= isset($id) ? $id : '' ?>" class="btn btn-primary bg-gradient btn-sm"><span class="material-icons">edit</span> Edit</a>
            <a href="javascript:void(0)" class="btn btn-danger bg-gradient btn-sm" id="delete_data"><span class="material-icons">delete</span> Delete</a>
            <a href="./?page=products" class="btn btn-light border btn-sm"><span class="material-icons">arrow_back_ios</span> Back to List</a>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#delete_data').click(function(){
            _conf("Are you sure to delete this from list?","delete_product",['<?= isset($id) ? $id : '' ?>'])
        })
        $('#update_status').click(function(){
            uni_modal("Updating product Status", 'products/update_status.php?id=<?= isset($id) ? $id : '' ?>')
        })
    })
    function delete_product($id){
        start_loader();
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert alert-danger err-msg")
        el.hide()
        $.ajax({
            url: '../classes/Master.php?f=delete_product',
            method: 'POST',
            data: {
                id: $id
            },
            dataType: 'json',
            error: err => {
                console.log(err)
                el.text('An error occurred.')
                el.show('slow')
                end_loader()
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    location.replace('./?page=user')
                } else if (!!resp.msg) {
                    el.text('An error occurred.')
                    el.show('slow')
                } else {
                    el.text('An error occurred.')
                    el.show('slow')
                }
                end_loader()
            }
        })
    }
</script>