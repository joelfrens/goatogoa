@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					New Category
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

						<div class="flash-message">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
						  @if(Session::has('alert-' . $msg))

						  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
						  @endif
						@endforeach
						</div> <!-- end .flash-message -->

						<!-- New Category Form -->
						{{ Form::open(array('url' => '/admin/category', 'files' => true)) }}
						
						{{ csrf_field() }}

						<div class="form-group">
						    {!! Form::label('category-name', 'Category Title:', ['class' => 'control-label']) !!}
						    {!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>

						{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}

						{!! Form::close() !!}
							
				</div>
			</div>

			<!-- Current Category -->
			@if (count($categories) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						Current Articles
					</div>

					<div class="panel-body">
						<table class="table table-striped task-table">
							<thead>
								<th>Articles</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($categories as $category)
									<tr>
										<td class="table-text"><div>{{ $category->name }}</div></td>

										<!-- article Delete Button -->
										<td>
											<form action="/admin/category/{{ $category->id }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" class="btn btn-danger">
													<i class="fa fa-trash"></i>Delete
												</button>
											</form>

										</td>
										<td>
											<a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary">Edit Category</a>
											
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
			<?php echo $categories->render(); ?>
		</div>
	</div>
@endsection
