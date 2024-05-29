
<?php 
require_once('../config.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$page_name = explode("/",$page)[count(explode("/",$page)) -1];
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<?php include_once('includes/header.php') ?>
<style>
    html, body{
        height:100%;
        width:100%;
    }
    body{
        background-image:url('<?= validate_image($_settings->info('cover')) ?>');
        background-size:cover;
        background-position:center center;
        background-repeat:no-repeat;
        overflow:auto;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        backdrop-filter:brightness(.8)
    }
    footer{
        position:fixed;
        bottom:0;
    }
    footer *{
        color: var(--bs-primary) !important;
    }
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<?php 

$qry = $conn->query("SELECT * FROM `users` where id = '{$_settings->userdata('id')}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_array() as $k => $v){
        if(!is_numeric($k)){
            $$k = $v;
        }
    }
}

?>
<body class="index-page bg-gray-200">
    <script>start_loader()</script>
    <div class="content w-100">
    <div class="row justify-content-center mx-0">
        <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
            <div class="card card-body shadow-blur mx-3 mx-md-4 rounded-0">
                <?php 
                if($_settings->chk_flashdata('success')):
                ?>
                <div class="alert alert-success ?> rounded-0 text-light py-1 px-4 mx-3">
                    <div class="d-flex w-100 align-items-center">
                        <div class="col-10">
                            <?= $_settings->flashdata('success') ?>
                        </div>
                        <div class="col-2 text-end">
                            <button class="btn m-0 text-sm" type="button" onclick="$(this).closest('.alert').remove()"><i class="material-icons mb-0">close</i></button>
                        </div>
                    </div> 
                </div>
                <?php endif; ?>
                <div class="container">
                    <h4 class="fw-bolder text-center">Seller Registration</h4>
                    <hr>
                    <br>
                    <form action="" id="register-user-form">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="type" value="2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="firstname" class="form-label">First Name <span class="text-primary">*</span></label>
                                    <input type="text" name="firstname" id="firstname" autofocus class="form-control form-control-lg" value="<?= isset($firstname) ?  $firstname : '' ?>" required="required">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="middlename" class="form-label">Middle Name</label>
                                    <input type="text" name="middlename" id="middlename" class="form-control form-control-lg" value="<?= isset($middlename) ?  $middlename : '' ?>">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="lastname" class="form-label">Last Name <span class="text-primary">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control form-control-lg" value="<?= isset($lastname) ?  $lastname : '' ?>" required="required">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="contact" class="form-label">Contact # <span class="text-primary">*</span></label>
                                    <input type="text" name="contact" id="contact" class="form-control form-control-lg" value="<?= isset($contact) ?  $contact : '' ?>" required="required">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="email" class="form-label">Email <span class="text-primary">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control form-control-lg" value="<?= isset($email) ?  $email : '' ?>" required="required">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="website" class="form-label">Website <small><span class="text-muted fst-italic">optional</span></small></label>
                                    <input type="text" name="website" id="website" class="form-control form-control-lg" value="<?= isset($website) ?  $website : '' ?>" placeholder="optional">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="fb_link" class="form-label">Facebook Page Link <small><span class="text-muted fst-italic">optional</span></small></label>
                                    <input type="text" name="fb_link" id="fb_link" class="form-control form-control-lg" value="<?= isset($fb_link) ?  $fb_link : '' ?>" placeholder="optional">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="username" class="form-label">Username</label>
                                    <span class="input-group-text"><i class="material-icons" aria-hidden="true">person_outline</i></span>
                                    <input type="text" name="username" id="username" class="form-control form-control-lg" value="<?= isset($username) ?  $username : '' ?>" required="required">
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg">
                                    <button type="button" tabindex="-1" class="btn btn-outline-primary btn-lg mb-0 rounded-0 border-0 px-1 pass_view"><i class="material-icons">visibility_off</i></button>
                                </div>
                                <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                                    <label for="cpassword" class="form-label">Confirm Password</label>
                                    <input type="password" id="cpassword" class="form-control form-control-lg">
                                    <button type="button" tabindex="-1" class="btn btn-outline-primary btn-lg mb-0 rounded-0 border-0 px-1 pass_view"><i class="material-icons">visibility_off</i></button>
                                </div>
                                <div class="form-group input-group input-group-dynamic is-filled mb-3">
                                    <label for="image" class="form-label">Avatar <span class="text-primary">*</span></label>
                                    <input type="file" name="image" id="image" onchange="displayImg(this)" class="form-control form-control-lg" accept="image/jpeg, image/png">
                                </div>
                                <div class="form-group mb-3 d-flex justify-content-center">
                                    <img src="<?php echo validate_image('') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                                </div>
                            </div>
                        </div>
                        
                    <br>
                    <div class="row justify-content-between align-items-center">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6 text-end">
                            <button class="btn btn-primary bg-gradient rounded-0 mb-0">Update Account</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    </div>


    <?php include_once('includes/footer.php') ?>
    <script>
        function displayImg(input,_this) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#cimg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }else{
                $('#cimg').attr('src', '<?php echo validate_image('') ?>');
            }
        }
        $(function(){
            $('.pass_view').click(function(){
                var type = $(this).siblings('input').attr('type')
                if(type =='password'){
                    $(this).html('<i class="material-icons">visibility</i>')
                    $(this).siblings('input').attr('type','text').focus()
                }else{
                    $(this).html('<i class="material-icons">visibility_off</i>')
                    $(this).siblings('input').attr('type','password').focus()
                }
            })
            $('#register-user-form').submit(function(e){
                e.preventDefault()
                $('.pop-alert').remove()
                var _this = $(this)
                var el = $('<div>')
                el.addClass("pop-alert alert alert-danger text-light mb-3 rounded-0 px-1 py-2")
                el.hide()
                if($('#password').val() != $('#cpassword').val()){
                    el.text('Passwords do not match.')
                    _this.prepend(el)
                    el.show('slow')
                    $('html, body').scrollTop(_this.offset().top - '150')
                    return false;
                }
                start_loader()
                $.ajax({
                    url:'../classes/Users.php?f=save_user',
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
                            location.href = "./login.php";
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
                    }
                })
            })
        })
    </script>
</body>

</html>