@extends('layouts.app')

@section('content')
	{{-- <table border="1"> --}}
	{{-- @foreach ($gamingMap as $i => $row)
		<tr>
		@foreach ($row as $j => $cell)
			<td style="
				width: 32px !important; 
				background-color: {{ $cell['type'] == -1 ? '' : ($cell['type'] == 0 ? 'red' : 'green') }};
				border-color: {{ $cell['win'] ? 'blue' : '' }};
				border-width: 2px;
			">
				{{ $cell['h'] }}
				{{ $cell['v'] }} <br>
				{{ $cell['di'] }}
				{{ $cell['dd'] }}

			</td>
		@endforeach
		</tr>
	@endforeach
	</table> --}}
	
	<div class="gameboard">
		<canvas id="canvas_gamingMap"></canvas>		
	</div>

@endsection


