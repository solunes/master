<tr>
  <td>{!! Segment::form_subinput($si, $count, 'floor', 'name', 'text', ['class'=>'text-control']) !!}</td>
  <td class="empty-field" data-text="Guarde para generar el QR">
  	@if(is_integer($si)&&$si==0)
  	  Guarde para generar el QR
  	@elseif($si&&$si->qr)
  	  <a target="_blank" href="{{ Asset::get_file($si->qr, 'qr') }}">Código QR</a>
  	@else
  	  No se encontró el QR
  	@endif
  </td>
  <td>{!! Segment::form_subinput($si, $count, 'floor', 'id', 'hidden') !!}<a class="delete_row" rel="floors" href="#">X</a></td>
</tr>