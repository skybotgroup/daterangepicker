<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$multiple?$id['start']:$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

            @if($multiple)

                <input value="{{ old($column['range'], $value['range']) }}" name="{{$name['range']}}" class="form-control {{$class['start']}}_{{$class['end']}}" style="width: 100%" {!! $attributes !!} />

                <input type="hidden" id="{{$id['start']}}" name="{{$name['start']}}" value="{{ old($column['start'], $value['start']) }}"/>
                <input type="hidden" id="{{$id['end']}}" name="{{$name['end']}}" value="{{ old($column['end'], $value['end']) }}"/>
            @else
                <input value="{{ old($column, $value) }}" name="{{$name}}" class="form-control {{$class}}" style="width: 100%" {!! $attributes !!} />
            @endif


        </div>

        @include('admin::form.help-block')

    </div>
</div>