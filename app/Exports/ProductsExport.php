<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    /**
     * Heading columns.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'SKU',
            'Category',
            'Supplier',
            'Stock',
            'Min Stock',
            'Price (Rp)',
            'Created At'
        ];
    }

    /**
     * Map each row.
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->category->name ?? 'N/A',
            $product->supplier->name ?? 'N/A',
            $product->stock,
            $product->minimum_stock,
            $product->price,
            $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : '-'
        ];
    }

    /**
     * Styling the header row.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Indigo-600
                ]
            ],
        ];
    }
}
