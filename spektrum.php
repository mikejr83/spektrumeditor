 <?php
//Handle AJAX update calls

//Handle AJAX update calls
if(isset($_GET['updateRecord'])){
	// $table=$_POST['table'];
	// $where=$_POST['where'];
	// $name=$_POST['name'];
	// $value=$_POST['value'];
	// $querytocall="UPDATE ".$table." SET ".$name."='".$value."' WHERE ".$where;
	// if ($DEBUG){
	   // echo ("QUERY TO CALL : ".$querytocall."<br>");
	   // die();
	// }
	die("UPDATE ".$table." SET ".$name."='".$value."' WHERE ".$where);
	// try{
		// $q=$DB->query($querytocall);
	// }catch(Exception $e){
	     // echo ("QUERY TO CALL : ".$querytocall."<br>");
		// die("Exception on UPDATE : ".$e);
	// }
	die("1");
}

if(isset($_GET['deleteRecord'])){
	// $table=$_POST['table'];
	// $where=$_POST['where'];
	// $querytocall="DELETE FROM `$table` WHERE $where";
	// if ($DEBUG){
	   // echo ("QUERY TO CALL : ".$querytocall."<br>");
	   // die();
	// }
	
	// try{
		// $q=$DB->query($querytocall);
	// }catch(Exception $e){
	    // echo ("QUERY TO CALL : ".$querytocall."<br>");
		// die("Exception on DELETE : ".$e);
	// }
	die('1');
}

if(isset($_POST['add'])){
	
	// $table=$_POST['table'];
	// $columnnames=$_POST['columnnames'];
	// $cols=explode(',',$columnnames);
	// array_shift($cols);
	// $columnnames=implode(',',$cols);
	// foreach($cols as $col){
	     // $values[]="''";
	// }

	// $values = implode(',',$values);
	// $querytocall = "INSERT INTO ".$table." (".$columnnames.")VALUES(".$values.")";
	// if ($DEBUG){
	   // echo ("QUERY TO CALL : ".$querytocall."<br>");
	   // die();
	// }
	// try{
	   // $DB->query($querytocall);
	// }catch(Exception $e){
		// echo ("QUERY TO CALL : ".$querytocall."<br>");
		// die("Exception on INSERT : ".$e);
	// }
	// header('location:?');
	die();
}



