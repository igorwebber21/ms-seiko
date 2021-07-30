<?php


namespace app\controllers\admin;


use app\models\admin\Blog;
use app\models\admin\Pages;
use app\models\AppModel;
use ishop\libs\Pagination;
use RedBeanPHP\R;

class PagesController extends AppController
{

  public function indexAction()
  {
    $pages = R::getAll("SELECT * FROM pages ORDER BY id DESC");

    $this->setMeta('Список страниц');
    $this->set(compact('pages'));

  }

  public function addAction()
  {

    $this->setMeta('Новая страница');

    if (!empty($_POST)) {

      $pages = new Pages();
      $data = $_POST;
      $pages->load($data);

      $pages->attributes['status'] = $pages->attributes['status'] ? 'visible' : 'hidden';
      $pages->attributes['date_add'] = date('Y-m-d H:i');


      if (!$pages->validate($data)) {
        $pages->getErrors();
      }

      if ($id = $pages->save('pages')) {
        $alias = AppModel::createAlias('pages', 'alias', $data['title'], $id);
        $loadedPage = R::load('pages', $id);
        $loadedPage->alias = $alias;
        R::store($loadedPage);

        $_SESSION['success'] = 'Страница добавлена';
      }

      redirect(ADMIN . '/pages/add');
    }
  }

  public function editAction()
  {

    if (!empty($_POST)) {
      $id = $this->getRequestID(false);
      $pages = new Pages();
      $data = $_POST;
      $pages->load($data);

      $pages->attributes['status'] = $pages->attributes['status'] ? 'visible' : 'hidden';
      //debug($pages->attributes, 1);
      if (!$pages->validate($data)) {
        $pages->getErrors();
        redirect();
      }

      if ($pages->update('pages', $id)) {
        $alias = AppModel::createAlias('pages', 'alias', $data['title'], $id);
        $loadedPages = R::load('pages', $id);
        $loadedPages->alias = $alias;
        R::store($loadedPages);

        $_SESSION['success'] = 'Изменения сохранены';
        redirect();
      }
    }


    $id = $this->getRequestID();
    $page = R::load('pages', $id);
    $this->setMeta("Редактирование страницы {$page->title}");
    $this->set(compact('page'));
  }

  public function deleteAction()
  {
    $page_id = $this->getRequestID();
    $page = R::load('pages', $page_id);
    R::trash($page);

    $_SESSION['success'] = 'Страница удалена';
    redirect(ADMIN . '/pages');
  }

}