<?php
class dataprovider
{
    /*
    this is the base for most of the classes. It provides basic database-strucutres und commands such as save, load etc.
    Create new class with:
    class xyz extends dataprovider
    */

    public $id = 0;
    

    private $table = '';
    public $errors = array();

	function __construct($table, $id = 0)
	{
        $this->id = $id;
        $this->table = $table;

        $this->load();
    }

    function load()
    {
        //get all table columns...
		$DS = database::Query('SHOW COLUMNS FROM ' . $this->table, array());
        
        //...and all data, if it's a existing dataset
        if($this->id > 0)
            $DSdata = database::Query('SELECT * FROM ' . $this->table . ' WHERE id = ' . $this->id, array())[0];

        foreach($DS as $dataset)
        //while($RS->get($DS))
        {

            if($dataset['Field'] == 'id')
                continue;

            if($this->id > 0)
                $this->data[$dataset['Field']] = $DSdata[$dataset['Field']]; 
            else
                $this->data[$dataset['Field']] = '';           
        }
	}

	public function get($field)
    {
    	//if(property_exists(get_class($this), $field))
    	   return $this->data[$field];
    }

    public function set($field, $value)
    {

        if(strpos($field, 'uniquer') !== false)
        {
            $parts = explode('_uniquer', $field);
            $field = $parts[0];
        }   

        $this->data[$field] = $value;
    }

    public function setError($key, $value)
    {
        $this->errors[$key] = $value;
    }

    //method for overloading objects
    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    
    public function save()
    {
        $RS = array();

        if(count($this->errors) > 0)
            return $this;
        else
        {
            $tail = '';

            if($this->id > 0)
            {
                $sql = 'UPDATE ' . $this->table . ' SET ';
                $tail = ' WHERE id = ' . $this->id;
            }
            else
                $sql = 'INSERT INTO ' . $this->table . ' SET ';

            $fields = array();

            foreach($this->data as $key=>$value)
            {
                if(is_object($this->get($key)) === false)
                {
                    if($key == 'update_date')
                        $fields[] = $key . ' = ' . time();
                    else
                        $fields[] = $key . ' = "' . $value . '"';    
                }
                else
                {

                    if(method_exists($this->get($key), 'save'))
                        $this->get($key)->save();
                }
            }

            $sql .= implode(', ', $fields) . $tail;
            
            if($this->id > 0)
            {
                database::Update($sql, $RS);
                return $this;
            }
            else
            {
                database::Insert($sql, $RS);
                $class=get_called_class();

                return new $class($RS);
            }   
        }
    }
}

?>