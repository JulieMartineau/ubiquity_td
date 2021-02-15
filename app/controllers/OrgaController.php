<?php
namespace controllers;
use models\User;
use Ubiquity\attributes\items\router\Post;
use models\Organization;
use Ubiquity\attributes\items\router\Route;
use models\Groupe;
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;

/**
  * Controller OrgaController
  */
class OrgaController extends ControllerBase{
    private ViewRepository $repo;

    public function initialize()
    {
        parent::initialize();
        $this->repo=new ViewRepository($this,Organization::class);
    }

    #[Route('orga')]
	public function index(){
        $this->repo->all("",false);
		//$orgas=DAO::getAll(Organization::class, "",false);
		$this->loadView("OrgaController/index.html");


	}

	#[Route(path: "orga/{idOrga}",name: "orga.getOne")]
	public function getOne($idOrga){
        $this->repo->byId($idOrga,['users.groupes','groupes.users'], false);
		//$orga=DAO::getById(Organization::class,$idOrga,['users.groupes','groupes.users']);
		$this->loadDefaultView();
	}


	#[Post(path: "orga/add",name: "orga.add")]
	public function add(){

		
	}
	#[Route()]
	public function getOrga($name,$aliases){
        $orga=DAO::getOne( Organization::class,"name= ? and aliases= ?",parameters:[$name, $aliases]);
    }

    public function testInsert(){
        $groupe=new Groupe();
        URequest::setValuesToObject($groupe);
        $idOrga=URequest::post('idOrga');
        $orga=DAO::getById(Organization::class,$idOrga);
        $groupe->setOrganization($orga);
        DAO::insert($orga);
    }

    public function testUpdate(){
        $groupe=DAO::getById(Groupe::class,URequest::post('idGroupe'));
        URequest::setValuesToObject($groupe);
        $idOrga=URequest::post('idOrga');
        $orga=DAO::getById(Organization::class,$idOrga);
        $groupe->setOrganization($orga);
        $idUsers=explode(',',URequest::post('idUsers'));
        $users=DAO::getAllByIds(User::class,$idUsers);
        foreach ($users as $user) {
            $groupe->addUser($user);
        }
        DAO::update($orga,true);
    }

}
