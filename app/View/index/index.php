<div class="main-container clearfix">
    

    <div class="container-fluid">
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
                                <div class="col-md-4 col-sm-6 single">
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
    </div>

    <div class="container">

    </div>

</div>