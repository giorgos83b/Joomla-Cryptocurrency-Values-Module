<?php
ini_set('display_errors',false);

/**
 * default class for Crypto Values module
 *
 * @package    Crypto.Values
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_cryptovalues is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die; ?>

<?php

$crypto_pairs = $user_options['crypto_pairs'];
$live_updates = $user_options['live_updates'];

$fr_link = '<a target="_blank" style="font-size:0.5em" href="https://s2p.me/cryptovalues">CryptoValues Module by s2p.me</a>';

?>


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/gdi2290/angular-websocket@v1.0.9/angular-websocket.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
  <style> 
.changed {
  
  background-color: white;
  animation-name: example;
  animation-duration: 2s;
}

@keyframes example {
  from {background-color: red;}
  to {background-color: white;}
}
</style>

<div ng-app="CryptoValues" ng-controller="CV_Ctrl">
  <p>{{msg}}</p>
<div class="table-responsive">          
  <table class="table">
  <thead>
  <tr>
  <th>Product</th>
  <th>Side</th>
  <th>Price</th>
  <th>Vol 24h</th>
  
  </tr>
  </thead>
  <tbody>
  <tr class="{{ x.cssclass }}" ng-repeat="x in products">
    <td>{{ x.product_id }}</td>
    <td>{{ x.side }}</td>
	<td>{{ x.price | number:2 }}</td>
    <td>{{ x.volume_24h | number:0 }}</td>
  </tr>
  </tbody>
</table>


<script type="text/javascript">
	'use strict';

	let userinput = "<?=$crypto_pairs?>";
	let live_updates = "<?=$live_updates?>";
	
	var app = angular.module('CryptoValues', ['angular-websocket']);

	const pairs = userinput.split(",");


	app.controller('CV_Ctrl', function ($scope, $websocket) {
		var ws = $websocket('wss://ws-feed.exchange.coinbase.com');
		let countPairs = 0;
		
		$scope.products = {};
		$scope.msg = "Connecting...";	
		$scope.data = '{"type":"subscribe","product_ids":'+JSON.stringify(pairs)+',"channels":[{"name":"ticker","product_ids":'+JSON.stringify(pairs)+'}]}';

		ws.send($scope.data); 

		ws.onOpen(function(message) {
			console.log('connection opened with cb server');
		});
		
		ws.onClose(function(message) {
			console.log('connection with cb server closed');
		});
		
		ws.onMessage(function(message) {
			$scope.msg = "";
			//console.log(message.data);
		
			var tableData = JSON.parse(message.data);
			
			if(tableData.product_id !== undefined)
			{
				if(!$scope.products[tableData.product_id]){
					countPairs++;
				}
				
				
				$scope.products[tableData.product_id] = tableData;

			
				angular.forEach($scope.products, function(value, key){
					 value.cssclass = "";
				});
				
				$scope.products[tableData.product_id].cssclass = "changed";
				
				//close connection after receiving initial prices if required

				if(live_updates == "0" && countPairs == pairs.length){
				  ws.close();
				}	

			}		
			
		  });
		  
	  
	  
	  


	});

</script>

<? $user_options['display_link'] ? ($fr_link) :'' ?>
