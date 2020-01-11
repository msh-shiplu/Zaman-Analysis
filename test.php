<?php
include_once('/var/www/html/simple_html_dom.php');
function do_your_job(){
        $html = file_get_html('http://dsebd.org');
        if(!$html){
                unset($html);
                exit(-1);
        }
        $last = '';
        foreach($html->find('div div div div div div') as $a){
                if(!$a->children(0)){
                        $a->clear();
                        unset($a);
                        continue;
                }
                if(trim($a->children(0)->plaintext) == "DSEX Index"){
                        //echo "DSEX Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
                        sscanf($a->children(1)->plaintext,"%lf",$dsex1);
                        sscanf($a->children(2)->plaintext,"%lf",$dsex2);
                }
                else if(trim($a->children(0)->plaintext) == "DSES Index"){
                        //echo "DSES Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
                        sscanf($a->children(1)->plaintext,"%lf",$dses1);
                        sscanf($a->children(2)->plaintext,"%lf",$dses2);
                }
                else if(trim($a->children(0)->plaintext) == "DS30 Index"){
                        //echo "DS30 Index: ".$a->children(1)->plaintext." ".$a->children(2)->plaintext."<br/>";
                        sscanf($a->children(1)->plaintext,"%lf",$dse301);
                        sscanf($a->children(2)->plaintext,"%lf",$dse302);
                }
                else if($last == "Total Trade"){
                        sscanf($a->children(2)->plaintext,"%d",$total_value);
                }
                else if($last == "Issues Advanced"){
                        sscanf($a->children(0)->plaintext,"%d",$issue_advanced);
                        sscanf($a->children(1)->plaintext,"%d",$issue_declined);
                        sscanf($a->children(2)->plaintext,"%d",$issue_unchanged);
                        $a->clear();
                        unset($a);
                        break;
                } 
                $last = trim($a->children(0)->plaintext);
                $a->clear();
                unset($a);
        }
        $html->clear();
        unset($html);
        unset($last);
	echo $dsex1.' '.$dsex2.' '.$dses1.' '.$dses2.' '.$dse301.' '.$dse302.' '.$total_value.' '.$issue_advanced.' '.$issue_declined.' '.$issue_unchanged.'\n';
}
do_your_job();
?>

