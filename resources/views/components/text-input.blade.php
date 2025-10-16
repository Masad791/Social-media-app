@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-blue-950 border-opacity-40 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
