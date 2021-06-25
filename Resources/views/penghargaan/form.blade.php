<div class="box-typical-body padding-panel">
	<div class="row">
		<div class="col-sm-9">

			<fieldset class="form-group {{ $errors->first('label', 'form-group-error') }}">
				<label for="label" class="form-label">
					{{ __('penghargaan::general.form.label.label') }} 
					<span class="color-red">*</span>
				</label>
				<div class="form-control-wrapper">
					{!! Form::text('label', null, ['class' => 'form-control', 'placeholder' => __('penghargaan::general.form.label.placeholder'), 'autofocus']) !!}
					{!! $errors->first('label', '<span class="text-muted"><small>:message</small></span>') !!}
				</div>
			</fieldset>

			{!! Form::textarea('konten', null, ['class' => 'tinymce']) !!}
			{!! $errors->first('konten', '<span class="text-muted text-danger"><small>:message</small></span>') !!}

		</div>
		<div class="col-sm-3">
			<fieldset class="form-group {{ $errors->first('icon', 'form-group-error') }}">
				<label for="icon" class="form-label">
					{{ __('penghargaan::general.form.icon.label') }} 
					<span class="color-red">*</span>
				</label>
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
						@if(!isset($edit))
							<img data-src="holder.js/500x500/auto" alt="...">
						@else
							<img src="{{ viewImg($edit->icon) }}" alt="{{ $edit->judul }}">
						@endif
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
					<div>
						<span class="btn btn-default btn-file">
							<span class="fileinput-new">{{ __('penghargaan::general.form.icon.select') }} </span>
							<span class="fileinput-exists">{{ __('penghargaan::general.form.icon.change') }} </span>
							{!! Form::file('icon', ['class' => 'form-control', 'accept' => 'image/*']) !!}
						</span>
						<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">{{ __('penghargaan::general.form.icon.remove') }} </a>
					</div>
					{!! $errors->first('icon', '<span class="text-muted"><small>:message</small></span>') !!}
				</div>
			</fieldset>
		</div>
	</div>
</div>