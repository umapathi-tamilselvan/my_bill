@props(['label' => '', 'name' => '', 'options' => [], 'value' => '', 'required' => false, 'class' => ''])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror {{ $class }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
        <option value="">-- Select --</option>
        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

