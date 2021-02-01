<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
 * Controller TodosController
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class TodosController extends ControllerBase{

    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';

    public function initialize(){
        parent::initialize();
        $this->menu();
    }

    #[Route('_default', name:'home')]
    public function index(){
        if(USession::exists(self::LIST_SESSION_KEY)){
            $list=USession::get(self::LIST_SESSION_KEY);
            return $this->displayList($list);
        }
        $this->showMessage('Bienvenue !', 'TodoLists permet de gérer des listes...', 'info', 'info circle',
            [[ 'url'=>Router::path('todos.new'),'caption'=>'Créer une nouvelle liste', 'style'=>'basic inverted' ]]);
    }

    #[Post(path: "todos/add", name:'todos.add')]
    public function addElement(){
        $list=USession::get(self::LIST_SESSION_KEY);
        if(URequest::filled('elements')){
            $elements=explode("\n", URequest::post('elements'));
            foreach ($elements as $elm){
                $list[]=$elm;
            }
        }else {
            $list[] = URequest::post('element');
        }
        USession::set(self::LIST_SESSION_KEY, $list);
        $this->displayList($list);
    }

    #[Get(path: "todos/delete/{index}", name:'todos.delete')]
    public function deleteElement($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        if(isset($list[$index])){
            array_splice($list, $index, 1);
            USession::set(self::LIST_SESSION_KEY, $list);
        }
        $this->showMessage('Suppression', 'élément à été supprimé',
            'success', 'success inverted message');
        $this->displayList($list);
    }

    #[Post(path: "todos/edit/{index}", name:'todos.edit')]
    public function editElements($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        /*if(URequest::has('index')){
            $elements=explode("\n", URequest::post('index'));
            foreach ($index as $elm){
                $list[]=$elm;
            }
        }else {
            $list[] = URequest::post('element');
        }*/
        USession::set(self::LIST_SESSION_KEY, $list);
        $this->displayList($list);
    }

    #[Get(path: "todos/loadList/{uniqid}", name:'todos.loadList')]
    public function loadList($uniqid){
    }

    #[Post(path: "todos/loadList", name:'todos.loadListPost')]
    public function loadListFromForm(){
    }

    #[Get(path: "todos/new/{force}", name:'todos.new')]
    public function newList($force=false){
       if(USession::exists(self::LIST_SESSION_KEY)){
            $this->showMessage('Nouvelle liste', 'Une liste a déjà été créée. Souhaitez-vous la vider ?',
                'warning', 'warning circle alternate',
                [['url'=>Router::path('todos.new'),'caption'=>'Créer une nouvelle liste', 'style'=>'basic inverted'],
                    [ 'url'=>Router::path('todos.home'),'caption'=>'Annuler', 'style'=>'inverted' ]]);
            $this->displayList(USession::get(self::LIST_SESSION_KEY));
        } else if ($force != false | !USession::exists(self::LIST_SESSION_KEY)){
            USession::set(self::LIST_SESSION_KEY, []);
            $this->displayList([]);
        }

    }

    #[Get(path: "todos/saveList", name:'todos.save')]
    public function saveList(){
    }

    public function menu(){
        $this->loadView('TodosController/menu.html');
    }

    private function displayList($list){
        if(\count($list)>0){
            $this->jquery->show('._saveList','','',true);
        }
        $this->jquery->change('#multiple', '$("._form").toggle();');
        $this->jquery->renderView('TodosController/displayList.html', ['list'=>$list]);
    }


    private function showMessage(string $header, string $message, string $type = '', string $icon = 'info circle',array $buttons=[]) {
        $this->loadView('TodosController/showMessage.html', compact('header', 'type', 'icon', 'message','buttons'));
    }


	
	public function testJquery(){
		$this->loadView('TodosController/testJquery.html');
        //$this->jquery->click('button','$('.elm').toggle();');
        $this->jquery->renderDefaultView();

	}



}
