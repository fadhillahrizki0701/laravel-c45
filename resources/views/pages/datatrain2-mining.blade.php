@extends('layouts.app')

@section('title', 'Data Training 2 - Mining')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Proses Mining Dataset 2</h2>

    @include('pages.partials.session-notification')

    <button type="button" name="proses" class="btn btn-success" id="getMiningResultButton">Pohon Keputusan</button>

    <section class="my-2" style="overflow: auto;">
        <svg width="960" height="600"></svg>
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

<script>
document.getElementById('getMiningResultButton').addEventListener('click', (e) => {
    e.preventDefault();

    fetch('{{ route('proses-mining-dataset-1') }}')
        .then(response => response.json())
        .then(data => {
            const svg = d3.select("svg"),
                width = +svg.attr("width"),
                height = +svg.attr("height");

            const g = svg.append("g")
                .attr("transform", "translate(0, 40)");

            const tree = d3.tree().size([width, height - 160]);

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

            // Draw the paths (lines between nodes) in vertical orientation
            link.append("path")
                .attr("class", "link")
                .attr("d", d3.linkVertical()
                    .x(d => d.x)
                    .y(d => d.y)
                )
                .attr("fill", "none")
                .attr("stroke", "#ccc")
                .attr("stroke-width", 1.5);

            // Add the attribute_value text above the paths
            link.append("text")
                .attr("class", "link-text")
                .attr("dy", -5)  // Position the text slightly above the link
                .attr("x", d => (d.source.x + d.target.x) / 2)  // Mid-point of the path (X)
                .attr("y", d => (d.source.y + d.target.y) / 2)  // Mid-point of the path (Y)
                .text(d => d.target.data.attribute_value);  // Display the attribute_value

            // Add the nodes (circles and labels)
            const node = g.selectAll(".node")
                .data(treeDataLayout.descendants())
                .enter().append("g")
                .attr("class", "node")
                .attr("transform", d => `translate(${d.x},${d.y})`);

            node.append("circle")
                .attr("r", 5)
                .style("fill", d => d.children ? "#fff" : "lightsteelblue");

            node.append("text")
                .attr("dy", -10)
                .attr("x", d => d.children ? -10 : 10)
                .style("text-anchor", "middle")
                .text(d => d.data.name);
        })
        .catch(error => console.error('Error fetching mining result:', error));
});
</script>
@endsection
