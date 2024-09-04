@extends('layouts.app')

@section('title', 'Data Training 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 1</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @include('pages.partials.session-notification')

    <form action="{{ route('datatrain1.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Impor data dari Excel</label>
        <div class="input-group my-3">
            <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
            <button type="submit" class="btn btn-success">Unggah Data</button>
        </div>
    </form>

    @role('admin')
        <form action="{{ route('datatrain1.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh file beserta isinya?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Bersihkan Data</button>
        </form>
    @endrole

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/U</strong>, berat badan per usia</li>
            <li><strong>TB/U</strong>, tinggi badan per usia</li>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

    <p class="mt-3"> Harap pastikan file CSV mengikuti format di bawah ini:</p>
<pre class="mt-2">nama;usia (bulan);BB_U;TB_U;BB_TB
Fitri;25;Kurang;Normal;Gizi Baik
Yusuf;30;Normal;Pendek;Gizi Baik
...</pre>

    <button type="button" name="proses" class="btn btn-success" id="getMiningResultButton">Proses Mining</button>

    <section class="my-2" style="overflow: auto;">
        <section id="treeContainer"></section>
    </section>

    <section class="table-responsive">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Usia</th>
                <th scope="col">BB/U</th>
                <th scope="col">TB/U</th>
                <th scope="col">BB/TB</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataset1 as $dt1)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dt1->nama }}</td>
                        <td>{{ $dt1->usia }}</td>
                        <td>{{ $dt1->berat_badan_per_usia }}</td>
                        <td>{{ $dt1->tinggi_badan_per_usia }}</td>
                        <td>{{ $dt1->berat_badan_per_tinggi_badan }}</td>           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>

<script defer src="https://d3js.org/d3.v7.min.js"></script>

<style>
#treeContainer {
    width: 100%;
    height: 600px;
}

.node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 3px;
}

.node text {
    font-size: 12px;
}

.link {
    fill: none;
    stroke: #ccc;
    stroke-width: 2px;
}

</style>
    
<script>
document.getElementById('getMiningResultButton').addEventListener('click', (e) => {
    e.preventDefault();

    fetch('{{ route('proses-mining-dataset-1') }}')
        .then(response => response.json())
        .then(data => {
            // Define dimensions and margins
            const width = document.getElementById('treeContainer').offsetWidth;
            const height = 600;
            const margin = { top: 20, right: 120, bottom: 20, left: 120 };

            // Remove any previous SVG
            d3.select("#treeContainer").selectAll("*").remove();

            // Append new SVG
            const svg = d3.select("#treeContainer").append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", `translate(${margin.left},${margin.top})`);

            const treeLayout = d3.tree().size([height - margin.top - margin.bottom, width - margin.left - margin.right]);
            const root = d3.hierarchy(data);

            treeLayout(root);

            // Links
            svg.selectAll('.link')
                .data(root.links())
                .enter().append('path')
                .attr('class', 'link')
                .attr('d', d3.linkHorizontal()
                    .x(d => d.y)
                    .y(d => d.x));

            // Nodes
            const node = svg.selectAll('.node')
                .data(root.descendants())
                .enter().append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.y},${d.x})`);

            node.append('circle')
                .attr('r', 5);

            node.append('text')
                .attr('dy', 3)
                .attr('x', d => d.children ? -10 : 10)
                .style('text-anchor', d => d.children ? 'end' : 'start')
                .text(d => d.data.label || d.data.attribute || "Node");
        })
        .catch(error => console.error('Error fetching mining result:', error));
});

</script>
@endsection
