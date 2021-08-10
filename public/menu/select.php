<?php $parent_id = \ishop\App::$app->getProperty('parent_id');
 ?>
<option value="<?=$id;?>" <?php if($id == $parent_id) echo 'selected';?>
    <?php //if(isset($_GET['id']) && $_GET['id'] == $id) echo 'disabled'; ?>>
    <?=$category['parent_title'].'&nbsp;'.$category['title'];?>
</option>
<?php if(isset($category['childs'])): ?>
    <?= $this->getMenuHtml($category['childs'], '&nbsp;' . $tab. '-', $category['title'], $parent_id) ?>
<?php endif; ?>
