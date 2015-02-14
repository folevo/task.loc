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
    public function indexAction()
    {
        $adapter = new Adapter( array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=task;host=localhost',
                'username' => 'root',
                'password' => '',
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',

            )
        );
        $stmt = $adapter->createStatement('SELECT  id,city FROM city ');
        $results = $stmt->execute();
        $city="[";
        foreach($results as $result){
            $city.=Json::encode($result).",";

        }

        $city=substr($city, 0,-1);

        $city.="]";
        $stmt = $adapter->createStatement('SELECT  view_education FROM education ');
        $results = $stmt->execute();
        $view_education="[";
        foreach($results as $result){
            $view_education.=Json::encode($result).",";

        }

        $view_education=substr($view_education, 0,-1);

        $view_education.="]";
        return new ViewModel(['view_education'=>$view_education,'city'=>$city]);
    }

    public function dataAction()
    {

        $adapter = new Adapter( array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=task;host=localhost',
                'username' => 'root',
                'password' => '',
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',

            )
        );

        $request = $this->getRequest();

        //Получаем данные

        $start = $request->getPost('start') ? : 0;
        $limit = $request->getPost('limit') ? : 5;
        if($request->getPost('city') &&  !$request->getPost('name') && !$request->getPost('view_education')){
            $where="WHERE city = '".$request->getPost('city')."' " ;
        }elseif(!$request->getPost('city') &&  $request->getPost('name') && !$request->getPost('view_education')){
            $where="WHERE name = '".$request->getPost('name')."' " ;
        }elseif(!$request->getPost('city') &&  !$request->getPost('name') && $request->getPost('view_education')){
            $where="WHERE view_education = '".$request->getPost('view_education')."' " ;
        }elseif($request->getPost('city') &&  !$request->getPost('name') && $request->getPost('view_education')){
            $where="WHERE city = '".$request->getPost('city')."' AND view_education='".$request->getPost('view_education')."' " ;
        }elseif($request->getPost('city') &&  $request->getPost('name') && !$request->getPost('view_education')){
            $where="WHERE city = '".$request->getPost('city')."' AND name='".$request->getPost('name')."' " ;
        }elseif(!$request->getPost('city') &&  $request->getPost('name') && $request->getPost('view_education')){
            $where="WHERE view_education = '".$request->getPost('view_education')."' AND name='".$request->getPost('name')."' " ;
        }elseif($request->getPost('city') &&  $request->getPost('name') && $request->getPost('view_education')){
            $where="WHERE view_education = '".$request->getPost('view_education')."' AND name='".$request->getPost('name')."' AND city='".$request->getPost('city')."' " ;
        }else{
            $where="";
        }

        if($sort=$request->getPost('sort')){
            $sort=Json::decode(rtrim(ltrim($request->getPost('sort'),'['),']'),Json::TYPE_ARRAY);
            $sort="ORDER BY ".$sort['property']." ".$sort['direction'];
        }else{
            $sort='';
        }
        $offset = ' LIMIT '.$start.','.$limit;

        $stmt = $adapter->createStatement("SELECT  name,view_education,city,id as user_id  FROM userview  ".$where."GROUP BY id ".$sort." ".$offset);
        $results = $stmt->execute();
       $count=$adapter->createStatement('SELECT  COUNT(*) as total FROM userview')->execute();
        foreach($count as $ct){
            $total=$ct;
        }



        $data="[";
        foreach($results as $result){
            $data.=Json::encode($result).",";

        }

        $data=substr($data, 0,-1);
        $data.=",".Json::encode($total);
        $data.="]";
        $result = new JsonModel(array(
            'data' => $data,
            'success'=>true,
        ));

        return $result;
    }
    public function updateAction(){

        $adapter = new Adapter( array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=task;host=localhost',
                'username' => 'root',
                'password' => '',
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',

            )
        );
        $sql = "UPDATE userview

        SET  view_education=:view_education

        WHERE id=:id";





        $stmt = $adapter->createStatement($sql);
        $stmt->execute([":id"=>$_POST['id'],":view_education"=>$_POST['view_education']]);
        $response = $this->getResponse();
        return $response;
    }
    public function cityAction(){
        $adapter = new Adapter( array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=task;host=localhost',
                'username' => 'root',
                'password' => '',
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',

            )
        );
        $stmt = $adapter->createStatement('SELECT  id,city FROM city ');
        $results = $stmt->execute();
        $city="[";
        foreach($results as $result){
            $city.=Json::encode($result).",";

        }

        $city=substr($city, 0,-1);

        $city.="]";


        $result = new JsonModel(array(
            'city' => $city,
            'success'=>true,
        ));
        return $result;
    }

}
