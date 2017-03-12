<?php
namespace Task\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Json\Json;
use \PDO;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    public $adapter;
    public function indexAction()
    {
        //test
        //получение объекта адптер
        $adapter = $this->getAdapter();
        //создание запроса к базе данных
        $stmt = $adapter->createStatement('SELECT  id,city FROM city ');
        //получение городов из баз даных и преобрпзование Json формат для combobox
        $results = $stmt->execute();
        $city=array();
        foreach($results as $result){
            $city[]=$result;

        }

        $city=Json::encode($city);
        //создание запроса к базе данных
        $stmt = $adapter->createStatement('SELECT  view_education FROM education ');
        //получение ученных степеней из баз даных и преобрпзование Json формат для combobox
        $results = $stmt->execute();
        $view_education=array();
        foreach($results as $result){
            $view_education[]=$result;

        }

        $view_education=Json::encode($view_education);
        //ответ на запрос
        return new ViewModel(['view_education'=>$view_education,'city'=>$city]);
    }
//метод возращающий данные в формате JSON
    public function dataAction()
    {
        //получение объекта адаптера
        $adapter = $this->getAdapter();
        //полученние объекта request
        $request = $this->getRequest();

        //Получение  данных в зависимости от парматеров POST

        $start = $request->getPost('start') ? : 0;
        $limit = $request->getPost('limit') ? : 5;
        $where="";
        $where = ($request->getPost('city') && !$request->getPost('view_education')) ?  $where.="WHERE city = '".$request->getPost('city')."' " :$where.= "";
        $where = ($request->getPost('view_education') && !$request->getPost('city')) ?   $where.="WHERE view_education = '".$request->getPost('view_education')."' " : $where.= "";
        $where = ($request->getPost('view_education') && $request->getPost('city')) ?   $where.="WHERE city = '".$request->getPost('city')."' AND view_education='".$request->getPost('view_education')."' " :  $where.="";

//сортировка данных в зависмостри от параметра sort
        if($sort=$request->getPost('sort')){
            $sort=Json::decode(rtrim(ltrim($request->getPost('sort'),'['),']'),Json::TYPE_ARRAY);
            $sort="ORDER BY ".$sort['property']." ".$sort['direction'];
        }else{
            $sort='';
        }
       //задание лимита выбираемых данных
        $offset = ' LIMIT '.$start.','.$limit;
        //получение данных
        $stmt = $adapter->createStatement("SELECT  name,view_education,city,id as user_id  FROM userview  ".$where."GROUP BY id ".$sort." ".$offset);
        $results = $stmt->execute();
        //подсчет количества строк в таблицы для передачи в грид
       $count=$adapter->createStatement('SELECT  COUNT(*) as total FROM userview')->execute();
        foreach($count as $ct){
            $total=$ct;
        }

//создане массива и преобразования в Json для передачи в грид

        $data=array();
        foreach($results as $result){
            $data[]=$result;

        }


        $data=Json::encode($data);
        $total=Json::encode($total);
        $result = new JsonModel(array(
            'data' => $data,
            'total'=>$total,
            'success'=>true,
        ));

        return $result;
    }
    public function updateAction(){
        //получение объекта адаптер для выполнения запроса на обновление
        $adapter = $this->getAdapter();
        $sql = "UPDATE userview

        SET  view_education=:view_education

        WHERE id=:id";



//создание запроса

        $stmt = $adapter->createStatement($sql);
        //выполение запроса на обновлеие
        $stmt->execute([":id"=>$_POST['id'],":view_education"=>$_POST['view_education']]);
        //получение объекта ответа
        $response = $this->getResponse();

        //возврат объекта ответа
        return $response;
    }
    //метод для получения объекта адаптер
    public function getAdapter()
    {
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }

}
