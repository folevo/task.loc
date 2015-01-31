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

        return new ViewModel();
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


        if($sort=$request->getPost('sort')){
            $sort=Json::decode(rtrim(ltrim($request->getPost('sort'),'['),']'),Json::TYPE_ARRAY);
            $sort="ORDER BY ".$sort['property']." ".$sort['direction'];
        }else{
            $sort='';
        }
        $offset = ' LIMIT '.$start.','.$limit;

        $stmt = $adapter->createStatement('SELECT  name,view_education,city FROM userview '.$sort." ".$offset);
        $results = $stmt->execute();

        $data="[";
        foreach($results as $result){
            $data.=Json::encode($result).",";

        }
        $data=substr($data, 0,-1);
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
        $sql = "UPDATE education

        SET  view_education=?

        WHERE id=?";





        $stmt = $adapter->createStatement($sql);
        $stmt->execute([$_POST['id'],$_POST['view_education']]);
        $response = $this->getResponse();
        return $response;
    }

}
