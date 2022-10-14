<?php

class SystemUnit extends TRecord
{
    const TABLENAME  = 'system_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    private $fk_como_conheceu;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('connection_name');
        parent::addAttribute('nome');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('como_conheceu');
            
    }

    /**
     * Method set_como_conheceu
     * Sample of usage: $var->como_conheceu = $object;
     * @param $object Instance of ComoConheceu
     */
    public function set_fk_como_conheceu(ComoConheceu $object)
    {
        $this->fk_como_conheceu = $object;
        $this->como_conheceu = $object->id;
    }

    /**
     * Method get_fk_como_conheceu
     * Sample of usage: $var->fk_como_conheceu->attribute;
     * @returns ComoConheceu instance
     */
    public function get_fk_como_conheceu()
    {
    
        // loads the associated object
        if (empty($this->fk_como_conheceu))
            $this->fk_como_conheceu = new ComoConheceu($this->como_conheceu);
    
        // returns the associated object
        return $this->fk_como_conheceu;
    }

    
}

