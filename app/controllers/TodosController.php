<?php
namespace controllers;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\USession;

/**
 * Controller TodosController
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 **/
class TodosController extends ControllerBase{
    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';

    public function initialize()
    {
        parent::initialize();
        $this->menu();
    }

    #[Route(path:"_default", name:'home' )]
    public function index(){
        if(USession::exists(self::LIST_SESSION_KEY)) {
            $list = USession::get(self::LIST_SESSION_KEY, []);
            return $this->display($list);
        }
        $this->showMessage('Bienvenue !','TodoLists permet de gerer des listes...','info', 'info circle',
            [['url'=>Router::path('todos.new'),'caption'=>'Créer une nouvelle liste','style'=>'basic inverted']]);

    }


	#[Post(path: "todos/add", name: 'todos.add')]
	public function addElement(){
		
	}


	#[Get(path: "todos/delete/{index}", name: 'todos.delete')]
	public function deleteElement($index){
		
	}


	#[Post(path: "todos/edit/{index}", name: 'todos.edit')]
	public function editElement($index){
		
	}


	#[Get(path: "todos/loadList/{uniqid}", name: 'todos.loadList')]
	public function loadList($uniqid){
		
	}


	
	private function menu(){
		
		$this->loadView('TodosController/menu.html');

	}


	#[Post(path: "todos/loadListPost", name: 'todos.loadListPost')]
	public function loadListFormForm(){
		
	}


	#[Get(path: "todos/new/{force}", name: 'todos.new')]
	public function newlist($force=false){
        USession::set(self::LIST_SESSION_KEY,[]);
        $this->displayList([]);
		
	}


	#[Get(path: "todos/save", name: 'todos.save')]
	public function saveList(){
		
	}


	
	private function displayList($list){
        $this->jquery->change('#multiple','$(".form").toggle();');
        $this->jquery->renderView('TodosController/displayList.html', ['list'=>$list]);

	}


	
	private function showMessage(string $header,string $message,string $type='info',string $icon='info circle',$buttons=[]){
		
		$this->loadView('TodosController/showMessage.html',
            compact('header','type', 'icon','message','buttons'));

	}

}
