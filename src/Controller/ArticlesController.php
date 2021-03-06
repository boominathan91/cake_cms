<?php 
// src/Controller/ArticlesController.php

namespace App\Controller;
// path of mpdf library included
require_once (ROOT .DS. 'vendor' .DS. 'mpdf' .DS.'mpdf'.DS.'src'.DS.'Mpdf.php'); 





use App\Controller\AppController;
use Mpdf;  // add mpdf library to the controller

class ArticlesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

    public function pdf(){
            
        $mpdf = new \Mpdf\Mpdf();        
        $mpdf->SetTitle('Certificate'); // title that is shown when pdf is opened in browser        
        $pdfName = 'Certificate.pdf'; //name of the pdf file
        $mpdf->WriteHTML('Hello Boomi'); //function used to convert HTML to pdf
        $mpdf->showImageErrors = true;    // show if any image errors are present
        $mpdf->debug = true;    // Debug warning or errors if set true(false by default)
        $mpdf->Output($pdfName,'I');    //output the pdf file
}


public function index()
{
    $articles = $this->Paginator->paginate($this->Articles->find());
    $this->set(compact('articles'));
}

public function view($slug)
{
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    $this->set(compact('article'));
}

public function add()
{
    $article = $this->Articles->newEntity();
    if ($this->request->is('post')) {
        $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
        $article->user_id = 1;

        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Your article has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Unable to add your article.'));
    }
    $this->set('article', $article);
}

public function edit($slug)
{
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    if ($this->request->is(['post', 'put'])) {
        $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Your article has been updated.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Unable to update your article.'));
    }

    $this->set('article', $article);
}

public function delete($slug)
{
    $this->request->allowMethod(['post', 'delete']);

    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    if ($this->Articles->delete($article)) {
        $this->Flash->success(__('The {0} article has been deleted.', $article->title));
        return $this->redirect(['action' => 'index']);
    }
}

}