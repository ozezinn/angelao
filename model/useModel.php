<?php
//incluindo o arquivos php
include("conexao.php");
class Cep implements JsonSerializable
{
    //O formato de envio de dados do servidor para o cliente
    //será em JSON
    //atributos da classe
    private $codigo;
    private $logradouro;
    private $bairro;
    private $cidade;
    private $estado;
    private $cep;

    //metodo para gerar o json
    //esse método irá configurar como os dados serão enviados do servidor
    //para o android
    function jsonSerialize(): mixed
    {
        return
            [
                'logradouro'    => $this->logradouro,
                'bairro'         => $this->bairro,
                'cidade'        => $this->cidade,
                'estado'        => $this->estado,
                'cep'            => $this->cep,
                'codigo'         => $this->codigo
            ];
    }


    //definição dos metodos GET SET
    function __get($atributo)
    {
        return $this->atributo;
    }

    function __set($atributo, $value)
    {
        $this->$atributo = $value;
    }

    //acessar o banco de dados
    private $con;
    function __construct()
    {
        //criando um objeto da classe chamando classe_con
        $classe_con = new Conexao();
        //executando o método conectar e estabelecendo uma conexão com o BD
        $this->con = $classe_con->Conectar();
    }

    //métodos para gerenciar as informações no banco de dados
    //Enviando informações que serão armazenadas no na tabela
    function cadastrar()
    {
        $comandoSql = "insert into cep (logradouro, bairro, cidade, estado, cep) values (?,?,?,?,?)";
        $valores = array($this->logradouro, $this->bairro, $this->cidade, $this->estado, $this->cep);
        $exec = $this->con->prepare($comandoSql);
        $exec->execute($valores);
    }
    //método que atualiza todos os valores presentes no registro da tabela
    function atualizar()
    {
        $comandoSql = "update cep set logradouro = ?, bairro = ?, cidade = ?, estado = ?, cep = ? where codigo = ?";
        $valores = array($this->logradouro, $this->bairro, $this->cidade, $this->estado, $this->cep, $this->codigo);
        $exec = $this->con->prepare($comandoSql);
        $exec->execute($valores);
    }
    //exclui o valor armazenado na tabela
    //apenas o valor que corresponde ao código informado
    function excluir()
    {
        $comandoSql = "delete from cep where codigo = ?";
        $valores = array($this->codigo);
        $exec = $this->con->prepare($comandoSql);
        $exec->execute($valores);
    }
    //realiza uma consulta de TODOS os registro presentes na tabela
    //retorna um array com os valores 
    function consultar()
    {
        $comandoSql = "select * from cep";
        $exec = $this->con->prepare($comandoSql);
        $exec->execute();

        $dados = array();

        foreach ($exec->fetchAll() as $value) {
            $rua = new Cep;
            $rua->logradouro      = $value["logradouro"];
            $rua->bairro        = $value["bairro"];
            $rua->cidade         = $value["cidade"];
            $rua->estado        = $value["estado"];
            $rua->cep             = $value["cep"];
            $rua->codigo        = $value["codigo"];
            $dados[] = $rua;
        }
        return $dados;
    }
    //retorna do banco de dados o valor correspondente ao código pesquisado
    //como o código é chave primária, retornará apenas um valor
    function consultaCodigo()
    {
        $comandoSql = "select * from cep where codigo = ?";
        $valores = array($this->codigo);
        $exec = $this->con->prepare($comandoSql);
        $exec->execute($valores);

        $value  = $exec->fetch();

        $rua = new Cep;
        $rua->logradouro      = $value["logradouro"];
        $rua->bairro        = $value["bairro"];
        $rua->cidade         = $value["cidade"];
        $rua->estado        = $value["estado"];
        $rua->cep             = $value["cep"];
        $rua->codigo        = $value["codigo"];

        return $rua;
    }
    //realiza uma consulta por nome
    // como utilizar o LIKE ele buscará por
    //valores proximos do que está sendo pesquisado

    function consultaCep()
    {
        $comandoSql = "select * from cep where cep like ?";
        $valores = array("%" . $this->cep . "%");
        $exec = $this->con->prepare($comandoSql);
        $exec->execute($valores);

        return $exec->fetchAll(PDO::FETCH_ASSOC);
        /*
		foreach ($exec->fetchAll() as $value) {
			$rua = new Cep;
			$rua->logradouro  	= $value["logradouro"];
			$rua->bairro		= $value["bairro"];
			$rua->localidade 	= $value["localidade"];
			$rua->estado		= $value["estado"];
			$rua->cep 			= $value["cep"];
			$rua->codigo		= $value["codigo"];	
			$dados[] = $rua;		
		}
		return $dados;
		}*/
    }
}