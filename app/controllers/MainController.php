<?php
namespace controllers;
use models\Basket;
use models\Order;
use models\Product;
use models\Section;
use models\User;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

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
    public function store($content=""){
        $sections=DAO::getAll(Section::class,'', ['products']);
        $product=DAO::getAll(Product::class,'promotion<?', false, [0]);
        //$promoEnCours=$this->loadView('MainController/sectionStore',['product'=>$product], true);
        $this->jquery->renderView('MainController/store.html',['sections'=>$sections,'content'=>$content]);
    }
    #[Route('section/{id}', name:'section')]
    public function sectionStore($id){
        $section=DAO::getById(Section::class,$id,['products']);
        if(!URequest::isAjax()){
            $this->store($this->loadView('MainController/sectionStore.html',['section'=>$section],true));
            return;
        }
        $this->loadView('MainController/sectionStore.html', ['section'=>$section]);
    }

    #[Route('product/{idS}/{idP}',name: 'product')]
    public function product($idS, $idP){
        $section=DAO::getById(Section::class, $idS, false);
        $product=DAO::getById(Product::class, $idP, false);
        if(!URequest::isAjax()){
            $content=$this->loadView('MainController/product.html',['product'=>$product,'section'=>$section],true);
            $this->store($content);
            return;
        }
        $this->loadView('MainController/product.html',['product'=>$product,'section'=>$section]);
    }

    /**
     * @return Basket
     */
    private function getBasket(){
        return USession::get('basket', new Basket());
    }

    #[Route('basket',name: 'basket')]
    public function basket(){
        $basket = $this->getBasket();
    }

    #[Route('/form',name:'form')]
    public function form(){
        $this->jquery->renderView("MyController/form.html");
    }
    #[Route(path: "form/submit",name: "main.submit")]
    public function submit(){
        var_dump($_POST);
    }
    #[Route(path: "/htmlForm",name: "main.htmlForm")]
    public function htmlForm(){
        $frm=$this->jquery->semantic()->htmlForm('html-form');
        $frm->setActionTarget(Router::path('main.submit'),'#response');
        $frm->addInput('name','Name','text','')->addRule('empty');
        $frm->addDropdown('user',UArrayModels::asKeyValues(DAO::getAll(User::class,'',false),'getId'),'User','no user');
        $frm->addButton('bt','positive');
        $frm->setValidationParams(['on'=>'blur','inline'=>true]);
        $this->jquery->renderView('MainController/htmlForm.html');
    }
    #[Route(path: "/dataForm",name: "main.dataForm")]
    public function dataForm(){
        $u=new User();
        $frm=$this->jquery->semantic()->dataForm('data-form',$u);
        $frm->setValidationParams(['on'=>'blur','inline'=>true]);
        $frm->setActionTarget(Router::path('main.addUser'),'#response');
        $frm->setFields(['name','password','email','submit']);

        $rules=ValidatorsManager::getUIConstraints($u);
        $frm->fieldAsInput('password',['inputType'=>'password']+($rules['password']??[]));
        $frm->fieldAsInput('name',$rules['name']??[]);
        $frm->fieldAsInput('email',$rules['email']??[]);
        $frm->fieldAsButton('submit','positive fluid',['value'=>'Valider']);

        $this->jquery->renderView('MainController/dataForm.html');
    }
    #[Route(path: "/addUser",name: "main.addUser")]
    public function addUser(){
        $user=new User();
        URequest::setValuesToObject($user);
        if(DAO::insert($user)){
            $message="Utilisateur $user ajouté.";
            $type='success';
        }else{
            $message="Impossible d'ajouter $user";
            $type='error';
        }
        $this->loadView('MainController/addUser.html',compact('message','type'));
    }
    #[Route(path: "/dt",name: "main.dt")]
    public function dt(){
        $products=DAO::getAll(Product::class,'1=1 order by idSection',['section']);
        $dt=$this->jquery->semantic()->dataTable('dt',Product::class,$products);
        $dt->setCompact(true);
        $dt->setFields(['name','section','price','stock']);

        $dt->fieldAsHeader('section');
        $dt->fieldAsLabel('name');
        $dt->addGroupBy('section');
        $this->jquery->renderView('MainController/dt.html');
    }
}
