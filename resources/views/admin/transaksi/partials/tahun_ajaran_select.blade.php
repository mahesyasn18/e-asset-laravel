<div class="input-group mb-3">
    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
        @foreach ($tahun as $row)
            @if ($row->status=="active")
                <option value="{{ $row->id }}" selected>{{ $row->tahun_ajaran }}</option>
            @else
                <option value="{{ $row->id }}">{{ $row->tahun_ajaran }}</option>
            @endif
        @endforeach
    </select>
    <div class="input-group-append">
        <button class="btn btn-success" type="button" id="button-addon2">Filter</button>
    </div>
</div>