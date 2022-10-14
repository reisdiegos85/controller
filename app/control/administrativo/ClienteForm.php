<?php

class ClienteForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'controller_db';
    private static $activeRecord = 'SystemUnit';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Clientes");


        $id = new TEntry('id');
        $name = new TEntry('name');
        $nome = new TEntry('nome');
        $telefone = new TEntry('telefone');
        $email = new TEntry('email');
        $como_conheceu = new TDBCombo('como_conheceu', 'controller_db', 'ComoConheceu', 'id', '{nome}','nome asc'  );

        $name->addValidation("Name", new TRequiredValidator()); 

        $telefone->setMask('(99) 9 9999-9999', true);
        $id->setEditable(false);
        $como_conheceu->enableSearch();

        $telefone->placeholder = "ex: (11) 9 9123-4567";

        $id->setSize(100);
        $name->setSize('100%');
        $nome->setSize('100%');
        $email->setSize('100%');
        $telefone->setSize('100%');
        $como_conheceu->setSize('100%');

        $row1 = $this->form->addFields([$id]);
        $row1->layout = [' col-12 col-sm-12 col-lg-1 col-xl-1 col-md-12'];

        $row2 = $this->form->addFields([new TLabel("Apelido da conta", null, '14px', null, '100%'),$name]);
        $row2->layout = [' col-12 col-sm-12 col-lg-3 col-xl-3 col-md-12'];

        $row3 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome]);
        $row3->layout = [' col-12 col-sm-12 col-lg-4 col-xl-4 col-md-12'];

        $row4 = $this->form->addFields([new TLabel("Telefone:", null, '14px', null, '100%'),$telefone]);
        $row4->layout = [' col-12 col-sm-12 col-lg-3 col-xl-3 col-md-12'];

        $row5 = $this->form->addFields([new TLabel("E-mail:", null, '14px', null, '100%'),$email]);
        $row5->layout = [' col-12 col-sm-12 col-lg-3 col-xl-3 col-md-12'];

        $row6 = $this->form->addFields([new TLabel("Como conheceu:", null, '14px', null, '100%'),$como_conheceu]);
        $row6->layout = [' col-12 col-sm-12 col-lg-3 col-xl-3 col-md-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Administrativo","Cadastro de Clientes"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new SystemUnit(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new SystemUnit($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

