@extends('layouts.app')

@section('title', 'Data Training 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 1</h2>

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
        <svg width="960" height="600"></svg>
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


<style>
.node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 3px;
}

.node text {
    font: 12px sans-serif;
}

.link {
    fill: none;
    stroke: #ccc;
    stroke-width: 2px;
}
</style>

<script defer src="https://d3js.org/d3.v7.min.js"></script>
<script>
document.getElementById('getMiningResultButton').addEventListener('click', (e) => {
    e.preventDefault();

    fetch('{{ route('proses-mining-dataset-1') }}')
        .then(response => response.json())
        .then(data => {
            // Create a tree layout and assign the data
            const svg = d3.select("svg"),
                width = +svg.attr("width"),
                height = +svg.attr("height");

            const g = svg.append("g")
                .attr("transform", "translate(40, 0)");

            const tree = d3.tree().size([height, width - 160]);

            // Function to process JSON structure into D3 hierarchy and attach "attribute_value" to links
            function processNode(node, attributeValue = null) {
                if (node.isLeaf) {
                    return { name: node.name, attribute_value: attributeValue };
                } else {
                    return {
                        name: node.name,
                        attribute_value: attributeValue,
                        children: node.children.map(child => processNode(child.node, child.attribute_value))
                    };
                }
            }

            const root = d3.hierarchy(processNode(data));

            // Generate the tree structure
            const treeDataLayout = tree(root);

            // Add the links (lines connecting nodes)
            const link = g.selectAll(".link")
                .data(treeDataLayout.links())
                .enter().append("g");

            // Draw the paths (lines between nodes)
            link.append("path")
                .attr("class", "link")
                .attr("d", d3.linkHorizontal()
                    .x(d => d.y)
                    .y(d => d.x));

            // Add the attribute_value text above the paths
            link.append("text")
                .attr("class", "link-text")
                .attr("dy", -5)  // Position the text slightly above the link
                .attr("x", d => (d.source.y + d.target.y) / 2)  // Mid-point of the path (X)
                .attr("y", d => (d.source.x + d.target.x) / 2)  // Mid-point of the path (Y)
                .text(d => d.target.data.attribute_value);  // Display the attribute_value

            // Add the nodes (circles and labels)
            const node = g.selectAll(".node")
                .data(treeDataLayout.descendants())
                .enter().append("g")
                .attr("class", "node")
                .attr("transform", d => `translate(${d.y},${d.x})`);

            node.append("circle")
                .attr("r", 5)
                .style("fill", d => d.children ? "#fff" : "lightsteelblue");

            node.append("text")
                .attr("dy", 3)
                .attr("x", d => d.children ? -10 : 10)
                .style("text-anchor", d => d.children ? "end" : "start")
                .text(d => d.data.name);
        })
        .catch(error => console.error('Error fetching mining result:', error));
});

</script>
@endsection
