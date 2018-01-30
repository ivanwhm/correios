<title>SRO - Internet</title>
<style>
*
{
	background-color: rgb(216,230,237);
}
b
{
	color: #CC0000;
}
table, td 
{
	border: 1px solid #ccc;
	border-collapse: collapse;
	color: navy;
	font-family: Arial;
	padding: 2px;
	outline: none;
}
</style>
<meta charset="UTF-8">
<?php

  /**
   * Contém um exemplo de utilização da classe de rastreamento de objetos.
   * 
   * @author Ivan Wilhelm <ivan.whm@me.com>
   * @version 1.1
   */
  //Ajusta a codificação e o tipo do conteúdo
  //header('Content-type: text/txt; charset=utf-8');

  //Importa as classes
  require '../classes/Correios.php';
  require '../classes/CorreiosRastreamento.php';
  require '../classes/CorreiosRastreamentoResultado.php';
  require '../classes/CorreiosRastreamentoResultadoOjeto.php';
  require '../classes/CorreiosRastreamentoResultadoEvento.php';
  require '../classes/CorreiosSro.php';
  require '../classes/CorreiosSroDados.php';

  try
  {
    //Cria o objeto
    $rastreamento = new CorreiosRastreamento('usuario', 'senha');
    //Envia os parâmetros
    $rastreamento->setTipo(Correios::TIPO_RASTREAMENTO_LISTA);
    $rastreamento->setResultado(Correios::RESULTADO_RASTREAMENTO_TODOS);
    //$rastreamento->addObjeto('CP123456789CN');
    //$rastreamento->addObjeto('CP987654321CN');
    $rastreamento->addObjeto($_GET["cod"]);
    if ($rastreamento->processaConsulta())
    {
      $retorno = $rastreamento->getRetorno();
      if ($retorno->getQuantidade() > 0)
      {
        //echo 'Versão.................................: ' . $retorno->getVersao() . PHP_EOL;
        //echo 'Quantidade.............................: ' . $retorno->getQuantidade() . PHP_EOL;
        //echo 'Tipo de pesquisa.......................: ' . $retorno->getTipoPesquisa() . PHP_EOL;
        //echo 'Tipo de resultado......................: ' . $retorno->getTipoResultado() . PHP_EOL . PHP_EOL;
        foreach ($retorno->getResultados() as $resultado)
        {
          //echo 'Objeto.................................: ' . $resultado->getObjeto() . PHP_EOL;
          echo $resultado->getObjeto().'<br>';
	  //Se desejar obter informações sobre o objeto
          $dadosObjeto = new CorreiosSroDados($resultado->getObjeto());
          //echo 'Serviço................................: ' . $dadosObjeto->getDescricaoTipoServico() . PHP_EOL;
	  echo $dadosObjeto->getDescricaoTipoServico().'<br><br>';
          echo PHP_EOL;
          echo '<table id="table" tabindex="0" border="1"><tr><td><b>Data</b></td><td><b>Local</b></td><td><b>Situação</b></td><td><b>Tipo</B></td></tr>';
	  foreach ($resultado->getEventos() as $eventos)
          {
	    ///echo '  Tipo................................: ' . $eventos->getTipo() . ' - ' . $eventos->getDescricaoTipo() . PHP_EOL;
            //echo ' - Status..............................: ' . $eventos->getStatus() . PHP_EOL;
            //echo '  Descrição do status.................: ' . $eventos->getDescricaoStatus() . PHP_EOL;
            //echo ' - Ação relacionada ao status..........: ' . $eventos->getAcaoStatus() . PHP_EOL;
            ///echo '  Data................................: ' . $eventos->getData() . ' ' . $eventos->getHora() . PHP_EOL;
            ///echo '  Descrição...........................: ' . $eventos->getDescricao() . PHP_EOL;
            //echo ' - Comentários.........................: ' . $eventos->getComentario() . PHP_EOL;
            ///echo '  Local do evento.....................: ' . $eventos->getLocalEvento() . ' (' . $eventos->getCidadeEvento() . ', ' . $eventos->getUfEvento() . ')' . PHP_EOL;
            if ($eventos->getPossuiDestino())
            {
              //echo '  Local de destino....................: ' . $eventos->getLocalDestino() . ' (' . $eventos->getCidadeDestino() . ' - ' . $eventos->getBairroDestino() . ', ' . $eventos->getUfDestino() . ' - ' . $eventos->getCodigoDestino() . ')' . PHP_EOL;
	      echo '<tr><td>'.$eventos->getData() . ' ' . $eventos->getHora().'</td><td>'. $eventos->getLocalEvento() . ' - ' . $eventos->getCidadeEvento() . '/' . $eventos->getUfEvento() . '' . '<br>' . $eventos->getLocalDestino() . ' - ' . $eventos->getCidadeDestino() . '/' . $eventos->getUfDestino() . '</td><td>'.$eventos->getDescricao().'</td><td>'.$eventos->getTipo() . ' - ' . $eventos->getDescricaoTipo().'</td></tr>';
            }
	    else
	    {
	      echo '<tr><td>'.$eventos->getData() . ' ' . $eventos->getHora().'</td><td>'. $eventos->getLocalEvento() . ' - ' . $eventos->getCidadeEvento() . '/' . $eventos->getUfEvento() . '' .'</td><td>'.$eventos->getDescricao().'</td><td>'.$eventos->getTipo() . ' - ' . $eventos->getDescricaoTipo().'</td></tr>';
	    }
            //echo PHP_EOL;
          }
	  echo '</table>';
        }
      }
    } else
    {
      echo 'Nenhum rastreamento encontrado.';
    }
  } catch (Exception $e)
  {
    echo 'Ocorreu um erro ao processar sua solicitação. Erro: ' . $e->getMessage() . PHP_EOL;
  }
