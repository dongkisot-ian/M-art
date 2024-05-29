<style>
    #search-field{
        border-color: #ababab61 !important;
        border:2px;
    }
</style>
<section class="py-3">
    <div class="container">
        <form action="<?= base_url ?>?page=products/search_product" id="search-form">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="fw-bolder text-center">Look For ArtWork</h4>
                    <div class="input-group input-group-dynamic mb-4">
                        <span class="input-group-text pe-5"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input id="search-field" class="form-control border border-dark px-3 py-2" placeholder="Search" name="search" type="search" >
                    </div>
                </div>
            </div>
        </form>
        <h3 class="text-center fw-bolder mt-3">Welcome to <?= $_settings->info('name') ?></h3>
        <hr>
        <div>
            <?php include "welcome.html" ?>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#search-form').submit(function(e){
            e.preventDefault()
            location.href= $(this).attr('action')+"&"+$(this).serialize()
        })
    })
</script>