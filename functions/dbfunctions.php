<?php


include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');

      function dbconnect()
      {
        global $HOSTNAME, $PORT, $DATABASE_NAME;
        $smongo = new Mongo("mongodb://$HOSTNAME:$PORT");
        $sdb = $smongo->selectDB($DATABASE_NAME);
	return $sdb;
      }
//Updating a field in collection and inserting if nothing there
	function updateColl($collection,$arr,$ary)
      {
	$sdb = dbconnect();
        $coll = new MongoCollection($sdb,$collection);
        $data = $coll->update($arr,$ary,array('upsert'=>true));
        return $data;
      }

      // To save the array into the collection 
      function SaveCollection($collection,$arr)
      {
	$sdb = dbconnect();
        $coll = new MongoCollection($sdb,$collection);
        $data = $coll->insert($arr);
        return $data;
      }
      // To find the one array from the collection
      function FindOneInCollection($coll,$arr)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $result = $collection->findOne($arr);
        return $result;
      }
      // To Check Username or Email etc
      function CheckField($coll,$field,$value)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
	$checkarray = array($field => $value);
        $CheckResult = $collection->findOne($checkarray);
        return $CheckResult;
      }

      function FindAllInCollection($coll)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $cursor = $collection->find();
        $array = iterator_to_array($cursor);
        return $array;
      
      }

      function FindAllAndFilter($coll,$arr)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $cursor = $collection->find(array(),$arr);
        $array = iterator_to_array($cursor);
        return $array;
      
      }

     
      
      function FindInCollection($coll,$arr)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $cursor = $collection->find($arr);
        $array = iterator_to_array($cursor);
        return $array;
        
      }

      

      function FindInCollectionSortDesc($coll,$arr,$sort_arr)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $cursor = $collection->find($arr)->sort($sort_arr);
        $array = iterator_to_array($cursor);
        return $array;
      }
      
      function UpdateCollection($coll,$set_arr,$where_arr)
      {
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $result =$collection->update($where_arr,$set_arr);
        return $result;
      }

      function RemoveInColletionById($coll,$id){
	$sdb = dbconnect();
        $collection = new MongoCollection($sdb,$coll);
        $result =$collection->remove(array('_id' => new MongoId($id)),true);
        return $result;
      }

	function checkSession()
	{
	session_start();
	if(isset($_SESSION['uid']))
		return true;
	else
		return false;
	}
 
	function redirect()
{
	header("location: /socialschoomin/login.php");	
}

/*
      function PaginateCollection($coll,$find,$sort,$itemsPerPage,$currentPage, $viewAll)
      {

        $pagination = new MongoPagination($sdb);
        if( $viewAll )
          $pagination->setQuery(array(
               '#collection' =>  $coll,
               '#sort'=>$sort,), $currentPage, $itemsPerPage);
        else
          $pagination->setQuery(array(
             '#collection' =>  $coll,
             '#find' => $find,'#sort'=>$sort,), $currentPage, $itemsPerPage);

        $dataSet= $pagination->Paginate();

        return $dataSet;

      }

*/
  

// CMS DB Functions and Queries

   

?>
