<?php

namespace App\Exports;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    use Exportable;

    public function __construct(public Request $request) {}

    public function collection(): Collection
    {
        $query = Inventory::with(['product']);

        // Apply product filter if provided
        if ($this->request->has('product_id') && $this->request->product_id) {
            $query->where('product_id', $this->request->product_id);
        }

        // Apply product name filter if provided
        if ($this->request->has('product_name') && $this->request->product_name) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->request->product_name . '%');
            });
        }

        // Apply storage location filter if provided
        if ($this->request->has('storage_location') && $this->request->storage_location) {
            $query->where('storage_location', 'like', '%' . $this->request->storage_location . '%');
        }

        // Apply quantity range filters if provided
        if ($this->request->has('min_quantity') && $this->request->min_quantity !== null) {
            $query->where('quantity', '>=', $this->request->min_quantity);
        }

        if ($this->request->has('max_quantity') && $this->request->max_quantity !== null) {
            $query->where('quantity', '<=', $this->request->max_quantity);
        }

        // Apply date filters if provided
        if ($this->request->has('date_from') && $this->request->date_from) {
            $query->where('last_stocked_at', '>=', $this->request->date_from);
        }

        if ($this->request->has('date_to') && $this->request->date_to) {
            $query->where('last_stocked_at', '<=', $this->request->date_to);
        }

        // Order by product name and storage location
        $query->orderBy('product_id')
            ->orderBy('storage_location');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Product Description',
            'Product Color',
            'Product Price',
            'Storage Location',
            'Quantity',
            'Price Per Unit',
            'Total Value',
            'Last Stocked At',
            'Created At',
            'Updated At'
        ];
    }

    public function map($inventory): array
    {
        $product = $inventory->product;

        return [
            $inventory->id,
            $product ? $product->name : 'N/A',
            $product ? $product->description : 'N/A',
            $product ? $product->color : 'N/A',
            $product ? number_format($product->price, 2) : 'N/A',
            $inventory->storage_location ?: 'N/A',
            $inventory->quantity,
            $product ? number_format($product->price, 2) : 'N/A',
            $product ? number_format($product->price * $inventory->quantity, 2) : 'N/A',
            $inventory->last_stocked_at ? $inventory->last_stocked_at->format('Y-m-d H:i:s') : 'N/A',
            $inventory->created_at->format('Y-m-d H:i:s'),
            $inventory->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style all data cells
        $sheet->getStyle('A2:L' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        // Center align ID and quantity columns
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Right align price columns
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Center align date columns
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K:K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add alternating row colors
        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':L' . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->setStartColor(new Color(Color::COLOR_BLUE));
            }
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Product Name
            'C' => 35,  // Product Description
            'D' => 15,  // Product Color
            'E' => 15,  // Product Price
            'F' => 20,  // Storage Location
            'G' => 12,  // Quantity
            'H' => 15,  // Price Per Unit
            'I' => 15,  // Total Value
            'J' => 20,  // Last Stocked At
            'K' => 20,  // Created At
            'L' => 20,  // Updated At
        ];
    }
}
