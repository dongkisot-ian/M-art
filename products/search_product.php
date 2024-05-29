<style>
    img.img-thumbnail.product-thumb {
        width: 5rem;
        height: 5rem;
        object-fit: scale-down;
        object-position: center center;
    }
    #product-list .prod-item:nth-child(even){
        direction:rtl !important;
    }
    #product-list .prod-item:nth-child(even) > * {
        direction:ltr !important;
    }
</style>
<section class="py-4">
    <div class="container">
        <h3 class="fw-bolder text-center">Search Results for '<?= $_GET['search'] ?>' keyword</h3>
        <center>
            <hr class="bg-primary w-25 opacity-100">
        </center>
        <div class="list-group" id="product-list">
            <?php 
            $qry = $conn->query("SELECT p.*, c.name as category, u.username FROM `product_list` p inner join `category_list` c on p.category_id = c.id inner join users u on p.user_id = u.id where p.`status` = 1 and (p.title LIKE '%{$_GET['search'] }%' or p.short_description LIKE '%{$_GET['search'] }%' or c.name LIKE '%{$_GET['search'] }%' or c.description LIKE '%{$_GET['search'] }%') ");
            while($row = $qry->fetch_assoc()):
            ?>
            <a href="./?page=products/view_details&id=<?= $row['id'] ?>" class="text-reset text-decoration-none list-group-item list-group-item-action d-flex w-100 prod-item align-items-center">
                <div class="col-3 text-center"><img src="<?= validate_image( is_file(base_app."uploads/thumbnails/".($row['id']).".png") ? "uploads/thumbnails/".($row['id']).".png?v=".(strtotime($row['date_updated'])) : '') ?>" class="product-thumb" alt=""></div>
                <div class="col-9">
                    <h3 class="fs-bolder"><?= $row['title'] ?></h3>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                            <div class="d-flex w-100 align-items-center">
                                <div class="col-auto d-flex align-items-center pe-3">
                                    <i class="material-icons me-3">category</i>
                                    Category:
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1"><?= isset($row['category']) ? ($row['category']) : 0 ?></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                            <div class="d-flex w-100 align-items-center">
                                <div class="col-auto d-flex align-items-center pe-3">
                                    <i class="material-icons me-3">inventory_2</i>
                                    Stock/s:
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1"><?= isset($row['stock']) ? number_format($row['stock']) : 0 ?></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                            <div class="d-flex w-100 align-items-center">
                                <div class="col-auto d-flex align-items-center pe-3">
                                    <i class="material-icons me-3">sell</i>
                                    Price:
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1"><?= isset($row['selling_price']) ? format_num($row['selling_price'],2) : 0 ?></div>
                            </div>
                        </div>
                    </div>
                    <p class="truncate-3"><?= $row['short_description'] ?></p>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<script>
    $(function(){
        
    })
</script>