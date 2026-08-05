<head>
    @vite(['resources/css/app.css'])
</head>

<div class="flex gap-4">
    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($model->public_id, 'DATAMATRIX', 8, 8) }}" class="size-24" />

    <div class="flex flex-col justify-center gap-1">
        <span class="text-3xl leading-none font-bold tabular-nums tracking-wide">
            {{ $model->public_id  }}
        </span>

        <span class="text-xl leading-tight">
            {{ $model->name }}
        </span>
    </div>
</div>
