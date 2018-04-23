<?php
$MDFe = new MDFe();
$aResposta = $MDFe->montaMDFe(2, 682);

//$MDFe->xml = $MDFe->tools->assina($MDFe->xml);
//$MDFe->tools->validarXml($filename)

//$MDFe->tools->sefazEnviaLote([$MDFe->xml], $idLote = 1, false, $aRetorno);
//$aResposta = $MDFe->tools->sefazConsultaChave('24180402037822000172580010000026761000026761');
echo is_array($aResposta) ? json_encode($aResposta) : $aResposta;
