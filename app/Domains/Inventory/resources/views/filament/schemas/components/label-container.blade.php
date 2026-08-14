<head>
    @vite(['resources/css/app.css'])
</head>

<div class="flex flex-col gap-0.5">
    <div class="flex gap-2">
        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($model->public_id, 'DATAMATRIX', 8, 8) }}" class="size-8" />
        <span class="text-3xl leading-none font-bold tabular-nums tracking-wide">
        {{ $model->public_id  }}
    </span>
    </div>

    <span class="text-xl leading-tight">
        {{ $model->name }}
    </span>
</div>
