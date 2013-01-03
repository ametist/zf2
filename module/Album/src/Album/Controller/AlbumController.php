<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album; 
use Album\Form\AlbumForm;
#use Album\Model\AlbumTable; 

class AlbumController extends AbstractActionController
{
        protected $albumTable;
    
        public function getAlbumTable()
        {
            if (!$this->albumTable) {
                $sm = $this->getServiceLocator();
                $this->albumTable = $sm->get('Album\Model\AlbumTable');
            }
            return $this->albumTable;
        }
    
	public function indexAction()
	{
            return new ViewModel(array(
                'albums' => $this->getAlbumTable()->fetchAll(),
            ));
	}
        
        public function newAction() 
        {
            $form = new AlbumForm();
            $form->get('submit')->setValue('Add');

            $request = $this->getRequest();
            if ($request->isPost()) {
                $album = new Album();
                $form->setInputFilter($album->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    
                    $album->exchangeArray($form->getData());
                    #$at = new AlbumTable();
                    #$at->saveAlbum($form->getData());
                    $this->getAlbumTable()->saveAlbum($album); 

                    // Redirect to list of albums
                    return $this->redirect()->toRoute('album');
                }
            }

            return new ViewModel(array('form' => $form));
       }
}