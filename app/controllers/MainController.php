<?php
namespace controllers;
 use models\Group;
 use models\User;
 use services\dao\OrgaRepository;
 use services\ui\UIGroups;
 use Ubiquity\attributes\items\di\Autowired;
 use Ubiquity\attributes\items\router\Get;
 use Ubiquity\attributes\items\router\Post;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\USession;

 /**
  * Controller MainController
  */
class MainController extends ControllerBase{
use WithAuthTrait;

    #[Autowired]
    private OrgaRepository $repo;
    private UIGroups $uiService;

    public function initialize(){
        parent::initialize();
        $this->uiService=new UIGroups($this);
    }


    public function setRepo(OrgaRepository $repo): void {
        $this->repo = $repo;
    }

    #[Route('_default',name:'home')]
	public function index(){
		$this->jquery->renderView("MainController/index.html");
	}

    protected function getAuthController(): AuthController{
        return new MyAuth($this);
    }

	#[Route(path: "test/ajax",name: "main.testAjax")]
	public function testAjax(){
		$user=DAO::getById(User::class,[1],false);
		$this->loadDefaultView(['user'=>$user]);
	}

	#[Route('user/details/',name:'user.details')]
    public function userDetails($id){
        $user=DAO::getById(User::class,[$id],true);
        echo "Organisation : ".$user->getOrganization();
    }
    #[Route('groups/list',name: 'groups.list')]
    public function listGroups(){
        $idOrga=USession::get('idOrga');
        $groups=DAO::getAll(Group::class,'idOrganization= ?',false,[$idOrga]);
        $this->uiService->listGroups($groups);
        $this->jquery->renderDefaultView();
    }

    #[Get('new/user', name: 'new.user')]
    public function newUser(){
        $this->ui->newUser('frm-user');
        $this->jquery->renderView('main/vForm.html');
    }

    #[Post('new/user', name: 'new.userPost')]
    public function newUserPost(){
        $idOrga=USession::get('idOrga');
        $orga=DAO::getById(Organization::class,$idOrga,false);
        $user=new User();
        URequest::setValuesToObject($user);
        $user->setEmail(\strtolower($user->getFirstname().'.'.$user->getLastname().'@'.$orga->getDomain()));
        $user->setOrganization($orga);
        if(DAO::insert($user)){
            $count=DAO::count(User::class,'idOrganization= ?',[$idOrga]);
            $this->jquery->execAtLast('$("#users-count").html("'.$count.'")');
            $this->showMessage("Ajout d'utilisateur","L'utilisateur $user a été ajouté à l'organisation.",'success','check square outline');
        }else{
            $this->showMessage("Ajout d'utilisateur","Aucun utilisateur n'a été ajouté",'error','warning circle');
        }
    }
}