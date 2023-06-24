<?php 
require_once './ripcord.php';
 
$odooUrl = 'https://demotest.odoo.com/';
$database  = 'odootest';
$username = 'admin';
$password = 'admin';
$api = 'your api';
 
//establish connexion to odoo api
$common = ripcord::client($odooUrl . 'xmlrpc/2/common');

// Authentication
$uid = $common->authenticate($database, $username, $api, []);

if (!empty($uid)) {
    
    echo '--- ';
    print_r($uid);
    echo ' ---';
    $client = ripcord::client($odooUrl);

    //searching product
    $model = 'product.product'; 
    $models= ripcord::client("{$odooUrl}xmlrpc/2/object");
     

    try {
         
        //total 
        $total= $models->execute_kw($database, $uid, $password, $model, 'search_count', [[['id', '>=', 0]]]);

        print_r($total);
        echo '<br>total found: '.$total.'<br>';
        
        //listing 
        $ids=$models->execute_kw($database, $uid, $password, $model, 'search', 
            [[['id', '>=',0]]]);
 
        print_r($ids); 

        $results= $models->execute_kw($database, $uid, $password, $model, 'read', [$ids], ['fields'=> ['id','name', 'description']]);
?>
<table> 
    <thead> 

        <tr> 
            <th>ID</th> 
            <th>Name</th>
            <th>Description</th> 
        </tr> 
    </thead>
    <tbody> 
   <?php    
        foreach ($results as $record) {
            echo '<tr><td>'.$record['id'].'</td>';
            echo '<td>'.$record['name'].'</td>'; 
            echo '<td>'.$record['description'].'</td></tr>'; 
        }  

    ?> 

</tbody> 
</table>
<?php 
    } catch (Exception $e) {
         
        echo "An error occured : " . $e->getMessage();
    }
} else {
    echo 'Failed to connect to Odoo api';
}
?>