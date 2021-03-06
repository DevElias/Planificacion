<?php 
namespace App\Controllers;

use Core\BaseController;
use Core\Container;

class AnosController extends BaseController
{
    public function __construct()
    {
        session_start();
        
        if($_SERVER['SERVER_NAME'] == 'admin.planificacion.techo.org' || $_SERVER['SERVER_NAME'] == 'localhost')
        {
            if(!isset($_SESSION['Planificacion']['token']))
            {
                header('Location: http://login.techo.org/?appid=98532jvfn145sas87aawrh154aeeth&redirect=https://admin.planificacion.techo.org/');
            }
        }
        else
        {
            if(!isset($_SESSION['Planificacion']['token']))
            {
                header('Location: http://login.techo.org/?appid=245sd4d5f4g8h1rt4584ht84t54tg8tg&redirect=https://planificacion.techo.org/');
            }
        }
    }
    
    public function index()
    {
        $this->setPageTitle('A&ntilde;os');
        $model = Container::getModel("Ano");
        $this->view->ano = $model->select();
        
        /* Render View Paises */
        $this->renderView('anos/index', 'layout');
    }
    
    public function add()
    {
        $this->setPageTitle('A&ntilde;os');
        $this->renderView('anos/add', 'layout');
    }
    
    public function save($aParam)
    {
        $aParam = (array) $aParam;
        
        $aParam['ano']     = filter_var($aParam['ano'], FILTER_SANITIZE_STRING);
        $aParam['status']  = filter_var($aParam['status'], FILTER_SANITIZE_STRING);
        
        $model  = Container::getModel("Ano");
        $result = $model->GuardarAno($aParam);
        
        if($result)
        {
            echo json_encode(array("results" => true));
        }
        else
        {
            echo json_encode(array("results" => false));
        }
    }
    
    public function show($id)
    {
        $model = Container::getModel("Ano");
        $this->view->ano = $model->search($id);
        
        /* Render View Paises */
        $this->renderView('anos/edit', 'layout');
    }
    
    public function edit($aParam)
    {
        $aParam = (array) $aParam;
        
        $aParam['id']     = filter_var($aParam['id'], FILTER_SANITIZE_STRING);
        $aParam['ano']    = filter_var($aParam['ano'], FILTER_SANITIZE_STRING);
        $aParam['status'] = filter_var($aParam['status'], FILTER_SANITIZE_STRING);
        
        $model  = Container::getModel("Ano");
        $result = $model->ActualizarAno($aParam);
        
        if($result)
        {
            echo json_encode(array("results" => true));
        }
        else
        {
            echo json_encode(array("results" => false));
        }
    }
    
    public function delete($id)
    {
        $model  = Container::getModel("Ano");
        $result = $model->delete($id);
        
        if($result)
        {
            header('Location: /anos');
        }
    }
    
}