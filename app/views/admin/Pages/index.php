<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Список страниц
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?=ADMIN;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
    <li class="active">Список страниц</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-centered">
              <thead>
              <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Алиас</th>
                <th>Статус</th>
                <th>Действия</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($pages as $page):?>
                <tr <?php if($page['status'] == 'hidden') echo 'class="product-hidden"';?>>
                  <td><?=$page['id'];?></td>
                  <td><a href="<?=ADMIN;?>/pages/edit?id=<?=$page['id'];?>"><?=$page['title'];?></a></td>
                  <td><?=$page['alias'];?></td>
                  <td><?=$page['status'] == 'visible' ? 'Активный' : 'Скрытый';?></td>
                  <td>
                    <a href="<?=ADMIN;?>/pages/edit?id=<?=$page['id'];?>">
                      <i class="fa fa-fw fa-eye"></i>
                    </a>
                    <a class="delete" href="<?=ADMIN;?>/pages/delete?id=<?=$page['id'];?>">
                      <i class="fa fa-fw fa-close text-danger"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>

      </div>
    </div>
  </div>

</section>
<!-- /.content -->