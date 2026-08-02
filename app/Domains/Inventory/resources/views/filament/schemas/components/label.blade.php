<div style="display: flex; gap: 1rem">
    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($model->public_id, 'DATAMATRIX') }}" style="height: 3rem; width: 3rem" />

    <div style="display: flex; flex-direction: column; gap: 0.25rem">
        <span style="font-size: 1.5rem; font-weight: bold; line-height: 1">
            {{ $model->public_id  }}
        </span>
        {{ $model->name  }}
    </div>
</div>

