<?php

$multa = 0; //% 
$juros = 0.00; //%
    
class Juros {
    
    private $valor;
    private $vencimento;
    private $multa;
    private $juros;
    
    private $valorTotal;
    private $jurosTotal;
    private $multaTotal;
    private $diasEmAberto;
    	
    public function __construct($valor,$vencimento,$multa,$juros){
		$this->valor      = $valor;
        $this->vencimento = $vencimento;
        $this->multa      = $multa;
        $this->juros      = $juros;
    }
	public function getValor()     { return $this->valor; }
    public function getVencimento(){ return $this->vencimento; }
    public function getMulta()     { return $this->multa; }
    public function getJuros()     { return $this->juros; }
    
    public function getValorTotal()     { return $this->valorTotal; }
    public function getJurosTotal()     { return $this->jurosTotal; }
    public function getMultaTotal()     { return $this->multaTotal; }
    public function getDiasEmAberto()   { return $this->diasEmAberto; }
            
    
    function ConverteData($data, $tipo){
    	if ($tipo == 0) {
    		$datatrans = explode ("/", $data);
    		$data = "$datatrans[2]-$datatrans[1]-$datatrans[0]";
    	} elseif ($tipo == 1) {
    		$datatrans = explode ("-", $data);
    		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
    	}elseif ($tipo == 2) {
    		$datatrans = explode ("-", $data);
    		$data = "$datatrans[1]/$datatrans[2]/$datatrans[0]";
    	} elseif ($tipo == 3) {
    		$datatrans = explode ("/", $data);
    		$data = "$datatrans[2]-$datatrans[1]-$datatrans[0]";
    	}
               
    	return $data;
    }    
    
       
    function VerificaBoleto(){
        
        list($ano,$mes,$dia) = explode("-",$this->ConverteData($this->vencimento,3));
        
        $VerificaBoleto['data']['vencimento'] = mktime (0, 0, 0, $mes, $dia, $ano);
        $VerificaBoleto['data']['atual'] = mktime (0, 0, 0, date("m"), date("d"), date("Y"));
        if($VerificaBoleto['data']['atual'] <= $VerificaBoleto['data']['vencimento']){
            return false; // BOLETO EM ABERTO            
        } else {             
            return true; // BOLETO VENCIDO      
        }

    }
    
    function DiasEntreData($date_ini, $date_end){
	    $data_ini = strtotime( $this->ConverteData($this->ConverteData($date_ini,3),2)); 
	    $hoje = $this->ConverteData($date_end,3);
    	$foo = strtotime($hoje);
    	$dias = ($foo - $data_ini)/86400; 
	    return $dias;
    }

    function Moeda($value){
    	return number_format($value, 2, ",", "");
    }
    
    function CalculaOsJuros(){        
        $juros = (($this->juros * ($this->DiasEntreData($this->vencimento,date("d/m/Y")))));
              
        if($this->DiasEntreData($this->vencimento,date("d/m/Y"))==0){
            $multa = 0;
        } else {
            $multa = (($this->multa * $this->valor) / 100);
        }
        
        $this->valorTotal    = $this->Moeda($this->valor + ($juros + $multa));
        $this->jurosTotal    = $this->Moeda($juros);
        $this->multaTotal    = $this->Moeda($multa);
        $this->diasEmAberto  = $this->DiasEntreData($this->vencimento,date("d/m/Y"));

    }
    
}
?>