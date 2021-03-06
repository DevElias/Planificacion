<?php 
namespace App\Controllers;

use Core\BaseController;
use Core\Container;

class HomeController extends BaseController
{
    public function __construct()
    {
        session_start();
    }
    
    public function index()
    {
        //Assume o Token Gerado no Login, em desenvolvimento local usa um token fixo
        if($_SERVER['SERVER_NAME'] != 'localhost')
        {
            if($_GET['token'])
            {
                $_SESSION['Planificacion']['token']   = $_GET['token'];
            }
            
            //Session Producao e Local
            if($_SESSION['Planificacion']['token'])
            {
                $url = 'http://login.techo.org/api?token='. $_SESSION['Planificacion']['token'];
                
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                
                $output = curl_exec($curl);
                curl_close($curl);
                
                $data = json_decode($output, TRUE);
                
                $_SESSION['Planificacion']['Mail']      = $data['email'];
                $_SESSION['Planificacion']['user_id']   = $data['id'];
                $_SESSION['Planificacion']['sede_id']   = $data['id_sede'];
                $_SESSION['Planificacion']['area_id']   = $data['id_area'];
                $_SESSION['Planificacion']['cargo_id']  = $data['id_cargo'];
                $_SESSION['Planificacion']['pais_id']   = $data['id_pais'];
                $_SESSION['Planificacion']['Name']      = $data['nombre'];
            }
        }
        else
        {
            $_SESSION['Planificacion']['token']   = '7c9b5c9b9baae1227deb96f1c51a7b61';
            
            $url = 'http://login.techo.org/api?token='. $_SESSION['Planificacion']['token'];
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            
            $output = curl_exec($curl);
            curl_close($curl);
            
            $data = json_decode($output, TRUE);
            
            $_SESSION['Planificacion']['Mail']      = $data['email'];
            $_SESSION['Planificacion']['user_id']   = $data['id'];
            $_SESSION['Planificacion']['sede_id']   = $data['id_sede'];
            $_SESSION['Planificacion']['area_id']   = $data['id_area'];
            $_SESSION['Planificacion']['cargo_id']  = $data['id_cargo'];
            $_SESSION['Planificacion']['pais_id']   = $data['id_pais'];
            $_SESSION['Planificacion']['Name']      = $data['nombre'];
        }
        
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
        
        $this->setPageTitle('Home');
        $this->renderView('home/index', 'layout');
    }
    
    //Busca Sede en id.techo.org
    public function GetSede($idSede)
    {
        $url = 'http://id.techo.org/sede?api=true&token='.$_SESSION['Planificacion']['token'].'&id='.$idSede;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $output = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($output, true);
        
        return $data;
    }
    
    //Busca Pais en login.techo.org
    public function GetPais($idPais)
    {
        $url = 'http://id.techo.org/pais?api=true&token='.$_SESSION['Planificacion']['token'].'&id='.$idPais;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $output = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($output, true);
        
        return $data;
    }
    
    public function Paises()
    {
        $url = 'http://id.techo.org/pais?api=true&token='.$_SESSION['Planificacion']['token'];
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CAINFO, getcwd() . DIRECTORY_SEPARATOR . 'cacert.pem');
        
        $output = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($output, true);
        
        for($i=0; $i < count($data); $i++)
        {
            $aTemp[$i]['id']   = $data[$i]['ID_Pais'];
            $aTemp[$i]['pais'] = $data[$i]['Nombre_Pais'];
        }
        
