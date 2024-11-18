@extends('layouts.app')

@section('title', 'Data Training 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 1</h2>

    @include('pages.partials.session-notification')

    <section class="bg-light rounded border border-1 p-3">
        <section class="d-flex flex-column justify-content-between mb-4">
            <section class="d-flex">
                <a href="{{ route('datatrain1-mining') }}" class="btn btn-success">Proses Mining</a>
            </section>
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
                    @foreach ($data as $dt1)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt1['nama'] }}</td>
                            <td>{{ $dt1['usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_usia'] }}</td>
                            <td>{{ $dt1['tinggi_badan_per_usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_tinggi_badan'] }}</td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>

    <section class="bg-light p-3 border border-1 mt-3">
        @if(count($rules) > 0)
            <details>
                <summary class=""><h5 class="d-inline">Rules</h5></summary>
                <br/>
                <ul style="list-style: none; margin-left: 0;" class="bg-white p-0 p-3 rounded text-secondary border border-1  overflow-x-auto">
                    @foreach ($rules as $rule)
                        @php
                            // Split the rule into lines
                            $lines = explode("\n", trim($rule));
                            $indentLevel = 0;  // Track indentation for nested IF statements
                        @endphp
                        <li>
                            @foreach ($lines as $line)
                                @if (strpos($line, 'IF') !== false)
                                    @php
                                        // Increase the indentation for nested IFs
                                        $indentLevel++;
                                        // Add indentation spaces based on the nesting level
                                        $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $indentLevel - 1);
                                    @endphp
                                    {!! $indentation . str_replace('IF', '├', $line) !!}<br>
                                @elseif (strpos($line, 'THEN') !== false)
                                    @php
                                        // Reset the indentation level for THEN
                                        $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $indentLevel);
                                    @endphp
                                    {!! $indentation . str_replace('THEN', '┗╸', $line) !!}<br>
                                    @php $indentLevel = 0; @endphp  {{-- Reset for next rule set --}}
                                @endif
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </details>
        @endif
    </section>
</section>
@endsection
