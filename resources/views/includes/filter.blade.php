@if (!empty(Request::get('search_string')) ||!empty(Request::get('show')) || (!empty(Request::get('sort_in')) && !empty(Request::get('sort_by'))))
	<div class="breadcrumb">
		<b>
			<span class='fa fa-filter'></span> Filtered
			{{ !empty($total) ? '('.$total.')': ''  }}

		</b><br>
		{!! filteredBy(request()) !!}
		<a href="{{ request()->url() }}">
			<span class="fa fa-times"></span>
			clear
		</a>
	</div>
@endif
