<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@foreach($models as $model)
    <div class="flex items-center justify-center h-lvh">
        @if($model instanceof \App\Domains\Inventory\Models\Container)
            @include('Inventory::filament.schemas.components.label-container', ['model' => $model])
        @elseif($model instanceof \App\Domains\Inventory\Models\Item)
            @include('Inventory::filament.schemas.components.label-item', ['model' => $model])
        @else
            Invalid model provided: {{ get_class($model) }}
        @endif
    </div>
    @pageBreak
@endforeach
