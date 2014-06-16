<?php
if (!extension_loaded('svm')) die('svm extension needed');
class EasyPhpSvm extends SVM{
    /**
     * debug:if debug mode is opening
     */
    public $debug=false;
    
    /**
     * cRange: the grid search range of cost parameter
     */
    private $cRange=array(-7,7,2);
    
    /**
     * gRange: the grid search range of gamma parameter
     */
    private $gRange=array(-7,7,2);
    
    /**
     * cMinStep: the accurate searching step of cost parameter
     */
    private $cMinStep=0.5;
    
    /**
     * gMinStep: the accurate searching step of gamma parameter
     */
    private $gMinStep=0.5;
    
    /**
     * set step range of cost parameter
     * 
     * start,end
     * step:the step length will take in rough searching
     * minStep:the step length will take in accurate searching
     */
    public function setcRange($start,$end,$step,$minStep){
        $this->cRange=array($start,$end,$step);
        $this->cMinStep=$minStep;
    }
    
    /**
     * set step range of gamma parameter
     * start,end
     * step:the step length will take in rough searching
     * minStep:the step length will take in accurate searching
     */
    public function setgRange($start,$end,$step,$minStep){
        $this->gRange=array($start,$end,$step);
        $this->gMinStep=$minStep;
    }
    
    /**
     * search best c,g parameter using gridSearch and cross validate
     * 
     * data:data set for crossvalidate,could be a svm light file url or an array like this array(-1,1=>0.87263,2=>9767,3=>0,4=>1)
     */
    public function gridSearchCG($data){
        if(count($this->cRange)!=3 || count($this->gRange)!=3){
            die("error crange array or grange array");
        }
        $bestAccuracy=0;
        $bestCost=0;
        $bestGamma=0;
        $this->debug("starting rough searching");
        //rough searching
        for($c=$this->cRange[0];$c<=$this->cRange[1];$c+=$this->cRange[2]){//cost from $this->cRange[0] to $this->cRange[1] by step $this->cRange[2]
            for($g=$this->gRange[0];$g<=$this->gRange[1];$g+=$this->gRange[2]){//the same way like cost
                $this->setOptions(array(SVM::OPT_C=>pow(2,$c),SVM::OPT_GAMMA=>pow(2,$g)));
                $accuracy=$this->crossvalidate($data, 5);
                if($accuracy-$bestAccuracy>0.001){//if accuracy got 0.1% better,thought it's an improvement
                    $bestAccuracy=$accuracy;
                    $bestCost=$c;
                    $bestGamma=$g;
                }elseif($accuracy-$bestAccuracy>0 && $c<$bestCost){// got a better result but less than 0.1%,then if c<bestCoust do things below
                    $bestAccuracy=$accuracy;
                    $bestCost=$c;
                    $bestGamma=$g;
                }
                $this->debug("now c:".pow(2,$c)." new g:".pow(2,$g)."now accuracy:".$accuracy.";bestC:".pow(2,$bestCost)." bestG:".pow(2,$bestGamma)." bestAccuracy:".$bestAccuracy);
            }
        }
        $this->debug("starting accurate searching");
        //accurate searching
        $cMingRange=array($bestCost-$this->cRange[2],$bestCost+$this->cRange[2],$this->cMinStep);
        $gMingRange=array($bestGamma-$this->gRange[2],$bestGamma+$this->gRange[2],$this->gMinStep);
        for($c=$cMingRange[0];$c<=$cMingRange[1];$c+=$cMingRange[2]){//the same way like cost
            for($g=$gMingRange[0];$g<=$gMingRange[1];$g+=$gMingRange[2]){//the same way like cost
                $this->setOptions(array(SVM::OPT_C=>pow(2,$c),SVM::OPT_GAMMA=>pow(2,$g)));
                $accuracy=$this->crossvalidate($data, 5);
                if($accuracy-$bestAccuracy>0.001){//if accuracy got 0.1% better,thought it's an improvement
                    $bestAccuracy=$accuracy;
                    $bestCost=$c;
                    $bestGamma=$g;
                }elseif($accuracy-$bestAccuracy>0 && $c<$bestCost){
                    $bestAccuracy=$accuracy;
                    $bestCost=$c;
                    $bestGamma=$g;
                }
                $this->debug("now c:".pow(2,$c)." new g:".pow(2,$g)."now accuracy:".$accuracy.";bestC:".pow(2,$bestCost)." bestG:".pow(2,$bestGamma)." bestAccuracy:".$bestAccuracy);
            }
        }
        
        return array("c"=>pow(2,$bestCost),"g"=>pow(2,$bestGamma));
    }
    
    /**
     * read svm light file data sets into an array data
     * 
     */
    public function fileData2Array($datafile){
        if(!file_exists($datafile)) die('no datafile founded in file system!');
        $filelines=file($datafile);
        $dataArray=array();
        foreach($filelines as $line){
            $tmpArray=array();
            $fields=explode(" ",$line);
            foreach($fields as $field){
                $value=explode(":",$field);
                if(count($value)==1){
                    $tmpArray[]=intval($value[0]);
                }elseif(count($value)==2){
                    $tmpArray[intval($value[0])]=(double) $value[1];
                }
            }
            $dataArray[]=$tmpArray;
            //print_r($dataArray);exit;
            //exit;
        }
        //print_r($dataArray);exit;
        return $dataArray;
    }
    
    private function debug($msg){
        if($this->debug) echo $msg.'<br/>'."\n";
    }
}