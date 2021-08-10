<!-- Page Content -->
<main class="page-main">
    <div class="block">
        <div class="container">
            <ul class="breadcrumbs">
                <?=$breadcrumbs;?>
               <!-- <li class="product-nav">
                    <i class="icon icon-angle-left"></i><a href="#" class="product-nav-prev">prev product
                        <span class="product-nav-preview">
							<span class="image"><img src="images/products/product-prev-preview.jpg" alt=""><span class="price">$280</span></span>
							<span class="name">Black swimsuit</span>
						</span></a>/
                    <a href="#" class="product-nav-next">next product
                        <span class="product-nav-preview">
							<span class="image"><img src="images/products/product-next-preview.jpg" alt=""><span class="price">$280</span></span>
							<span class="name">Black swimsuit</span>
						</span></a><i class="icon icon-angle-right"></i>
                </li>-->
            </ul>
        </div>
    </div>
    <div class="block product-block product-item-inside">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-5">
                    <!-- Product Gallery -->
                    <div class="container">
                        <div class="row">
                            <?php  $previewImg = ($gallery) ? GALLERYIMG.reset($gallery)->img : '/public/upload/images/product-9.jpg';?>
                            <div class="main-image col-xs-10">
                                <img src="<?=$previewImg?>" class="zoom product-image-photo" alt="" data-zoom-image="<?=$previewImg?>" />
                                <?php if($gallery): ?>
                                <div class="dblclick-text"><span>Double click for enlarge</span></div>
                                <a href="<?=$previewImg?>" class="zoom-link"><i class="icon icon-zoomin"></i></a>
                                <?php endif; ?>
                            </div>

                            <?php if($gallery): ?>
                            <div class="product-previews-wrapper col-xs-2">
                                <div class="product-previews-carousel" id="previewsGallery">
                                    <?php foreach ($gallery as $item): ?>
                                    <a href="#" data-image="<?=GALLERYIMG.$item->img?>" data-zoom-image="<?=GALLERYIMG.$item->img?>"><img src="<?=GALLERYIMG.$item->img?>" alt="" /></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /Product Gallery -->
                </div>
                <div class="col-sm-6 col-md-6 col-lg-7">
                    <div class="product-info-block classic">
                        <div class="product-name-wrapper">
                            <h1 class="product-name"><?=$product->title?></h1>
                            <div class="product-labels">

                              <?php if($product->hit == 'yes'): ?>
                                  <span class="product-label new">Новинка</span>
                              <?php endif; ?>
                              <?php if($product->old_price): ?>
                                  <span class="product-label sale">- <?=round(($product['old_price'] - $product['price']) / ($product['old_price'] / 100))?>%</span>
                              <?php endif; ?>

                            </div>
                        </div>
                        <div class="product-availability">Артикул: <span><?=$product->vendor_code?></span></div>
                        <div class="product-description">
                            <p>Брюки из коллекции Medicine. Модель выполнена из гладкой ткани.</p>
                        </div>
                        <div class="product-options">
                            <?php if($productFiltersArr)://debug($productFiltersArr);
                                    foreach ($productFiltersArr as $groupID => $filters):  ?>

                                    <?php if($groupID == 6): //debug($filters); echo $groupID; ?>
                                        <div class="product-size swatches">
                                            <span class="option-label"><?php echo $filters['Title']?>:</span>
                                            <div class="select-wrapper-sm">
                                                <select class="form-control input-sm size-variants">
                                                    <?php
                                                        foreach ($sizes as $size):
                                                          $selected = ($filters['Items'][0]['value'] == $size['value']) ? ' selected' : '';
                                                    ?>
                                                         <option<?=$selected?> value="<?=$size['value']?>"><?=$size['value']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                            <ul class="size-list">
                                                  <?php
                                                  foreach ($sizes as $size):
                                                        $absentOption = ' class="absent-option"';
                                                        foreach ($filters['Items'] as $filter){
                                                          if ($filter['value'] == $size['value']) {
                                                            $absentOption = ' ';
                                                          //  return;
                                                          }
                                                        }
                                                    ?>
                                                      <li<?=$absentOption?>><a href="#" data-value='<?=$size['value']?>'><span class="value"><?=$size['value']?></span></a></li>
                                                  <?php endforeach;?>
                                               <!-- <li class="absent-option"><a href="#" data-value='36'><span class="value">36</span></a></li>
                                                <li><a href="#" data-value='38'><span class="value">38</span></a></li>
                                                <li><a href="#" data-value='40'><span class="value">40</span></a></li>
                                                <li><a href="#" data-value='42'><span class="value">42</span></a></li>-->
                                            </ul>
                                        </div>
                                    <?php else:?>

                                        <div class="flex end">
                                            <span class="option-label"><?php echo $filters['Title']?>:</span>
                                            <div>
                                               <?php foreach ($filters['Items'] as $item) echo $item['value']; ?>
                                            </div>
                                        </div>

                                        <?php endif;?>

                              <?php endforeach; endif;?>
                            <!--<div class="product-color swatches">
                                <span class="option-label">Цвет:</span>
                                <div class="select-wrapper-sm">
                                    <select class="form-control input-sm">
                                        <option value="Red">Red</option>
                                        <option value="Green">Green</option>
                                        <option value="Blue" selected>Blue</option>
                                        <option value="Yellow">Yellow</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Violet">Violet</option>
                                    </select>
                                </div>
                                <ul class="color-list">
                                    <li class="absent-option"><a href="#" data-toggle="tooltip" data-placement="top" title="Red" data-value="Red" data-image="images/products/product-color-red.jpg"><span class="value"><img src="images/colorswatch/color-red.png" alt=""></span></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Pink" data-value="Green" data-image="images/products/product-color-green.jpg"><span class="value"><img src="images/colorswatch/color-green.png" alt=""></span></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Marine" data-value="Blue" data-image="images/products/product-color-blue.jpg"><span class="value"><img src="images/colorswatch/color-blue.png" alt=""></span></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Orange" data-value="yellow" data-image="images/products/product-color-yellow.jpg"><span class="value"><img src="images/colorswatch/color-yellow.png" alt=""></span></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Orange" data-value="grey" data-image="images/products/product-color-grey.jpg"><span class="value"><img src="images/colorswatch/color-grey.png" alt=""></span></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Orange" data-value="grey" data-image="images/products/product-color-violet.jpg"><span class="value"><img src="images/colorswatch/color-violet.png" alt=""></span></a></li>
                                </ul>
                            </div>-->
                            <div class="product-qty">
                                <span class="option-label">Кол-во:</span>
                                <div class="qty qty-changer">
                                    <fieldset>
                                        <input type="button" value="&#8210;" class="decrease">
                                        <input type="text" class="qty-input" value="1" data-min="0">
                                        <input type="button" value="+" class="increase">
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="product-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-meta">

                                        <span>

                                            <?php if(isset($_SESSION['user']['favourites'][$product['id']])): ?>
                                               <a href="#" class="add-to-wishlist wishlist-product active" data-product="1"  data-id="<?=$product['id']?>">
                                                 <i class="icon icon-heart"></i>
                                                <span class="wishlist-text">В избранном</span>
                                               </a>
                                            <?php else: ?>
                                                <a href="#" class="add-to-wishlist wishlist-product" data-product="1"  data-id="<?=$product['id']?>">
                                                 <i class="icon icon-heart"></i>
                                                <span class="wishlist-text">В избранное</span>
                                               </a>
                                            <?php endif;?>


                                        </span>
                                    </div>
                                    <div class="social">
                                        <div class="share-button toLeft">
                                            <span class="toggle">Поделиться</span>
                                            <ul class="social-list">
                                                <li>
                                                    <a class="icon icon-vk fa-vk share-btn-vk" style="background-color: #509FF5; border-color: #509FF5;"
                                                       href="https://vk.com/share.php?url=<?=PATH?>/product/<?=$product['alias']?>" target="_blank" onclick="return Share.me(this);"></a>
                                                </li>
                                                <li>
                                                    <a class="icon icon-linkedin linkedin share-btn-in" href="https://www.linkedin.com/cws/share?url=<?=PATH?>/product/<?=$product['alias']?>" target="_blank" onclick="return Share.me(this);">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Ffiddle.jshell.net%2F_display%2F&text=[TITLE]&url=<?=PATH?>/product/<?=$product['alias']?>"
                                                       class="icon icon-twitter-logo twitter"  target="_blank" onclick="return Share.me(this)">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="icon icon-facebook-logo facebook share-btn-fb"
                                                       href="http://www.facebook.com/sharer/sharer.php?s=100&p%5Btitle%5D=[TITLE]&p%5Bsummary%5D=[TEXT]&p%5Burl%5D=<?=PATH?>/product/<?=$product['alias']?>&p%5Bimages%5D%5B0%5D=[IMAGE]"
                                                       target="_blank" onclick="return Share.me(this);"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="price">
                                        <?php if($product['old_price']): ?>
                                            <span class="old-price"><span><?=$curr['symbol_left'];?><?=round($product->old_price * $curr['value']);?><?=$curr['symbol_right'];?></span></span>
                                        <?php endif; ?>
                                            <span class="special-price"><span><?=$curr['symbol_left'];?><?=round($product->price * $curr['value']);?><?=$curr['symbol_right'];?></span></span>
                                    </div>
                                    <div class="actions">
                                        <button class="btn btn-lg add-to-cart add-to-cart-fly" data-id="<?=$product->id;?>" href="cart/add?id=<?=$product->id;?>">
                                            <i class="icon icon-cart"></i><span>В корзину</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="block">
        <div class="tabaccordion">
            <div class="container">
              <?=$product->content;?>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="container">
            <div class="row">

                <?php if($related): ?>
                <div class="col-md-12">
                    <!-- Deal Carousel -->
                    <div class="title">
                        <h2 class="custom-color">Похожие товары</h2>
                        <div class="toggle-arrow"></div>
                        <div class="carousel-arrows"></div>
                    </div>
                    <div class="collapsed-content">
                        <div class="similar-products-carousel products-grid product-variant-5">
                            <!-- Product Item -->
                            <?php foreach ($related as $item): ?>
                            <div class="product-item large">
                                <div class="product-item-inside">
                                    <div class="product-item-info">
                                        <!-- Product Photo -->
                                        <div class="product-item-photo">
                                            <!-- Product Label -->
                                            <div class="product-item-label label-new"><span>New</span></div>
                                            <!-- /Product Label -->
                                            <div class="product-item-gallery">
                                                <!-- product main photo -->
                                                <div class="product-item-gallery-main">
                                                    <a href="product/<?=$item['alias']?>"><img class="product-image-photo" src="<?=PRODUCTIMG.$item['img']?>" alt=""></a>

                                                </div>
                                                <!-- /product main photo  -->
                                            </div>
                                            <!-- Product Actions -->
                                            <a href="#" title="Add to Wishlist" class="no_wishlist"> <i class="icon icon-heart"></i><span>В избранное</span> </a>

                                            <!-- /Product Actions -->
                                        </div>
                                        <!-- /Product Photo -->
                                        <!-- Product Details -->
                                        <div class="product-item-details">
                                            <div class="product-item-name"> <a title="Style Dome Men's Solid Red Color" href="product/<?=$item['alias']?>" class="product-item-link"><?=$item['title']?></a> </div>

                                            <div class="price-box">
                                                    <span class="price-container">
                                                           <span class="price-wrapper">
                                                               <span class="old-price"><?=$curr['symbol_left'];?><?=round($item['old_price'] * $curr['value']);?><?=$curr['symbol_right'];?></span>
                                                                <span class="special-price">
                                                                    <?=$curr['symbol_left'];?><?=round($item['price'] * $curr['value']);?><?=$curr['symbol_right'];?>
                                                                </span>
                                                           </span>
												    </span>
                                            </div>

                                            <button class="btn btn-lg add-to-cart modal-activate" data-id="<?=$item['id']?>" data-sizes="<?=$item['sizes']?>"> <i class="icon icon-cart"></i><span>В корзину</span> </button>
                                        </div>
                                        <!-- /Product Details -->
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- /Deal Carousel -->
                </div>
                <?php endif; ?>

                <?php if($recentlyViewed): ?>
                <div class="col-md-12">
                    <!-- Deal Carousel -->
                    <div class="title">
                        <h2 class="custom-color">Просмотренные товары</h2>
                        <div class="toggle-arrow"></div>
                        <div class="carousel-arrows"></div>
                    </div>
                    <div class="collapsed-content">
                        <div class="viewed-products-carousel products-grid product-variant-5">
                            <!-- Product Item -->
                            <?php foreach ($recentlyViewed as $item): ?>
                                <div class="product-item large">
                                    <div class="product-item-inside">
                                        <div class="product-item-info">
                                            <!-- Product Photo -->
                                            <div class="product-item-photo">
                                                <!-- Product Label -->
                                                <div class="product-item-label label-new"><span>New</span></div>
                                                <!-- /Product Label -->
                                                <div class="product-item-gallery">
                                                    <!-- product main photo -->
                                                    <div class="product-item-gallery-main">
                                                        <a href="product/<?=$item['alias']?>"><img class="product-image-photo" src="<?=PRODUCTIMG.$item['img']?>" alt=""></a>

                                                    </div>
                                                    <!-- /product main photo  -->
                                                </div>
                                                <!-- Product Actions -->
                                                <?php  $favouritesClass = isset($_SESSION['user']['favourites'][$item['id']]) ? 'wishlist active' : 'no_wishlist'; ?>
                                                <a href="#" title="Add to Wishlist" class="add-to-wishlist <?=$favouritesClass?>" data-id="<?=$item['id']?>">
                                                    <i class="icon icon-heart"></i>
                                                    <span>Добавить в избранное</span>
                                                </a>

                                                <!-- /Product Actions -->
                                            </div>
                                            <!-- /Product Photo -->
                                            <!-- Product Details -->
                                            <div class="product-item-details">
                                                <div class="product-item-name"> <a title="Style Dome Men's Solid Red Color" href="product/<?=$item['alias']?>" class="product-item-link"><?=$item['title']?></a> </div>

                                                <div class="price-box">
                                                    <span class="price-container">
                                                           <span class="price-wrapper">
                                                               <?php if($item['old_price']): ?>
                                                               <span class="old-price"><?=$curr['symbol_left'];?><?=round($item['old_price'] * $curr['value']);?><?=$curr['symbol_right'];?></span>
                                                               <?php endif; ?>
                                                                <span class="special-price">
                                                                    <?=$curr['symbol_left'];?><?=round($item['price'] * $curr['value']);?><?=$curr['symbol_right'];?>
                                                                </span>
                                                           </span>
												    </span>
                                                </div>

                                                <button class="btn btn-lg add-to-cart modal-activate" data-id="<?=$item['id']?>" data-sizes="<?=$item['sizes']?>"> <i class="icon icon-cart"></i><span>В корзину</span> </button>
                                            </div>
                                            <!-- /Product Details  data-id="<?//=$item['id']?>" href="cart/add?id=<?//=$item['id']?>"  -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- /Product Item -->

                        </div>
                    </div>
                    <!-- /Deal Carousel -->
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<!-- /Page Content -->