<?php
namespace controllers;
use models\Organization;
use Ubiquity\attributes\items\router\Route;
 use models\Groupe;
 use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;

/**
  * Controller OrgaController
  */
class OrgaController extends ControllerBase{
    //private ViewRepository $repo;

    /*public function initialize()
    {
        parent::initialize();
        $this->repo=new ViewRepository($this,Organization::class);
    }*/

    #[Route('orga')]
	public function index(){
        //$this->repo->all("",false);
		$orgas=DAO::getAll(Organization::class, "",false );
		$this->loadView("OrgaController/index.html",['orgas'=>$orgas]);


	}

	#[Route(path: "orga/idOrga",name: "orga.getOne")]
	public function getOne($idOrga){
		$orga=DAO::getById(Organization::class,$idOrga,['users.groupes','groupes.users']);
		$this->loadDefaultView(['orga'=>$orga]);
	}

}
