<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `product_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0 ){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
	img#cimg{
		max-height: 20vh;
		max-width: 15rem;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<section class="py-3">
    <div class="container">
        <h3 class="fw-bolder text-center"><?= isset($id) ? "Update Product Details" : "Add New Product" ?></h3>
        <center>
            <hr class="bg-primary w-25 opacity-100">
        </center>
        <form action="" id="product-form">
            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group mb-4">
                        <label for="category_id" class="form-label">Category <span class="text-primary">*</span></label>
                        <select name="category_id" id="category_id" class="form-select rounded-0 select2" required>
                            <option value="" disabled <?= !isset($category_id)? 'selected' : '' ?>></option>
                            <?php 
                            $qry = $conn->query("SELECT * FROM `category_list` where `status` = 1 and `delete_flag` = 0 ".(isset($category_id) ? " or id = '{$category_id}'" : '' )." order by `name` asc ");
                            while($row = $qry->fetch_assoc()):
                            ?>
                            <option class="px-2 py-2" value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? 'selected': '' ?>><?= $row['name'] ?></option>
                            <?php endwhile; ?>

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group input-group input-group-dynamic <?= isset($title) ? "is-filled" : '' ?> mb-3">
                        <label for="title" class="form-label">Product Title <span class="text-primary">*</span></label>
                        <input type="text" class="form-control" value="<?= isset($title) ? $title : '' ?>" name="title" id="title">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group input-group input-group-dynamic <?= isset($stock) ? "is-filled" : '' ?> mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-primary">*</span></label>
                        <input type="number" class="form-control text-end" value="<?= isset($stock) ? $stock : '' ?>" name="stock" id="stock">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group input-group input-group-dynamic <?= isset($selling_price) ? "is-filled" : '' ?> mb-3">
                        <label for="selling_price" class="form-label">Selling Price <span class="text-primary">*</span></label>
                        <input type="number" step="any" class="form-control text-end" value="<?= isset($selling_price) ? $selling_price : '' ?>" name="selling_price" id="selling_price">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-group mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea name="short_description" id="short_description" cols="30" rows="3" class="form-control border rounded-0 px-2 py-1"><?= isset($short_description) ? $short_description : '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Other Information</label>
                        <textarea name="content" id="" cols="30" rows="2" class="form-control summernote"><?php echo  (isset($id) && is_file(base_app."contents/$id.html")) ? file_get_contents(base_app."contents/$id.html") : "" ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                        <label for="" class="form-label">Thumbnail</label>
                        <input type="file" class="py-2" id="customFile" name="img" onchange="displayImg(this,$(this))">
                    </div>
                </div>
            </div>
			<div class="form-group mb-3 d-flex justify-content-start">
				<img src="<?php echo validate_image((isset($id) && is_file(base_app."uploads/thumbnails/{$id}.png")) ? "uploads/thumbnails/{$id}.png?v=".(strtotime($date_updated)): "") ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
			</div>
            <div class="form-group mb-3">
                <label for="status" class="form-label">Status <span class="text-primary">*</span></label>
                <select name="status" id="status" class="form-select rounded-0" required>
                    <option class="px-2 py-2" value="0" <?= isset($status) && $status == 0 ? 'selected': '' ?>>Sold</option>
                    <option class="px-2 py-2" value="1" <?= isset($status) && $status == 1 ? 'selected': '' ?>>Available</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn bg-primary bg-gradient btn-sm text-light w-25"><span class="material-icons">save</span> Save</button>
                    <a href="./?page=products" class="btn bg-deafult border bg-gradient btn-sm w-25"><span class="material-icons">keyboard_arrow_left</span> Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
    function displayImg(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
            $('#cimg').attr('src', '<?php echo validate_image((isset($id) && is_file(base_app."uploads/thumbnails/$id.png")) ? "uploads/thumbnails/$id.png?v=".(strtotime($date_updated)): "") ?>');
        }
	}
    $(document).ready(function(){
        $('#category_id').select2({
            placeholder:'Please Select Category Here',
            width:'100%'
        })
		 $('.summernote').summernote({
		        height: "25vh",
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'insert', [ 'picture', 'video' ] ],
		            [ 'view', [ 'undo', 'redo', 'help' ] ]
		        ]
		    })
            // $('.note-modal').find('.close').html('')
            $('.note-modal').find('.close').removeClass('close').addClass('btn-close text-dark').attr('data-bs-dismiss','modal')
            $('.panel-heading.note-toolbar').find('.dropdown-toggle').removeAttr('data-toggle').attr('data-bs-toggle','dropdown')
            
            $('#product-form').submit(function(e){
            e.preventDefault()
            $('.pop-alert').remove()
            var _this = $(this)
            var el = $('<div>')
            el.addClass("pop-alert alert alert-danger text-light")
            el.hide()
            if($('[name="to_user"]').val() == ''){
                el.text('Recepient is required.')
                _this.prepend(el)
                el.show('slow')
                $('html, body').scrollTop(_this.offset().top - '150')
                return false;
            }
            start_loader()
            $.ajax({
                url:'../classes/Master.php?f=save_product',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.error(err)
                    el.text("An error occured while saving data")
                    _this.prepend(el)
                    el.show('slow')
                    $('html, body').scrollTop(_this.offset().top - '150')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href = "./?page=products/view_details&id="+resp.pid;
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $('html, body').scrollTop(_this.offset().top - '150')
                    }else{
                        el.text("An error occured while saving data")
                        _this.prepend(el)
                        el.show('slow')
                        $('html, body').scrollTop(_this.offset().top - '150')
                    }
                    end_loader()
                    console

                }
            })
        })
	})
</script>