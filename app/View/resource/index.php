<div class="main-container clearfix">
    <div class="container">

        <div class="row resource">
            <?php foreach ($this->resources as $item) { ?>
                <div class="col-md-3 list-item">
                    <img src="<?php echo $this->baseUrl(); ?><?php echo $item['preview_path']; ?>" class="img-responsive" alt="" />
                    <h3 class="uppercase">
                        <a href="<?php echo $this->baseUrl(); ?>/resource/view/id/<?php echo $item['id']; ?>/"><?php echo $item['title']; ?></a>
                    </h3>
                    <div class="excerpt">
                        <?php echo $item['content']; ?>
                    </div>
                    <div class="h-10"></div>
                </div>
            <?php } ?>
        </div>

        <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
        <?php $last = $this->page * $this->per; ?>
        <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>

        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($this->page != 1) { ?>
                    <li>
                        <a href="<?php echo $this->baseUrl(); ?>/resource/index/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
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
                    <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/resource/index/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
                <?php } ?>
                <?php if ($after_ellipsis) { ?>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                <?php } ?>
                <?php if ($this->page != $this->total_page) { ?>
                    <li>
                        <a href="<?php echo $this->baseUrl(); ?>/resource/index/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

    </div>

</div>

<?php $this->beginBlock('jumbotron'); ?>
<!-- Top bar -->
<div class="top-bar">
    <h1>资源列表</h1>
    <p><a href="<?php echo $this->baseUrl(); ?>/">首页</a> / 资源</p>
</div>
<!-- end Top bar -->
<?php $this->endBlock(); ?>