<?php 
namespace App\Controllers;

use Core\BaseController;
use Core\Container;

class TInformeController extends BaseController
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
        $this->setPageTitle('Informe Trimestre');
        $model = Container::getModel("TInforme");
        
        //Busca Anos
        $this->view->ano = $model->ListAnos();
        
        //Todos Paises
   //     $paises = $this->Paises();
        
     //   unset($paises[15]);
        
  //      $this->view->paises = $paises;
        
        /* Render View Paises */
        $this->renderView('tinforme/index', 'layout');
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
    
    public function CarregaPais($idplanificacion)
    {
        //Buscar Paises Planificados neste ano
        $id = (array) $idplanificacion;
        $id = $id['id'];
        
        $model = Container::getModel("TInforme");
        
        //Busca paises
        $result = $model->BuscaPaises($id);
        
        $html .= '<div class="col-lg-3">';
        $html .= '<div class="form-group">';
        $html .= '<label for="paises">Pais</label>';
        $html .= '<select  id="pais" class="form-control" >';
        $html .= '<option value="0">-- SELECCIONE --</option>';
        foreach ($result as $pais)
        {
            $aRet = $this->GetPais($pais->id_pais);
            $html.= '<option value="'.$aRet['id'].'">'.$aRet['nombre'].'</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo ($html);
    }
    
    public function CarregaSede($aDados)
    {
        //Buscar Paises Planificados neste ano
        $aDados         = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion'];
        $id              = $aDados['idPais'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Sedes
        $result = $model->BuscaSedes($idplanificacion, $id);
        
        $html .= '<div class="col-lg-3">';
        $html .= '<div class="form-group">';
        $html .= '<label for="sedes">Sede</label>';
        $html .= '<select  id="sedes" class="form-control" >';
        $html .= '<option value="0">-- SELECCIONE --</option>';
        foreach ($result as $sede)
        {
            $aRet = $this->GetSede($sede->id_sede);
            $html.= '<option value="'.$aRet[0]['id'].'">'.$aRet[0]['nombre'].'</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo ($html);
    }
    
    public function CarregaIndicadores($aDados)
    {
        //Buscar Paises Planificados neste ano
        $aDados         = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion'];
        $pais            = $aDados['idPais'];
        $sede            = $aDados['idSede'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Indicadores
        $result = $model->BuscaIndicadores($idplanificacion, $pais, $sede);
        
        $html .= '<div class="col-lg-6">';
        $html .= '<div class="form-group">';
        $html .= '<label for="indicadores">Indicadores</label>';
        $html .= '<select  id="indicador" class="form-control" >';
        $html .= '<option value="0">-- SELECCIONE --</option>';
        foreach ($result as $indicadores)
        {
            $html.= '<option value="'.$indicadores->id_indicador.'">'.$indicadores->indicador.'</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo ($html);
    }
    
    public function CarregaTrimestre($aDados)
    {
        //Buscar Paises Planificados neste ano
        $aDados         = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion'];
        $pais            = $aDados['idPais'];
        $sede            = $aDados['idSede'];
        $indicador       = $aDados['idIndicador'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Trismestre
        $result = $model->BuscaTrimestre($idplanificacion, $pais, $sede, $indicador);
        
        //Dados para o Chart Plan
        $T1Plan = $result[0]->enero_plan + $result[0]->febrero_plan + $result[0]->marzo_plan;
        $T2Plan = $result[0]->abril_plan + $result[0]->mayo_plan    + $result[0]->junio_plan;
        $T3Plan = $result[0]->julio_plan + $result[0]->agosto_plan  + $result[0]->septiembre_plan;
        $T4Plan = $result[0]->octubre_plan + $result[0]->noviembre_plan + $result[0]->diciembre_plan;
        
        //Dados para o Chart Real
        $T1Real = $result[0]->enero_real + $result[0]->febrero_real + $result[0]->marzo_real;
        $T2Real = $result[0]->abril_real + $result[0]->mayo_real + $result[0]->junio_real;
        $T3Real = $result[0]->julio_real + $result[0]->agosto_real + $result[0]->septiembre_real;
        $T4Real = $result[0]->octubre_real + $result[0]->noviembre_real + $result[0]->diciembre_real;
        
        //tudo junto pq deu merda separado
        echo json_encode(array("plan" => $T1Plan . ',' . $T2Plan . ',' . $T3Plan. ',' . $T4Plan . ',' . $T1Real. ',' . $T2Real. ',' . $T3Real. ',' . $T4Real));
      //  echo json_encode(array("real" => $T1Real. ',' . $T2Real. ',' . $T3Real. ',' . $T4Real));
    }
    
    public function mensual()
    {
        $this->setPageTitle('Informe Mensual');
        $model = Container::getModel("TInforme");
        
        //Busca Anos
        $this->view->ano = $model->ListAnos();
        
        /* Render View Paises */
        $this->renderView('tinforme/mensual', 'layout');
    }
    
    public function CarregaMensal($aDados)
    {
        //Buscar Paises Planificados neste ano
        $aDados         = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion'];
        $pais            = $aDados['idPais'];
        $sede            = $aDados['idSede'];
        $indicador       = $aDados['idIndicador'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Trismestre
        $result = $model->BuscaMensual($idplanificacion, $pais, $sede, $indicador);
        
        //Dados para o Chart Plan
        $EneroPlan      = $result[0]->enero_plan;
        $FebreroPlan    = $result[0]->febrero_plan;
        $MarzoPlan      = $result[0]->marzo_plan;
        $AbrilPlan      = $result[0]->abril_plan;
        $MayoPlan       = $result[0]->mayo_plan;
        $JunioPlan      = $result[0]->junio_plan;
        $JulioPlan      = $result[0]->julio_plan;
        $AgostoPlan     = $result[0]->agosto_plan;
        $SeptiembrePlan = $result[0]->septiembre_plan;
        $OctubrePlan    = $result[0]->octubre_plan;
        $NoviembrePlan  = $result[0]->noviembre_plan;
        $DiciembrePlan  = $result[0]->diciembre_plan;
        
        //Dados para o Chart Real
        $EneroReal      = $result[0]->enero_real;
        $FebreroReal    = $result[0]->febrero_real;
        $MarzoReal      = $result[0]->marzo_real;
        $AbrilReal      = $result[0]->abril_real;
        $MayoReal       = $result[0]->mayo_real;
        $JunioReal      = $result[0]->junio_real;
        $JulioReal      = $result[0]->julio_real;
        $AgostoReal     = $result[0]->agosto_real;
        $SeptiembreReal = $result[0]->septiembre_real;
        $OctubreReal    = $result[0]->octubre_real;
        $NoviembreReal  = $result[0]->noviembre_real;
        $DiciembreReal  = $result[0]->diciembre_real;
        
        //tudo junto pq deu merda separado
        echo json_encode(array("plan" => $EneroPlan. ',' . $FebreroPlan. ',' . $MarzoPlan. ',' . $AbrilPlan. ',' . $MayoPlan. ',' . $JunioPlan. ',' . $JulioPlan. ',' . $AgostoPlan. ',' .$SeptiembrePlan. ',' .$OctubrePlan. ',' .$NoviembrePlan. ',' .$DiciembrePlan. ',' .
            $EneroReal. ',' .$FebreroReal. ',' .$MarzoReal. ',' .$AbrilReal. ',' .$MayoReal. ',' .$JunioReal. ',' .$JulioReal. ',' .$AgostoReal. ',' .$SeptiembreReal. ',' .$OctubreReal. ',' .$NoviembreReal. ',' .$DiciembreReal));
    }
    
    public function anual()
    {
        $this->setPageTitle('Informe Anual');
        $model = Container::getModel("TInforme");
        
        //Busca Anos
        $this->view->ano = $model->ListAnos();
        
        /* Render View Paises */
        $this->renderView('tinforme/anual', 'layout');
    }
    
    public function CarregaAnual($aDados)
    {
        //Buscar Paises Planificados neste ano
        $aDados         = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion'];
        $pais            = $aDados['idPais'];
        $sede            = $aDados['idSede'];
        $indicador       = $aDados['idIndicador'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Anual
        $result = $model->BuscaAnual($idplanificacion, $pais, $sede, $indicador);
        
        //Dados para o Chart Plan
        $aPlanificado = $result[0]->enero_plan + $result[0]->febrero_plan + $result[0]->marzo_plan + $result[0]->abril_plan + $result[0]->mayo_plan + 
                        $result[0]->junio_plan + $result[0]->julio_plan + $result[0]->agosto_plan  + $result[0]->septiembre_plan + $result[0]->octubre_plan +
                        $result[0]->noviembre_plan + $result[0]->diciembre_plan;
                
        //Dados para o Chart Real
        $aRealizado  = $result[0]->enero_real + $result[0]->febrero_real + $result[0]->marzo_real + $result[0]->abril_real + $result[0]->mayo_real + 
                       $result[0]->junio_real + $result[0]->julio_real + $result[0]->agosto_real + $result[0]->septiembre_real + $result[0]->octubre_real + 
                       $result[0]->noviembre_real + $result[0]->diciembre_real;
               
        //tudo junto pq deu merda separado
        echo json_encode(array("plan" => $aPlanificado. ',' . $aRealizado));
    }
    
    public function Monitoreo()
    {
        $this->setPageTitle('Monitoreo KPIs');
        $model = Container::getModel("TInforme");
        
        //Busca Anos
        $this->view->ano = $model->ListAnos();
        
        /* Render View Paises */
        $this->renderView('tinforme/monitoreo', 'layout');
    }
    
    public function BuscaValoresMonitoreo($aDados)
    {
        $aDados = (array) $aDados;
        
        $idplanificacion = $aDados['cplanificacion']; // sei qual planificacao pelo ano
        $pais            = $aDados['idPais'];
        $sede            = $aDados['idSede'];
        
        $model = Container::getModel("TInforme");
        
        //Busca Anual
        $result = $model->BuscaMonitoreo($idplanificacion, $pais, $sede);
        
        for($i=0; $i < count($result); $i++)
        {
            $result[$i] = (array) $result[$i];
            
            $this->view->indicadores[$i] = (object) $result[$i];
        }
        
        //Montar Grid Monitoreo KPIs
        $html  = '';
        $html .= '<div  class="wrapper wrapper-content animated fadeInRight">';
        $html .= '<div class="row">';
        $html .= '<div class="col-lg-12">';
        $html .= '<div class="ibox float-e-margins">';
        $html .= '<div class="ibox-title">';
        $html .= '<h5>Monitoreo de KPIs</h5>';
        $html .= '</div>';
        $html .= '<div class="ibox-content">';
        $html .= '<table class="table table-striped table-bordered table-hover dataTables-example" >';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Indicador</th>';
        $html .= '<th>Tipo</th>';
        $html .= '<th>Plan Anual</th>';
        $html .= '<th>Real Anual </th>';
        $html .= '<th>% (R/P) Anual</th>';
        $html .= '<th>Plan T1</th>';
        $html .= '<th>Real T1</th>';
        $html .= '<th>% (R/P) T1</th>';
        $html .= '<th>Plan T2</th>';
        $html .= '<th>Real T2</th>';
        $html .= '<th>% (R/P) T2</th>';
        $html .= '<th>Plan T3</th>';
        $html .= '<th>Real T3</th>';
        $html .= '<th>% (R/P) T3</th>';
        $html .= '<th>Plan T4</th>';
        $html .= '<th>Real T4</th>';
        $html .= '<th>% (R/P) T4</th>';
        $html .= '<th>Plan S1</th>';
        $html .= '<th>Real S1</th>';
        $html .= '<th>% (R/P) S1</th>';
        $html .= '<th>Plan S2</th>';
        $html .= '<th>Real S2</th>';
        $html .= '<th>% (R/P) S2</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($this->view->indicadores as $indicadores)
        {
            $formato = $indicadores->formato;
            $porcento = ' % ';
            
            if($formato == '#')
            {
                $formato = '&#160;';
            }
            
            $html .= '<tr class="gradeX">';
            $html .= '<td>' . $indicadores->indicador . '</td>';
            $html .= '<td>' . $indicadores->tipo. '</td>';
            
            if($indicadores->tipo == 'Acumulado')
            {
                $html .= '<td>' . $indicadores->acumulado_plan_anual . ' ' . $formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_anual. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_t1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_t2. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_t3. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_t4. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_s1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->acumulado_plan_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_real_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->acumulado_rp_s2. ' ' .$porcento.'</td>';
                
            }
            
            if($indicadores->tipo == 'Promedio')
            {
                $html .= '<td>' . $indicadores->promedio_plan_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_anual. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_t1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_t2. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_t3. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_t4. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_s1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->promedio_plan_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_real_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->promedio_rp_s2. ' ' .$porcento.'</td>';
            }
            
            if($indicadores->tipo == 'Minimo')
            {
                $html .= '<td>' . $indicadores->minimo_plan_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_anual. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_t1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_t2. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_t3. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_t4. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_s1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->minimo_plan_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_real_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->minimo_rp_s2. ' ' .$porcento.'</td>';
            }
            
            if($indicadores->tipo == 'Maximo')
            {
                $html .= '<td>' . $indicadores->maximo_plan_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_anual. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_t1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_t2. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_t3. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_t4. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_s1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->maximo_plan_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_real_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->maximo_rp_s2. ' ' .$porcento.'</td>';
            }
            
            if($indicadores->tipo == 'Ultimo')
            {
                $html .= '<td>' . $indicadores->ultimo_plan_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_anual. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_anual. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_t1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_t1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_t2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_t2. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_t3. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_t3. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_t4. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_t4. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_s1. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_s1. ' ' .$porcento.'</td>';
                $html .= '<td>' . $indicadores->ultimo_plan_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_real_s2. ' ' .$formato .'</td>';
                $html .= '<td>' . $indicadores->ultimo_rp_s2. ' ' .$porcento.'</td>';
            }
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
               
        echo ($html);
    }
    
}