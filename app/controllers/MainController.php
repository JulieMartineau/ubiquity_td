<?php
namespace controllers;
use models\Order;
use models\Product;
use models\Section;
use models\User;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\orm\DAO;

/**
  * Controller MainController
  */
class MainController extends ControllerBase{
use WithAuthTrait;


    #[Route('_default',name:'home')]
	public function index(){
        $promos=DAO::getAll(Product::class,'promotion<?', false, [0]);
        $this->loadView("MainController/index.html", ["promos"=>$promos]);
	}

    public function initialize() {
        parent::initialize();
        $this->jquery->getHref('a[data-target]','',['ListenerOn'=>'body']);
    }
    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }
    #[Route('store',name:'store')]
    public function store(){
        $sections=DAO::getAll(Section::class,'', ['products']);
        $this->loadView('MainController/store.html',['sections'=>$sections]);
    }
    #[Route('section/{id}', name:'section')]
    public function sectionStore($id){
        $section=DAO::getById(Section::class,$id,['products']);
        $this->loadView('MainController/sectionStore.html', ['section'=>$section]);
    }

}
