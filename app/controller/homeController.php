<?php
//instanciar o caminho para a model;
require_once __DIR__ . '/../model/noticiaModel.php';

class homeController
{
  public function index()
  {
    //instanciar a classe
    $noticiaModel = new noticiaModel();
    //executar o metodo getNoticia
    $noticia = $noticiaModel->getNoticia();
    //exibir o conteudo na home
    require_once __DIR__ . '/../view/luumina.php';
  }
}
