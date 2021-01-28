<?php
namespace controllers;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\attributes\items\router\Post;

 /**
 * Controller TodosController
 **/
class TodosController extends ControllerBase{

    public function initialize()
    {
        parent::initialize();
        $this->menu();
    }

    #[Route('_default',name: 'home')]
    public function index(){

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
	public function loadlist($uniqid){
		
	}


	
	public function menu(){
		
		$this->loadView('TodosController/menu.html');

	}


	#[Post(path: "todos/loadListPost", name: 'todos.loadListPost')]
	public function loadListFormForm(){
		
	}


	#[Get(path: "todos/new/{force}", name: 'todos.new')]
	public function newlist($force){
		
	}


	#[Get(path: "todos/save", name: 'todos.save')]
	public function saveList(){
		
	}

}
