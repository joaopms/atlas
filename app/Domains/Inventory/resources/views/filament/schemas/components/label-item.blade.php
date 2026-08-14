<head>
    @vite(['resources/css/app.css'])
</head>

<div class="flex gap-2">
    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($model->public_id, 'DATAMATRIX', 8, 8) }}" class="size-8" />

    <div class="flex flex-col">
        @if($model->container)
            <span class="text-xs leading-none">
                {{ $model->container->name_with_id }}
            </span>
        @endif

        <span class="text-xl leading-none font-bold tabular-nums tracking-wide">
            {{ $model->public_id  }}
        </span>
    </div>
</div>