        //  echo json_encode(array("values" => $aTemp));
        return $aTemp;
    }
    
    public function Sedes($idPais)
    {
        $url = 'http://id.techo.org/sede?api=true&token='.$_SESSION['Planificacion']['token'].'&id_pais='.$idPais;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CAINFO, getcwd() . DIRECTORY_SEPARATOR . 'cacert.pem');
        
        $output = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($output, true);
        
        for($i=0; $i < count($data); $i++)
        {
            $aTemp[$i]['id']   = $data[$i]['id'];
            $aTemp[$i]['sede'] = $data[$i]['nombre'];
        }
        
        //  echo json_encode(array("values" => $aTemp));
        return $aTemp;
    }
    
    //Monta o DashBoard do Usuario
    public function UserLogado($aParam)
    {
        $aParam = (array) $aParam;
        $model  = Container::getModel("Dashboard");
        
        //Ano corrente
        $ano = date('Y');
        
        $sede = $this->GetSede($aParam['sede']);
        $pais = $this->GetPais($aParam['pais']);
        
        //Oficina Internacional
        if($sede[0]['id'] == 1)
        {
            //Envia os Dashboard desse usuario
            $aDash = $model->BuscaDashboard($_SESSION['Planificacion']['user_id']);
            
            for($i=0; $i < count($aDash); $i++)
            {
                $aDashboard[$i] = (array) $aDash[$i];
            }
            
            //Enviar Nome de todos Paises
            $aPaises = $this->Paises();
            $result  = $aPaises;
            
            if(!empty($aDashboard))
            {
                $tipo    = 'AdminValor';
            }
            else
            {
                $tipo    = 'AdminSemValor';
            }
        }
        
        //Sede Nacional
        if($sede[0]['nombre'] == 'Sede Nacional')
        {
            //Enviar Nome do Pais e todas Sedes deste Pais
            $aSedes = $this->Sedes($aParam['pais']);
            $result = $aSedes;
            $tipo   = 'Nacional';
            
            //Busca id da Planificacion
            $aAno = $model->BuscaAno($ano);
            
            //Busca cPlanificacion
            if(!empty($aAno))
            {
                $aPlanificacion = $model->BuscaPlanificacion($aAno[0]->id);
            }
            
            //Dados Importantes
            $idPais          = $aParam['pais'];
            $idAno           = $aAno[0]->id;
            $idPlanificacion = $aPlanificacion[0]->id;
            
            for($i=0; $i < count($result); $i++)
            {
                $aRet = $model->BuscaDadosGerais($idPais, $idPlanificacion, $result[$i]['id']);
                
                $cCor = '';
                
                //Montar Grid
                $html  = '';
                $html .= '<div  class="wrapper wrapper-content animated fadeInRight">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12">';
                $html .= '<div class="ibox float-e-margins">';
                $html .= '<div class="ibox-title">';
                $html .= '</div>';
                $html .= '<div class="ibox-content">';
                $html .= '<table class="table table-striped table-bordered table-hover dataTables-example" >';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>KPI - &Iacute;NDICES DE EXCELENCIA</th>';
                $html .= '<th class="azul">Plan ' . $ano .'</th>';
                $html .= '<th class="azul">Real ' . $ano .'</th>';
                $html .= '<th class="azul">% (R/P)</th>';
                $html .= '<th class="azul">Plan S1</th>';
                $html .= '<th class="azul">Real S1</th>';
                $html .= '<th class="azul">% (R/P) S1</th>';
                $html .= '<th class="azul">Plan S2</th>';
                $html .= '<th class="azul">Real S2</th>';
                $html .= '<th class="azul">% (R/P) S2</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                
                foreach ($aRet as $indicadores)
                {
                    
                    $formato = $indicadores->formato;
                    $porcento = ' % ';
                    
                    if($formato == '#')
                    {
                        $formato = '&#160;';
                    }
                    
                    $html .= '<tr class="gradeX">';
                    $html .= '<td>' . $indicadores->indicador . '</td>';
                    
                    if($indicadores->tipo == 'Acumulado')
                    {
                        //Verifica Valor por Valor
                        if(number_format($indicadores->acumulado_rp_anual, 2, '.', '') == '0.00' || number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '60.00')
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        $html .= '<td>' . $indicadores->acumulado_plan_anual . ' ' . $formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP Anual
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->acumulado_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP S1
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->acumulado_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP S2
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Promedio')
                    {
                        $html .= '<td>' . $indicadores->promedio_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP Anual
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->promedio_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->promedio_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP S1
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->promedio_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP S2
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Minimo')
                    {
                        $html .= '<td>' . $indicadores->minimo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP Anual
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->minimo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP S1
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->minimo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP S2
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Maximo')
                    {
                        $html .= '<td>' . $indicadores->maximo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP Anual
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->maximo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP S1
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->maximo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP S2
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Ultimo')
                    {
                        $html .= '<td>' . $indicadores->ultimo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP Anual
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_anual, 2, '.', '') . ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->ultimo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP S1
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->ultimo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP S2
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                }
                
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                
                $result[$i]['select'] = $html;
            }
        } 
        //Outras Sedes
        if($sede[0]['nombre'] != 'Sede Nacional' && $sede[0]['id'] != 1)
        {
            $aSedes[0]['id'] = $sede[0]['id'];
            $aSedes[0]['sede'] = $sede[0]['nombre'];
            $tipo = 'Normal';
            
            //Envia Apenas Nome desta Sede
            $result = $aSedes;
            
            //Busca id da Planificacion
            $aAno = $model->BuscaAno($ano);
            
            //Busca cPlanificacion
            if(!empty($aAno))
            {
                $aPlanificacion = $model->BuscaPlanificacion($aAno[0]->id);
            }
            
            //Dados Importantes
            $idPais          = $aParam['pais'];
            $idAno           = $aAno[0]->id;
            $idPlanificacion = $aPlanificacion[0]->id;
            
            for($i=0; $i < count($result); $i++)
            {
                $aRet = $model->BuscaDadosGerais($idPais, $idPlanificacion, $result[$i]['id']);
                
                //Montar Grid
                $html  = '';
                $html .= '<div  class="wrapper wrapper-content animated fadeInRight">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12">';
                $html .= '<div class="ibox float-e-margins">';
                $html .= '<div class="ibox-title">';
                $html .= '</div>';
                $html .= '<div class="ibox-content">';
                $html .= '<table class="table table-striped table-bordered table-hover dataTables-example" >';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>KPI - &Iacute;NDICES DE EXCELENCIA</th>';
                $html .= '<th class="azul">Plan ' . $ano .'</th>';
                $html .= '<th class="azul">Real ' . $ano .'</th>';
                $html .= '<th class="azul">% (R/P)</th>';
                $html .= '<th class="azul">Plan S1</th>';
                $html .= '<th class="azul">Real S1</th>';
                $html .= '<th class="azul">% (R/P) S1</th>';
                $html .= '<th class="azul">Plan S2</th>';
                $html .= '<th class="azul">Real S2</th>';
                $html .= '<th class="azul">% (R/P) S2</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                
                foreach ($aRet as $indicadores)
                {
                    
                    $formato = $indicadores->formato;
                    $porcento = ' % ';
                    
                    if($formato == '#')
                    {
                        $formato = '&#160;';
                    }
                    
                    $html .= '<tr class="gradeX">';
                    $html .= '<td>' . $indicadores->indicador . '</td>';
                    
                    if($indicadores->tipo == 'Acumulado')
                    {
                        $html .= '<td>' . $indicadores->acumulado_plan_anual . ' ' . $formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP Anual
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->acumulado_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP S1
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->acumulado_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->acumulado_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Acumulado RP S1
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Promedio')
                    {
                        $html .= '<td>' . $indicadores->promedio_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP Anual
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->promedio_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP S1
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->promedio_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->promedio_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Promedio RP S2
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Minimo')
                    {
                        $html .= '<td>' . $indicadores->minimo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP Anual
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->minimo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->minimo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP S1
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->minimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->minimo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->minimo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Minimo RP S2
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->minimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Maximo')
                    {
                        $html .= '<td>' . $indicadores->maximo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP Anual
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->maximo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->maximo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP S1
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->maximo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->maximo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Maximo RP S2
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->maximo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                    
                    if($indicadores->tipo == 'Ultimo')
                    {
                        $html .= '<td>' . $indicadores->ultimo_plan_anual. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_anual. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP Anual
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_anual, 2, '.', '') . ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->ultimo_plan_s1. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_s1. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP S1
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        
                        $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                        $html .= '<td>' . $indicadores->ultimo_plan_s2. ' ' .$formato .'</td>';
                        $html .= '<td class="cinza">' . $indicadores->ultimo_real_s2. ' ' .$formato .'</td>';
                        
                        //Cor Ultimo RP S2
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '59.99'))
                        {
                            $cCor = 'Vermelho';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '79.99'))
                        {
                            $cCor = 'Amarelo';
                        }
                        
                        if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '80.00'))
                        {
                            $cCor = 'Verde';
                        }
                        
                        $html .= '<td class="'.$cCor.'">' .number_format($indicadores->ultimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                    }
                }
                
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                
                $result[$i]['select'] = $html;
            }
        }
        
        echo json_encode(array("data" => $result, 'dash' => $aDashboard, 'tipo' => $tipo));
    }
    
    //Grava Nomes dos DashBoards
    public function GravaDashboard($aParam)
    {
        $aParam = (array) $aParam;
        
        $aParam['nome'] = filter_var($aParam['nome'], FILTER_SANITIZE_STRING);
        
        $model = Container::getModel("Dashboard");
        
        $result = $model->GravaDashboard($aParam);
        
        if($result)
        {
            echo json_encode(array("results" => true));
        }
        else
        {
            echo json_encode(array("results" => false));
        }
    }
    
    public function ListaPaises()
    {
        //Busca paises
        $aPaises = $this->Paises();
        
        //Busca os DashBoard do usuario logado
        $model = Container::getModel("Dashboard");
        
        $result = $model->BuscaDashboard($_SESSION['Planificacion']['user_id']);
        
        echo json_encode(array("data" => $aPaises, 'dash' => $result));
        
    }
    
    public function FinalizaDashboard($aParam)
    {
        $aParam = (array) $aParam;
        $model  = Container::getModel("Dashboard");
        
        $paises = explode(',',$aParam['paises']);
        $paises = array_filter($paises);
        
        //Comeca a gravar os paises do dashboard
        for($i=0; $i < count($paises); $i++)
        {
            $aData = $paises[$i];
            
            $result = $model->GuardarDashPaises($aData, $aParam['dashboard']);
        }
        
        if($result)
        {
            echo json_encode(array("results" => true));
        }
        else
        {
            echo json_encode(array("results" => false));
        }
    }
    
    public function CarregaCompleto($aParam)
    {
        $aParam = (array) $aParam;
        $model  = Container::getModel("Dashboard");
        
        $cCor = '';
        
        //Ano corrente
        $ano = date('Y');
        
        //Busca id da Planificacion
        $aAno = $model->BuscaAno($ano);
        
        //Busca cPlanificacion
        if(!empty($aAno))
        {
            $aPlanificacion = $model->BuscaPlanificacion($aAno[0]->id);
        }
        
        $result = $model->BuscaDashPaises($aParam['id']);
        
        for($i=0; $i < count($result); $i++)
        {
            $aDados[$i] = (array) $result[$i];
            
            $pais = $this->GetPais($aDados[$i]['id_pais']);
            
            $aRet = $model->BuscaDadosGerais($aDados[$i]['id_pais'], $aPlanificacion[0]->id);
            
            //Montar Grid
            $html  = '';
            $html .= '<div  class="wrapper wrapper-content animated fadeInRight">';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12">';
            $html .= '<div class="ibox float-e-margins">';
            $html .= '<div class="ibox-title">';
            $html .= '</div>';
            $html .= '<div class="ibox-content">';
            $html .= '<table class="table table-striped table-bordered table-hover dataTables-example" >';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>KPI - &Iacute;NDICES DE EXCELENCIA</th>';
            $html .= '<th class="azul">Plan ' . $ano .'</th>';
            $html .= '<th class="azul">Real ' . $ano .'</th>';
            $html .= '<th class="azul">% (R/P)</th>';
            $html .= '<th class="azul">Plan S1</th>';
            $html .= '<th class="azul">Real S1</th>';
            $html .= '<th class="azul">% (R/P) S1</th>';
            $html .= '<th class="azul">Plan S2</th>';
            $html .= '<th class="azul">Real S2</th>';
            $html .= '<th class="azul">% (R/P) S2</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            
            foreach ($aRet as $indicadores)
            {
                
                $formato = $indicadores->formato;
                $porcento = ' % ';
                
                if($formato == '#')
                {
                    $formato = '&#160;';
                }
                
                $html .= '<tr class="gradeX">';
                $html .= '<td>' . $indicadores->indicador . '</td>';
                
                if($indicadores->tipo == 'Acumulado')
                {
                    $html .= '<td>' . $indicadores->acumulado_plan_anual . ' ' . $formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->acumulado_real_anual. ' ' .$formato .'</td>';
                    
                    //Cor Acumulado RP Anual
                    if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_anual, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_anual, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->acumulado_plan_s1. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->acumulado_real_s1. ' ' .$formato .'</td>';
                    
                    //Cor Acumulado RP S1
                    if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s1, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_s1, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->acumulado_plan_s2. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->acumulado_real_s2. ' ' .$formato .'</td>';
                    
                    //Cor Acumulado RP S2
                    if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->acumulado_rp_s2, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->acumulado_rp_s2, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->acumulado_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                }
                
                if($indicadores->tipo == 'Promedio')
                {
                    $html .= '<td>' . $indicadores->promedio_plan_anual. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->promedio_real_anual. ' ' .$formato .'</td>';
                    
                    //Cor Promedio RP Anual
                    if((number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_anual, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->promedio_rp_anual, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->promedio_plan_s1. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->promedio_real_s1. ' ' .$formato .'</td>';
                    
                    //Cor Promedio RP S1
                    if((number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s1, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->promedio_rp_s1, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->promedio_plan_s2. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->promedio_real_s2. ' ' .$formato .'</td>';
                    
                    //Cor Promedio RP S2
                    if((number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->promedio_rp_s2, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->promedio_rp_s2, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->promedio_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                }
                
                if($indicadores->tipo == 'Minimo')
                {
                    $html .= '<td>' . $indicadores->minimo_plan_anual. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->minimo_real_anual. ' ' .$formato .'</td>';
                    
                    //Cor Minimo RP Anual
                    if((number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_anual, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->minimo_rp_anual, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->minimo_plan_s1. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->minimo_real_s1. ' ' .$formato .'</td>';
                    
                    //Cor Minimo RP S1
                    if((number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s1, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->minimo_rp_s1, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->minimo_plan_s2. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->minimo_real_s2. ' ' .$formato .'</td>';
                    
                    //Cor Minimo RP S2
                    if((number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->minimo_rp_s2, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->minimo_rp_s2, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->minimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                }
                
                if($indicadores->tipo == 'Maximo')
                {
                    $html .= '<td>' . $indicadores->maximo_plan_anual. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->maximo_real_anual. ' ' .$formato .'</td>';
                    
                    //Cor Maximo RP Anual
                    if((number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_anual, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->maximo_rp_anual, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_anual, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->maximo_plan_s1. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->maximo_real_s1. ' ' .$formato .'</td>';
                    
                    //Cor Maximo RP S1
                    if((number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s1, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->maximo_rp_s1, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->maximo_plan_s2. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->maximo_real_s2. ' ' .$formato .'</td>';
                    
                    //Cor Maximo RP S2
                    if((number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->maximo_rp_s2, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->maximo_rp_s2, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->maximo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                }
                
                if($indicadores->tipo == 'Ultimo')
                {
                    $html .= '<td>' . $indicadores->ultimo_plan_anual. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->ultimo_real_anual. ' ' .$formato .'</td>';
                    
                    //Cor Ultimo RP Anual
                    if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_anual, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_anual, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_anual, 2, '.', '') . ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->ultimo_plan_s1. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->ultimo_real_s1. ' ' .$formato .'</td>';
                    
                    //Cor Ultimo RP S1
                    if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s1, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_s1, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_s1, 2, '.', ''). ' ' .$porcento.'</td>';
                    $html .= '<td>' . $indicadores->ultimo_plan_s2. ' ' .$formato .'</td>';
                    $html .= '<td class="cinza">' . $indicadores->ultimo_real_s2. ' ' .$formato .'</td>';
                    
                    //Cor Ultimo RP S2
                    if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '0.00') || (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '59.99'))
                    {
                        $cCor = 'Vermelho';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '60.00') && (number_format($indicadores->ultimo_rp_s2, 2, '.', '') <= '79.99'))
                    {
                        $cCor = 'Amarelo';
                    }
                    
                    if((number_format($indicadores->ultimo_rp_s2, 2, '.', '') >= '80.00'))
                    {
                        $cCor = 'Verde';
                    }
                    
                    $html .= '<td class="'.$cCor.'">' . number_format($indicadores->ultimo_rp_s2, 2, '.', ''). ' ' .$porcento.'</td>';
                }
            }
            
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            
            $aDados[$i]['nome'] = $pais['nombre'];
            $aDados[$i]['id']   = $pais['id'];
            $aDados[$i]['select'] = $html;
            
        }
        
        echo json_encode(array("results" => $aDados));
    }
    
}