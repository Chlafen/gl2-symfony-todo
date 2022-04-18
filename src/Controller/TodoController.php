<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
  #[Route('/todo', name: 'todo')]
  public function index(Request $req): Response
  {
    $session = $req->getSession();
    if(!$session->has('todos')) {
      $session->set('todos', []);
    }
    $this->addFlash('info', 'Welcome to the Todo List!');
    

    return $this->render('todo/index.html.twig', [
        'todos' => $session->get('todos'),
    ]);
  }
  /**
   * @Route("/todo/add/{title}/{description}", name="todo_add")
   */ 
  public function addTodo(Request $req, string $title, string $description){
    $session = $req->getSession();

    $id = 1;
    $created_at = new \DateTime();

    $todos = [];
    if($session->has('todos')) {
      $todos = $session->get('todos');
    } 
    
    if(count($todos) > 0) {
      $lastTodo = end($todos);
      $id = $lastTodo['id'] + 1;
    }
    $todos[] = [
      'id' => $id,
      'title' => $title,
      'description' => $description,
      'created_at' => $created_at,
    ];
    $session->set('todos', $todos);
    return $this->redirectToRoute('todo');
  }
  #[Route('/todo/delete/{id}', name: 'todo.delete')]
  public function deleteTodo(Request $req, $id) {
    $session = $req->getSession();
    $todos = $session->get('todos');
    foreach($todos as $key => $todo) {
      if($todo['id'] == $id) {
        unset($todos[$key]);
      }
    }
    $session->set('todos', $todos);
    return $this->redirectToRoute('todo');
  }
  #[Route('/', name: 'root')]
  public function root() {
    return $this->redirectToRoute('todo');
  }
}
