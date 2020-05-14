<html>
<head><title>ALERTA: NOVO PROTOCOLO</title></head>
<body>
<p>Estimado {{$responsavel}} ( {{$setor}}) ,</p>
<p>O cliente  <b>{{$cliente}}</b>, criou um novo protocolo direcionado ao setor, com a seguinte solicitação:</p>
<br>
<hr>
<p>{!! nl2br($solicitacao) !!} </p>
<hr>
<p> Acesse agora mesmo <a href="{{ url($link)}}"> Clicando AQUI! </a></p>

<br>
<p>Att.</p>
<p>Equipe Globalsat</p>
</body>
</html>

