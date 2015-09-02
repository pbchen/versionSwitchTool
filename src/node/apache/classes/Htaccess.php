<?php
class Htaccess{
	protected $path='';
	protected $start='';
	protected $end='';
	protected $part1 = '';
	protected $part2 = '';
	protected $content='';
	protected $error= 0;
	protected $rules = array();
	//0 无错误
	//1  htaccess文件错误
	//2  文件内无确定标识符
	//3  写文件错误
	//4


	public function __construct( $path, $start, $end ) {
		
		$this->path = $path;
		$this->start = $start;
		$this->end = $end;
		$this->content = @file_get_contents($path);

		if($this->content){
			$start_pos = strpos($this->content, $start);
			$end_pos = strpos($this->content, $end);
			if($end_pos>$start_pos && $start_pos>0){
				$this->part1 = trim(substr($this->content, 0, $start_pos));
				$this->part2 = trim(substr($this->content, $end_pos+strlen($end)));
			} else {
				$this->error = 2;
			}

		} else {
			$this->error = 1;
		}
    }

    public function add_rule($value){
    	$this->rules[$value]='';
    }

    public function errors(){
    	return $this->error;
    }

    public function save(){
    	$output=$this->part1."\n".$this->start;
    	foreach ($this->rules as $key => $val) {
    		$output.="\n".$key;    		
    	}
    	$output.="\n".$this->end."\n".$this->part2;
    	//die($output);
    	$r=@file_put_contents($this->path, $output);
    	if(!$r){
    		$this->error=3;
    	}
    }
}

?>