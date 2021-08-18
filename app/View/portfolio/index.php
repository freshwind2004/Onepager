<div class="container-fluid">
    <!-- main container -->
    <div class="main-container portfolio-inner clearfix">
        <!-- portfolio div -->
        <div class="portfolio-div">
            <div class="portfolio">

                <!-- portfolio_container -->
                <div class="portfolio_container clearfix">
                    <?php if ($this->onepagers === false) { ?>
                        <p>没有检索到一页书</p>
                    <?php } else { ?>
                        <?php foreach ($this->onepagers as $onepager) { ?>
                            <!-- single work -->
                            <div class="col-md-4 col-sm-6 single <?php echo $onepager['category_url']; ?>">
                                <a href="<?php echo $this->baseUrl(); ?>/portfolio/view/id/<?php echo $onepager['id']; ?>/" class="portfolio_item">
                                    <img src="<?php echo $this->baseUrl(); ?><?php echo $onepager['preview_path']; ?>" alt="image" class="img-responsive" />
                                    <div class="portfolio_item_hover">
                                        <div class="portfolio-border clearfix">
                                            <div class="item_info">
                                                <span><?php echo $onepager['subtitle']; ?></span>
                                                <em><?php echo $onepager['title']; ?></em>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- end single work -->
                        <?php } ?>
                    <?php } ?>

                </div>
                <!-- end portfolio_container -->

            </div>
            <!-- portfolio -->
        </div>
        <!-- end portfolio div -->
    </div>
    <!-- end main container -->
    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
    <?php $last = $this->page * $this->per; ?>
    <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($this->page != 1) { ?>
                <li>
                    <a href="<?php echo $this->baseUrl(); ?>/portfolio/index/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>
            <?php
            // 输出当前页面临近的8个页面
            $before_ellipsis = $after_ellipsis = false;
            if ($this->total_page > 10) {
                if ($this->page < 6) {
                    $page_range = range(1, 9);
                    $after_ellipsis = true;
                } elseif ($this->page > $this->total_page - 5) {
                    $page_range = range($this->total_page - 8, $this->total_page);
                    $before_ellipsis = true;
                } else {
                    $before_ellipsis = $after_ellipsis = true;
                    $page_range = range($this->page - 4, $this->page + 4);
                }
            } else {
                $page_range = $this->page_range;
            }
            ?>
            <?php if ($before_ellipsis) { ?>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
            <?php } ?>
            <?php foreach ($page_range as $page) { ?>
                <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/portfolio/index/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
            <?php } ?>
            <?php if ($after_ellipsis) { ?>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
            <?php } ?>
            <?php if ($this->page != $this->total_page) { ?>
                <li>
                    <a href="<?php echo $this->baseUrl(); ?>/portfolio/index/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>


<?php $this->beginBlock('jumbotron'); ?>
<!-- top bar -->
<div class="top-bar">
    <h1>浏览一页书</h1>
    <p>共有 <?php echo $this->total_count; ?> 项</p>
    <p><a href="<?php echo $this->baseUrl(); ?>/">首页</a> / 全部一页书</p>
</div>
<!-- end top bar -->
<?php $this->endBlock(); ?>