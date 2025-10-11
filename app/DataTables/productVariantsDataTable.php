<?php

namespace App\DataTables;

use App\Models\productVariant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class productVariantsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<productVariant> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.product-variants.edit', $query->id) . '"  class="btn btn-primary btn-md edit-product-variant-btn" data-bs-toggle="modal" data-bs-target="#edit-product-variant" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.product-variants.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#productvariants-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('product_name', function ($query) {
                return $query->product ? $query->product->getTranslation('name', 'en') : '';
            })->addColumn('is_track_stock', function ($query) {
                return $query->is_track_stock == 1 ? '<span class = "badge bg-success text-white">Yes</span>' : '<span class = "badge bg-warning text-white">Yes</span>';
            })->addColumn('attribute', function ($query) {
                return $query->attributeValues
                    ->map(fn($val) => $val->getTranslation('value', 'en'))
                    ->implode(', ');
            })
            ->rawColumns(['action', 'is_track_stock'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<productVariant>
     */
    public function query(productVariant $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productvariants-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'scrollX' => "true",
                "scrollCollapse" => "false",
                "autowidth" => true,
                "dom" => "Blfrtip",
            ])
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('product_name'),
            Column::make('attribute'),
            Column::make('sku'),
            Column::make('barcode'),
            Column::make('barcode'),
            Column::make('price'),
            Column::make('compare_price'),
            Column::make('cost_price'),
            Column::make('qty'),
            Column::make('min_order_qty'),
            Column::make('max_order_qty'),
            Column::make('is_track_stock'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'productVariants_' . date('YmdHis');
    }
}
