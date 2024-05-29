<section class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 position-relative bg-gradient bg-opacity-50">
                <div class="p-3 text-center">
                    <?php 
                    $products = $conn->query("SELECT * FROM `product_list` where `status` =  1 and `user_id` = '{$_settings->userdata('id')}' ")->num_rows;
                    ?>
                    <h1 class=""><span id="state1" countto="70"><?= format_num($products) ?></span></h1>
                    <h5 class="mt-3 ">Available ArtWorks</h5>
                    <p class="text-lg h2 font-weight-normal text-primary"><span style="font-size:3rem" class="material-icons">inventory_2</span></p>
                </div>
                <hr class="vertical dark border-dark">
            </div>
            <div class="col-md-6 position-relative bg-gradient bg-opacity-50">
                <div class="p-3 text-center">
                    <?php 
                    $products = $conn->query("SELECT * FROM `product_list` where `status` =  0 and `user_id` = '{$_settings->userdata('id')}' ")->num_rows;
                    ?>
                    <h1 class=""><span id="state1" countto="70"><?= format_num($products) ?></span></h1>
                    <h5 class="mt-3 ">Sold Products</h5>
                    <p class="text-lg h2 font-weight-normal text-muted"><span style="font-size:3rem" class="material-icons">inventory_2</span></p>
                </div>
                <hr class="vertical dark border-dark">
            </div>
        </div>
    </div>
</section>

<section class="py-1">
    <div class="container">
        <h3 class="text-center fw-bolder">Welcome to <?= $_settings->info('name') ?></h3>
    </div>
</section>