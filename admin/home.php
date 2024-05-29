<section class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 position-relative bg-gradient bg-opacity-25">
                <div class="p-3 text-center">
                    <?php 
                    $category = $conn->query("SELECT * FROM `category_list` where delete_flag = 0")->num_rows;
                    ?>
                    <h1 class=""><span id="state1" countto="70"><?= format_num($category) ?></span></h1>
                    <h5 class="mt-3 ">Categories</h5>
                    <p class="text-lg h2 font-weight-normal text-dark"><span style="font-size:3rem" class="material-icons">view_list</span></p>
                </div>
            </div>
            <div class="col-md-4 position-relative bg-gradient bg-opacity-50">
                <div class="p-3 text-center">
                    <?php 
                    $users = $conn->query("SELECT * FROM `users` where `type` = 2 ")->num_rows;
                    ?>
                    <h1 class=""><span id="state1" countto="70"><?= format_num($users) ?></span></h1>
                    <h5 class="mt-3 ">Sellers</h5>
                    <p class="text-lg h2 font-weight-normal text-muted"><span style="font-size:3rem" class="material-icons">people_alt</span></p>
                </div>
                <hr class="vertical dark border-dark">
            </div>
            <div class="col-md-4 position-relative bg-gradient bg-opacity-50">
                <div class="p-3 text-center">
                    <?php 
                    $products = $conn->query("SELECT * FROM `product_list` where `status` =  1")->num_rows;
                    ?>
                    <h1 class=""><span id="state1" countto="70"><?= format_num($products) ?></span></h1>
                    <h5 class="mt-3 ">Available ArtWorks</h5>
                    <p class="text-lg h2 font-weight-normal text-primary"><span style="font-size:3rem" class="material-icons">inventory_2</span></p>
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