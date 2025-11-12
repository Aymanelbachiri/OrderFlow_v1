@props(['headers' => [], 'rows' => [], 'class' => ''])

<div class="overflow-x-auto {{ $class }}">
    <!-- Desktop Table -->
    <table class="hidden md:table w-full bg-white rounded-lg shadow-sm">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($row as $index => $cell)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $cell }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Mobile Table -->
    <div class="md:hidden">
        @foreach($rows as $rowIndex => $row)
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4 border border-gray-200">
                @foreach($row as $cellIndex => $cell)
                    <div class="flex justify-between items-center py-2 {{ $cellIndex < count($row) - 1 ? 'border-b border-gray-100' : '' }}">
                        <span class="text-sm font-medium text-gray-500">
                            {{ $headers[$cellIndex] ?? 'Field' }}
                        </span>
                        <span class="text-sm text-gray-900 text-right flex-1 ml-4">
                            {{ $cell }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