if(isset($_GET['saveFile'])){
	// $filename=$_POST['filename'];
	// $returnvalue=file_put_contents($filename.'xml',$resultstr);
	// if ($returnvalue===false)
	// {
		// echo "<script>alert('write failed');</script>";
	// } 
	// else 
	// {
		// echo "<script>alert('Bytes written : ".$returnvalue." to file ".$filename,"';";
	// }
	// var_dump($resultstr);
	die("1");
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Spektrum File Editor</title>
	<!--  Stylesheets -->
	<!-- Libraries -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!--  Stylesheets -->
	<link rel="stylesheet" href="/css/spektrum.css"> <!-- CSS reset -->
	<!--  Meta  -->
	<meta name="description" content="Spektrum File Editor!">
	<meta name="author" content="MIMS">
	<!-- favicons for the site  -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	<!--  Device viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!--  HTML5 for older browsers -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--  IE8 fixes -->
	<!--[if IE 8]>
	  <link rel="stylesheet" href="css/ie8.css">
	<![endif]-->
	<script>
		$(window).load(function() {
            $('.editableRow td[table]').each(function(i,e){
				e=$(e);
				e.attr('contenteditable',true);
				var oldValue;
				var focusFunction=function(){
					oldValue=$(this).html();
				};
				var blurFunction=function(ev,el){
					var input=el?el:$(this);
					var newValue=input.find('textarea').length?CKEDITOR.instances[input.find('textarea').attr('name')].getData():el?input.find('select').val():input.html();
					if(el)
						input.find('select option[value="-1"]').remove();
					
					if(oldValue!==newValue){
						oldValue=newValue;
						input.addClass('saving');
						$.ajax('?updateRecord',{
							type:"POST",
							data:{
								arrayname:input.attr('arrayname'),
								value:newValue
							}
						}).done(function(v){
							if(v!='1'){
								input.addClass('error').removeClass('saving');
								alert(v);
							}else{
								input.addClass('saved').removeClass('saving');
							}
							setTimeout(function(){
								input.removeClass('error').removeClass('saved');
							},1000);
						});
					}
				}
				var sel=e.find('select');
				var area=e.find('textarea');
				if(sel.length){
					sel.change(function(){
						oldValue=false;
						blurFunction(null,e);	
					});
				}else if(area.length){
					$('#cke_'+area.attr('name')+' iframe')[0].document.body.onblur=function(){
						alert('test');
					}
				}else{
					e.focus(focusFunction);
					e.blur(blurFunction);
				}
				
				
			});
			
			$('.buttonDel').click(function(){
				(function(input){
					$.ajax('?deleteRecord',{
								type:"POST",
								data:{
									table:input.attr('table_del'),
									where:input.attr('where_del')
								}
					});
					input.fadeOut('slow',function(){
						$(this).remove();
					});
				})($(this).parents('tr'));
			});
			
			$('.buttonSaveToArray').click(function(){
				(function(input){
					$.ajax('?SaveToArray',{
								type:"POST",
								data:{
									arrayname:input.attr('arrayname'),
									arrayvalue:input.attr('arrayvalue')
								}
					});
					input.fadeOut('slow',function(){
						$(this).remove();
					});
				})($(this).parents('tr'));
			});
			
			
        });
	</script>
  </head>
  <body>
   <?php
		$filename='350QX.SPM';
		
		/*
		$textfromfile = '<?xml version="1.0" encoding="ISO-8859-1"?><main> Generator="DX18" VCode=" 1.04D" PosIndex= 5 Type=Acro curveIndex= 7 </main>';
		*/
		
		$textfromfile = file_get_contents($filename, true);
	   
		$textfilearray = explode(chr(13), $textfromfile);
		
		$myNewArray=array();
		
		$parentElement='';
		$subElement='';
		
		
		echo "<h1>Spektrum File Editor</h1><br><br><br><br>";
		echo '<form method="post">';
		echo '	<input type="hidden" name="savetofile" value="1"/>';
		echo '	<input type="hidden" name="filename" value="'.$filename.'"/>';
		echo '	<input type="hidden" name="stringtosave" value="'.$myNewArray.'"/>';
		echo '	<input type="submit" value="Save File"/>';
		echo '</form>'; 
		
		
		
		foreach($textfilearray as $piece){
			$piece=trim($piece);
			if(empty($piece)) continue;
			
			$firstChar=substr($piece,0,1);
			if($parentElement=='' && $firstChar=='<'){
				$parentElement=$piece;
				$myNewArray[$parentElement]=array();
			}else if($firstChar=='<'){
				$parentElement='';
			}else if($subElement=='' && $firstChar=='['){
				$subElement=$piece;
				$myNewArray[$parentElement][$subElement]=array();
			}else if($firstChar=='['){
				$subElement='';
			}else if($firstChar=='*'){
				continue;
			}else{
				
				$explode=explode('=',$piece);
				if(count($explode)==1){
					$explode=explode(':',$piece);
					$explode[0].=':';
				}else{
					$explode[0].='=';
				}
				if(count($explode)==1){
					continue;
				}
				
						
				
				//print_r('<br>subelement : '.$subElement);
				
				$nameofvalue=$explode[0];
				$valueofname=$explode[1];
				
				//print_r('<br>name : '.$nameofvalue);
				//print_r(' | value : '.$valueofname);
				
				// if empty add normal without subelement else check to add to array
				if(!empty($subElement)){
					// check if set else create array
					if(isset($myNewArray[$parentElement][$subElement][$nameofvalue]))
					{
						// is the subElement an array? if so then ass value
						if(is_array($myNewArray[$parentElement][$subElement])){
							$myNewArray[$parentElement][$subElement][$nameofvalue]=$valueofname;
						}else{
							$myNewArray[$parentElement][$subElement] = array();
							$myNewArray[$parentElement][$subElement][$nameofvalue]=$valueofname;
						}
					}else{
						// create array with first value
						$myNewArray[$parentElement][$subElement][$nameofvalue]=$valueofname;
					}
				}else{
				    //add normal no subelement
					$myNewArray[$parentElement][$nameofvalue]=$valueofname;
				}
				
				
				
				
				// if(!empty($subElement)){
					// if(isset($myNewArray[$parentElement][$subElement][$explode[0]])){
						// if(is_array($myNewArray[$parentElement][$subElement][$explode[0]])){
							// $myNewArray[$parentElement][$subElement][$explode[0]]=$explode[1];
						// }else{
							// $myNewArray[$parentElement][$subElement][$explode[0]][]=array($myNewArray[$parentElement][$subElement][$explode[0]],$explode[1]);
						// }
					// }else{
						// $myNewArray[$parentElement][$subElement][$explode[0]][]=$explode[1];
					// }
				// }else{
					// $myNewArray[$parentElement][$explode[0]]=$explode[1];
				// }
			}
		}
		
		echo "<table class='contentprijzen' border=1>";
		foreach($myNewArray as $key => $value) {
		    //echo "<tr><td >".preg_replace('/<[\/]{0,1}\w[^>]*>/', '',$key)."</td></tr>";
			echo "<tr></tr>";
			echo "<tr><td><h2>".substr($key,1,-1)."</h2></td></tr>";
			echo "<tr></tr>";
			echo "<tr>";
			foreach($value as $key => $val) {
				echo "<td><h3>".preg_replace('/=/',"",$key)."</h3></td>";
				/* etc */
			}
			echo "</tr>";
			echo "<tr>";
			foreach($value as $key => $val) {
				echo "<td><div arrayname=".$key." contenteditable='true' value=".preg_replace('/"/',"",$val)." >".preg_replace('/"/',"",$val)."</div></td>"; 
				
				/* etc */
			}
			//echo'<td><input type="button" class="buttonSaveToArray" value="Save"/></td>';
			echo "</tr>";
		}
		echo "</table";
		echo "<br><br><br><br><br>";
		
		//print_r($myNewArray);die();
		
	?>
	
	<?php
		// try
		// {
			// $xml=simplexml_load_file("350QX.SPM.XML");
			
			// echo $xml->getName() . "<br>";
		
			// foreach($xml->children() as $child)
			  // {
			      // echo $child->getName() . ": " . $child . " : ";
				  // foreach($child->children() as $baby)
				  // {
					// echo $baby->getName() . ": " . $baby . " | ";
				  // } 
				  // echo"<br>";
			  // }
	
		// } catch (Exception $e) {
			// echo 'Caught exception: ',  $e->getMessage(), "\n";
		// }
	?>
	
	
	<script type="text/javascript">
		$(function(){
		   $("table.contentprijzen tr:even").addClass("d0");
		   $("table.contentprijzen tr:odd").addClass("d1");
		   $("table.contentprijzen td:even").addClass("d0");
		   $("table.contentprijzen td:odd").addClass("d1");
		});
	 </script>
  </body>
</html>

